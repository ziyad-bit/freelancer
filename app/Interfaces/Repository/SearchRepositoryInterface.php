<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\SearchRequest;

interface SearchRepositoryInterface
{
	public function searchChatroom(SearchRequest $request):array;
}
