<?php

namespace App\Repositories\User;

use App\Enums\Constants;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Facility;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    public function getListPaginate(Request $request): LengthAwarePaginator
    {
        $user = Auth::user();
        $role = $user->getRoleNames()[0];

        $query = $this->model->select('users.*')
            ->with([
                'agency' => function ($q) {
                    $q->select(['id', 'name']);
                },
                'company' => function ($q) {
                    $q->select(['id', 'name']);
                },
                'facility' => function ($q) {
                    $q->select(['id', 'name']);
                },
            ])
            ->when($role == 'agency_owner' || $role == 'agency_staff', function ($q) use($user) {
                $q->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->leftJoin('roles', 'model_has_roles.role_id' , '=', 'roles.id')
                    ->where('agency_id', $user->agency_id)
                    ->where('roles.name', '!=', 'admin');
            })
            ->when($role == 'company_owner' || $role == 'company_staff', function ($q) use($user) {
                $q->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->leftJoin('roles', 'model_has_roles.role_id' , '=', 'roles.id')
                    ->where('company_id', $user->company_id)
                    ->whereNotIn('roles.name', ['admin', 'agency_owner', 'agency_staff']);
            });;

        if ($request->input('search.text')) {
            $text = $request->input('search.text');
            $query->where(function (Builder $q) use ($text) {
                $q->orWhere('name', 'LIKE', "%$text%")
                    ->orWhere('name_kana', 'LIKE', "%$text%")
                    ->orWhere('email', 'LIKE', "%$text%");
            });
        }

        if ($request->input('agency_id')) {
            $query->where('agency_id', $request->input('agency_id'));
        }

        if ($request->input('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->input('facility_id')) {
            $query->where('facility_id', $request->input('facility_id'));
        }

        return $query
            ->orderBy('users.created_at', 'desc')
            ->paginate($request->input('per_page', Constants::PER_PAGE));
    }

    /**
     * @param $agency_id
     * @param $company_id
     * @param $facility_id
     * @return void
     */
    public function removeUserByConditional($agency_id = null, $company_id = null, $facility_id = null): void
    {
        $data = [];
        if ($facility_id) {
            $data['facility_id'] = null;
        }
        if ($company_id) {
            $data = [
                'company_id' => null,
                'facility_id' => null
            ];
        }
        if ($agency_id) {
            $data = [
                'agency_id' => null,
                'company_id' => null,
                'facility_id' => null
            ];
        }

        $this->model
            ->when($agency_id, function ($query) use($agency_id) {
                $query->where('agency_id', $agency_id);
            })
            ->when($company_id, function ($query) use($company_id) {
                $query->where('company_id', $company_id);
            })
            ->when($facility_id, function ($query) use($facility_id) {
                $query->where('facility_id', $facility_id);
            })->update($data);
    }
}
