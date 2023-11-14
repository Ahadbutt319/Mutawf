<?php

namespace App\Http\Controllers\api\superadmin;

use App\Models\Complain;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreComplainRequest;
use App\Http\Requests\UpdateComplainRequest;
use App\Models\Action;
use App\Models\UserComplain;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function complainlist()
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin') {
                $data = Complain::get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json([
                    'code' => 200,
                    'message' => 'All Complains has been  fetched successfully',
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
            if ($role === 'admin' ) {
                $data = $request->all();
                $rules = [
                    'name' => 'required',
                ];
                // Create a validator instance
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = Complain::create([
                        'name' => $data['name'],
                        'compain_type_id' => $data['compain_type_id'],
                        'email' => $data['email'],
                        'description' => $data['description'],
                        'complaint_status_id' => Complain::complaint_status_pending,
                        'user_id' => auth()->user()->id,
                    ]);
                  $compalainUsers =   UserComplain::create([
                        'user_id' => auth()->user()->id,
                        'complain_id'=>  $data->id,
                    ]);
                    return response()->json(['message'=>'Complain has been added Successfully!','data'=> $data,'compalainUsers'=> $compalainUsers],200);    
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
     * Store a newly created resource in storage.
     */
    public function statuschange(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' ) {
                $data = $request->all();
                $data = Complain::where('id', $data['id'])
                    ->update([
                        'complaint_status_id' => $data['complaint_status_id'],
                    ]);
                    return ResponseService::successResponse('Status has be changed!', $data);
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
     * Display the specified resource.
     */
    public function adminactiononcomplain(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin' || $role === 'customer') {
                $requestdata = $request->all();
                $rules = [
                    'name' => 'required',
                    'description' => 'required',
                ];
                // Create a validator instance
                $validation = Validator::make($requestdata, $rules);
                if ($validation->fails()) {
                    return ResponseService::validationErrorResponse($validation->errors()->first());
                } else {
                    $data = Action::create([
                        'name' => $requestdata['name'],
                        'description' => $requestdata['description'],
                    ]);
                    AdminAction::create([
                        'user_id' => auth()->user()->id,
                        'complain_id' => $requestdata['complain_id'],
                        'action_id' => $data->id,
                    ]);
                    
                    return ResponseService::successResponse('Action added Successfully!', $data);
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


    public function detailview(Request $request)
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin') {
                $data = Complain::where('id', $request->id )->with('actions')->first();
                return response()->json([
                    'code' => 200,
                    'message' => ' Complain has been  fetched successfully',
                    'data' =>  $data,
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
     * Update the specified resource in storage.
     */
    public function update(UpdateComplainRequest $request, Complain $complain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complain $complain)
    {
        //
    }
}
