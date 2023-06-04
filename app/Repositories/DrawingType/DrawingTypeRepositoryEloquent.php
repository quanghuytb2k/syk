<?php

namespace App\Repositories\DrawingType;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DrawingType\DrawingTypeRepository;
use App\Entities\DrawingType\DrawingType;
use App\Validators\DrawingType\DrawingTypeValidator;

/**
 * Class DrawingTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories\DrawingType;
 */
class DrawingTypeRepositoryEloquent extends BaseRepository implements DrawingTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DrawingType::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
