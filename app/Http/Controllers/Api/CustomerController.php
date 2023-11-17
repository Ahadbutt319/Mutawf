<?php

namespace App\Http\Controllers\api;

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
use Illuminate\Support\Facades\Mail;
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
               
                $data  = $request->data;
                $get_price =  UmrahPackage::where('id', $data['package_id'])->pluck('price')->first();
                $total_amount = $data['quantity'] * $get_price;
                //if customer have visa already 
                if ($data['visa_status'] == 1) {
                    $booking =  PackageBooking::create([
                        'user_id' => $id,
                        "package_id" => $data['package_id'],
                        "from" => $data['from'],
                        "date" => $data['date'],
                        "quantity" =>$data['quantity'],
                        "to" =>$data['to'],
                        'payment_status' => $data['payment_status'],
                        "total_amount" => $total_amount,
                        "payment_id" => $data['payment_id'],
                    ]);
                    foreach ($data['persons'] as $person) {
                        $persons = PackageBookedPerson::create([
                            "email" => $person['email'],
                            "phone" => $person['phone'],
                            "name" => $person['name'],
                            "booking_id" => $booking->id
                        ]);
                    }
                    foreach ($data['visas'] as $visa) {
                        $visas = Visa::create([
                            "passport_number" => $visa['passport_number'],
                            "id_number" => $visa['id_number'],
                            "visa_number" => $visa['visa_number'],
                            "booking_id" => $booking->id
                        ]);
                    }
                }
                // if they dont have visa and aplly for visa at same time
                else {
                    $booking =  PackageBooking::create([
                        'user_id' => $id,
                        "package_id" => $data['package_id'],
                        "from" => $data['from'],
                        "date" => $data['date'],
                        "quantity" =>$data['quantity'],
                        "to" =>$data['to'],
                        'payment_status' => $data['payment_status'],
                        "total_amount" => $total_amount,
                        "payment_id" => $data['payment_id'],
                    ]);
                    foreach ($data['persons'] as $person) {
                       
                        $persons = PackageBookedPerson::create([
                            "email" => $person['email'],
                            "phone" => $person['phone'],
                            "name" => $person['name'],
                            "booking_id" => $booking->id
                        ]);
                    }
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
