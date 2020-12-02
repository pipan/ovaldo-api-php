<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class RoomEntity extends Model
{
    protected $table = 'room';

    protected $fillable = ['name'];

    public function activities()
    {
        return $this->hasMany(ActivityEntity::class, 'room_id', 'id');
    }
}