<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\SearchRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface SearchRepositoryInterface
{
	public function searchChatroom(SearchRequest $request):array;
}
