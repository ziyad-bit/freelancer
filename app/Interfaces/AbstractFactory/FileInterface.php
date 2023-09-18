<?php

namespace App\Interfaces\AbstractFactory;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileInterface
{
	public function insert(Request $request, int $project_id):void;
	public function download( string $file ):StreamedResponse;
	public function destroy(string $file):JsonResponse;
}
