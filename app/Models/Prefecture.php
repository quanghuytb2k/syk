<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Prefecture extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_prefectures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefecture_name',
        'order'
    ];
}
