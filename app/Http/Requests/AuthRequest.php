<?php

namespace App\Http\Requests;

class AuthRequest extends BaseRequest
{
    const ROUTE_POST_LOGIN = 'login';
    const ROUTE_POST_REGISTER = 'register';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $route = $this->route()->action;

        $return = [];

        switch ($this->method()) {
            case parent::POST_METHOD:
                if ($route['name'] === self::ROUTE_POST_LOGIN) {
                    $return = [
                        'email' => 'required',
                        'password' => 'required',
                    ];
                } elseif ($route['name'] === self::ROUTE_POST_REGISTER) {
                    $return = [
                        'email' => 'required|email|unique:users,email',
                        'password' => 'required|string',
                    ];
                }

                break;
            case parent::PATCH_METHOD:
                break;
        }
        return $return;
    }

    public function messages()
    {
        return [
            'unique' => ':attribute already taken'
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }
}
