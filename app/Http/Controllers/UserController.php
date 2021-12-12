<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);

        $email = $request->email;
        $password = Hash::make($request->password);

        $user = User::create([
            'email' => $email,
            'password' => $password
        ]);

        if ($user) {
            return response()->json([
                'message' => 'User registered.'
            ], 201);
        }
        else {
            return response()->json([
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password.'
            ], 401);
        }

        $token = bin2hex(random_bytes(40));

        $user->update([
            'token' => $token
        ]);

        return response()->json([
            'message' => 'Login successfully',
            'user' => $user
        ]);
    }
}