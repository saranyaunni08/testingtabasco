<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TotalReturnedAmount implements Rule
{
    protected $totalReceived;

    public function __construct($totalReceived)
    {
        $this->totalReceived = $totalReceived;
    }

    public function passes($attribute, $value)
    {
        // Sum all returned amounts
        $totalReturned = collect($value)->sum('returned_amount');

        // Ensure the total returned amount doesn't exceed the total received cash value
        return $totalReturned <= $this->totalReceived;
    }

    public function message()
    {
        return 'The total returned amount cannot exceed the total received cash value.';
    }
}
