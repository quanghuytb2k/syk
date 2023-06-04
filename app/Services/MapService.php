<?php

namespace App\Services;

use App\Models\Map;
use App\Repositories\Map\MapRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MapService
{
    /**
     * constructor
     */
    public function __construct(
        protected MapRepository $mapRepository
    )
    {
    }

    /**
     * @param array $params
     * @param array $pathFiles
     * @return bool
     */

    public function list(array $searchParams): LengthAwarePaginator
    {
        return $this->mapRepository->list($searchParams);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function create(array $params): mixed
    {
        return $this->mapRepository->create($params);
    }

    public function listDrawingType()
    {
        return $this->mapRepository->listDrawingType();
    }

    public function detail(int $id): mixed
    {
        return $this->mapRepository->getMapById($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteMap(int $id): bool
    {
        try {
            $map = Map::select('files')->find($id);
            if (!empty($map['files'])) {
                $pathFiles = collect($map['files'])->map(function ($items) {
                    return $items['path'];
                })->toArray();

                foreach ($pathFiles as $path) {
                    Storage::disk('s3')->delete($path);
                }
            }

            $this->mapRepository->delete($id);
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $params
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateFile(int $id, array $params): mixed
    {
        $currentRecord = Map::select('files')->find($id);
        $currentRecord['files'] = @$currentRecord['files'] ?: [];
        $files = [];
        $files['files'] = array_merge($currentRecord['files'], $params);

        return $this->mapRepository->update($files, $id);
    }

    /**
     * @param int $id
     * @param string $path
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function deleteFile(int $id, string $path)
    {
        $currentRecord = Map::select('files')->find($id);
        $files = [];
        $files['files'] = collect($currentRecord['files'])->map(function ($items) use ($path) {
            if ($items['path'] != $path) {
                return $items;
            }
        })->filter(function ($items) {
            return $items;
        })->values()->toArray();

        return $this->mapRepository->update($files, $id);
    }
}
