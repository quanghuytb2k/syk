<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getListPaginate(Request $request): mixed;

    /**
     * @param $agency_id
     * @param $company_id
     * @param $facility_id
     * @return void
     */
    public function removeUserByConditional($agency_id = null, $company_id = null, $facility_id = null): void;
}
