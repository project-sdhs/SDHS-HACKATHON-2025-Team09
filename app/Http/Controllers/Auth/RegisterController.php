<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|unique:users,user_id',
            'password' => 'required|confirmed|min:6',
            'name' => 'required',
            'birth' => 'required',
            'gender' => 'required'
        ]);

        User::create([
            'user_id' => $request->user_id,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'birth' => $request->birth,
            'gender' => $request->gender
        ]);

        return redirect('login')->with('success', '회원가입 성공!');
    }
}