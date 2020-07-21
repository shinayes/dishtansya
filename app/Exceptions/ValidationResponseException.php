<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ValidationResponseException extends BaseException
{
    private $errors;

    public function __construct($message, $errors)
    {
        $this->setMessage($message);

        $this->errors = $errors;
    }

    public function report()
    {
        $message = $this->getMessage();

        // $this->setMessage(self::BAD_REQUEST_ERROR_MESSAGE);

        Log::error("[VALIDATION_RESPONSE_EXCEPTION] - {$message}");
    }

    public static function getStatusCode()
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    /**
     * @return array
     * @throws BaseException
     */
    public function setParams()
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors
        ];
    }
}
