<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   public function register( Request $request)
   {
        try {
            $validator =Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'profesi' => 'required|string|max:255',
                'password' => 'required|string|min:8',
              ]);

              if ($validator->fails()) {
                $errors = [];

                if ($validator->errors()->has('name')) {
                    $errors['name'] = $validator->errors()->first('name');
                }
                if ($validator->errors()->has('email')) {
                    $errors['email'] = $validator->errors()->first('email');
                }
                if ($validator->errors()->has('profesi')) {
                    $errors['profesi'] = $validator->errors()->first('profesi');
                }
                if ($validator->errors()->has('password')) {
                    $errors['password'] = $validator->errors()->first('password');
                }
                return response()->json([
                    'code' => '400',
                    'status' => 'BAD_REQUEST',
                    'message' => 'Validation error',
                    'errors' => $errors,
                ],400);
              }

              $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'profesi' => $request->profesi,
                'password' => Hash::make($request->password),
             ]);

              if ($user) {
                return response()->json([
                    'code' => '200',
                    'status' =>  'OK',
                    'message' => 'User created successfully',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'profesi' => $user->profesi,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                ], 200);
              } else {
                return response()->json([
                    'code' => '400',
                    'status' =>  'BAD_REQUEST',
                    'message' => 'User created failed',
                    'data' => null,
                ]);
              }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '500',
                'status' =>  'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage(),
                'data' => null,
            ],500);
        }

   }

   public function login(Request $request)
   {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'code' => '401',
            'status' =>  'UNAUTHORIZED',
            'message' => 'Unauthorized',
            'errors' => 'Email dan Password tidak sesuai',
        ],401);
    }

    $token = $user->createToken('token-name')->plainTextToken;
    return response()->json([
        'code' => '200',
        'status' =>  'OK',
        'message' => 'Login success',
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profesi' => $user->profesi,
            'token' => $token,
        ],
    ],200);

   }

   public function logout(Request $request)
   {
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'code' => '200',
        'status' =>  'OK',
        'message' => 'Logout success',
        'data' => null,
    ],200);
   }
}
