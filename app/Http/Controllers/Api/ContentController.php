<?php

namespace App\Http\Controllers\api;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Http\Resources\ContentResource;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function cancellation()
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $General=Content::where('content_id' , 6 )->first();
                $Refunds=Content::where('content_id' , 7 )->first();
                $Cancellationbythecustomer=Content::where('content_id' , 8 )->first();
                $Assistedrefund=Content::where('content_id' , 9 )->first();
                $Specialcases=Content::where('content_id' , 10 )->first();
                return response()->json([
                    'code' => 200,
                    'message' => 'Cancellation fetched successfully',
                    'general'=>$General,
                    'refunds'=>$Refunds,
                    'cancellation_by_the_customer'=>$Cancellationbythecustomer,
                    'assisted_refund'=>$Assistedrefund,
                    'special_cases'=>$Specialcases,
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
    public function index()
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $aboutus = Content::where('content_id' , 1 )->first();
                $termsandcondition = Content::where('content_id' , 2 )->first();
                $disclimars = Content::where('content_id' , 3 )->first();
                $privacy = Content::where('content_id' , 4 )->first();
                $policycancellation=Content::where('content_id' , 5 )->first();
                return response()->json([
                    'code' => 200,
                    'message' => 'Terms fetched successfully',
                    'about_us' =>   $aboutus,
                    'terms_condition' =>   $termsandcondition,
                    'disclimars' =>   $disclimars,
                    'privacy' =>   $privacy,
                    'policycancellation'=>$policycancellation
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
                    $data = Content::create([
                        'content' => $data['content'],
                        'user_id' => auth()->user()->id,
                        'content_id' => $data['content_type']
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
    public function update(Request $request, $id)
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
                    $data = Content::where('id', $id)
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
    public function destroy($id)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {

                $record = Content::find($id);
                if ( $record) {
                    $data = Content::where('id',$id)->delete();
                    return response()->json(['code' => 200, 'message' => 'content Successfully deleted', 'data' => $data], 200);
                }
                else{
                    return response()->json(['code' => 500, 'message' => 'content not found' ], 500);
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
