<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        $user = Auth::user();

        if ($user->role !== config('constants.roles.admin')) {
            Auth::logout();
            return ApiResponse::error('Access denied: Not an admin account', 403);
        }

        $token = $user->createToken('AdminToken')->plainTextToken;

        return ApiResponse::success([
            'admin' => $user->load('admin'),
            'token' => $token,
        ], 'Admin login successful');
    }


    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return ApiResponse::success([], 'Logged out successfully');
    }


    public function profile()
    {
        $user = Auth::user()->load('admin');

        if ($user->role !== config('constants.roles.admin')) {
            return ApiResponse::error('Unauthorized', 403);
        }

        return ApiResponse::success([
            'user' => $user,
        ], 'Admin profile retrieved successfully');
    }

}
