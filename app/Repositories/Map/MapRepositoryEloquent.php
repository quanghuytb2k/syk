<?php

namespace App\Repositories\Map;

use App\Enums\Constants;
use App\Models\DrawingType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Map\MapRepository;
use App\Entities\Map\Map;
use App\Validators\Map\MapRepositoryValidator;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class MapRepositoryEloquent.
 *
 * @package namespace App\Repositories\Map;
 */
class MapRepositoryEloquent extends BaseRepository implements MapRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Map::class;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException
     */
    public function boot(): void
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function list(array $params): LengthAwarePaginator
    {
        $agency_id = Auth::user()->agency_id;
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;

        return $this->model->select('maps.*')
            ->with([
                'facility' => function ($query) {
                    $query->select('id', 'name');
                },
                'drawingType' => function ($query) {
                    $query->select('id', 'name');
            },])
            ->when($agency_id != null, function ($query) use($agency_id) {
                $query->join('facilities', 'facilities.id', '=', 'maps.facility_id')
                ->where('facilities.agency_id', $agency_id);
            })
            ->when(isset($params['storageLocation']), function ($query) use ($params) {
                $query->where('maps.storage_location', 'LIKE', '%' . $params['storageLocation'] . '%');
            })
            ->when(isset($params['code']), function ($query) use ($params) {
                $query->where('maps.code', 'LIKE', '%' . $params['code'] . '%');
            })
            ->when(isset($params['facilityId']), function ($query) use ($params) {
                $query->where('maps.facility_id', $params['facilityId']);
            })
            ->when(isset($params['drawingType']), function ($query) use ($params) {
                $query->where('maps.drawing_type_id', $params['drawingType']);
            })
            ->paginate($perPage);
    }

    public function listDrawingType(): mixed
    {
        return DrawingType::get(['id', 'name']);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getMapById(int $id): mixed
    {
        return $this->model->find($id);
    }
}
