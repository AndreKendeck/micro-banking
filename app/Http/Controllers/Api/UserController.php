<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function showCurrent(): JsonResponse
    {
        return response()->json(new UserResource(auth()->user()));
    }
}
