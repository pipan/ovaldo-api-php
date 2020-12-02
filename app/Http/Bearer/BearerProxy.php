<?php

namespace App\Http\Bearer;

class BearerProxy implements Bearer
{
    private $bearer;

    public function __construct(Bearer $bearer)
    {
        $this->bearer = $bearer;
    }

    public function setBearer($bearer)
    {
        $this->bearer = $bearer;
    }

    public function getUser()
    {
        return $this->bearer->getUser();
    }

    public function hasUser()
    {
        return $this->bearer->hasUser();
    }
}