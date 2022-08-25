<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserChangePassword;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('swad_office')->get();
        return fractal($users, new UserTransformer)->parseIncludes('swad_office');
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        return $user;
    }
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->except('password'));
        return $user;
    }

    public function resetPassword(UserChangePassword $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only('password'));
        return $user;
    }
}
