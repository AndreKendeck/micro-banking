<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $attempt = Auth::attempt($loginRequest->only(['email', 'password']));
        if (!$attempt) return $this->loginFailedResponse("Authentication Failed.");
        /** @var User */
        $user = User::byEmail($loginRequest->get('email'))->firstOrFail();
        return response()->json([
            'token' => $user->createToken(Str::random())->plainTextToken,
            'user' => new UserResource($user),
            'message' => "Login success."
        ]);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function loginFailedResponse(string $message): JsonResponse
    {
        return response()->json([
            'errors' => [
                'email' => $message
            ]
        ]);
    }
}
