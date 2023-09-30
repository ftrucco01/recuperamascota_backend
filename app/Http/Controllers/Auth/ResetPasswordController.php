<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Mail\UpdatePasswordConfirmation;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Traits\ResponseTrait;

class ResetPasswordController extends Controller
{
    use ResponseTrait;
    /**
     * Send a reset link to the given user.
     *
     * @return JsonResponse
     */
    public function sendResetLinkEmail(): JsonResponse
    {
        request()->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::whereEmail(request('email'))->first();

        if ($user) {
            $payload = [
                'id' => $user->id,
                'email' => request('email'),
                'exp' => time() + 3600
            ];

            $token = JWT::encode($payload, config('services.jwt_secret'), 'HS256');

            $user->update([
                'reset_password_token' => $token
            ]);

            $url = config('services.app_front') . '/account/reset-password/' . $token . '/' . base64_encode($user->email);
            Mail::to($user->email)->send(new ResetPassword($url));
        }

        return $this->successMessage();
    }

    /**
     * Reset password
     *
     * @return JsonResponse
     */
    public function resetPassword(): JsonResponse
    {
        request()->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        try {
            $decodedToken = JWT::decode(request('token'), new Key(config('services.jwt_secret'), 'HS256'));

            $user = User::where([
                ['id', '=', $decodedToken->id],
                ['email' , '=', $decodedToken->email],
                ['reset_password_token' , '=', request('token')]
            ])->first();

            $user->update([
                'password' => request('password'),
                'reset_password_token' => null
            ]);

            Mail::to($user->email)->send(new UpdatePasswordConfirmation($user));

            return $this->successMessage();
        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('failed_reset_password', 401);
        }
    }
}
