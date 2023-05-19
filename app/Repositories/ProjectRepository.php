<?php

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use App\Traits\GetCursor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
				->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->join('skills', 'project_skill.skill_id', '=', 'skills.id')
				->join('users', 'users.id', '=', 'projects.user_id')
				->join('user_infos', 'users.id', '=', 'user_infos.user_id')
				->join('proposals', 'projects.id', '=', 'proposals.project_id')
				->join('user_skill', 'project_skill.skill_id', '=', 'user_skill.skill_id')
				->whereIn('projects.id', $projects_ids)
				->groupBy('projects.id')
				->latest()
				->cursorPaginate(10);

		$cursor = $this->getCursor($projects);

		if ($request->ajax()) {
			$view = view('users.project.index_projects', compact('projects'))->render();

			return response()->json(['view' => $view, 'cursor' => $cursor]);
		}

		return view('users.project.index', compact('projects', 'cursor'));
	}

	####################################   storeProject   #####################################
	public function storeProject(ProjectRequest $request):void
	{
		$Projects    = $request->input('Projects_name');

		$Projects_arr = [];
		foreach ($Projects as $Project) {
			$Projects_arr[] = ['Project_id' => $Project, 'user_id' => Auth::id()];
		}

		DB::table('user_Project')->insert($Projects_arr);
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
					'name',
					'review',
					DB::raw('GROUP_CONCAT(DISTINCT skill) as skills_names'),
					DB::raw('COUNT(DISTINCT proposals.id) as proposals_count')
				)
				->join('project_skill', 'projects.id', '=', 'project_skill.project_id')
				->join('skills', 'project_skill.skill_id', '=', 'skills.id')
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->join('users', 'users.id', '=', 'projects.user_id')
				->join('user_infos', 'users.id', '=', 'user_infos.user_id')
				->join('proposals', 'projects.id', '=', 'proposals.project_id')
				->where('projects.id', $id)
				->groupBy('projects.id')
				->first();

		if ($project) {
			$auth_proposal = DB::table('proposals')
						->select('proposals.*', 'location', 'card_num', 'name', 'review', )
						->join('users', 'users.id', '=', 'proposals.user_id')
						->join('user_infos', 'users.id', '=', 'user_infos.user_id')
						->where(['project_id' => $project->id, 'proposals.user_id' => Auth::id()])
						->first();

			$project->proposal = $auth_proposal;
		}

		return $project;
	}

	####################################   updateUserInfo   #####################################
	public function deleteProject(int $id):void
	{
		DB::table('user_Project')->where('id', $id)->delete();
	}
}
