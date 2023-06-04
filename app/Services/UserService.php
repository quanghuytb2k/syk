<?php

namespace App\Services;

use App\Enums\Constants;
use App\Enums\UserEnums;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\Agency\AgencyRepositoryInterface;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Facility\FacilityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    protected UserRepositoryInterface $userRepository;
    protected AgencyRepositoryInterface $agencyRepository;
    protected CompanyRepositoryInterface $companyRepository;
    protected FacilityRepositoryInterface $facilityRepository;

    /**
     * Constructor
     * @param UserRepositoryInterface $userRepository
     * @param AgencyRepositoryInterface $agencyRepository
     * @param CompanyRepositoryInterface $companyRepository
     * @param FacilityRepositoryInterface $facilityRepository
     */
    public function __construct(
        UserRepositoryInterface     $userRepository,
        AgencyRepositoryInterface   $agencyRepository,
        CompanyRepositoryInterface  $companyRepository,
        FacilityRepositoryInterface $facilityRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->agencyRepository = $agencyRepository;
        $this->companyRepository = $companyRepository;
        $this->facilityRepository = $facilityRepository;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function createUser(Request $request): bool
    {
        try {
            $currentUser = Auth::user();
            $currentUserRoles = $currentUser->getRoleNames()->toArray();
            $data = $request->all();
            $data['status'] = UserEnums::STATUS_ACTIVE;
            $data['password'] = Hash::make($data["password"]);
            if (in_array('agency_owner', $currentUserRoles) || in_array('agency_staff', $currentUserRoles)) {
                $data['agency_id'] = $currentUser->agency_id;
            }

            if (in_array('company_owner', $currentUserRoles) || in_array('company_staff', $currentUserRoles)) {
                $data['agency_id'] = $currentUser->agency_id;
                $data['company_id'] = $currentUser->company_id;
            }

            $user = $this->userRepository->create($data);
            $user->assignRole($data['role']);
            return true;
        } catch (\Throwable $throwable) {
            Log::error($throwable);
            return false;
        }
    }

    /**
     *
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function updateUser(int $id, Request $request): mixed
    {
        $user = User::find($id);
        $data = $request->input('user');

        if (!$user->hasRole($data['role']) && Auth::id() != $id) {
            $user->removeRole($user->role);
            $user->assignRole($data['role']);
        } else {
            unset($data['role']);
        }

        if (isset($data['email'])) {
            unset($data['email']);
        }
        return $this->userRepository->update($data, $id);
    }

    /**
     *
     * @return Collection[]
     */
    public function getListScreenData(): array
    {
        return [
            'agencies' => $this->agencyRepository->getAllAgencyName(),
            'companies' => $this->companyRepository->getAllCompanyName(),
            'facilities' => $this->facilityRepository->getAllFacilityName(),
        ];
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function getListPaginate(Request $request): mixed
    {
        return $this->userRepository->getListPaginate($request);
    }

    /**
     *
     * @param int $id
     * @return App\Repositories\Illuminate\Database\Eloquent\Model
     */
    public function getUserById(int $id)
    {
        return $this->userRepository->find($id);
    }

    public function deleteUser(int $id): bool
    {
        DB::beginTransaction();
        try {
            $this->userRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
