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
        $id = request()->get('user');
        return static::staticRules($id);
    }

    public static function staticRules($id = null)
    {
        return [
            User::FIRST_NAME => 'required|max:50',
            User::LAST_NAME => 'required|max:50',
            User::PHONE_NUMBER => 'required|unique:' .
            User::TABLE_NAME . ',' . User::PHONE_NUMBER . ',' . $id . ',' . User::ID,
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
            User::FIRST_NAME . '.required' => trans('default.user_first_name_required'),
            User::FIRST_NAME . '.max' => trans('default.user_first_name_max'),
            User::LAST_NAME . '.required' => trans('default.user_last_name_required'),
            User::LAST_NAME . '.max' => trans('default.user_last_name_max'),
            User::PHONE_NUMBER . '.required' => trans('default.user_phone_required'),
            User::PHONE_NUMBER . '.unique' => trans('default.user_phone_unique'),
        ];
    }
}
