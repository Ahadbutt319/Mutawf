<?php

namespace App\Http\Controllers\Api;


use App\Services\ResponseService;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function airportSearch(){
        $client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://travel-advisor.p.rapidapi.com/airports/search?query=new%20york&locale=en_US', [
	'headers' => [
		'X-RapidAPI-Host' => 'travel-advisor.p.rapidapi.com',
		'X-RapidAPI-Key' => '59712ca40dmshee9bc214bfab1d9p19de0fjsn1ed2f564f838',
	],
]);

return $response;
    }


	public function getAirports(Request $request){

        $rules=[
            'country' =>'required',
         ];

         $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        }

        $country = $request->string('country'); // Replace with the country you want to search for
        $client = new \GuzzleHttp\Client();
        $url = 'https://tripadvisor16.p.rapidapi.com/api/v1/flights/searchAirport?query=' . urlencode($country);
        $response = $client->request('GET', $url, [
        'headers' => [
        'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
        'X-RapidAPI-Key' => '1f73cff242msh0f5b618317565cap1d394fjsnf1a4d8234f55',
        ] ,
    ]);
        echo $response->getBody();
    }


    public function getFilter(Request $request){
        $client = new \GuzzleHttp\Client();
        // Get the dynamic parameters from the request

        $rules=[
            'sourceAirportCode' =>'required',
            'destinationAirportCode' =>'required',
            'date' =>'required | Date',
            'itineraryType' =>'required',
            'classOfService' =>'required'
              ];

         $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        }

        $sourceAirportCode = $request->input('sourceAirportCode');
        $destinationAirportCode = $request->input('destinationAirportCode');
        $date = $request->input('date');
        $itineraryType = $request->input('itineraryType');
        $classOfService = $request->input('classOfService');
        $client = new \GuzzleHttp\Client();

// Construct the URL with the dynamic parameters
    $url = "https://tripadvisor16.p.rapidapi.com/api/v1/flights/getFilters?" .
    "sourceAirportCode=$sourceAirportCode&" .
    "destinationAirportCode=$destinationAirportCode&" .
    "date=$date&" .
    "itineraryType=$itineraryType&" .
    "classOfService=$classOfService";

    $response = $client->request('GET', $url, [
    'headers' => [
        'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
        'X-RapidAPI-Key' => '1f73cff242msh0f5b618317565cap1d394fjsnf1a4d8234f55',
    ],
]);

echo $response->getBody();
    }


    public function getFlights(Request $request){
        $client = new \GuzzleHttp\Client();
        // Get the dynamic parameters from the request

        $rules=[
            'sourceAirportCode' =>'required',
            'destinationAirportCode' =>'required',
            'date' =>'required | Date',
            'itineraryType' =>'required',
            'classOfService' =>'required',
            'numAdults' =>'required | int ',  //age>18 and <64
            'numSeniors' =>'required | int',  //>64
              ];

         $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        }

        $sourceAirportCode = $request->input('sourceAirportCode');
        $destinationAirportCode = $request->input('destinationAirportCode');
        $date = $request->input('date');
        $itineraryType = $request->input('itineraryType');
        $classOfService = $request->input('classOfService');
        $numAdults=$request->input('numAdults');
        $numSeniors=$request->input('numSeniors');
        $sortOrder=$request->input('sortOrder');
        $client = new \GuzzleHttp\Client();

// Construct the URL with the dynamic parameters
    $url = "https://tripadvisor16.p.rapidapi.com/api/v1/flights/getFilters?" .
    "sourceAirportCode=$sourceAirportCode&" .
    "destinationAirportCode=$destinationAirportCode&" .
    "date=$date&" .
    "itineraryType=$itineraryType&" .
    "numAdults=$numAdults&" .
    "numSeniors=$numSeniors&" .
    "sortOrder=$sortOrder&" .
    "classOfService=$classOfService";

    $response = $client->request('GET', $url, [
    'headers' => [
        'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
        'X-RapidAPI-Key' => '1f73cff242msh0f5b618317565cap1d394fjsnf1a4d8234f55',
    ],
]);

echo $response->getBody();
    }


}
