<?php

namespace App\Http\Controllers;

use App\Http\Helpers\JwtHelper;
use App\Http\Requests\TokenRequest;
use App\Http\Requests\UserDetachFromRoleRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request) //make request validation for user
    {
        $user = User::firstOrNew(['email' => $request->email]);
        $user->name = $request->name;
        $user->password = Hash::make($request->getPassword());
        $user->save();

        $user->roles()->attach($request->role_id);

        return new UserResource($user);
    }

    public function issueToken(TokenRequest $request)
    {
        return response()->json([
            'token' => JwtHelper::issue($request->aud, $request->sub, $request->exp)
        ]);
    }

    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    public function update(Request $request, User $user)
    {
        $user->name = $request->name ?? $user->name;
        $user->save();

        return $user->load('roles');
    }

    public function detachFromRole(UserDetachFromRoleRequest $request)
    {
        $user = User::find($request->user_id);
        $user->roles()->detach($request->role_id);

        return $user->load('roles');
    }

    public function destroy(User $user): Response
    {
        $user->delete();

        return response(null, 204);
    }
}
