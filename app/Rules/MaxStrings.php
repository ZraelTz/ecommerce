<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxStrings implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
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
        return count(explode(',', $value)) >= 1 && count(explode(',', $value)) <= 10 ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You must select at least 1 and maximum 10 fields of interest';
        // return 'The :attribute must be max 4 numbers.';
    }
}
