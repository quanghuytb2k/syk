<?php

namespace App\Repositories\Agency;

use App\Enums\Constants;
use App\Models\Agency;
use App\Repositories\BaseRepository;
use App\Repositories\Model;
use Auth;
use Illuminate\Support\Collection;

class AgencyRepository extends BaseRepository implements AgencyRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Agency::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function listAgency(array $data): mixed
    {
        $agencies =  $this->model->select('*')->with('prefecture')->withCount('corporations')->withCount('users');
        if (!empty($data['keyword'])) {
            $keyword = $data['keyword'];
            $agencies->where(function ($query) use ($keyword) {
                $query->Where('name', 'like', '%' . $keyword . '%');
                $query->orWhere('phone', 'like', '%' . $keyword . '%');
                $query->orWhere('contact_person_name', 'like', '%' . $keyword . '%');
                $query->orWhere('mail', 'like', '%' . $keyword . '%');
            });
        }
        $perPage = Constants::PER_PAGE;
        return $agencies->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createAgency(array $data): mixed
    {

        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateAgency(int $id, array $data): mixed
    {
        return $this->update($id, $data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getDetailById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Get list of agency name
     * @return Collection
     */
    public function getAllAgencyName(): Collection
    {
        return $this->model
            ->select([
                'id',
                'name',
            ])
            ->orderBy('name', 'asc')
            ->get();
    }
}
