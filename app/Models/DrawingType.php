<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrawingType extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'm_drawing_types';

    protected $fillable = [
        'name',
        'order'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'order'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
