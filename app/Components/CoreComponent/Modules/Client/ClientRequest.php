<?php

namespace App\Components\CoreComponent\Modules\Client;

use Illuminate\Foundation\Http\FormRequest;

/*
 * Author: Raksa Eng
 */

class ClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->get('client');
        return static::staticRules($id);
    }

    public static function staticRules($id = null)
    {
        return [
            Client::FIRST_NAME => 'required|max:50',
            Client::LAST_NAME => 'required|max:50',
            Client::PHONE_NUMBER => 'required|unique:' .
            Client::TABLE_NAME . ',' . Client::PHONE_NUMBER . ',' . $id . ',' . Client::ID,
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
            Client::FIRST_NAME . '.required' => trans('default.client_first_name_required'),
            Client::FIRST_NAME . '.max' => trans('default.client_first_name_max'),
            Client::LAST_NAME . '.required' => trans('default.client_last_name_required'),
            Client::LAST_NAME . '.max' => trans('default.client_last_name_max'),
            Client::PHONE_NUMBER . '.required' => trans('default.client_phone_required'),
            Client::PHONE_NUMBER . '.unique' => trans('default.client_phone_unique'),
        ];
    }
}
