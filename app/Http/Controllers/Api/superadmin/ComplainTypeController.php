<?php

namespace App\Http\Controllers\api\superadmin;

use App\Models\ComplainType;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreComplainTypeRequest;
use App\Http\Requests\UpdateComplainTypeRequest;
use Illuminate\Http\Request;

class ComplainTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = ComplainType::get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json([
                    'code' => 200,
                    'message' => 'Complain types fetched successfully',
                    'data' => $records,
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
                    'name' => 'required',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = ComplainType::create([
                        'name' => $data['name'],
                        'user_id' => auth()->user()->id,
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
    public function update(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = $request->all();
                $rules = [
                    'name' => 'required',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = ComplainType::where('id', $data['id'])
                        ->update([
                            'name' => $data['name'],
                        ]);
                    return ResponseService::successResponse('Complain type has been updated successfully!', $data);
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
    public function destroy($id)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $record = ComplainType::find($id);
                if ($record) {
                    $data = ComplainType::where('id', $id)->delete();
                    return response()->json(['code' => 200, 'message' => 'Complain type Successfully deleted', 'data' => $data], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Complain type not found'], 500);
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
}
