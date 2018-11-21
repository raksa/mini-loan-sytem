<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use Illuminate\Foundation\Http\FormRequest;

/*
 * Author: Raksa Eng
 */

class RepaymentRequest extends FormRequest
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
        // FIXME: update validation rules
        return [
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
        ];
    }
}
