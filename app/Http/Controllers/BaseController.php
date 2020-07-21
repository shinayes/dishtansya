<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const MESSAGE_SUCCESS_CREATED = 'Successfully Created!';
    const MESSAGE_SUCCESS_UPDATED = 'Successfully Updated!';

    public $flagAction = false;
    public $message = '';
    public $error = '';
    public $count = '';

    public function sendJson($unkData, $intHttpStatusCode)
    {
        $response = [];

        if ($this->message) {
            $response['message'] = $this->message;
        }

        if ($this->error) {
            $response['error'] = $this->error;
        }

        if (sizeof($unkData) > 0) {
            $response['data'] = $unkData;
        }

        return response()->json($response, $intHttpStatusCode, ['Content-Type' => 'application/json']);
    }

    protected function sendBadRequest(array $arrData, $message = '')
    {
        $this->error = $message;

        if ($message === '') {
            $this->error = 'Your browser sent a request that this server could not understand.';
        }

        return $this->sendJson($arrData, Response::HTTP_BAD_REQUEST);
    }

    protected function sendNotFound(array $arrData)
    {
        $this->error = 'Request not found!';

        return $this->sendJson($arrData, Response::HTTP_NOT_FOUND);
    }

    protected function sendUnauthorized(array $arrData, $message = '')
    {
        $this->error = $message;

        if ($message === '') {
            $this->error = 'Request unauthorized!';
        }

        return $this->sendJson($arrData, Response::HTTP_UNAUTHORIZED);
    }

    protected function sendInternalError(array $arrData)
    {
        $this->error = 'There might be an error in the server. Please contact your system administrator.';

        return $this->sendJson($arrData, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function sendResponseOk(array $arrData, $message = '')
    {
        $responseCode = Response::HTTP_OK;

        $this->message = self::MESSAGE_SUCCESS_UPDATED;

        if ($this->flagAction) {
            $responseCode = Response::HTTP_CREATED;

            $this->message = self::MESSAGE_SUCCESS_CREATED;
        }

        if ($message !== '') {
            $this->message = $message;
        }

        return $this->sendJson($arrData, $responseCode);
    }

    /*
     * from array to object
     * Numeric key will accessible by adding underscore prefix
     * $array[0] => $array->_0
     */
    public function makeObject($array)
    {
        $returnObj = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $returnObj->{"_{$key}"} = $value;
                continue;
            }
            $returnObj->{$key} = $value;
        }
        return $returnObj;
    }
}
