<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UnauthorizedException extends BaseException
{

    public function __construct($message)
    {
        $this->setMessage($message);
    }

    public function report()
    {
        $message = $this->getMessage();

        //$this->setMessage(self::UNAUTHORIZED_ERROR_MESSAGE);

        Log::error("[UNAUTHORIZED_EXCEPTION] - {$message}");
    }

    public static function getStatusCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    /**
     * @return array
     */
    public function setParams()
    {
        return [
            'message' => $this->getMessage(),
        ];
    }
}
