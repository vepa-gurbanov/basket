<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
    }


    // Login
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string'],
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();

        return $this->respondWithToken($token, $user);
    }


    // Logout
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }


    // Self
    public function self(): JsonResponse
    {
        return response()->json(Auth::guard('api')->user());
    }


    // Refresh token
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::guard('api')->refresh(), Auth::guard('api')->user());
    }


    // Respond with token
    protected function respondWithToken($token, $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ]
        ]);
    }


    // Forgot Password
    public function forgotPassword(Request $request): JsonResponse
    {
        $input = $request->all();
        $rules = [
            'email' => ['required', 'email'],
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = ['status' => 400, 'message' => $validator->errors()->first(), 'data' => []];
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return response()->json(['status' => 200, 'message' => trans($response), 'data' => []]);
                    case Password::INVALID_USER:
                        return response()->json(['status' => 400, 'message' => trans($response), 'data' => []]);
                }
            } catch (\Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return response()->json($arr);
    }


    // Reset Password
    public function changePassword(Request $request): JsonResponse
    {
        $input = $request->all();
        $user_id = Auth::guard('api')->id();
        $rules = [
            'old_password' => ['required'],
            'new_password' => ['required','min:6'],
            'confirm_password' => ['required','same:new_password'],
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = ['status' => 400, 'message' => $validator->errors()->first(), 'data' => []];
        } else {
            try {
                if (!(Hash::check(request('old_password'), Auth::user()->password))) {
                    $arr = ['status' => 400, 'message' => 'Check your old password.', 'data' => []];
                } else if ((Hash::check(request('new_password'), Auth::user()->password))) {
                    $arr = ['status' => 400, 'message' => 'Please enter a password which is not similar then current password.', 'data' => []];
                } else {
                    User::where('id', $user_id)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = ['status' => 200, 'message' => 'Password updated successfully.', 'data' => []];
                }
            } catch (\Exception $ex) {
                $msg = $ex->errorInfo[2] ?? $ex->getMessage();
                $arr = ['status' => 400, 'message' => $msg, 'data' => []];
            }
        }
        return response()->json($arr);
    }
}
