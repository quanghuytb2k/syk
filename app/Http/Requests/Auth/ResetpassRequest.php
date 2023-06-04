<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ResetpassRequest extends FormRequest
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::exists(User::class, 'forgot_pass_code'),
                function ($attribute, $value, $fail) {
                    $users = $this->userRepository->findByField('forgot_pass_code', $value);
                    if ($users->count() != 1) {
                        $fail('Multiple user have same reset pass code.');
                        return;
                    }

                    $user = $users->first();
                    if ($user->forgot_pass_code_expired_time < now()) {
                        $fail('Reset pass code has expired.');
                        return;
                    }
                },
            ],
            'password' => [
                'required',
                'max:255',
                'ascii',
                'confirmed',
                Password::min(8)->mixedCase(),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'reset pass code',
        ];
    }
}
