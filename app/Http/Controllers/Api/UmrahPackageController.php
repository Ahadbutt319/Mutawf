<?php

namespace App\Http\Controllers\api;


use App\Models\User;
use App\Traits\FileUpload;
use App\Models\HotelPackage;
use App\Models\UmrahPackage;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\AgentPackageActivity;
use Illuminate\Support\Facades\Storage;
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




    public function update(Request $request)
    {
        try {
            $data = $request->all();

            $validation = Validator::make($data, [
                'id'=>'required',
                'name' => 'string',
                'details' => 'string',
                'managed_by' => 'string',
                'duration' => 'string',
                'person' => 'sometimes|required', // Only validate if present
                'type' => 'string',
                'tags' => 'array',
                'price' => 'numeric',
                'package_status' => 'string',
            ]);

            if ($validation->fails()) {
                return ResponseService::validationErrorResponse($validation->errors()->first());
            } else {

                // Find the UmrahPackage by packageId
                $ummrah_package = UmrahPackage::findOrFail($data["id"]);

                // Prepare an array of fields to update
                $updateData = [];
                foreach (['name', 'details', 'managed_by', 'duration', 'person', 'type', 'tags', 'price', 'package_status'] as $field) {
                    if (isset($data[$field])) {
                        $updateData[$field] = $data[$field];
                    }
                }

                // Update UmrahPackage fields
                $ummrah_package->update($updateData);

                // Update or create AgentPackageActivity
                /*
                $activityimagePath = $data['acitivity_image']->store('public/images');
                $activityimageUrl = asset(str_replace('public', 'storage', $activityimagePath));
                */

                $packageactivities=AgentPackageActivity::where('package_id',$data["id"])->first();
                $updateData2=[];
                if(isset($packageactivities)){
                if(isset($data["activity_name"])){
                       $packageactivities->name=$data["activity_name"];
                }
                if(isset($data["description"])){
                    $packageactivities->description=$data["description"];
             }
             if(isset($data["image"])){

                Storage::delete($packageactivities->image);
                $packageactivities->image = FileUpload::file($data['image'], 'public/package_images/');
         }
            }

 $packageactivities->save();

             /*   $packageactivities = AgentPackageActivity::updateOrCreate(
                    ['package_id' => $ummrah_package->id],
                    [
                        'name' => $data['activity_name'] ?? $ummrah_package->agentPackageActivity->name,
                        'description' => $data['activity_description'] ?? $ummrah_package->agentPackageActivity->description,
                        'user_id' => auth()->user()->id,
                        'package_id' => $ummrah_package->id,
                        'image' => $activityimageUrl,
                    ]
                );
*/
                return response()->json(['data' => $ummrah_package, 'messsage' => "package has been updated successfully "], 200);
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
                $imageUrl = FileUpload::file($data['acitivity_image'], 'public/package_images/');
                $packageactivities =   AgentPackageActivity::create([
                    'name' => $data['activity_name'],
                    'description' => $data['activity_description'],
                    'user_id' => $agentId,
                    'package_id' =>  $ummrah_package->id,
                    'image' =>  $imageUrl ,
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


    public function deletePackage(Request $request)
    {
        try {
            $data = $request->all();
            $validation = validator::make($data, [
                'id' => 'required|exists:umrah_packages,id',
            ]);
            if ($validation->fails()) {
                return ResponseService::validationErrorResponse($validation->errors()->first());
            } else {
/*                $delId = ImageCategory::where('image_type', 'Package')->first();
                AgentImage::where("type_id", $data["id"])->where('category_id', $delId)->delete();
                PackageKey::where("package", $data["id"])->delete();*/
                UmrahPackage::where('id', $data['id'])->delete();

                return response()->json(['code' => 200, 'message' => 'Package Successfully deleted'], 200);
            };
        } catch (\Throwable $th) {
            return response()->json(['error' => $$th->getMessage(),]);
        }
    }
};
