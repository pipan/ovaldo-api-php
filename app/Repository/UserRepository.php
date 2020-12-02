<?php

namespace App\Repository;

class UserRepository
{
    public function find($id)
    {
        return UserEntity::find($id);
    }

    public function insert($user)
    {
        $entity = new UserEntity($user);
        $entity->save();
        return $entity;
    }

    public function update($id, $user)
    {
        $entity = $this->find($id);
        $entity->fill($user);
        $entity->save();
        return $entity;
    }
}