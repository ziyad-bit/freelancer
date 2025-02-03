<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array<int, class-string<Throwable>>
	 */
	protected $dontReport = [

	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->renderable(function (GeneralNotFoundException $e) {
			abort(404, $e->getMessage());
		});

		$this->renderable(function (RecordExistException $e) {
			abort(409, $e->getMessage());
		});

		$this->reportable(
			function (Throwable $e) {
				// abort(500, 'Something went wrong');
			}
		);
	}

	public function report(Throwable $exception)
	{
		if ($exception instanceof ValidationException) {
			Log::error('validation error', ['errors' => $exception->errors()]);
		}

		return parent::report($exception);
	}
}
