<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\groundServiceRepositoryInterface;

class GroundServicesContoller extends Controller
{

    //this is  laravel Reposity  pattern code structure
    //to understand this pattern open this link https://www.twilio.com/blog/repository-pattern-in-laravel-application

    private groundServiceRepositoryInterface $groudServiceRepository;

    public function __construct(groundServiceRepositoryInterface $groudServiceRepository)
    {
        $this->groudServiceRepository = $groudServiceRepository;
    }


public function update(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'id'=>'required',
            'guider_name' => 'string',
            'pu_location' => 'string',
            'persons' => 'string',
            'price' => 'string',
            'description' => 'sometimes|required', // Only validate if present
            'services' => 'string',
            'start_date' => 'date',
            'image' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {

        //$service = GroundService::findOrFail($data["id"]);
        $updateDetails = $request->all();
        return response()->json([
            'data' => $this->groudServiceRepository->updateGroundService($updateDetails), 'message' => 'Ground Services has been updated successfully'
        ], 200);

    }
        // Prepare an array of fields to upda
    }

    public function delete(Request $request){
        $data=$request->all();
        $rules=[
          'id'=>'required|exists:ground_services,id'
        ];
        $validator=validator::make($data,$rules);
         // If validation fails, return the errors
         if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 400);
      }
          else{
              return response()->json([
                'data' => $this->groudServiceRepository->deleteGroundService($data["id"]), 'message' => 'Ground Services has been deleted succcessfully'
            ], 200);
          }
    }

    public function create(Request $request)
    {
        // Validation rules
        $rules = [
            'guider_name' => 'required|string|max:255',
            'pu_location' => 'required|string|max:255',
            'persons' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'groundserviceactivity.*.visit_location' => 'required|string|max:255',
            'groundserviceactivity.*.description' => 'required|string|',
        ];
        // Validation messages
        $messages = [
            'image.required' => 'The image field is required.',
            'image.image' => 'The image must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $groundServiceDetails = $request->all();
        return response()->json([
            'data' => $this->groudServiceRepository->createGroundService($groundServiceDetails), 'message' => 'Ground Services has been created succcessfully'
        ], 200);
    }
    public function index()
    {
        return response()->json(['data' =>  $this->groudServiceRepository->getAllGroundServices()], 200);
    }
    public function detail(Request $request)
    {
        // Validation rules
        $rules = [
            'id' => 'required|exists:ground_services,id',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules,);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $groundServiceId = $request->id;

        return response()->json([
            'data' => $this->groudServiceRepository->getDetailGroundServices($groundServiceId), 'message' => 'Ground Services has been fetched succcessfully'
        ], 200);
    }
    public function search(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'pu_location' => 'required|string|max:255',
                'persons' => 'required|integer|min:1',
                'start_date' => 'required|date',
            ];
            // Validate the request data
            $validator = Validator::make($request->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $groundServicSearch = $request->all();
            return response()->json([
                'data' => $this->groudServiceRepository->searchGroundService($groundServicSearch), 'message' => 'Ground Services has been fetched succcessfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['erro' => $th->getMessage()], 400);
        }
    }
    public function booking(Request $request)
    {
        try {

            $rules = [
                
                'ground_id' => 'required|exists:ground_services,id',
                'pu_date' => 'required|date',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'payment_id' => 'required',
                'persons' => 'required|integer|min:1',
                'details' => 'nullable|string',
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $bookingdetail = $request->all();
            return response()->json([
                'data' => $this->groudServiceRepository->bookGroundService($bookingdetail), 'message' => 'Ground Services has been Booked succcessfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['erro' => $th->getMessage()], 400);
        }
    }
}
