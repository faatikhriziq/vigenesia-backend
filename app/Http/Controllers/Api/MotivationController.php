<?php

namespace App\Http\Controllers\Api;

use App\Models\Motivation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MotivationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $motivations = Motivation::all();
            return response()->json([
                'code' => '200',
                'status' => 'OK',
                'message' => 'Motivations retrieved successfully',
                'data' => $motivations,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '500',
                'status' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'motivation' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                $errors = [];

                if ($validator->errors()->has('motivation')) {
                    $errors['motivation'] = $validator->errors()->first('motivation');
                }

                return response()->json([
                    'code' => '400',
                    'status' => 'BAD_REQUEST',
                    'message' => 'Validation error',
                    'errors' => $errors,
                ], 400);
            }

            $motivation = Motivation::create([
                'user_id' => auth()->user()->id, // tambahkan ini untuk mengisi kolom 'user_id
                'motivation' => $request->motivation,
            ]);

            if($motivation) {
                return response()->json([
                    'code' => '200',
                    'status' => 'OK',
                    'message' => 'Motivation created successfully',
                    'data' => [
                        'id' => $motivation->id,
                        'motivation' => $motivation->motivation,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'code' => '400',
                    'status' => 'BAD_REQUEST',
                    'message' => 'Motivation failed to create',
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '500',
                'status' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $motivation = Motivation::find($id);

            if (!$motivation) {
                return response()->json([
                    'code' => '404',
                    'status' => 'NOT_FOUND',
                    'message' => 'Motivation not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'motivation' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                $errors = [];

                if ($validator->errors()->has('motivation')) {
                    $errors['motivation'] = $validator->errors()->first('motivation');
                }

                return response()->json([
                    'code' => '400',
                    'status' => 'BAD_REQUEST',
                    'message' => 'Validation error',
                    'errors' => $errors,
                ], 400);
            }

            $motivation->update([
                'motivation' => $request->motivation,
            ]);

            return response()->json([
                'code' => '200',
                'status' => 'OK',
                'message' => 'Motivation updated successfully',
                'data' => [
                    'id' => $motivation->id,
                    'motivation' => $motivation->motivation,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '500',
                'status' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $motivation = Motivation::find($id);
            if (!$motivation) {
                return response()->json([
                    'code' => '404',
                    'status' => 'NOT_FOUND',
                    'message' => 'Motivation not found',
                ], 404);
            }
            $motivation->delete();
            return response()->json([
                'code' => '200',
                'status' => 'OK',
                'message' => 'Motivation deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => '500',
                'status' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
