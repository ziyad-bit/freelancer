<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileRepositoryInterface
{
	public function download_file(string $file):StreamedResponse;
	public function insertAnyFile(Request $request, int $project_id):void;
	public function upload_file(Request $request):array;
	public function destroy_file(string $file):JsonResponse;
}
