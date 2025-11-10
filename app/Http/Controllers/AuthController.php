<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\MessageEnumFr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponses;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(MessageEnumFr::CREDENTIALS_INVALIDES, 401);
        }

        // Check if user is admin
        if ($user->role !== 'admin') {
            return $this->errorResponse(MessageEnumFr::ACCES_INTERDIT, 403);
        }

        $token = $user->createToken('AdminToken');

        return $this->successResponse([
            'user' => $user,
            'access_token' => $token->accessToken,
            'refresh_token' => $token->token->id, 
            'token_type' => 'Bearer',
        ], MessageEnumFr::LOGIN_REUSSI);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->successResponse(null, MessageEnumFr::LOGOUT_REUSSI);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return $this->successResponse($request->user());
    }
}
