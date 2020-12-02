<?php

namespace App\Http\ResponseSchema;

interface Adapter
{
    public function adapt($item);
}