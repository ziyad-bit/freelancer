<?php

namespace App\Classes;

use App\Exceptions\GeneralNotFoundException;
use Illuminate\Support\Facades\DB;
use stdClass;

class Project
{
	public static function show(string $slug):array
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

	public static function edit(string $slug):stdClass
	{
		return DB::table('projects')
			->select(
				'projects.id',
				'projects.slug',
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
	}
}
