<?php

namespace App\Http\Controllers;

use App\Exceptions\RepositoryRequestException;
use App\Http\Requests\AuthRequest;
use App\Repositories\AuthRepository;

class AuthController extends BaseController
{
    const REGISTRATION_MESSAGE_SUCCESS = 'User successfully registered!';
    const LOG_OUT_MESSAGE_SUCCESS = 'Successfully logged out.';
    const REGISTRATION_MESSAGE_FAILED = 'User Registration Failed!';
    const LOG_OUT_MESSAGE_FAILED = 'Failed to logout user invalid token!';

    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @param AuthRequest $authRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws RepositoryRequestException
     */
    public function register(AuthRequest $authRequest)
    {
        if ($this->authRepository->registerUser($authRequest)) {
            $this->flagAction = true;

            return $this->sendResponseOk([], self::REGISTRATION_MESSAGE_SUCCESS);
        }

        throw new RepositoryRequestException(
            __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' .
            self::REGISTRATION_MESSAGE_FAILED . print_r($authRequest->all(), true)
        );
    }

    /**
     * @param AuthRequest $authRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UnauthorizedException
     */
    public function login(AuthRequest $authRequest)
    {
        $this->flagAction = true;

        return $this->sendResponseOk($this->authRepository->loginUser($authRequest));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws RepositoryRequestException
     */
    public function logout()
    {
        if ($this->authRepository->logoutUser()) {
            return $this->sendResponseOk([], self::LOG_OUT_MESSAGE_SUCCESS);
        }

        throw new RepositoryRequestException(
            __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' .
            self::LOG_OUT_MESSAGE_FAILED
        );
    }
}
