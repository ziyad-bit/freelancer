<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Interfaces\Repository\SearchRepositoryInterface;
use App\Traits\GetCursor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
	public function __construct(private SearchRepositoryInterface $SearchRepository)
	{
		$this->middleware('auth');
	}

	// MARK: index_chatrooms
	public function index_chatrooms(SearchRequest $request):JsonResponse
	{
		$view = $this->SearchRepository->searchChatroom($request);

		return response()->json([
			'chat_room_view' => $view['chat_room_view'],
			'chat_box_view'  => $view['chat_box_view'],
		]);
	}

	public function recent_search_projects():JsonResponse
	{
		$view = $this->SearchRepository->show_recent_projects();

		return response()->json(['view'=>$view]);
	}

	public function index_projects(SearchRequest $request):JsonResponse
	{
		$view = $this->SearchRepository->show_projects($request);

		return response()->json(['view'=>$view]);
	}
}
