<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Models\AgentImages;
use App\Models\AgentHotel;
use App\Models\Room;
use App\Models\AgentPackage;
use App\Models\RoomCategory;
class AgentController extends Controller
{
    public function getProfile(){}


    public function addHotel(Request $request){
        $data=$request->all();
        $validation=validator::make($data,[
            'hotel_name'=>'required|string',
            'private_transport'=>'required|string',
            'Location'=>'required|string',
            'Details'=>'required|string',
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
        $agentHotel = AgentHotel::create([
        'hotel_name' => $data['hotel_name'],
        'private_transport' => $data['private_transport'],
        'Location' => $data['Location'],
        'Details' => $data['Details'],
        // You can also add 'Additional_Notes', 'Travel', 'Managed_by', 'Added_by' as necessary
        'added_by' => auth()->user()->id,
        ]);
        $rooms=$request->input('room_categories');
        $images=$request->file('image');
        $room_images=$request->file('room_image');
        foreach ($images as $image) {
            if ($image->isValid()) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(storage_path('app/public/images'), $imageName);

                $agentImage = new AgentImages();
                $agentImage->Package_id	 = $agentHotel->id;
                $agentImage->category_id = 1;
                $agentImage->image = 'agent_images/' . $imageName;
                $agentImage->save();
            }
        }
        foreach ($room_images as $image) {
            if ($image->isValid()) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(storage_path('app/public/images'), $imageName);

                $agentImage = new AgentImages();
                $agentImage->Package_id	 = $agentHotel->id;
                $agentImage->category_id = 2;
                $agentImage->image = 'agent_images/' . $imageName;
                $agentImage->save();


            }
        }
;
            for ($i = 0; $i < count($rooms); $i++) {
            $room = new RoomCategory();
            $room->category_name = $request->room_categories[$i];
            $room->hotel_id = $agentHotel->id;
            $room->added_by = auth()->user()->id;
            $room->save();
            }
        return ResponseService::successResponse('Your Hotel is Added Successfully !');

      }


    }

    public function addPackage(Request $request){
      $data=$request->All();
      $validation=validator::make($data,[
       'Package_Name'=>'required|string',
       'Duration'=>'required|string',
       'Visa'=>'required|string',
       'Details'=>'required|string',
       'Additional_Notes'=>'string',
       'Travel'=>'required|string',
       'Managed_by'=>'required|string',
       'image' => 'required|array|min:2|max:3',
       'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
    ]);

      if($validation->fails())
      {
        return ResponseService::validationErrorResponse($validation->errors()->first());
      }
     else{

        $agentPackage = AgentPackage::create([
            'Package_Name' => $data['Package_Name'],
            'Duration' => $data['Duration'],
            'Visa' => $data['Visa'],
            'Details' => $data['Details'],
            'Additional_Notes' => $data['Additional_Notes'] ?? null,
            'Travel' => $data['Travel'],
            'Managed_by' => $data['Managed_by'],
            'Added_by'=>auth()->user()->id
        ]);

          $images=$request->file('image');
            //adding images to dabatabse
            foreach ($images as $image) {
                if ($image->isValid()) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(storage_path('app/public/images'), $imageName);

                    $agentImage = new AgentImages();
                    $agentImage->Package_id	 = $agentPackage->id;
                    $agentImage->category_id	= 1;
                    $agentImage->image = 'agent_images/' . $imageName;
                    $agentImage->save();
                }
            }
            return ResponseService::successResponse('You Package is Added Successfully !');
         }
    }

    public function getPackages()
    {
         $myPackages=AgentPackage::where('added_by',auth()->user()->id)->get();
         return $myPackages;
    }

}
