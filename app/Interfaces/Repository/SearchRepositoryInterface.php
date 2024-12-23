<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\SearchRequest;

interface SearchRepositoryInterface
{
	public function searchChatroom(SearchRequest $request):array;
	public function show_recent_projects():string;
	public function show_projects_after_typing(SearchRequest $request):string;
}
