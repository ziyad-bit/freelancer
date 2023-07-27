<?php

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\FileRepositoryInterface;
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

	private $fileRepository;

	public function __construct(FileRepositoryInterface $fileRepository)
	{
		$this->fileRepository = $fileRepository;
	}

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
	public function storeProject(ProjectRequest $request):void
	{
		$project_data = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
		$project_id   = DB::table('projects')->insertGetId($project_data);

		$project_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']) + ['project_id' => $project_id];

		DB::table('project_infos')->insert($project_info_data);

		$this->fileRepository->insertAnyFile($request, $project_id);

		$skills_arr = [];
		$skills     = $request->input('skill_id');

		if ($skills != []) {
			foreach ($skills as $skill) {
				$skills_arr[] = [
					'skill_id'   => $skill,
					'project_id' => $project_id,
				];
			}

			DB::table('project_skill')->insert($skills_arr);
		}
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
					DB::raw('GROUP_CONCAT(DISTINCT project_files.file) as files_names'),
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

		$auth_proposal = DB::table('proposals')
					->select('proposals.*', 'location', 'card_num', 'name', 'review', )
					->join('users', 'users.id', '=', 'proposals.user_id')
					->join('user_infos', 'users.id', '=', 'user_infos.user_id')
					->where(['project_id' => $project->id, 'proposals.user_id' => Auth::id()])
					->first();

		$project->proposal = $auth_proposal;

		return view('users.project.show', compact('project'));
	}

	####################################     editProject    #####################################
	public function editProject(int $id):object
	{
		$project = DB::table('projects')
				->select(
					'title',
					'content',
					'project_infos.*',
					DB::raw('GROUP_CONCAT(DISTINCT project_files.file) as files_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.video) as videos_names'),
					DB::raw('GROUP_CONCAT(DISTINCT project_files.image) as images_names'),
				)
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->leftJoin('project_files', 'projects.id', '=', 'project_files.project_id')
				->where('projects.id', $id)
				->groupBy('projects.id')
				->first();

		$skills = DB::table('skills')
				->join('project_skill', 'skills.id', '=', 'project_skill.skill_id')
				->where('project_skill.project_id', $id)
				->get();

		$project->skills = $skills;

		return $project;
	}

	####################################   updateUserInfo   #####################################
	public function deleteProject(int $id):void
	{
		DB::table('user_Project')->where('id', $id)->delete();
	}
}
