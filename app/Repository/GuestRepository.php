<?php

namespace App\Repository;

use Illuminate\Support\Str;

class GuestRepository
{
    public function find($id)
    {
        return GuestEntity::find($id);
    }

    public function findByHash($hash)
    {
        return GuestEntity::where('hash', '=', $hash)->first();
    }

    public function insert($model)
    {
        $guest = new GuestEntity($model);
        $guest->hash = (string) Str::uuid();
        $guest->save();
        return $guest;
    }

    public function update($id, $model)
    {
        $guest = $this->find($id);
        $guest->fill($model);
        $guest->save();
        return $guest;
    }
}