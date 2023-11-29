<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use App\Models\Agents;
use Faker\Core\Number;
use App\Models\Operator;
use App\Models\AgentVisa;
use App\Models\RoomImage;
use App\Models\AgentHotel;
use App\Models\AgentImage;
use App\Models\HotelImage;
use App\Models\PackageKey;
use App\Models\RoomBooking;
use App\Models\AgentPackage;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Models\GroundService;
use App\Models\ImageCategory;
use phpseclib3\System\SSH\Agent;
use App\Services\ResponseService;
use App\Models\AgentTransportation;
use App\Http\Controllers\Controller;
use App\Models\AgentPackageActivity;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{


    public function deleteHotel(Request $request){

        try {
            $data = $request->all();
            $validation = validator::make($data, [
                'id' => 'required|exists:agent_hotels,id',
            ]);
            if ($validation->fails()) {
                return ResponseService::validationErrorResponse($validation->errors()->first());
            } else {

/*                $delId = ImageCategory::where('image_type', 'Package')->first();
                AgentImage::where("type_id", $data["id"])->where('category_id', $delId)->delete();
                PackageKey::where("package", $data["id"])->delete();*/
                AgentHotel::where('id', $data['id'])->delete();

                return response()->json(['code' => 200, 'message' => 'Hotel Successfully deleted'], 200);
            };
        } catch (\Throwable $th) {
            return response()->json(['error' => $$th->getMessage(),]);
        }
    }

    public function addGroundServices(Request $request)
    {

        $rules = [
            'guider_name' => 'required|string',
            'tour_location' => 'required|string',
            'services' => 'required|array',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'activities' => 'required|array',
            'title' => 'required|string',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Insert data into the ground_services table
        $data = $request->only([
            'guider_name',
            'tour_location',
            'services',
            'description',
            'price',
            'activities',
            'title',
        ]);

        $data['added_by'] = auth()->user()->id;

        GroundService::create($data);

        return response()->json(['message' => 'Ground service created successfully'], 201);
    }

    public function getGroundServices()
    {
        return response()->json([
            'code' => 200,
            'message' => 'GroundServices fetched successfully',
            'packages' =>  GroundService::All()
        ], 200);
    }



    public function updateTransportation(Request $request)
    {
        $data = $request->all();
        $rules = [
            'id' => 'required',
            'type' => 'nullable|string',
            'availability' => 'nullable|string',
            'location' => 'nullable|string',
            'pickup' => 'nullable|string',
            'no_of_persons' => 'nullable|integer',
            'manage_by' => 'nullable|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|array|min:1',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $transportation = AgentTransportation::where('id', $data['id'])->first();

            $data;

            // Check each field and update it if it's not empty
            if ($request->filled('type')) {
                $data['type'] = $request->input('type');
            }

            if ($request->filled('availability')) {
                $data['availability'] = $request->input('availability');
            }

            if ($request->filled('location')) {
                $data['location'] = $request->input('location');
            }

            if ($request->filled('pickup')) {
                $data['pickup'] = $request->input('pickup');
            }

            if ($request->filled('no_of_persons')) {
                $data['no_of_persons'] = $request->input('no_of_persons');
            }

            if ($request->filled('manage_by')) {
                $data['manage_by'] = $request->input('manage_by');
            }

            if ($request->filled('tags')) {
                $data['tags'] = $request->input('tags');
            }

            // Handle image uploads if provided
            if ($request->has('image')) {
                $images = $request->file('image');
                $category = ImageCategory::where('image_type', 'Transportation')->pluck('id')->first();
                foreach ($images as $image) {
                    if ($image->isValid()) {

                        $imagePath = $image->move('public/images'); // Store the image file

                        $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                        $agentImage = new AgentImage();
                        $agentImage->type_id = $transportation->id;
                        $agentImage->category_id = $category;
                        $agentImage->image = $imageUrl;
                        $agentImage->save();
                    }
                }
            }

            $transportation->update($data);
        }
        return ResponseService::successResponse('Transportation is updated successfully !', $transportation);
    }



    public function updateVisa(Request $request)
    {
        $data = $request->all();
        $rules = [
            'id' => 'required',
            'visa' => 'nullable|string',
            'duration' => 'nullable|string',
            'visa_to' => 'nullable|string',
            'immigration' => 'nullable|string',
            'validity' => 'nullable|string',
            'manage_by' => 'nullable|string',
            'images' => 'nullable|array|min:1|max:2',
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $visa = AgentVisa::where('id', $data['id'])->first();

            // Create an array to store the data to update
            $data;

            // Check each field and update it if it's not null
            if ($request->filled('visa')) {
                $data['visa'] = $request->input('visa');
            }

            if ($request->filled('duration')) {
                $data['duration'] = $request->input('duration');
            }

            if ($request->filled('visa_to')) {
                $data['visa_to'] = $request->input('visa_to');
            }

            if ($request->filled('immigration')) {
                $data['immigration'] = $request->input('immigration');
            }

            if ($request->filled('validity')) {
                $data['validity'] = $request->input('validity');
            }

            if ($request->filled('manage_by')) {
                $data['manage_by'] = $request->input('manage_by');
            }

            // Handle image uploads if provided
            if ($request->has('images')) {

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
            }
            $visa->update($data);
        }
        return ResponseService::successResponse('Visa is updated successfully !', $visa);
    }




    public function updateOperator(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'clearance_area' => 'nullable|string',
            'availability' => 'nullable|string',
            'type' => 'nullable|string',
            'images' => 'nullable|array|min:1|max:2',
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {

            $operator = Operator::where('id', $data['id'])->first();

            // Create an array to store the data to update
            $data;

            // Check each field and update it if it's not null
            if ($request->filled('name')) {
                $data['name'] = $request->input('name');
            }

            if ($request->filled('phone')) {
                $data['phone'] = $request->input('phone');
            }

            if ($request->filled('email')) {
                $data['email'] = $request->input('email');
            }

            if ($request->filled('clearance_area')) {
                $data['clearance_area'] = $request->input('clearance_area');
            }

            if ($request->filled('availability')) {
                $data['availability'] = $request->input('availability');
            }

            if ($request->filled('type')) {
                $data['type'] = $request->input('type');
            }

            // Handle image uploads if provided
            if ($request->has('images')) {

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
            }

            // Update the Operator data
            $operator->update($data);
        }
        return ResponseService::successResponse('Your information is updated successfully !', $operator);
    }

    public function updatePackage(Request $request)
    {

        $data = $request->all();

        $rules = [
            'id' => 'required',
            'package_name' => 'nullable|string',
            'duration' => 'nullable|string',
            'details' => 'nullable|string',
            'additional_Notes' => 'string',
            'managed_by' => 'nullable',
            'status' => 'nullable',
            'image' => 'nullable|array|min:2|max:3',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust
        ];
        $validator = validator::make($data, $rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        } else {
            $data;
            $package = AgentPackage::where('id', $data['id'])->first();


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

            $packageKeys = PackageKey::where('package', $data['id'])->first();

            if (isset($data['visa'])) {
                $packageKeys->visa = true;
            }
            if (isset($data['travel'])) {
                $packageKeys->travel = true;
            }
            if (isset($data['hotel'])) {
                $packageKeys->hotel = true;
            }
            $packageKeys->update();
            // Handle image uploads if provided
            if ($request->has('image')) {

                $images = $request->file('images');
                $category = ImageCategory::where('image_type', 'Package')->pluck('id')->first();
                foreach ($images as $image) {
                    if ($image->isValid()) {

                        $imagePath = $image->move('public/images'); // Store the image file

                        $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                        $agentImage = new AgentImage();
                        $agentImage->type_id = $package->id;
                        $agentImage->category_id = $category;
                        $agentImage->image = $imageUrl;
                        $agentImage->update();
                    }
                }
            }

            $package->update($data);
            return ResponseService::successResponse('Package updated Successfully!', $package);
        }
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
            'luxuries' => 'required|string',
            'details' => 'required|string',
        ]);
        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        } else {
            $agentHotel = AgentHotel::create([
                'hotel_name' => $data['hotel_name'],
                'private_transport' => $data['private_transport'],
                'location' => $data['location'],
                'details' => $data['details'],
                'luxuries' => $data['luxuries'],
                'checkin_time' => $data['checkin_time'],
                'checkout_time' => $data['checkout_time'],
                'is_active' => $data['is_active'],
                'parking' => $data['parking'],
                'wifi' => $data['wifi'],
                'food' => $data['food'],
                'added_by' => auth()->user()->id,
            ]);
            foreach ($request->hotel_images as $image) {
                // $imageName = time() . '_' . $image['image']->getClientOriginalName();
                // dd( $imageName);
                // $image->move(storage_path('app/public/images'), $imageName);
                $hotel_image = HotelImage::create([
                    'hotel_id' => $agentHotel->id,
                    'image' =>  $image['image'],
                    'image_type' =>   $image['image_type'],

                ]);
            }
            foreach ($request->rooms as $room) {
                $room_hotel = RoomBooking::create([
                    'name' =>  $room['name'],
                    'sku' => 'Room_' . uniqid(),
                    'price_per_night' => $room['price_per_night'],
                    'room_number' => $room['room_number'],
                    'floor_number' => $room['floor_number'],
                    'bed_type' => $room['bed_type'],
                    'is_available' => $room['is_available'],
                    'room_category_id' =>  $room['room_category_id'],
                    "capacity" => $room['capacity'],
                    "quantity" => $room['quantity'],
                    "added_by" => auth()->user()->id,
                    "room_hotel_id" =>  $agentHotel->id
                ]);
                foreach ($room['room_images'] as $room_image) {
                    // $imageName = time() . '_' . $room_image['image']->getClientOriginalName();
                    // $image->move(storage_path('app/public/images'), $imageName);
                    $room_images = RoomImage::create([
                        'room_id' => $room_hotel->id,
                        'image' =>  $room_image['image'],
                        'room_image_type' => $room_image['image_type'],
                    ]);
                }
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
        } else {
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
                'person' => $data['person'],
                'price' => $data['price'],
                'added_by' => $agentId
            ]);
            $activityimagePath = $data['acitivity_image']->store('public/images'); // Store the image file
            $activityimageUrl = asset(str_replace('public', 'storage', $activityimagePath)); // Generate the image URL
            $packageactivities =   AgentPackageActivity::create([
                'name' => $data['activity_name'],
                'description' => $data['activity_description'],
                'user_id' => $agentId,
                'package_id' => $agentPackage->id,
                'image' => $activityimageUrl,

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
            return ResponseService::successResponse('You Package is Added Successfully !', $agentPackage, $packageactivities);
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
        try {
            $data = $request->all();
            $validation = validator::make($data, [
                'id' => 'required|exists:agent_packages,id',
            ]);
            if ($validation->fails()) {
                return ResponseService::validationErrorResponse($validation->errors()->first());
            } else {

/*                $delId = ImageCategory::where('image_type', 'Package')->first();
                AgentImage::where("type_id", $data["id"])->where('category_id', $delId)->delete();
                PackageKey::where("package", $data["id"])->delete();*/
                AgentPackage::where('id', $data['id'])->delete();

                return response()->json(['code' => 200, 'message' => 'Package Successfully deleted'], 200);
            };
        } catch (\Throwable $th) {
            return response()->json(['error' => $$th->getMessage(),]);
        }

    }

    public function getGeneralPackage()
    {
        return response()->json([
            'code' => 200,
            'message' => 'Packages fetched successfully',
            'packages' =>  AgentPackage::with('Keys')->with('packageactivites')->with('Images')->get()
        ], 200);
    }

    public function getHotels()
    {
        $data = AgentHotel::with('hotel_images')->with('rooms.roomImages')->get();
        $record  = [];
        foreach ($data as $d) {
            $record[] = $d->getdata();
        }

        return response()->json([
            'code' => 200,
            'message' => 'Hotels fetched successfully',
            'hotels' =>  $record,

        ], 200);
    }

    public function getHotelDetail(Request $request)
    {
        try {
            $data = AgentHotel::where('id', $request->id)->with('hotel_images')->with('rooms.roomImages')->first();
            $record =  $data->getdetails();
            return response()->json([
                'code' => 200,
                'message' => 'Hotel fetched successfully',
                'hotek_detail' =>  $record 

            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $$th->getMessage(),]);
        }
    }

    public function searchHotel(Request $request)
    {
        try {
            $query = AgentHotel::query();
            // Name
            if ($request->has('name')) {
                $query->where('hotel_name', 'like', '%' . $request->input('name') . '%');
            }
            // Check-in and Check-out time range
            if ($request->has('checkin_time') && $request->has('checkout_time')) {
                $checkinTime = \Carbon\Carbon::parse($request->input('checkin_time'));
                $checkoutTime = \Carbon\Carbon::parse($request->input('checkout_time'));

                $query->where(function ($subQuery) use ($checkinTime, $checkoutTime) {
                    $subQuery->whereBetween('checkin_time', [$checkinTime, $checkoutTime])
                        ->orWhereBetween('checkout_time', [$checkinTime, $checkoutTime]);
                });
            } elseif ($request->has('checkin_time')) {
                // Only Check-in time provided
                $checkinTime = \Carbon\Carbon::parse($request->input('checkin_time'));
                $query->where('checkin_time', '>=', $checkinTime);
            } elseif ($request->has('checkout_time')) {
                // Only Check-out time provided
                $checkoutTime = \Carbon\Carbon::parse($request->input('checkout_time'));
                $query->where('checkout_time', '<=', $checkoutTime);
            }
            // Persons
            if ($request->has('persons')) {
                $query->whereHas('rooms', function ($subQuery) use ($request) {
                    $subQuery->where('capacity', '>=', $request->input('persons'));
                });
            }
            // Location
            if ($request->has('location')) {
                $query->where('location', 'like', '%' . $request->input('location') . '%');
            }
            $results = $query->get();
            // You can customize the returned data as per your requirements
            $formattedResults = $results->map(function ($hotel) {
                return $hotel->getdata();
            });
            return response()->json(['message'=>'Hotels fetched successfully','hotels' => $formattedResults], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $$th->getMessage(),]);
        }
    }
}
