<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class GeneralNotFoundException extends Exception
{
    public function __construct(string $record)
    {
        $message =$record . " not found";
        parent::__construct($message);
    }
}
