<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function login(Request $request)
    {
        $creds = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($creds->fails()) {
            return ApiResponse::validationError($creds->errors());
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        $user = Auth::user();

        if ($user->role !== config('constants.roles.customer')) {
            Auth::logout();  // Optionally log the user out
            return ApiResponse::error('Access denied: Not a customer account', 403);
        }

        $token = $user->createToken('CustomerToken')->plainTextToken;

        return ApiResponse::success([
            'user' => $user->load('customer'),
            'token' => $token,
        ], 'Customer login successful');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return ApiResponse::success([], 'Logged out successfully');
    }


    public function profile()
    {
        $user = Auth::user()->load('customer');

        if ($user->role !== config('constants.roles.customer')) {
            return ApiResponse::error('Unauthorized', 403);
        }

        return ApiResponse::success([
            'user' => $user,
        ], 'Customer profile retrieved successfully');
    }
}
