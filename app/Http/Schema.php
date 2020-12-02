<?php

namespace App\Http;

use App\Http\ResponseSchema\ListSchema;
use App\Http\ResponseSchema\WhitelistSchema;

class Schema
{
    public static function forUser()
    {
        return new WhitelistSchema(['id', 'name']);
    }

    public static function forRoom()
    {
        return new WhitelistSchema([
            'id', 'name', 'created_at',
            'activities' => new ListSchema(
                Schema::forActivity()
            ),
        ]);
    }

    public static function forActivity()
    {
        return new WhitelistSchema([
            'id', 'text', 'starts_at',
            'place' => Schema::forPlace(),
            'created_by' => Schema::forUser(),
            'users' => new ListSchema(Schema::forUser()),
        ]);
    }

    public static function forPlace()
    {
        return new WhitelistSchema(['id', 'external_id', 'name', 'menu', 'location']);
    }

    public static function forPlaces()
    {
        return new ListSchema(Schema::forPlace());
    }
}