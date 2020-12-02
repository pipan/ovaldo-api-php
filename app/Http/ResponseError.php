<?php

namespace App\Http;

use Exception;

class ResponseError
{
    public static function resourceNotFound($message = "Resource not found")
    {
        return response([
            'message' => $message
        ], 404);
    }

    public static function methodNotAllowed()
    {
        return response([
            'message' => 'Method not allowed'
        ], 405);
    }

    public static function invalidRequest($errors = [])
    {
        return response([
            'message' => 'Invalid request',
            'errors' => $errors
        ], 422);
    }

    public static function unauthorized()
    {
        return response([
            'message' => 'Unauthorized'
        ], 401);
    }

    public static function forbidden()
    {
        return response([
            'message' => 'Forbidden'
        ], 403);
    }

    public static function error(Exception $ex)
    {
        return response([
            'message' => $ex->getMessage()
        ], 500);
    }
}