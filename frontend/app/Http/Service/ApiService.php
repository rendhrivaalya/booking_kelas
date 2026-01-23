<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    public static function user()
    {
        return Http::baseUrl('http://localhost:3001');
    }

    public static function booking()
    {
        return Http::baseUrl('http://localhost:3002');
    }
}
