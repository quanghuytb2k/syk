<?php

namespace App\Entities\DrawingType;

use App\Models\Map;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class DrawingType.
 *
 * @package namespace App\Entities\DrawingType;
 */
class DrawingType extends Model
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'm_drawing_types';

    protected $fillable = [
        'name',
        'order'
    ];

    protected $hidden = [
        'order'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
