<?php

namespace App\Http\Controllers\api;

use App\Models\Room;
use App\Models\User;
use App\Models\Agents;
use App\Models\Operator;
use App\Models\AgentVisa;
use App\Models\AgentHotel;
use App\Models\AgentImage;
use App\Models\PackageKey;
use App\Models\RoomBooking;
use App\Models\AgentPackage;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Models\ImageCategory;
use App\Services\ResponseService;
use App\Models\AgentTransportation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{

    public function getProfile()

    public function getProfile(){}


    public function updatePackage(Request $request){

        $data=$request->all();

        $rules=[
            'id'=>'required',
            'package_name'=>'nullable|string',
            'duration'=>'nullable|string',
            'details'=>'nullable|string',
            'additional_Notes'=>'string',
            'managed_by'=>'nullable',
            'status'=>'nullable',
            'image' => 'nullable|array|min:2|max:3',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust
        ];
        $validator=validator::make($data,$rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        }

        else{
            $data;
            $package=AgentPackage::where('id',$data['id'])->first();


         // Check each field and update it if it's not null
    if ($request->filled('package_name')) {
        $data['package_name'] = $request->input('package_name');
    }

    if ($request->filled('duration')) {
        $data['duration'] = $request->input('duration');
    }

    if ($request->filled('details')) {
        $data['details'] = $request->input('details');
    }

    if ($request->filled('additional_Notes')) {
        $data['additional_Notes'] = $request->input('additional_Notes');
    }

    if ($request->filled('managed_by')) {
        $data['managed_by'] = $request->input('managed_by');
    }

    if ($request->filled('status')) {
        $data['status'] = $request->input('status');
    }

    $packageKeys=PackageKey::where('package',$data['id'])->first();

    if(isset($data['visa'])){
    $packageKeys->visa=true;
    }
    if(isset($data['travel'])){
    $packageKeys->travel=true;
    }
    if(isset($data['hotel'])){
    $packageKeys->hotel=true;
    }
    $packageKeys->update();
    // Handle image uploads if provided
    if ($request->has('image')) {

        $images=$request->file('images');
        $category=ImageCategory::where('image_type','Package')->pluck('id')->first();
        foreach ($images as $image) {
            if ($image->isValid()) {

                $imagePath = $image->move('public/images'); // Store the image file

                $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                $agentImage = new AgentImage();
                $agentImage->type_id= $package->id;
                $agentImage->category_id= $category;
                $agentImage->image =$imageUrl;
                $agentImage->update();
            }
        }
    }

                $package->update($data);
                return ResponseService::successResponse('Package updated Successfully!',$package );

        }
    }



public function addVisa(Request $request){

    $data=$request->all();
    $rules = [
        'visa' => 'required|string',
        'duration' => 'required|string',
        'visa_to' => 'required|string',
        'immigration' => 'required|string',
        'validity'=>'required|string',
        'manage_by' => 'required|string',
        'images' => 'required|array|min:1|max:2',
    ];

    // Create a validator instance
    $validation = Validator::make($data, $rules);

    if($validation->fails())

    {
    }
    public function addVisa(Request $request)
    {

        $data = $request->all();
        $rules = [
            'visa' => 'required|string',
            'duration' => 'required|string',
            'visa_to' => 'required|string',
            'immigration' => 'required|string',
            'validity' => 'required|string',
            'manage_by' => 'required|string',
            'images' => 'required|array|min:1|max:2',
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $visa = AgentVisa::create([
                'visa' => $data['visa'],
                'duration' => $data['duration'],
                'visa_to' => $data['visa_to'],
                'immigration' => $data['immigration'],
                'manage_by' => $data['manage_by'],
                'added_by' => auth()->user()->id,
                'validity' => $data['validity']
            ]);
            $images = $request->file('images');
            $category = ImageCategory::where('image_type', 'Visa')->pluck('id')->first();
            foreach ($images as $image) {
                if ($image->isValid()) {

                    $imagePath = $image->move('public/images'); // Store the image file

                    $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                    $agentImage = new AgentImage();
                    $agentImage->type_id = $visa->id;
                    $agentImage->category_id = $category;
                    $agentImage->image = $imageUrl;
                    $agentImage->save();
                }
            }
            return ResponseService::successResponse('Visa added Successfully!', $visa);
        }
    }
    public function addTransportation(Request $request)
    {
        $data = $request->all();

        $rules = [
            'type' => 'required|string',
            'availability' => 'required|string',
            'location' => 'required|string',
            'pickup' => 'required|string',
            'no_of_persons' => 'required|integer',
            'manage_by' => 'required|string',
            'tags' => 'required|string',
            'image' => 'required|array|min:1',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjus
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {
            $agentTransportation = AgentTransportation::create([
                'added_by' =>  auth()->user()->id,
                'type' => $data['type'],
                'availability' => $data['availability'],
                'location' => $data['location'],
                'pickup' => $data['pickup'],
                'no_of_persons' => $data['no_of_persons'],
                'manage_by' => $data['manage_by'],
                'tags' => $data['tags'],
            ]);

            $images = $request->file('image');
            $category = ImageCategory::where('image_type', 'Transportation')->pluck('id')->first();
            foreach ($images as $image) {
                if ($image->isValid()) {

                    $imagePath = $image->move('public/images'); // Store the image file

                    $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                    $agentImage = new AgentImage();
                    $agentImage->type_id = $agentTransportation->id;
                    $agentImage->category_id = $category;
                    $agentImage->image = $imageUrl;
                    $agentImage->save();
                }
            }

            return ResponseService::successResponse('Agent transportation record created successfully', $agentTransportation);
        }
    }
    public function addImageCategory(Request $request)
    {
        $data = $request->all();
        $rules = [
            'image_type' => 'required|string',
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {
            ImageCategory::create([
                'image_type' => $data["image_type"],
            ]);
            return ResponseService::successResponse('Your Category Added');
        }
    }
    public function fetchOperators()
    {
        return response()->json([
            'code' => 200,
            'message' => 'Operators fetched successfully',
            'operators' => Operator::select('id', 'phone', 'email', 'name')->get()
        ], 200);
    }
    public function becomeAnOperator(Request $request)
    {


        $existsInOperators = Operator::where('user_id', auth()->user()->id)->exists();

        if ($existsInOperators) {
            return ResponseService::successResponse('You are already an operator!', $existsInOperators);
        }


        $data = $request->all();
        $rules = [
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'clearance_area' => 'required|string',
            'availability' => 'required|string',
            'type' => 'required|string',
            'images' => 'required|array|min:1|max:2',
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $operator = Operator::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'clearance_area' => $data['clearance_area'],
                'availability' => $data['availability'],
                'type' => $data['type'],
                'user_id' => auth()->user()->id,
            ]);
            $images = $request->file('images');
            $category = ImageCategory::where('image_type', 'Operator')->pluck('id')->first();
            foreach ($images as $image) {
                if ($image->isValid()) {

                    $imagePath = $image->move('public/images'); // Store the image file

                    $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                    $agentImage = new AgentImage();
                    $agentImage->type_id = $operator->id;
                    $agentImage->category_id = $category;
                    $agentImage->image = $imageUrl;
                    $agentImage->save();
                }
            }
            return ResponseService::successResponse('You are an operator now!', $operator);
        }
    }
    public function addHotel(Request $request)
    {
        $data = $request->all();
        $validation = validator::make($data, [
            'hotel_name' => 'required|string',
            'private_transport' => 'required|string',
            'location' => 'required|string',
            'details' => 'required|string',
            'image' => 'required|array|min:2|max:3',
            'room_image' => 'required|array|min:2|max:3',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            'room_image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            //'reference_number' => 'required|string',
            'room_categories' => 'required|array',
        ]);


        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {
            $agentId = User::where('id', auth()->user()->id)->pluck('id')->first();

            $agentHotel = AgentHotel::create([
                'hotel_name' => $data['hotel_name'],
                'private_transport' => $data['private_transport'],
                'location' => $data['location'],
                'details' => $data['details'],
                // You can also add 'Additional_Notes', 'Travel', 'Managed_by', 'Added_by' as necessary
                'added_by' => $agentId,
            ]);
            $rooms = $request->input('room_categories');
            $images = $request->file('image');
            $room_images = $request->file('room_image');
            foreach ($images as $image) {
                if ($image->isValid()) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(storage_path('app/public/images'), $imageName);

                    $agentImage = new AgentImage();
                    $agentImage->type_id     = $agentHotel->id;
                    $agentImage->category_id = 1;
                    $agentImage->image = 'agent_images/' . $imageName;
                    $agentImage->save();
                }
            }
            foreach ($room_images as $image) {
                if ($image->isValid()) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(storage_path('app/public/images'), $imageName);

                    $agentImage = new AgentImage();
                    $agentImage->type_id     = $agentHotel->id;
                    $agentImage->category_id = 2;

                    $agentImage->image = 'agent_images/' . $imageName;
                    $agentImage->save();
                }
            }
            for ($i = 0; $i < count($rooms); $i++) {
                $room = new RoomBooking();
                $room->room_hotel_id = $agentHotel->id;
                $id = RoomCategory::where('name', $rooms[$i])->pluck('id')->first();
                $room->room_category_id = $id;
                $room->added_by = auth()->user()->id;
                $room->save();
            }
            return ResponseService::successResponse('Your Hotel is Added Successfully !', $agentHotel);
        }
    }
    public function addPackage(Request $request)
    {
        $data = $request->All();
        $validation = validator::make($data, [
            'package_name' => 'required|string',
            'duration' => 'required|string',
            'details' => 'required|string',
            'additional_Notes' => 'string',
            'managed_by' => 'required',
            'status' => 'required',
            'image' => 'required|array|min:2|max:3',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        ]);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        }
         else {
            $agentId = User::where('id', auth()->user()->id)->pluck('id')->first();
            $agentPackage = AgentPackage::create([
                'package_name' => $data['package_name'],
                'duration' => $data['duration'],
                'visa' => $data['visa'] ?? NULL,
                'details' => $data['details'],
                'additional_notes' => $data['additional_notes'] ?? null,
                'travel' => $data['travel'] ?? NULL,
                'managed_by' => $data['managed_by'],
                'hotel' => $data['hotel'] ?? NULL,
                'status' => $data['status'],
                'added_by' => $agentId
            ]);

            $images = $request->file('image');
            //adding images to dabatabse
            foreach ($images as $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('public/images'); // Store the image file

                    $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                    $agentImage = new AgentImage();
                    $agentImage->type_id = $agentPackage->id;
                    $agentImage->category_id    = 1;
                    $agentImage->image_type = $data["image_type"];
                    $agentImage->image = $imageUrl;
                    $agentImage->save();
                }
            }
            $package = new PackageKey;
            $package->package = $agentPackage->id;

            if (isset($data['visa'])) {
                $package->visa = true;
            }
            if (isset($data['travel'])) {
                $package->travel = true;
            }
            if (isset($data['hotel'])) {
                $package->hotel = true;
            }
            $package->save();
            return ResponseService::successResponse('You Package is Added Successfully !', $agentPackage);
        }
    }

    public function deleteVisa(Request $request)
    {

        $data = $request->all();
        $validation = validator::make($data, [
            'id' => 'required|exists:agent_visas,id',
        ]);
        if ($validation->fails()) {

            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $delId = ImageCategory::where('image_type', 'Visa')->first();
            AgentImage::where("type_id", $data["id"])->where('category_id', $delId)->delete();
            AgentVisa::where('id', $data['id'])->delete();

            return response()->json(['code' => 200, 'message' => 'Package Successfully deleted'], 200);
        };
    }
    public function deleteOperator(Request $request)
    {


        $data = $request->all();
        $validation = validator::make($data, [
            'id' => 'required|exists:operators,id',
        ]);
        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $delId = ImageCategory::where('image_type', 'Operator')->first();
            AgentImage::where("type_id", $data["id"])->where('category_id', $delId)->delete();
            Operator::where('id', $data['id'])->delete();

            return response()->json(['code' => 200, 'message' => 'Operator Successfully deleted'], 200);
        };
    }

    public function deletePackage(Request $request)
    {

        $data = $request->all();
        $validation = validator::make($data, [
            'id' => 'required|exists:agent_packages,id',
        ]);
        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $delId = ImageCategory::where('image_type', 'Package')->first();
            AgentImage::where("type_id", $data["id"])->where('category_id', $delId)->delete();
            PackageKey::where("package", $data["id"])->delete();
            AgentPackage::where('id', $data['id'])->delete();

            return response()->json(['code' => 200, 'message' => 'Package Successfully deleted'], 200);
        };
    }
    public function getGeneralPackage()
    {
        return response()->json([
            'code' => 200,
            'message' => 'Packages fetched successfully',
            'packages' =>  AgentPackage::with('Keys')->with('Images')->get()
        ], 200);
    }
    public function getHotels()
    {
        return response()->json([
            'code' => 200,
            'message' => 'Hotels fetched successfully',
            'hotels' =>  AgentHotel::with('getImages')->get()
        ], 200);
    }
    /*
    public function getPackages()
    {
        return response()->json([
            'code'=>200,
            'message'=>'Packages fetched successfully',
            'packages' => AgentPackage::with('getImages')->get()
        ], 200);

    }

    */
}
