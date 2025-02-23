<?php

namespace App\Repositories;

use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\{FilterRequest, ProjectRequest};
use App\Interfaces\Repository\{FileRepositoryInterface, ProjectRepositoryInterface, SkillRepositoryInterface};
use App\Traits\{GetCursor, Slug};
use Illuminate\Support\Facades\{Auth, DB, Log};

class ProjectRepository implements ProjectRepositoryInterface
{
	use GetCursor,Slug;

	//MARK:   fetchProjects
	public function fetchProjects(FilterRequest $request):array
	{
		/**
			in case of search, we will search in project title and skills.
			in case search is empty, we will get
			project which match user skills if user is authenticated
			if user is not authenticated, we will get all projects.
			user can filter projects by number of days, price and experience.
		 */
		$auth_id        = Auth::id();
		$search         = $request->search;
		$num_of_days    = $request->num_of_days;
		$min_price      = $request->min_price;
		$max_price      = $request->max_price;
		$exp            = $request->exp ?? [];


		$projects_query = DB::table('projects')
			->select(
				'projects.*',
				'project_infos.*',
				'location',
				'card_num',
				DB::raw('IFNULL(ROUND(AVG(rate), 1),0) as review'),
				DB::raw('GROUP_CONCAT(DISTINCT skills.skill) as skills_names'),
				DB::raw('COUNT(DISTINCT proposals.id) as proposals_count')
			)
			->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
			->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
			->join('skills', 'project_skill.skill_id', '=', 'skills.id')
			->join('users', 'users.id', '=', 'projects.user_id')
			->join('user_infos', 'users.id', '=', 'user_infos.user_id')
			->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
			->leftJoin('proposals', 'projects.id', '=', 'proposals.project_id');

		$projects_query = $projects_query
					->when(
						$search,
						function ($query) use ($search) {
							$query->where(function ($query) use ($search) {
								$query->where('title', 'LIKE', "{$search}%")
									->orWhere('skills.skill', 'LIKE', "{$search}%");
							});
						},
						function ($query) use ($auth_id) {
							$query->when(Auth::check(), function ($query) use ($auth_id) {
								$query->join('user_skill', function ($join) use ($auth_id) {
									$join->on('project_skill.skill_id', '=', 'user_skill.skill_id')
										->where('user_skill.user_id', '=', $auth_id);
								});
							});
						}
					);


		if ($num_of_days) {
			$projects_query = $projects_query
							->where('project_infos.num_of_days', '<=', $request->num_of_days);
		}

		if ($min_price) {
			$projects_query = $projects_query
					->where('min_price','>=' ,$request->min_price)
					->where('max_price','<=' ,$request->max_price);
		}

		if ($exp !== []) {
			$projects_query = $projects_query
				->whereIn('exp', $request->exp);
		}

		$projects = $projects_query
					->where('active', 'active')
					->groupBy('projects.id')
					->latest('projects.id')
					->cursorPaginate(5);

		if ($search && Auth::check()) {
			DB::table('searches')
				->insert([
					'search'     => $search,
					'user_id'    => $auth_id,
					'created_at' => now(),
				]);
		}

		$cursor = $this->getCursor($projects);

		if ($request->ajax()) {
			$view = view('users.project.index_projects', compact('projects'))->render();

			return ['view' => $view, 'cursor' => $cursor];
		}

		return [
			'projects'      => $projects,
			'search'        => $search,
			'search'        => $search,
			'min_price'     => $min_price,
			'max_price'     => $max_price,
			'num_of_days'   => $num_of_days,
			'exp'           => $exp,
			'cursor'        => $cursor,
		];
	}

	//MARK:storeProject
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
		try {
			DB::beginTransaction();
			$slug = $this->createSlug('projects', 'title', $request->title);

			$project_data = $request->safe()->only(['title', 'content']) +
						[
							'user_id'    => Auth::id(),
							'created_at' => now(),
							'slug'       => $slug,
						];

			$project_id  = DB::table('projects')->insertGetId($project_data);

			$project_info_data = ['project_id' => $project_id]  +
					$request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']);

			DB::table('project_infos')->insert($project_info_data);

			$fileRepository->insert_file($request, 'project_files', 'project_id', $project_id);

			$skillRepository->storeSkill($request, 'project_skill', 'project_id', $project_id);
			DB::commit();

			Log::info('database commit');
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is ' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	//MARK: showProject
	public function showProject(string $slug):array
	{
		$project = DB::table('projects')
			->select(
				'projects.*',
				'projects.content as project_body',
				'project_infos.*',
				'project_infos.num_of_days as time',
				'location',
				'card_num',
				'users.name',
				DB::raw('IFNULL(ROUND(AVG(rate), 1),0) as review'),
				DB::raw('GROUP_CONCAT(DISTINCT skill) as skills_names'),
				DB::raw('GROUP_CONCAT(DISTINCT Concat(file,":",project_files.type))  as files'),
			)
			->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
			->join('skills', 'project_skill.skill_id', '=', 'skills.id')
			->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
			->leftJoin('project_files', 'projects.id', '=', 'project_files.project_id')
			->join('users', 'users.id', '=', 'projects.user_id')
			->join('user_infos', 'users.id', '=', 'user_infos.user_id')
			->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
			->where('projects.slug', $slug)
			->groupBy('projects.id')
			->first();

		if (!$project) {
			throw new GeneralNotFoundException('project');
		}

		$proposals = DB::table('proposals')
					->select(
						'proposals.*',
						'location',
						'card_num',
						'name',
						'slug',
						DB::raw('IFNULL(ROUND(AVG(rate), 1),0) as review'),
					)
					->join('users', 'users.id', '=', 'proposals.user_id')
					->join('user_infos', 'users.id', '=', 'user_infos.user_id')
					->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
					->where('proposals.project_id', $project->id)
					->groupBy('proposals.id')
					->orderBy('proposals.id')
					->cursorPaginate(3);

		$cursor = $this->getCursor($proposals);

		return ['proposals' => $proposals, 'project' => $project, 'cursor' => $cursor];
	}

	//MARK:editProject
	public function editProject(string $slug):\stdClass
	{
		$project = DB::table('projects')
				->select(
					'projects.id',
					'title',
					'content',
					'project_infos.*',
					DB::raw('GROUP_CONCAT(DISTINCT Concat(file,":",project_files.type)) as files'),
					DB::raw('GROUP_CONCAT(DISTINCT Concat(skills.skill,":",project_skill.id)) as skills')
				)
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->leftJoin('project_files', 'projects.id', '=', 'project_files.project_id')
				->leftJoin('project_skill', 'projects.id', '=', 'project_skill.project_id')
				->leftJoin('skills', 'project_skill.skill_id', '=', 'skills.id')
				->where('projects.slug', $slug)
				->groupBy('projects.id')
				->first();

		if (!$project) {
			throw new GeneralNotFoundException('project');
		}

		return $project;
	}

	//MARK:   updateProject
	public function updateProject(
		ProjectRequest $request,
		FileRepositoryInterface $fileRepository,
		SkillRepositoryInterface $skillRepository,
		string $slug,
	):void {
		try {
			$project_data      = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
			$project_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']);

			$project_query = DB::table('projects')->where('slug', $slug);
			$project       = $project_query->first();

			if (!$project) {
				throw new GeneralNotFoundException('project');
			}

			DB::beginTransaction();

			$project_query->update($project_data);

			DB::table('project_infos')->where('project_id', $project->id)->update($project_info_data);

			$fileRepository->insert_file($request, 'project_files', 'project_id', $project->id);

			$skillRepository->storeSkill($request, 'project_skill', 'project_id', $project->id);
			DB::commit();

			Log::info('database commit');
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	//MARK: deleteProject
	public function deleteProject(string $slug):void
	{
		$project_query = DB::table('projects')->where('slug', $slug);
		$project       = $project_query->first();

		if (!$project) {
			throw new GeneralNotFoundException('project');
		}

		$project_query->delete();
	}
}
