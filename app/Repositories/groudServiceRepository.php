<?php

namespace App\Repositories;

use App\Traits\FileUpload;
use App\Models\GroundService;
use App\Models\GroundServiceActivity;
use App\Interfaces\groundServiceRepositoryInterface;
use App\Models\GroundBooking;
      
class groudServiceRepository implements  groundServiceRepositoryInterface
{

    public function deleteGroundService(int $id){
        GroundService::where('id',$id)->delete();
    }

    public function updateGroundService(array $updateDetails){
        $service=GroundService::where('id',$updateDetails["id"])->first();

        $updateData = [];
        foreach (['guider_name', 'pu_location', 'persons', 'price', 'description', 'services', 'start_date'] as $field) {
            if (isset($updateDetails[$field])) {
                $updateData[$field] = $updateDetails[$field];
            }
        }
        $service->update($updateData);
        if(isset($updateDetails['image'])){
        //$service->image->delete();
        $activityimagePath = $updateDetails['image']->store('public/images'); // Store the image file
        $activityimageUrl = asset(str_replace('public', 'storage', $activityimagePath)); // Generate the image URL
        $service->image=$activityimageUrl;
        }
        $service->save();
    }
    public function createGroundService(array $groundServiceDetails)
    {
        $imageUrl = FileUpload::file($groundServiceDetails['image'], 'public/ground_service_image/');
        $data =  GroundService::create([
            'added_by' => auth()->id(), // Assuming you are using authentication
            'guider_name' => $groundServiceDetails['guider_name'],
            'pu_location' => $groundServiceDetails['pu_location'],
            'name' => $groundServiceDetails['name'],
            'persons' => $groundServiceDetails['persons'],
            'price' => $groundServiceDetails['price'],
            'description' => $groundServiceDetails['description'],
            'services' => $groundServiceDetails['services'],
            'start_date' => $groundServiceDetails['start_date'],
            'image' =>  $imageUrl,
        ]);
        foreach ($groundServiceDetails['groundserviceactivity'] as $activity) {
            $imageUrl = FileUpload::file($activity['image'], 'public/ground_service_acitvities_images/');
            GroundServiceActivity::create([
                'ground_Service_id' => $data->id,
                'visit_location' =>  $activity['visit_location'],
                'description' =>  $activity['description'],
                'image' =>  $imageUrl,
            ]);
        }
        return $data;
    }
    public function getAllGroundServices()
    {
        return GroundService::with('groundActivites')->get();
    }
    public function getDetailGroundServices(int $groundServiceId)
    {
        return GroundService::where('id', $groundServiceId)->with('groundActivites')->get();

    }
    public function searchGroundService(array $groundServicSearch)
    {

        $query = GroundService::query();

        // Check if start_date is provided in the request
        if (isset($groundServicSearch['start_date'])) {

            $query->where('start_date', '>=', $groundServicSearch['start_date']);
        }

        // Check if persons is provided in the request
        if (isset($groundServicSearch['persons'])) {
            $query->where('persons', '>=', $groundServicSearch['persons']);
        }

        // Check if pu_location is provided in the request
        if (isset($groundServicSearch['pu_location'])) {
            $query->where('pu_location', 'like', '%' . $groundServicSearch['pu_location'] . '%');
        }

        return  $query->get();
    }
    public function bookGroundService(array $bookingdetail)
    {
    
        $per_person_price = GroundService::where('id',$bookingdetail['ground_id'])->first();
        $total_price =   $bookingdetail['persons']*$per_person_price['price'];
        if ($bookingdetail['persons'] <= $per_person_price['persons']  ) {
            $data = GroundBooking::create([
                'user_id' => auth()->id(), // Assuming you are using authentication
                'ground_id' => $bookingdetail['ground_id'],
                'pu_date' => $bookingdetail['pu_date'],
                'name' => $bookingdetail['name'],
                'email' => $bookingdetail['email'],
                'payment_id' => $bookingdetail['payment_id'],
                'persons' => $bookingdetail['persons'],
                'details' => $bookingdetail['details'],
                'total_price' =>   $total_price ,
            ]);      
            $per_person_price['persons'] -= $bookingdetail['persons'];
            $per_person_price->save();
            return  $data;
        }
        else{
            
            return response()->json(['message' => 'perosn limit  is exceded']);   
        }
      

    }
}
