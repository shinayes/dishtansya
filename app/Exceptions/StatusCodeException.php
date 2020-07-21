<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StatusCodeException extends BaseException
{
    public function report()
    {
        Log::error("[STATUS_CODE_EXCEPTION] - ". $this->getMessage());
    }

    public static function getStatusCode()
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
