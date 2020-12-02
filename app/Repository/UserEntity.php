<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class UserEntity extends Model
{
    protected $table = 'user';

    protected $fillable = ['name'];
}