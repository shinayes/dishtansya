<?php namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class BaseException extends Exception
{
    const BAD_REQUEST_ERROR_MESSAGE = "Invalid or malformed request!";

    const INTERNAL_SERVER_ERROR_MESSAGE = "Internal Server Error. Please contact your System Administrator.";

    const UNAUTHORIZED_ERROR_MESSAGE = "Invalid token";

    public $status;

    public $status_code;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->setStatus();
    }

    /**
     * @param string $status
     * @return string
     */
    public function setStatus($status = 'error')
    {
        return $this->status = $status;
    }

    /**
     * @throws BaseException
     */
    public static function getStatusCode()
    {
        throw new StatusCodeException(
            "Exception '". get_class(self::class) . "' does not implement getStatusCode method!"
        );
    }

    /**
     * @param string $message
     * @return string $this->message
     */
    public function setMessage($message = 'Bad Request!')
    {
        $this->message = $message;
    }

    /**
     * @return array
     * @throws BaseException
     */
    public function getParams()
    {
        return $this->setParams();
    }

    /**]
     * @return array
     * @throws BaseException
     */
    public function setParams()
    {
        return [
            'status' => $this->getStatus(),
            'status_code' => $this->getStatusCode(),
            'message' => $this->getMessage(),
        ];
    }
}
