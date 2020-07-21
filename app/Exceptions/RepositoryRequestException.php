<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RepositoryRequestException extends BaseException
{
    public function report()
    {
        $message = $this->getMessage();

        $this->setMessage(self::BAD_REQUEST_ERROR_MESSAGE);

        Log::error("[REPOSITORY_REQUEST_EXCEPTION] - {$message}");
    }

    public static function getStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
