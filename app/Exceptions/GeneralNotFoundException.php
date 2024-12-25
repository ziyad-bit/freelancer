<?php

namespace App\Exceptions;

use Exception;

class GeneralNotFoundException extends Exception
{
	public function __construct(string $record)
	{
		$message = $record . ' not found';
		parent::__construct($message);
	}
}
