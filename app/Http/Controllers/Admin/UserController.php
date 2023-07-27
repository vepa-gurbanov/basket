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
use function PHPUnit\Framework\isFalse;

class UserController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::where('disabled', false)->with('roles')->orderBy('id')->get();
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

        if (Blacklist::where('email', $validation['email'])->exists()) {
            return back()->with('error', 'User registration blocked!');
        }

        $i = 0;
        do {
            try {
                $i++;
                User::create($validation)->sendUserCreatedNotification($validation['email'], $validation['password']);
                return redirect()->back()->with('success', 'User created!' . ' Counted: ' . $i);
            } catch (\Exception $e) {
                if ($i < 3) {
                    continue;
                }
                $e = $e->getMessage();
            }
        } while ($i < 3);
        return redirect()->back()->with('error', $e . ' Counted: ' . $i);
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
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'block_type' => ['in:temporary,forever'],
        ]);
        try {
            $user = User::where('id', $request->user_id)->first();
            $rUser = auth()->user();
            $email = $user->email;
            $user->delete();
            $blockedBy = ($rUser->has('roles') ? $rUser->roles()->first()->name . ', ' : '') . 'Administrator';

            $i = 0;
            do {
                try {
                    $i++;
                    $this->addToBlacklist(user: $email, blockedBy: $blockedBy, blockType: $request->block_type);
                    Mail::to($email)->send(new UserDeleted('admin', $blockedBy));
                } catch (\Exception $e) {
                    if($i < 3) continue;
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } while ($i < 3);

            return to_route('admin.users')->with('success', 'User was deleted and mailed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function disable($id): \Illuminate\Http\RedirectResponse
    {
        try {
            User::where('id', $id)->first()->update(['disabled' => true]);
            return redirect()->back()->with('success', 'User disabled!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function addToBlacklist($user, $blockedBy, $blockType = 'temporary', $status = 1, $authType = 'admin'): bool
    {
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
