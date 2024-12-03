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
	public function getProjects(SearchRequest $request):View|JsonResponse
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
							$query->where('title', 'LIKE', "%{$searchTitle}%");
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

		return view('users.project.index', compact('projects', 'cursor', 'searchTitle'));
	}

	//MARK:  createProject
	public function createProject(Collection $skills):View
	{
		return view('users.project.create', compact('skills'));
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
	public function showProject(int $id):object|null
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
					DB::raw('GROUP_CONCAT(DISTINCT file)  as files_name'),
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
					->where('proposals.project_id', $project->id)
					->groupBy('proposals.id')
					->get();

		return view('users.project.show', compact('project', 'proposals'));
	}

	//MARK:   editProject
	public function editProject(int $id, Collection $skills):RedirectResponse|View
	{
		$project = DB::table('projects')
				->select(
					'projects.id',
					'title',
					'content',
					'project_infos.*',
					DB::raw('GROUP_CONCAT(DISTINCT project_files.application) as files_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.video) as videos_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.image) as images_names'),
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

		return view('users.project.edit', compact('project', 'skills'));
	}

	//MARK:   updateProject
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
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

		return redirect()->back()->with('success', 'you updated successfully project');
	}

	//MARK: deleteProject
	public function deleteProject(int $id):RedirectResponse
	{
		$project_query = DB::table('projects')->where('id', $id);
		$project       = $project_query->first();

		if (!$project) {
			return redirect()->back()->with('error', 'project not found');
		}

		$project_query->delete();

		return to_route('project.index_posts')->with('success', 'you deleted successfully project');
	}
}
