<?php

namespace App\Repositories;

use App\Http\Requests\{ProjectRequest, SearchRequest};
use App\Interfaces\Repository\{FileRepositoryInterface, ProjectRepositoryInterface, SkillRepositoryInterface};
use App\Traits\GetCursor;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class ProjectRepository implements ProjectRepositoryInterface
{
	use GetCursor;

	//MARK:   getProjects
	public function fetchProjects(SearchRequest $request):array|JsonResponse
	{
		$auth_id     = Auth::id();
		$searchTitle = $request->input('search');
		$projects    = DB::table('projects')
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
				->leftJoin('proposals', 'projects.id', '=', 'proposals.project_id')
				->when(
					$searchTitle == null,
					function ($query) use ($auth_id) {
						$query->join('user_skill', function ($join) use ($auth_id) {
							$join->on('project_skill.skill_id', '=', 'user_skill.skill_id')
								->where('user_skill.user_id', '=', $auth_id);
						});
					},
					function ($query) use ($searchTitle) {
						$query->where(function ($query) use ($searchTitle) {
							$query->where('title', 'LIKE', "{$searchTitle}%");
						});
					}
				)
				->groupBy('projects.id')
				->latest('projects.id')
				->cursorPaginate(10);

		if ($searchTitle) {
			DB::table('searches')
				->insert(['search' => $searchTitle, 'user_id' => $auth_id, 'created_at' => now()]);
		}

		$cursor = $this->getCursor($projects);

		if ($request->ajax()) {
			$view = view('users.project.index_projects', compact('projects'))->render();

			return response()->json(['view' => $view, 'cursor' => $cursor]);
		}

		return ['projects'=>$projects,'searchTitle'=>$searchTitle,'cursor'=>$cursor];
	}

	//MARK:   storeProject
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
		$project_data = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
		$project_id   = DB::table('projects')->insertGetId($project_data);

		$project_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']) + ['project_id' => $project_id];

		DB::table('project_infos')->insert($project_info_data);

		$fileRepository->insert_file($request, 'project_files', 'project_id', $project_id);

		$skillRepository->storeSkill($request, 'project_skill', 'project_id', $project_id);
	}

	//MARK: showProject
	public function showProject(int $id):JsonResponse|RedirectResponse|array
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
			->where('projects.id', $id)
			->groupBy('projects.id')
			->first();

		if (!$project) {
			return to_route('project.index_posts')->with('error', 'project not found');
		}

		$proposals = DB::table('proposals')
					->select(
						'proposals.*',
						'location',
						'card_num',
						'name',
						DB::raw('IFNULL(ROUND(AVG(rate), 1),0) as review'),
					)
					->join('users', 'users.id', '=', 'proposals.user_id')
					->join('user_infos', 'users.id', '=', 'user_infos.user_id')
					->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
					->where('proposals.project_id', $id)
					->groupBy('proposals.id')
					->orderBy('proposals.id')
					->cursorPaginate(3);

		$cursor = $this->getCursor($proposals);

		if (request()->ajax()) {
			$view = view('users.project.show_proposals', compact('proposals','project'))->render();

			return response()->json(['view' => $view,'cursor'=>$cursor]);
		}

		return ['proposals' => $proposals, 'project' => $project, 'cursor' => $cursor];
	}

	//MARK:editProject
	public function editProject(int $id, Collection $skills):object
	{
		$project = DB::table('projects')
				->select(
					'projects.id',
					'title',
					'content',
					'project_infos.*',
					DB::raw('GROUP_CONCAT(DISTINCT Concat(file,":",project_files.type))  as files'),
				)
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->leftJoin('project_files', 'projects.id', '=', 'project_files.project_id')
				->where('projects.id', $id)
				->groupBy('projects.id')
				->first();

		if (!$project) {
			return redirect()->back()->with('error', 'project not found');
		}

		$skills = DB::table('skills')
				->join('project_skill', 'skills.id', '=', 'project_skill.skill_id')
				->where('project_skill.project_id', $id)
				->get();

		$project->skills = $skills;

		return $project;
	}

	//MARK:   updateProject
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse|null
	{
		$project_data      = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
		$project_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']);

		$project_query = DB::table('projects')->where('id', $id);
		$project       = $project_query->first();

		if (!$project) {
			return redirect()->back()->with('error', 'project not found');
		}

		$project_query->update($project_data);

		DB::table('project_infos')->where('project_id', $id)->update($project_info_data);

		$fileRepository->insert_file($request, 'project_files', 'project_id', $id);

		$skillRepository->storeSkill($request, 'project_skill', 'project_id', $id);

		return null;
	}

	//MARK: deleteProject
	public function deleteProject(int $id):RedirectResponse|null
	{
		$project_query = DB::table('projects')->where('id', $id);
		$project       = $project_query->first();

		if (!$project) {
			return redirect()->back()->with('error', 'project not found');
		}

		$project_query->delete();

		return null;
	}
}
