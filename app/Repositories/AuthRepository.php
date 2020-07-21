<?php

namespace App\Repositories;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\AuthRequest;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository extends BaseRepository
{
    /**
     * AuthRepository constructor.
     */
    public function __construct()
    {
        $this->setModel(User::class);
    }

    const VALID_LOGIN_PARAMS = [
        'email',
        'password'
    ];

    const VALID_REGISTER_PARAMS = [
        'email',
        'password'
    ];

    const ADMIN_TOKEN = 'Dishtansya Access Token';

    const INVALID_CREDENTIALS = 'invalid_credentials';
    const ACCOUNT_SUSPENDED = 'account_suspended';
    const ACCOUNT_UNVERIFIED = 'account_unverified';

    const FAILED_MESSAGES = [
        'invalid_credentials' => 'Invalid username or password.',
        'account_suspended' => 'Account suspended please contact your system administrator.',
        'account_unverified' => 'Email address not yet verified.'
    ];

    protected $model = User::class;

    public function registerUser($request)
    {
        $params = $request->only(self::VALID_REGISTER_PARAMS);

        $params['password'] = Hash::make($params['password']);

        $details = ['email' => $params['email']];

        SendEmail::dispatchNow($details);

        return factory(User::class)->create($params);
    }

    /**
     * @param AuthRequest $authRequest
     * @return array
     * @throws UnauthorizedException
     */
    public function loginUser(AuthRequest $authRequest)
    {
        $tokenResult = null;

        $credentials = $authRequest->only(self::VALID_LOGIN_PARAMS);

        if (!Auth::attempt($credentials)) {
            if ($user = $this->findBy('email', $authRequest->email)) {
                if ($user->attempt < config('dishtansya.max_login_attempt')) {
                    $user->attempt++;
                } else {
                    $user->status = config('dishtansya.user_status.suspended');
                }
                $user->save();
            }

            throw new UnauthorizedException($this->getUnauthorizedFailedMessage(self::INVALID_CREDENTIALS));
        }

        $user = request()->user();

        if ($user->email_verified_at === null) {
            throw new UnauthorizedException($this->getUnauthorizedFailedMessage(self::ACCOUNT_UNVERIFIED));
        }

        if ($user->status === config('dishtansya.user_status.suspended')) {
            throw new UnauthorizedException($this->getUnauthorizedFailedMessage(self::ACCOUNT_SUSPENDED));
        }

        if ($tokenResult = $this->createToken(self::ADMIN_TOKEN)) {
            return [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer'
            ];
        }

        throw new UnauthorizedException($this->getUnauthorizedFailedMessage(self::INVALID_CREDENTIALS));
    }

    public function logoutUser()
    {
        if (request()->user()) {
            $this->revokeToken();
        }

        return true;
    }

    private function getUnauthorizedFailedMessage($type)
    {
        $messages = self::FAILED_MESSAGES;

        return $messages[$type];
    }

    private function createToken()
    {
        $token = Str::random(60);

        request()->user()->forceFill([
            'api_token' =>  hash('sha256', $token),
        ])->save();

        return $token;
    }

    private function revokeToken()
    {
        request()->user()->forceFill([
            'api_token' => null,
        ])->save();

        return true;
    }
}
