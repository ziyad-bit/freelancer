<?php

namespace App\Repositories\Admins;

use App\Classes\Project;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\{ProjectRequest};
use App\Interfaces\Repository\Admins\ProjectRepositoryInterface;
use App\Interfaces\Repository\{FileRepositoryInterface, SkillRepositoryInterface};
use App\Traits\{Slug};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\{Auth, DB, Log};
use stdClass;

class ProjectRepository implements ProjectRepositoryInterface
{
	use Slug;

	//MARK: indexProject
	public function indexProject():LengthAwarePaginator
	{
		return DB::table('projects')
			->select('projects.id', 'title', 'created_at', 'slug', 'active')
			->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
			->latest('projects.id')
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
							'created_at'  => now(),
							'slug'        => $slug,
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
		return Project::show($slug);
	}

	//MARK: editProject
	public function activeProject(int $id):void
	{
		$project_query = DB::table('project_infos')->where('project_id', $id);

		if (!$project_query->exists()) {
			throw new GeneralNotFoundException('Project');
		}

		$project_query->update(['active' => 'active']);
	}

	//MARK: editProject
	public function editProject(string $slug):stdClass
	{
		$project = Project::edit($slug);

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
		$Project_id    = $project_query->value('id');

		if (!$Project_id) {
			throw new GeneralNotFoundException('Project');
		}

		$project_query->delete();
	}
}
