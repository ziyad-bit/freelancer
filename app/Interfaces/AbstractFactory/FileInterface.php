<?php

namespace App\Interfaces\AbstractFactory;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileInterface
{
	public function insert(Request $request, int $project_id):void;
	public function download(string $file):StreamedResponse;
	public function destroy(string $file):JsonResponse;
}
