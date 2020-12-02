<?php

namespace App\Http\Bearer;

interface Bearer
{
    public function getUser();
    public function hasUser();
}