<?php

namespace App\Http\Bearer;

class SimpleBearer implements Bearer
{
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
    public function getUser()
    {
        return $this->entity;
    }

    public function hasUser()
    {
        return $this->entity !== null;
    }
}