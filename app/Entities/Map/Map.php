<?php

namespace App\Entities\Map;

use App\Entities\DrawingType\DrawingType;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Map.
 *
 * @package namespace App\Entities\Map;
 */
class Map extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'maps';
    protected $fillable = [
        'facility_id',
        'drawing_type_id',
        'storage_location',
        'creator',
        'note',
        'code',
        'files',
        'date_created',
    ];

    protected $casts = [
        'files' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function facility(): HasOne
    {
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }
    public function drawingType(): HasOne
    {
        return $this->hasOne(DrawingType::class, 'id', 'drawing_type_id');
    }
}
