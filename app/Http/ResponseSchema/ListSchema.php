<?php

namespace App\Http\ResponseSchema;

class ListSchema implements Adapter
{
    private $schema;

    public function __construct(Adapter $schema)
    {
        $this->schema = $schema;
    }

    public function adapt($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = $this->schema->adapt($model);
        }
        
        return $result;
    }
}