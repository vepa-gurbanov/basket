<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException;

class ResetPasswordController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth:api')->except('sendResetPasswordNotification');
//    }

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
            session()->put('api_auth', $mailableVariables);
//            $user->sendPasswordResetCodeNotification($mailableVariables['token'], $mailableVariables['code']);
            return $this->successResponse('Verification code sent!', 200);
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 404);
        }
    }


    public function resetPassword(Request $request): JsonResponse
    {
        $validation = $request->validate([
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'string', 'digits:5']
        ]);

        try {
            $requestIsValid = DB::table('password_reset_tokens')
                ->where(['email' => $validation['email'], 'code' => $validation['code']])
                ->first();

            return $this->successResponse('Verification correct!', 200);
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 404);
        }
    }


    public function changePassword(Request $request) {
        $validation = $request->validate([
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'string', 'digits:5']
        ]);

    }

    // Short variables
    public function createToken($email): array
    {
        DB::table('password_reset_tokens')
            ->updateOrInsert(
                ['email' => $email],
                [
                    'code' => Str::random(5),
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
