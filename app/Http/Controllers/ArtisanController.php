<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function routeClear()
    {
        Artisan::call('route:clear');
        return response()->json(['message' => 'Routes cache cleared successfully']);
    }

    public function routeCache()
    {
        Artisan::call('route:cache');
        return response()->json(['message' => 'Routes cached successfully']);
    }

    public function optimize()
    {
        Artisan::call('optimize');
        return response()->json(['message' => 'Optimization completed successfully']);
    }
}