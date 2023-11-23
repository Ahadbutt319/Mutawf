<?php
namespace App\Http\Controllers\api;
use App\Models\User;

use App\Models\Booking;
use App\Models\AgentHotel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class BookingController extends Controller
{
   public function booking(Request $request)
   {
    try {
          $hotelBooking = AgentHotel::find($request->hotel_id)->bookings()->create([
            'checkin_time' => $request->checkin_time,
            'checkout_time' => $request->checkout_time,
            "user_id"=> auth()->user()->id,
            'payment_id'=> $request->payment_id,
            'name'=> $request->name,
            'email'=>$request->email,
            'staus'=> 'pending'
        ]);
        return response()->json(['message' => 'Hotel booking created successfully', 'booking' => $hotelBooking]);   

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
