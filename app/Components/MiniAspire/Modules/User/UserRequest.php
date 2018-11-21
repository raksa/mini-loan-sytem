<?php

namespace App\Components\MiniAspire\Modules\User;

use Illuminate\Foundation\Http\FormRequest;

/*
 * Author: Raksa Eng
 */

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return static::staticRules();
    }

    public static function staticRules()
    {
        // TODO: update validation rules
        return [
            User::FIRST_NAME => 'required',
        ];
    }

    /**
     * Get the validation custom error message.
     *
     * @return array
     */
    public function messages()
    {
        return static::staticMessages();
    }
    public static function staticMessages()
    {
        return [
            User::FIRST_NAME . 'required' => trans('default.user_first_name_required'),
        ];
    }
}
