<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ResponseService;
class HotelController extends Controller

{
    public function searchLocation(Request $request){

        $rules=[
            'Location' =>'required',
         ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        }
    $client = new \GuzzleHttp\Client();
    $location = $request->string('location');
    $url =  'https://tripadvisor16.p.rapidapi.com/api/v1/hotels/searchLocation?query='. urlencode($location);
    $response = $client->request('GET', $url,[
	'headers' => [
		'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
		'X-RapidAPI-Key' => '59712ca40dmshee9bc214bfab1d9p19de0fjsn1ed2f564f838',
	],
        ]);

    echo $response->getBody();
    }


    public function getGeoId(){
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://tripadvisor16.p.rapidapi.com/api/v1/hotels/getHotelsFilter?geoId=17674289&checkIn=2023-10-31&checkOut=2023-10-21', [
            'headers' => [
                'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
                'X-RapidAPI-Key' => '59712ca40dmshee9bc214bfab1d9p19de0fjsn1ed2f564f838',
            ],
        ]);

        echo $response->getBody();

    }

    public function getHotelDetails(){

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://tripadvisor16.p.rapidapi.com/api/v1/hotels/getHotelDetails?id=%3CREQUIRED%3E&checkIn=%3CREQUIRED%3E&checkOut=%3CREQUIRED%3E&currency=USD', [
	'headers' => [
		'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
		'X-RapidAPI-Key' => '59712ca40dmshee9bc214bfab1d9p19de0fjsn1ed2f564f838',
	],
]);

echo $response->getBody();
    }

}
