<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends AccessTokenController
{
    /**
     * Issue an authentication token and return user information with roles.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * 
     * @return Collection|JsonResponse The token and user data as a collection or JSON response in case of an error.
     */
    public function token(ServerRequestInterface $request)
    {
        try {
            $token = collect(json_decode(parent::issueToken($request)->getContent(), true));
            $user = User::whereEmail(request('username'))
                        ->with(['roles:id,name', 'image'])
                        ->first();

            // If the user has an associated image, adjust its URL
            if ($user && $user->image) {
                $adjustedUrl = "img/users/" . $user->image->url;
                $fullUrl = url($adjustedUrl);
                $user->setAttribute('image_url', $fullUrl);
                $user->makeHidden('image'); // Hide the image relationship from the result
            }

            return $token->merge(['user' => $user]);
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['error' => 'failed_authentication'], 401);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->revoke();
        return response()->json(['message' => 'ok']);
    }
}