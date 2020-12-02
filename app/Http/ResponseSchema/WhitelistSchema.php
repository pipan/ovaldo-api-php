<?php

namespace App\Http\ResponseSchema;

class WhitelistSchema implements Adapter
{
    private $whitelistKeys;

    public function __construct($whitelistKeys)
    {
        $this->whitelistKeys = $whitelistKeys;
    }

    public function adapt($model)
    {
        $result = [];
        foreach ($this->whitelistKeys as $whitelistKey => $whitelistValue) {
            $key = is_int($whitelistKey) ? $whitelistValue : $whitelistKey;
            if (!isset($model[$key])) {
                continue;
            }
            $value = $model[$key];
            if ($whitelistValue instanceof Adapter) {
                $value = $whitelistValue->adapt($model[$key]);
            }
            $result[$key] = $value;
        }
        
        return $result;
    }
}