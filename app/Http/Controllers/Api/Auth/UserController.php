<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function user()
    {
        if (auth()->user()) {
            return response()->json([
                'code' => 200,
                'status' => 'OK',
                'message' => 'User retrieved successfully',
                'data' => User::all(),
            ], 200);
        } else {
            return response()->json([
                'code' => 401,
                'status' => 'UNAUTHORIZED',
                'message' => 'Authentication required',
            ], 401);
        }
    }
}
