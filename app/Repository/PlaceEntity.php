<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class PlaceEntity extends Model
{
    protected $table = 'place';

    protected $casts = [
        'menu' => 'array',
    ];

    protected $fillable = ['name', 'external_id', 'menu'];
}