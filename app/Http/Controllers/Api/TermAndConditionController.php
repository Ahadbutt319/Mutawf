<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\TermAndCondition;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTermAndConditionRequest;
use App\Http\Requests\UpdateTermAndConditionRequest;

class TermAndConditionController extends Controller
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
                    'message' => 'Terms fetched successfully',
                    'data' => TermAndCondition::get()->all(),
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
                    $data = TermAndCondition::create([
                        'content' => $data['content'],
                        'user_id' => auth()->user()->id
                    ]);
                    return ResponseService::successResponse('Terms And Condition added Successfully!', $data);
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
    public function update(Request $request, TermAndCondition $termAndCondition)
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
                    $data = TermAndCondition::where('id', $termAndCondition->id)
                        ->update([
                            'content' =>  $data['content'],
                        ]);
                    return ResponseService::successResponse('Terms And Condition has been updated successfully',$data );
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
    public function destroy(TermAndCondition $termAndCondition)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = TermAndCondition::where('id', $termAndCondition->id)->delete();
                return response()->json(['code' => 200, 'message' => 'Terms And Condition Successfully deleted', 'data' => $data], 200);
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
