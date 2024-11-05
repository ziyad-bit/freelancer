<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Interfaces\Repository\SearchRepositoryInterface;
use App\Traits\GetCursor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
	use GetCursor;

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

	// MARK: index_chatrooms
	public function index_projects(SearchRequest $request):View
	{
		$projects = $this->SearchRepository->searchProjects($request);
		$cursor   = $this->getCursor($projects);

		return view('users.project.index', compact('projects', 'cursor'));
	}
}
