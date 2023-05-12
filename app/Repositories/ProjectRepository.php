<?php

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectRepository implements ProjectRepositoryInterface
{
	####################################   storeUserInfo   #####################################
	public function getProjects(Request $request):View|JsonResponse
	{
		$projects = DB::table('projects')
				->select(
					'projects.id',
					'title',
					'content',
					'projects.created_at',
					'min_price',
					'max_price',
					'exp',
					'num_of_days',
					'location',
					'card_num'
				)
				->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
				->join('users', 'users.id', '=', 'projects.user_id')
				->join('user_infos', 'users.id', '=', 'user_infos.user_id')
				->paginate(10);

		if ($request->ajax()) {
			$view = view('users.project.index_projects', compact('projects'))->render();

			return response()->json(['view' => $view]);
		}

		return view('users.project.index', compact('projects'));
	}

	####################################   storeUserInfo   #####################################
	public function storeProject(ProjectRequest $request):void
	{
		$Projects    = $request->input('Projects_name');

		$Projects_arr = [];
		foreach ($Projects as $Project) {
			$Projects_arr[] = ['Project_id' => $Project, 'user_id' => Auth::id()];
		}

		DB::table('user_Project')->insert($Projects_arr);
	}

	####################################   updateUserInfo   #####################################
	public function deleteProject(int $id):void
	{
		DB::table('user_Project')->where('id', $id)->delete();
	}
}
