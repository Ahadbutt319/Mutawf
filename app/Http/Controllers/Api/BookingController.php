<?php
namespace App\Http\Controllers\api;
use App\Models\User;

use App\Models\Booking;
use App\Models\AgentHotel;

use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
   public function booking(Request $request)
   {

    try {
        $data = $request->All();
        $validation = validator::make($data, [
            'hotel_id' => 'required|exists:agent_hotels,id',
            'room_id' => 'required|exists:room_bookings,id',
            'name' => 'required|string',
            'email' => 'required|string',
            'checkin_time' => [
                'required',
                'date',
                'after_or_equal:today', // Ensures checkin_time is today or a future date
            ],
            'checkout_time' => [
                'required',
                'date',
                Rule::notIn([$request->checkin_time]), // Ensures checkout_time is not the same as checkin_time
                'after:' . $request->checkin_time, // Ensures checkout_time is after checkin_time
            ],
        ]);
        if ($validation->fails()) {
            return ResponseService::validationErrorResponse($validation->errors()->first());
        }
        else{
            $rooms =  RoomBooking::where('id',$request->room_id)->first();
            // In this module we are checking room quantity is available or not 
            if ( $rooms->quantity > 0  ) {
                $hotelBooking = AgentHotel::find($request->hotel_id)->bookings()->create([
                    'checkin_time' => $request->checkin_time,
                    'checkout_time' => $request->checkout_time,
                    "user_id"=> auth()->user()->id,
                    'payment_id'=> $request->payment_id,
                    'name'=> $request->name,
                    'email'=>$request->email,
                    'room_id'=> $request->room_id,
                    'staus'=> 'pending'
                ]);
                $rooms->quantity -= 1;
                $rooms->save();
                return response()->json(['message' => 'Hotel booking created successfully', 'booking' => $hotelBooking]);   
            }
            else{
                return response()->json(['message' => 'Room is not available']);   
            }
        }
    } catch (\Throwable $th) {
        return response()->json([
            'error' => $th->getMessage(),
        ]);
    }
   }
   public function getBookingDetails(Request $request)
   {

    try {
         // Retrieve booking details with associated hotel details

       $booking = Booking::where('user_id',  auth()->user()->id)->with('bookable')->get();

       // Check if the booking exists
       if (!$booking) {
           return response()->json(['message' => 'Booking not found'], 404);
       }

       return response()->json(['data' => $booking], 200);
    } catch (\Throwable $th) {
        return response()->json(['error'=> $th->getMessage()]);
    }
      
   }
}
