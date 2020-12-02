<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class ActivityEntity extends Model
{
    protected $table = 'activity';

    protected $fillable = ['starts_at', 'room_id', 'place_id', 'text', 'created_by'];

    protected $dates = [
        'starts_at',
    ];

    public function place()
    {
        return $this->belongsTo(PlaceEntity::class, 'place_id');
    }

    public function created_by()
    {
        return $this->belongsTo(UserEntity::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(UserEntity::class, 'activity_user', 'activity_id', 'user_id');
    }
}