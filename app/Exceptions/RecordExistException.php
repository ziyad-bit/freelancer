<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class RecordExistException extends Exception
{
    public function __construct(string $record)
    {
        $message =$record . " already exist";
        parent::__construct($message);
    }
}
