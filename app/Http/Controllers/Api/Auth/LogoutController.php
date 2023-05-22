<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully.',
            'user' => null
        ]);
    }
}
