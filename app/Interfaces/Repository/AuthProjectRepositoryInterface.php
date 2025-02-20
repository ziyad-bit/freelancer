<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\DebateRequest;
use Illuminate\Support\Collection;

interface AuthProjectRepositoryInterface
{
	public function indexAuthProjects():Collection;
	public function storeDebateData(DebateRequest $request):void;
}
