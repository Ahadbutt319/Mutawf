<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\AboutUs;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAboutUsRequest;
use App\Http\Requests\UpdateAboutUsRequest;


class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $role = auth()->user()->role->role;

            if ($role === 'admin' || $role === 'customer') {
                return response()->json([
                    'code' => 200,
                    'data' => AboutUs::get()->all(),
                    'message' => 'Content fetched successfully',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = $request->all();
                $rules = [
                    'content' => 'required|string',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = AboutUs::create([
                        'content' => $data['content'],
                        'user_id' => auth()->user()->id
                    ]);
                    return ResponseService::successResponse('Content added Successfully!', $data);
                }
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = $request->all();
                $rules = [
                    'content' => 'required|string',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = AboutUs::where('id', $aboutUs->id)
                        ->update([
                            'content' =>  $data['content'],
                        ]);
                    return ResponseService::successResponse('Content has been updated successfully',$data );
                }
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = AboutUs::where('id', $aboutUs->id)->delete();
                return response()->json(['code' => 200, 'message' => 'About content Successfully deleted', 'data' => $data], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
}
