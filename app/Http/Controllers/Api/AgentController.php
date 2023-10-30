<?php

namespace App\Http\Controllers\api;

use App\Models\ImageCategory;
use App\Models\Agents;
use App\Models\Operator;
use App\Models\PackageKey;
use App\Models\RoomBooking;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Models\AgentImage;
use App\Models\AgentHotel;
use App\Models\Room;
use App\Models\AgentPackage;
use App\Models\RoomCategory;
use App\Models\User;

class AgentController extends Controller
{
    public function getProfile(){}


    public function addImageCategory(Request $request){
        $data=$request->all();
        $rules = [
            'image_type' => 'required|string',
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);
        if($validation->fails())
        {
        return ResponseService::validationErrorResponse($validation->errors()->first());
        }
        else{
            ImageCategory::create([
            'image_type'=>$data["image_type"],
            ]);
            return ResponseService::successResponse('Your Category Added' );
        }
    }
    public function fetchOperators(){
        return response()->json([
            'code'=>200,
            'message'=>'Operators fetched successfully',
            'hotels' =>Operator::select('id','phone', 'email', 'name')->get()
        ], 200);
    }
    public function becomeAnOperator(Request $request){


        $existsInOperators = Operator::where('user_id', auth()->user()->id)->exists();

        if ($existsInOperators) {
        return ResponseService::successResponse('You are already an operator!',$existsInOperators);
        }


        $data=$request->all();
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

            if($validation->fails())
            {
            return ResponseService::validationErrorResponse($validation->errors()->first());
            }
            else{

                $operator = Operator::create([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'clearance_area' => $data['clearance_area'],
                    'availability' => $data['availability'],
                    'type' => $data['type'],
                    'user_id' => auth()->user()->id,
                ]);
                $images=$request->file('images');
                $category=ImageCategory::where('image_type','Operator')->pluck('id')->first();
                foreach ($images as $image) {
                    if ($image->isValid()) {
                        $imagePath = $image->store('public/images'); // Store the image file

                        $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                        $agentImage = new AgentImage();
                        $agentImage->type_id= $operator->id;
                        $agentImage->category_id= $category;
                        $agentImage->image =$imageUrl;
                        $agentImage->save();
                    }
                }
                return ResponseService::successResponse('You are an operator now!',$operator );

        }
    }
    public function addHotel(Request $request){
        $data=$request->all();
        $validation=validator::make($data,[
            'hotel_name'=>'required|string',
            'private_transport'=>'required|string',
            'location'=>'required|string',
            'details'=>'required|string',
            'image' => 'required|array|min:2|max:3',
            'room_image'=>'required|array|min:2|max:3',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            'room_image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            //'reference_number' => 'required|string',
            'room_categories' => 'required|array',
        ]);


      if($validation->fails())
      {
        return ResponseService::validationErrorResponse($validation->errors()->first());
      }
      else{
        $agentId=User::where('id',auth()->user()->id)->pluck('id')->first();

        $agentHotel = AgentHotel::create([
        'hotel_name' => $data['hotel_name'],
        'private_transport' => $data['private_transport'],
        'location' => $data['location'],
        'details' => $data['details'],
        // You can also add 'Additional_Notes', 'Travel', 'Managed_by', 'Added_by' as necessary
        'added_by' => $agentId,
        ]);
        $rooms=$request->input('room_categories');
        $images=$request->file('image');
        $room_images=$request->file('room_image');
        foreach ($images as $image) {
            if ($image->isValid()) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(storage_path('app/public/images'), $imageName);

                $agentImage = new AgentImage();
                $agentImage->type_id	 = $agentHotel->id;
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
                $agentImage->type_id	 = $agentHotel->id;
                $agentImage->category_id = 2;
                $agentImage->image = 'agent_images/' . $imageName;
                $agentImage->save();


            }
        }
        for ($i = 0; $i < count($rooms); $i++) {
            $room = new RoomBooking();
            $room->room_hotel_id = $agentHotel->id;
            $id=RoomCategory::where('name',$rooms[$i])->pluck('id')->first();
            $room->room_category_id = $id;
            $room->added_by = auth()->user()->id;
            $room->save();
            }
        return ResponseService::successResponse('Your Hotel is Added Successfully !', $agentHotel );

      }


    }

    public function addPackage(Request $request){
      $data=$request->All();
      $validation=validator::make($data,[
       'package_name'=>'required|string',
       'duration'=>'required|string',
       'details'=>'required|string',
       'additional_Notes'=>'string',
       'managed_by'=>'required',
       'image' => 'required|array|min:2|max:3',
       'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
    ]);

      if($validation->fails())
      {
        return ResponseService::validationErrorResponse($validation->errors()->first());
      }
     else{
            $agentId=User::where('id',auth()->user()->id)->pluck('id')->first();
            $agentPackage = AgentPackage::create([
            'package_name' => $data['package_name'],
            'duration' => $data['duration'],
            'visa' => $data['visa'] ??NULL,
            'details' => $data['details'],
            'additional_notes' => $data['additional_notes'] ?? null,
            'travel' => $data['travel']??NULL,
            'managed_by' => $data['managed_by'],
            'hotel' => $data['hotel']??NULL,
            'added_by'=>$agentId
        ]);

          $images=$request->file('image');
            //adding images to dabatabse
            foreach ($images as $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('public/images'); // Store the image file

                    $imageUrl = asset(str_replace('public', 'storage', $imagePath)); // Generate the image URL


                    $agentImage = new AgentImage();
                    $agentImage->type_id= $agentPackage->id;
                    $agentImage->category_id	= 1;
                    $agentImage->image =$imageUrl;
                    $agentImage->save();
                }
            }
            $package=new PackageKey;
            $package->package=$agentPackage->id;
        if(isset($data['visa'])){
            $package->visa=true;
        }
        if(isset($data['travel'])){
            $package->travel=true;
        }
        if(isset($data['hotel'])){
            $package->hotel=true;
        }
        $package->save();
            return ResponseService::successResponse('You Package is Added Successfully !',$agentPackage);
         }
    }


    public function getGeneralPackage()
    {
        return response()->json([
            'code'=>200,
            'message'=>'Hotels fetched successfully',
            'hotels' =>  AgentPackage::with('Keys')->with('Images')->get()
        ], 200);
    }


    public function getHotels()
    {
        return response()->json([
            'code'=>200,
            'message'=>'Hotels fetched successfully',
            'hotels' =>  AgentHotel::with('getImages')->get()
        ], 200);
    }

    public function getPackages()
    {
        return response()->json([
            'code'=>200,
            'message'=>'Packages fetched successfully',
            'packages' => AgentPackage::with('getImages')->get()
        ], 200);

    }

}
