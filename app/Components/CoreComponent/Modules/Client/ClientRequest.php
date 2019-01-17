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
        $id = request()->route("client");
        return static::staticRules($id);
    }

    public static function staticRules($id = null)
    {
        return [
            'first_name' => "required|max:50",
            'last_name' => "required|max:50",
            "phone_number" => "required|unique:clients,phone_number,$id,id",
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
            "first_name.required" => trans("default.client_first_name_required"),
            "first_name.max" => trans("default.client_first_name_max"),
            "last_name.required" => trans("default.client_last_name_required"),
            "last_name.max" => trans("default.client_last_name_max"),
            "phone_number.required" => trans("default.client_phone_required"),
            "phone_number.unique" => trans("default.client_phone_unique"),
        ];
    }
}
