<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AccountIsVerified implements Rule
{

    private $username;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($username)
    {
        $this->username = $username;
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
        $user = User::where('username', $this->username)
                    ->orWhere('email', $this->username)
                    ->first();
        
        return is_null($user) ? false : ! is_null($user->email_verified_at);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email must be verified first before it can be used.';
    }
}
