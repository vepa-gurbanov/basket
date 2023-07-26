<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request) {
        return User::all();


//        DB::table('password_reset_tokens')
//            ->updateOrInsert(
//                ['email' => 'tazesalgy@gmail.com'],
//                [
//                    'code' => Str::random(5),
//                    'token' => Str::random(64),
//                    'created_at' => Carbon::now()
//                ]
//            );
//
//        $table = DB::table('password_reset_tokens')
//            ->where('email', 'tazesalgy@gmail.com')->first(['code', 'token']);
//        $data = [
//            'code' => $table->code,
//            'token' => $table->token,
//        ];
//        return $data['code'];

//        if ($request->user()->cannot('admin')){
//            abort(403);
//    }
    }
}
