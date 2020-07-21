<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';
    const PUT_METHOD = 'PUT';
    const PATCH_METHOD = 'PATCH';
    const DELETE_METHOD = 'DELETE';

    /**
     * This will override the Request validator response
     *
     * @param Validator $validator
     * @throws ValidationResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationResponseException('Invalid form values!', $validator->errors());
    }

    public function messages()
    {
        return [
            '*.current_password' => 'The :attribute field is invalid.',
            'password.new_password' => 'The :attribute field cannot be the same with your current password.',
            '*.*_regex' => 'The :attribute field is invalid.',
            '*.user_*' => 'The selected :attribute is invalid.',
        ];
    }
}
