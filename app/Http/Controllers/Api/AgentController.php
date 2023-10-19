<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Models\AgentImages;
use App\Models\AgentPackage;
class AgentController extends Controller
{
    public function getProfile(){}


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
