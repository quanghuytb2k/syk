<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class ValidateUniqueEmailUser implements Rule
{
    /**
     * @var int $userId
     */
    protected int $userId;

    /**
     * Create a new rule instance.
     * @param int $userId
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($this->userId) {
            $user = User::where($attribute, $value)->where('id', '!=', $this->userId)->first();
            if ($user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute has already been taken.';
    }
}
