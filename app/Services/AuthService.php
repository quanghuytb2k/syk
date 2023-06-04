<?php

namespace App\Services;

use App\Enums\Constants;
use App\Mail\ActivationMail;
use App\Mail\ForgotPassMail;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\Company\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class AuthService
{

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected CompanyRepositoryInterface $companyRepository,
        protected RoleRepository $roleRepository
    )
    {
    }

    /**
     * Attemp to login
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return bool
     */
    public function login(string $username, string $password, bool $remember = true): bool
    {
        return Auth::attempt([
            'email' => $username,
            'password' => $password,
        ], $remember);
    }

    /**
     * Do logout
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Return logged user info
     * @return mixed
     */
    public function me(): mixed
    {
        return Auth::user();
    }

    /**
     * Do register
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $user = null;

        // Save to database
        try {
            DB::beginTransaction();

            $company = $this->companyRepository->create([
                'name' => $request->input('company.name'),
                'business_type' => $request->input('company.business_type'),
                'insdustry' => $request->input('company.insdustry'),
                'is_stock_listing' => $request->input('company.is_stock_listing'),
                'post_code' => $request->input('company.post_code'),
                'prefecture_id' => $request->input('company.prefecture_id'),
                'municipality' => $request->input('company.municipality'),
                'street' => $request->input('company.street'),
                'building' => $request->input('company.building'),
                'phone' => $request->input('company.phone'),
                'homepage' => $request->input('company.homepage'),
                'agency_id' => Constants::DEFAULT_AGENCY_ID,
            ]);

            $user = $this->userRepository->create([
                'name' => $request->input('user.name'),
                'name_kana' => $request->input('user.name_kana'),
                'email' => $request->input('user.email'),
                'password' => Hash::make($request->input('user.password')),
                'department' => $request->input('user.department'),
                'job_title' => $request->input('user.job_title'),
                'phone' => $request->input('user.phone'),
                'role' => 'coporation_owner',
                'agency_id' => Constants::DEFAULT_AGENCY_ID,
                'status' => Constants::USER_WAITING_ACTIVATION,
                'company_id' => $company->id,
                'energy_role' => $request->input('user.energy_role'),
            ]);

            // Generate activation code
            $activationCode = hash('sha256', $user->id . time() . Str::random());
            $user = $this->userRepository->update([
                'activation_code' => $activationCode,
            ], $user->id);

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        // Send activation mail
        Mail::to($user->email)->send(new ActivationMail($user));
    }

    /**
     * Activate user
     * @return void
     */
    public function activate(Request $request): bool
    {
        $activationCode = $request->input('code');
        if ($activationCode == null) return true;

        $users = $this->userRepository->findByField('activation_code', $activationCode);
        if ($users->count() != 1) return false;

        $user = $users->first();
        if ($user->status == Constants::USER_ACTIVATED) return true;

        $this->userRepository->update([
            'activation_code' => null,
            'status' => Constants::USER_ACTIVATED,
        ], $user->id);

        return true;
    }

    /**
     * Change password
     * @return void
     */
    public function changepass(Request $request): void
    {
        $user = Auth::user();
        $passwordHash = Hash::make($request->input('password'));

        $this->userRepository->update([
            'password' => $passwordHash,
        ], $user->id);
    }

    /**
     * Forgot password
     * @return void
     */
    public function forgotpass(Request $request): bool
    {
        $users = $this->userRepository->findByField('email', $request->input('email'));
        if ($users->count() != 1) return false;

        $user = $users->first();

        // Generate forgot password request code
        $code = hash('sha256', $user->id . time() . Str::random());
        $user = $this->userRepository->update([
            'forgot_pass_code' => $code,
            'forgot_pass_code_expired_time' => now()->addMinutes(config('app.forgot_pass_code_expires')),
        ], $user->id);

        // Send password reset mail
        Mail::to($user->email)->send(new ForgotPassMail($user));

        return true;
    }

    /**
     * Reset password
     * @return void
     */
    public function resetpass(Request $request): bool
    {
        $users = $this->userRepository->findByField('forgot_pass_code', $request->input('code'));
        if ($users->count() != 1) return false;

        $user = $users->first();
        if ($user->forgot_pass_code == null) return false;
        if ($user->forgot_pass_code_expired_time < now()) return false;

        $passwordHash = Hash::make($request->input('password'));
        $this->userRepository->update([
            'password' => $passwordHash,
            'forgot_pass_code' => null,
            'forgot_pass_code_expired_time' => null,
        ], $user->id);

        return true;
    }

    /**
     * @return mixed
     */
    public function getAllRoles(): mixed
    {
        return $this->roleRepository->all();
    }
}
