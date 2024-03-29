<?php

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, ProjectRepositoryInterface, SkillRepositoryInterface};
use App\Traits\GetCursor;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class ProjectRepository implements ProjectRepositoryInterface
{
	use GetCursor;

	####################################   getProjects   #####################################
	public function getProjects(Request $request):View|JsonResponse
	{
		$projects_ids = DB::table('projects')
				->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
				->join('user_skill', 'project_skill.skill_id', '=', 'user_skill.skill_id')
				->where('user_skill.user_id', Auth::user()->id)
				->pluck('projects.id')
				->toArray();

		$projects = DB::table('projects')
				->select(
					'projects.*',
					'project_infos.*',
					'location',
					'card_num',
					'review',
					DB::raw('GROUP_CONCAT(DISTINCT skill) as skills_names'),
					DB::raw('COUNT(DISTINCT proposals.id) as proposals_count')
				)
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
				->join('skills', 'project_skill.skill_id', '=', 'skills.id')
				->join('users', 'users.id', '=', 'projects.user_id')
				->join('user_infos', 'users.id', '=', 'user_infos.user_id')
				->leftjoin('proposals', 'projects.id', '=', 'proposals.project_id')
				->whereIn('projects.id', $projects_ids)
				->groupBy('projects.id')
				->latest()
				->cursorPaginate(100);

		$cursor = $this->getCursor($projects);

		if ($request->ajax()) {
			$view = view('users.project.index_projects', compact('projects'))->render();

			return response()->json(['view' => $view, 'cursor' => $cursor]);
		}

		return view('users.project.index', compact('projects', 'cursor'));
	}

	####################################   storeProject   #####################################
	public function createProject(Collection $skills):View
	{
		return view('users.project.create', compact('skills'));
	}

	####################################   storeProject   #####################################
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
		$project_data = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
		$project_id   = DB::table('projects')->insertGetId($project_data);

		$project_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']) + ['project_id' => $project_id];

		DB::table('project_infos')->insert($project_info_data);

		$fileRepository->insertAnyFile($request, $project_id);

		$skillRepository->storeSkill($request, 'project_skill', 'project_id', $project_id);
	}

	####################################   showProject   #####################################
	public function showProject(int $id):object|null
	{
		$project = DB::table('projects')
				->select(
					'projects.*',
					'projects.content as project_body',
					'project_infos.num_of_days as time',
					'project_infos.*',
					'location',
					'card_num',
					'users.name',
					'review',
					DB::raw('GROUP_CONCAT(DISTINCT skill) as skills_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.application) as files_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.video) as videos_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.image) as images_names'),
					DB::raw('COUNT(DISTINCT proposals.id) as proposals_count')
				)
				->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
				->join('skills', 'project_skill.skill_id', '=', 'skills.id')
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->leftJoin('project_files', 'projects.id', '=', 'project_files.project_id')
				->join('users', 'users.id', '=', 'projects.user_id')
				->join('user_infos', 'users.id', '=', 'user_infos.user_id')
				->leftJoin('proposals', 'projects.id', '=', 'proposals.project_id')
				->where('projects.id', $id)
				->groupBy('projects.id')
				->first();

		if (!$project) {
			return to_route('project.index_posts')->with('error', 'project not found');
		}

		$proposals = DB::table('proposals')
					->select('proposals.*', 'location', 'card_num', 'name', 'review', )
					->join('users', 'users.id', '=', 'proposals.user_id')
					->join('user_infos', 'users.id', '=', 'user_infos.user_id')
					->where('project_id', $project->id)
					->get();

		$project->proposals = $proposals;

		$auth_proposal = DB::table('proposals')
					->where(['project_id' => $project->id, 'user_id' => Auth::id()])
					->value('id');

		return view('users.project.show', compact('project', 'auth_proposal'));
	}

	####################################     editProject    #####################################
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

	####################################   updateProject   #####################################
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		$project_data      = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
		$project_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']);

		$project = DB::table('projects')->where('id', $id)->first();

		if (!$project) {
			return redirect()->back()->with('error', 'project not found');
		}

		DB::table('projects')->where('id', $id)->update($project_data);

		DB::table('project_infos')->where('project_id', $id)->update($project_info_data);

		$fileRepository->insertAnyFile($request, $id);

		$skillRepository->storeSkill($request, 'project_skill', 'project_id', $id);

		return redirect()->back()->with('success', 'you updated successfully project');
	}

	####################################   deleteProject   #####################################
	public function deleteProject(int $id):RedirectResponse
	{
		$project = DB::table('projects')->where('id', $id)->first();

		if (!$project) {
			return redirect()->back()->with('error', 'project not found');
		}

		DB::table('projects')->where('id', $id)->delete();

		return to_route('project.index_posts')->with('success', 'you deleted successfully project');
	}
}
