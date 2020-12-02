<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class ActivityUserEntity extends Model
{
    protected $table = 'activity_user';
    public $timestamps = false;

    protected $fillable = ['activity_id', 'user_id'];
}