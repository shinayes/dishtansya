<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RepositoryInternalException extends BaseException
{
    public function report()
    {
        $message = $this->getMessage();

        $this->setMessage(self::INTERNAL_SERVER_ERROR_MESSAGE);

        Log::error("[REPOSITORY_INTERNAL_EXCEPTION] - {$message}");
    }

    public static function getStatusCode()
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
