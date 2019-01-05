<?php

namespace App\Rules;

use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use Illuminate\Contracts\Validation\Rule;

/*
 * Author: Raksa Eng
 */
class RepaymentFrequencyTypeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return RepaymentFrequency::isValidType($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('default.repayment_frequency_type_invalid');
    }
}
