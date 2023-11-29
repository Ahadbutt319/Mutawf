<?php

namespace App\Repositories;
use App\Interfaces\groundServiceRepositoryInterface;
use App\Models\GroundService;
use App\Models\GroundServiceActivity;

class groudServiceRepository implements  groundServiceRepositoryInterface 
{
    public function createGroundService(array $groundServiceDetails) 
    {
        $groundservice_image =  time() . '_' . $groundServiceDetails['image']->getClientOriginalName();
        $imagePath = $groundServiceDetails['image']->move('public/ground_service_image',$groundservice_image); // Store the image file
        $imageUrl = asset(str_replace('public', 'storage',  $groundservice_image));   
        $data =  GroundService::create([
        'added_by' => auth()->id(), // Assuming you are using authentication
        'guider_name' => $groundServiceDetails['guider_name'],
        'pu_location' => $groundServiceDetails['pu_location'],
        'persons' => $groundServiceDetails['persons'],
        'price' => $groundServiceDetails['price'],
        'description' => $groundServiceDetails['description'],
        'services' => $groundServiceDetails['services'],
        'start_date' => $groundServiceDetails['start_date'],
        'image' =>  $imageUrl,
        ]);
        foreach ($groundServiceDetails['groundserviceactivity'] as $activity) {
        $groundserviceactivity_image =  time() . '_' . $activity['image']->getClientOriginalName();
        $imagePath =  $activity['image']->move('public/ground_service_acitvities_images',$groundserviceactivity_image); // Store the image file
        $AciticytimageUrl = asset(str_replace('public', 'storage',  $groundserviceactivity_image));
            GroundServiceActivity::create([
                'ground_Service_id'=> $data->id,
                'visit_location'=>  $activity['visit_location'],
                'description'=>  $activity['description'],
                'image'=>  $AciticytimageUrl,
            ]);   
        } 
        return $data ;
    }

}




