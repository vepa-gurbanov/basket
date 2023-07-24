<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Admin\UserDeleted;
use App\Models\Blacklist;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class UserController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::with('roles')->orderBy('id')->get();
        $roles = Role::orderBy('id')->get(['id', 'name', 'ability']);

        $data = [
            'title' => 'Users',
            'users' => $users,
            'roles' => $roles,
        ];
        return view('admin.users.index')
            ->with($data);
    }


    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validation = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            User::create($validation)->sendUserCreatedNotification($validation['email'], $validation['password']);
            return redirect()->back()->with('success', 'User created!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function updateRole(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validation = $request->validate([
            'user_id' => ['exists:users,id', 'integer', 'min:1'],
            'role' => ['array'],
            'role.*' => ['exists:roles,id', 'integer', 'min:1'],
        ]);
        try {
            User::where('id', $validation['user_id'])->first()->roles()->sync($validation['role']);
            return redirect()->back()->with('success', 'Role updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $user = User::where('id', $request->user_id)->first();
            $rUser = auth()->user();
            $email = $user->email;
            $user->delete();
            $blockedBy = ($rUser->has('roles') ? $rUser->roles()->first()->name . ', ' : '') . 'Administrator';

            try {
//                $i = 0;
//                do {
//                    $i++;
                $this->addToBlacklist(user: $email, blockedBy: $blockedBy);
                Mail::to($email)->send(new UserDeleted('admin', $blockedBy));
//                } while ($i < 4);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return to_route('admin.users')->with('success', 'User was deleted and mailed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addToBlacklist($user, $blockedBy, $blockType = 'temporary', $status = 1, $authType = 'admin') {
        try {
            Blacklist::updateOrCreate(
                ['email' => $user],
                [
                    'auth_type' => $authType,
                    'block_type' => $blockType,
                    'blocked_by' => $blockedBy,
                    'status' => $status,
                ]
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
