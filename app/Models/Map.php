<?php

namespace App\Models;

use App\Entities\DrawingType\DrawingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Map extends Model
{
    use HasFactory;

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

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'files' => 'array',
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
