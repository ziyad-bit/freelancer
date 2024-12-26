<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Interfaces\Repository\SearchRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
	public function __construct(private SearchRepositoryInterface $SearchRepository)
	{
		$this->middleware(['auth','verifyEmail'])->only('index_chatrooms');
	}

	// MARK: index_chatrooms
	public function index_chatrooms(SearchRequest $request):JsonResponse
	{
		$data = $this->SearchRepository->searchChatroom($request);

		return response()->json($data);
	}

	public function recent_search_projects():JsonResponse
	{
		$view = $this->SearchRepository->show_recent_projects();

		return response()->json(['view' => $view]);
	}

	public function index_projects(SearchRequest $request):JsonResponse
	{
		$view = $this->SearchRepository->show_projects_after_typing($request);

		return response()->json(['view' => $view]);
	}
}
