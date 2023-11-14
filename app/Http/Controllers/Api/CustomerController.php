<?php

namespace App\Http\Controllers\api;

use App\Mail\ReachusNotification;
use App\Models\User;
use App\Models\AgentPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\bookpackage;
use App\Models\PackageBooking;
use Illuminate\Support\Facades\Mail;

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
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function packagebooking( Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                $data =  PackageBooking::create([
                    'user_id' => $id ,
                    "package_id" => $request->package_id,
                    'payment_status' => false,
                ]);
                $pivot =  bookpackage::create([
                    "package_id" => $request->package_id,
                    "booking_id" =>$data->id,
                ]);
                return response()->json(['data'=> $data, 'message'=> 'package has been booked successfully'],200);
            }
            else{
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
                $data = PackageBooking::where('user_id', $id)->get();
                $records = [];
                foreach ($data as $d) {
                    $records[] = $d->getdata();
                }
                return response()->json(['data'=> $records , 'message'=> 'package has been booked successfully'],200);
            }
            else{
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
            }
            else{
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
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }

    public function contactus(Request $request)  {
        try {
             // Validate the form data
                $this->validate($request, [
                    'name' => 'required',
                    'email' => 'required|email',
                    'description' => 'required',
                ]);
                $data = $request->all();
                Mail::to('admin@example.com')->send(new ReachusNotification( $request->name,$request->email,$request->description,$request->phone));
                return response()->json([
                    'code' => 200,
                    'message' => 'Mail has been sent Successfully',
                ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'data' => null], 500);
        }
    }
}
