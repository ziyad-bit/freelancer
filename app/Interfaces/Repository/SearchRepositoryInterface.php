<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\SearchRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface SearchRepositoryInterface
{
	public function searchChatroom(SearchRequest $request):array;
	public function show_recent_projects():string;
	public function show_projects(SearchRequest $request):string;
}
