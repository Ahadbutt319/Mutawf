<?php

namespace App\Http\Controllers\api;


use App\Models\User;
use App\Models\HotelPackage;
use App\Models\UmrahPackage;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\AgentPackageActivity;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreUmrahPackageRequest;
use App\Http\Requests\UpdateUmrahPackageRequest;

class UmrahPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                $data = UmrahPackage::with('hotels')->with('package_activities')->get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' =>$records
                ], 200);
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function  detailpackage(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer' || $user->role->role === 'admin') {
                $data =  UmrahPackage::where('id', $request->id)->with(['hotels.hotel_images','hotels.rooms','hotels.rooms.roomImages' ])->with('package_activities')->first();
                    $records = $data->getDetailRecord();       
                return response()->json([
                    'code' => 200,
                    'message' => 'Package  Detail fetched successfully',
                    'package_details' =>   $records
                ], 200);
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function searchpackage(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                $data = UmrahPackage::where('name', 'LIKE', '%' . $request->search . '%')->with('hotels')->with('package_activities')->get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' =>  $records 
                ], 200);
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
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
            $data = $request->All();

            $validation = validator::make($data, [
                'name' => 'required|string',
                'details' => 'required|string',
                'managed_by' => 'required|string',
                'duration' => 'string',
                'person' => 'required',
                'type' => 'required',
                'tags' => 'required',
                'price' => 'required',
                'package_status' => 'required',
            ]);

            if ($validation->fails()) {
                return ResponseService::validationErrorResponse($validation->errors()->first());
            } else {
                $agentId = User::where('id', auth()->user()->id)->pluck('id')->first();
                $ummrah_package =  UmrahPackage::create([
                    'sku' => 'SKU_' . uniqid(),
                    'name' => $data['name'],
                    'details' => $data['details'],
                    'managed_by' => $data['managed_by'],
                    'duration' => $data['duration'],
                    'person' => $data['person'],
                    'type' => $data['type'],
                    'first_start' => $data['first_start'],
                    'first_end' => $data['first_end'],
                    'second_start' => $data['second_start'],
                    'second_end' => $data['second_end'],
                    'tags' => $data['tags'],
                    'transport' => $data['transport'],
                    'user_id' => $agentId,
                    'price' => $data['price'],
                    'package_status' => $data['package_status'],
                ]);
                $activityimagePath = $data['acitivity_image']->store('public/images'); // Store the image file
                $activityimageUrl = asset(str_replace('public', 'storage', $activityimagePath)); // Generate the image URL
                $packageactivities =   AgentPackageActivity::create([
                    'name' => $data['activity_name'],
                    'description' => $data['activity_description'],
                    'user_id' => $agentId,
                    'package_id' =>  $ummrah_package->id,
                    'image' => $activityimageUrl,
                ]);
                $count_hotel_id = count($data['hotel_id']);
                for ($i = 0; $i <  $count_hotel_id; $i++) {
                    HotelPackage::create([
                        'package_id' => $ummrah_package->id,
                        'hotel_id' => $data['hotel_id'][$i],
                    ]);
                }
                return response()->json(['data' => $ummrah_package, 'messsage' => "package has been added successfully "], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }

   

   
}
