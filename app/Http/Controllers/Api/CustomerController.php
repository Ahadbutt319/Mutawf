<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visa;
use App\Models\bookpackage;
use App\Models\AgentPackage;
use App\Models\UmrahPackage;
use Illuminate\Http\Request;
use App\Models\PackageBooking;
use App\Mail\ReachusNotification;
use App\Models\PackageBookedPerson;
use App\Http\Controllers\Controller;
use App\Models\VisaBooking;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function getpackages()
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' =>  AgentPackage::with('Keys')->with('package_activities')->with('Images')->get()
                ], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function packagebooking(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {

                $data  = $request->all();

                $get_price =  UmrahPackage::where('id', $data['package_id'])->value('price');
                $total_amount = $data['quantity'] * $get_price;
                //if customer have visa already 

                if ($data['visa_status'] == 1) {
                    $validator = Validator::make($request->all(), [
                        'package_id' => 'required|exists:umrah_packages,id',
                        'from' => 'required|string',
                        'quantity' => 'required|integer|min:1',
                        'to' => 'required|string',
                        'payment_status' => 'required|boolean',
                        
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors' => $data], 422);
                    }
                    $booking =  PackageBooking::create([
                        'user_id' => $id,
                        "package_id" => $data['package_id'],
                        "from" => $data['from'],
                        "date" => Carbon::now(), // Use Carbon::now() to get the current date and time
                        "quantity" => $data['quantity'],
                        "to" => $data['to'],
                        'payment_status' => $data['payment_status'],
                        "total_amount" => $total_amount,
                        "payment_id" => $data['payment_id'],
                    ]);
                    // this condition for mobile side devloper becasue they send us a json string so that we need to change this into array and perfor our task 
                    if (is_string($data['persons']) && is_array(json_decode($data['persons'], true))) {
                        $persons = json_decode($data['persons'], true);
                        foreach ($persons as $person) {
                            $persons = PackageBookedPerson::create([
                                "email" => $person['email'],
                                "phone" => $person['phone'],
                                "name" => $person['name'],
                                "booking_id" => $booking->id
                            ]);
                        }
                    } else {
                        foreach ($data['persons'] as $person) {
                            $persons = PackageBookedPerson::create([
                                "email" => $person['email'],
                                "phone" => $person['phone'],
                                "name" => $person['name'],
                                "booking_id" => $booking->id
                            ]);
                        }
                    }
                    if (is_string($data['visas']) && is_array(json_decode($data['visas'], true))) {
                        $visas = json_decode($data['visas'], true);

                        foreach ($visas as $visa) {
                            $visas = Visa::create([
                                "passport_number" => $visa['passport_number'],
                                "id_number" => $visa['id_number'],
                                "visa_number" => $visa['visa_number'],
                                "booking_id" => $booking->id
                            ]);
                        }





                    } else {
                        foreach ($data['visas'] as $visa) {
                            $visas = Visa::create([
                                "passport_number" => $visa['passport_number'],
                                "id_number" => $visa['id_number'],
                                "visa_number" => $visa['visa_number'],
                                "booking_id" => $booking->id
                            ]);
                        }
                    }
                }
                // if they dont have visa and aplly for visa at same time
                else {
                    $validator = Validator::make($request->all(), [
                        'package_id' => 'required|exists:umrah_packages,id',
                        'from' => 'required|string',
                        'quantity' => 'required|integer|min:1',
                        'to' => 'required|string',
                        'payment_status' => 'required|boolean',
                       
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 422);
                    }
                    $booking =  PackageBooking::create([
                        'user_id' => $id,
                        "package_id" => $data['package_id'],
                        "from" => $data['from'],
                        "date" => Carbon::now(), // Use Carbon::now() to get the current date and time
                        "quantity" => $data['quantity'],
                        "to" => $data['to'],
                        'payment_status' => $data['payment_status'],
                        "total_amount" => $total_amount,
                        "payment_id" => $data['payment_id'],
                    ]);
                    // this condition for mobile side devloper becasue they send us a json string so that we need to change this into array and perfor our task 
                    if (is_string($data['persons']) && is_array(json_decode($data['persons'], true))) {
                        $persons = json_decode($data['persons'], true);
                        foreach ($persons as $person) {
                            $persons = PackageBookedPerson::create([
                                "email" => $person['email'],
                                "phone" => $person['phone'],
                                "name" => $person['name'],
                                "booking_id" => $booking->id
                            ]);
                        }
                    } else {
                        foreach ($data['persons'] as $person) {
                            $persons = PackageBookedPerson::create([
                                "email" => $person['email'],
                                "phone" => $person['phone'],
                                "name" => $person['name'],
                                "booking_id" => $booking->id
                            ]);
                        }
                    }
                    if (is_string($data['visas']) && is_array(json_decode($data['visas'], true))) {
                        $visas = json_decode($data['visas'], true);
                        foreach ($visas as $visa) { 
                            // Decode base64 to binary
                             $passportImageData = base64_decode($visa['passport_image']);
                            // Store binary data in storage
                            $passportImagePath = 'passport_images/' . uniqid() . '.jpg';
                            Storage::put($passportImagePath, $passportImageData);
                            // Decode base64 to binary
                            $passportPerosnphoto = base64_decode($visa['photo']);
                            // Store binary data in storage
                            $passportPerosnImagePath = 'passport_person_images/' . uniqid() . '.jpg';
                            Storage::put($passportPerosnImagePath, $passportPerosnphoto);
                           $visa_data =   Visa::create([
                                "passport_number" => $visa['passport_number'],
                                "nationality" => $visa['nationality'],
                                "id_number" => $visa['id_number'],
                                "visa_number" => $visa['visa_number'],
                                'photo' => $passportPerosnImagePath,
                                "passport_image" => $passportImagePath,
                                "booking_id" => $booking->id
                            ]);
                            VisaBooking::create([
                                'visas_id'=> $visa_data->id,
                                'user_id'=> auth()->user()->id,
                                'status'=> 'pending',
                            ]);

                        }
                    } else {
                        foreach ($data['visas'] as $visa) {
                            $visas = Visa::create([
                                "passport_number" => $visa['passport_number'],
                                "nationality" => $visa['nationality'],
                                "id_number" => $visa['id_number'],
                                "visa_number" => $visa['visa_number'],
                                "photo" => $visa['photo'],
                                "passport_image" => $visa['passport_image'],
                                "booking_id" => $booking->id
                            ]);
                        }
                    }
                }
                $pivot =  bookpackage::create([
                    "package_id" => $data['package_id'],
                    "booking_id" =>    $booking->id
                ]);
                return response()->json(['data' => $booking, 'message' => 'package has been booked successfully'], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function getbookings()
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                $data = PackageBooking::where('user_id', $id)->with('visas')->with('package_persons')->get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json(['data' => $records, 'message' => 'package has been booked successfully'], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function detailpackage(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer' || $user->role->role === 'admin') {
                return response()->json([
                    'code' => 200,
                    'message' => 'Package  Detail fetched successfully',
                    'package_details' =>  AgentPackage::where('id', $request->id)->with('Keys')->with('package_activities')->with('Images')->first()
                ], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function searchpackage(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                $data = AgentPackage::where('package_name', 'LIKE', '%' . $request->search . '%')->with('Keys')->with('Images')->get();
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' => $data
                ], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function contactus(Request $request)
    {
        try {
            // Validate the form data
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'description' => 'required',
            ]);
            $data = $request->all();
            Mail::to('admin@example.com')->send(new ReachusNotification($request->name, $request->email, $request->description, $request->phone));
            return response()->json([
                'code' => 200,
                'message' => 'Mail has been sent Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'data' => null], 500);
        }
    }
}
