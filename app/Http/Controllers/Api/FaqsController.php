<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class FaqsController extends Controller
{
    public function index()
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = Faq::get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json([
                    'code' => 200,
                    'message' => 'FAqs fetched successfully',
                    'faqs' => $records,
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

    public function create(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = $request->all();
                $rules = [
                    'question' => 'required',
                    'answer' => 'required',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = Faq::create([
                        'question' => $data['question'],
                        'answer' => $data['answer'],
                        'is_active' =>  $data['is_active'],
                        'created_by' => auth()->user()->id,
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
    public function destroy($id)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {

                $record = Faq::find($id);
                if ($record) {
                    $data = Faq::where('id', $id)->delete();
                    return response()->json(['code' => 200, 'message' => 'Faqs Successfully deleted', 'data' => $data], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Faqs not found'], 500);
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
    public function update(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $data = $request->all();
                $rules = [
                    'question' => 'required',
                    'answer' => 'required',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = Faq::where('id', $data['id'])
                        ->update([
                            'question' => $data['question'],
                            'answer' => $data['answer'],
                            'is_active' =>  $data['is_active'],
                            'created_by' => auth()->user()->id,
                        ]);
                    return ResponseService::successResponse('Content has been updated successfully!', $data);
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
