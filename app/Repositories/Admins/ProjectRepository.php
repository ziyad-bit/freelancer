<?php

namespace App\Repositories\Admins;

use App\Classes\Project;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\ProjectRepositoryInterface;
use App\Interfaces\Repository\FileRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
use App\Traits\File;
use App\Traits\Slug;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};
use stdClass;

class ProjectRepository implements ProjectRepositoryInterface
{
	use Slug;
	//MARK: getProject
	public function indexProject():LengthAwarePaginator
	{
		return DB::table('projects')
			->select('id', 'title', 'created_at','slug')
			->paginate(10);
	}

	//MARK: storeProject
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
		try {
			DB::beginTransaction();
			$slug = $this->createSlug('projects', 'title', $request->title);

			$project_data = $request->safe()->only(['title', 'content']) +
						[
							'admin_id'    => Auth::guard('admins')->id(),
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
					->paginate(3);

		return ['proposals' => $proposals, 'project' => $project];
	}

	//MARK: editProject
	public function editProject(int $id):stdClass
	{
		$project_query = DB::table('projects')->where('id', $id);
		$Project_id    = $project_query->value('id');

		if (!$Project_id) {
			throw new GeneralNotFoundException('Project');
		}

		return $project_query->first();
	}

	//MARK: updateProject
	public function updateProject(ProjectRequest $request,int $id):void
	{
		$project_query = DB::table('projects')->where('id', $id);
		$Project_id    = $project_query->value('id');

		if (!$Project_id) {
			throw new GeneralNotFoundException('Project');
		}

		if ($request->has('password')) {
			$data = $request->safe()->except('password') + ['password' => $request->password];
		} else {
			$data = $request->validated() + ['updated_at' => now()];
		}

		DB::table('projects')->where('id', $id)->update($data);
	}

	//MARK: deleteProject
	public function deleteProject(int $id):void
	{
		$project_query = DB::table('projects')->where('id', $id);
		$Project_id    = $project_query->value('id');

		if (!$Project_id) {
			throw new GeneralNotFoundException('Project');
		}

		$project_query->delete();
	}
}
