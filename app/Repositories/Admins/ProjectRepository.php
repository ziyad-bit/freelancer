<?php

namespace App\Repositories\Admins;

use App\Classes\Project;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\ProjectRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Traits\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use stdClass;

class ProjectRepository implements ProjectRepositoryInterface
{
	//MARK: getProject
	public function indexProject():LengthAwarePaginator
	{
		return DB::table('projects')
			->select('id', 'title', 'created_at')
			->paginate(10);
	}

	//MARK: storeProject
	public function storeProject(ProjectRequest $request):void
	{
		$data = $request->validated() +
			[
				'user_id' => Auth::id(),
				'created_at' => now(),
			];

		DB::table('projects')->insert($data);
	}

	//MARK: showProject
	public function showProject(int $id):stdClass
	{
		$project_query = DB::table('projects')->where('id', $id);

		if (!$project_query->exists()) {
			throw new GeneralNotFoundException('Project');
		}

		return DB::table('projects')->where('id',$id)->first();
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
