<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class GuestEntity extends Model
{
    protected $table = 'guest';

    protected $fillable = ['user_id', 'accessed_at'];

    protected $dates = ['accessed_at'];

    public function user()
    {
        return $this->belongsTo(UserEntity::class, 'user_id');
    }
}