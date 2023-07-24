<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    public function sendResetPasswordNotification(Request $request): JsonResponse
    {
        $validation = $request->validate([
            'email' => ['required', 'string', 'email']
        ]);

        try {
            $user = User::where('email', $validation['email'])->first();
        } catch (UserNotDefinedException $e) {
            return $this->failedResponse($e->getMessage(), 404);
        }

        try {
            $mailableVariables = $this->createToken($validation['email']);
            $user->sendPasswordResetCodeNotification($mailableVariables['token'], $mailableVariables['code']);
            return $this->successResponse('Verification code sent!', 200);
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 404);
        }
    }


    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $isValid = DB::table('password_reset_tokens')
                ->where(['email' => $request->only('email'), 'code' => $request->only('code')])
                ->first();

            if (Carbon::now() > $isValid->code_expires_at) {
                return $this->failedResponse('Verification code expired!', 400);
            }

            $message = [
                'message' => 'Verification correct!',
                'token' => $isValid->token,
            ];
            return $this->successResponse($message, 200);
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 404);
        }
    }


    public function changePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->only('email'))->first();
        $db = DB::table('password_reset_tokens')
            ->where(['email' => $request->only('email'), 'token' => $request->only('token')])
            ->exists();

        if ($db && $user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
            return $this->successResponse('Password changed!', 200);
        }
        return $this->failedResponse('Password mismatch!', 400);
    }


    // Short variables
    public function createToken($email): array
    {
        DB::table('password_reset_tokens')
            ->updateOrInsert(
                ['email' => $email],
                [
                    'code' => Str::random(5),
                    'code_expires_at' => Carbon::now()->addMinutes(10),
                    'token' => Str::random(64),
                    'created_at' => Carbon::now()
                ]
            );

        $table = DB::table('password_reset_tokens')
            ->where('email', 'tazesalgy@gmail.com')->first(['code', 'token']);

        return [
            'code' => $table->code,
            'token' => $table->token,
        ];
    }


    public function failedResponse($message, $code): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }


    public function successResponse($message, $code): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], $code);
    }
}
