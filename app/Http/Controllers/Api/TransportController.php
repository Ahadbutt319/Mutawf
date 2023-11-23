<?php

namespace App\Http\Controllers\api;

use App\Models\Transport;
use App\Models\TransportCar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTransportRequest;
use App\Http\Requests\UpdateTransportRequest;
use App\Models\TransportBooking;
use Symfony\Component\Mailer\Transport\Transports;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Transport::with('cars')->get();
            $records = [];
            foreach ($data as $record) {
                $records[] = $record->getdata();
            }
            return response()->json([
                'code' => 200,
                'message' => 'Transport fetched successfully',
                'packages' => $records
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' =>  $exception->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {

            // Validation rules
            $rules = [
                'company_id' => 'required|exists:companies,id',
                'type' => 'required|string',
                'capacity' => 'required|integer',
                'price' => 'required|numeric',
                'details' => 'required|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'car_type' => 'required|string',
                'name' => 'required|string',
                'bags' => 'required|integer',
            ];

            // Validation messages
            $messages = [
                'company_id.exists' => 'The selected company does not exist.',
                // 'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);

            // Check for validation errors
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $data = Transport::create([
                'company_id' => $request->company_id,
                'type' => $request->type,
                'capacity' => $request->capacity,
                'price' => $request->price,
                'details' => $request->details,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'user_id' => auth()->user()->id
            ]);
            $car = TransportCar::create([
                'image' => $request->image,
                'type' => $request->car_type,
                'name' => $request->name,
                'bags' => $request->bags,
                'transport_id' => $data->id,
            ]);
            return response()->json(['data' => $data, 'messsage' => "Transportation has been added successfully "], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' =>  $exception->getMessage(), 'data' => null], 500);
        }
    }

    public function booking(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'passengers' => 'required|integer',
                'luggages' => 'required|integer',
                'details' => 'nullable|string',
                'type' => 'required|string|max:255',
                'pickup' => 'required|string|max:255',
                'drop_off' => 'nullable|string|max:255',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'return_date' => 'nullable|date',
                'return_time' => 'nullable|date_format:H:i',
                'duration' => 'nullable|string',
                'transport_id' => 'required|exists:transports,id',
            ];

            // Validation messages
            $messages = [
                'transport_id.exists' => 'The selected transport_id does not exist.',

            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules, $messages);

            // Check for validation errors
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $luggages = Transport::where('id', $request->transport_id)->with('cars')->first();
            $available = $luggages->is_active;
            //  First check transport is available
            if ($available  ==  1) {
                //  second check passanger can sit in car not 
                if ($request->passengers  ==  $luggages->capacity) {
                    //  third bags are allowed or not 
                    if ($request->luggages ==  $luggages->cars[0]['bags']) {
                        $data =    TransportBooking::create([
                            'name' => $request->name,
                            'email' =>  $request->email,
                            'phone' => $request->phone,
                            'passengers' =>  $request->passengers,
                            'luggages' =>  $request->luggages,
                            'details' =>  $request->details,
                            'type' =>  $request->type,
                            'pickup' =>  $request->pickup,
                            'drop_off' =>  $request->drop_off,
                            'date' =>  $request->date,
                            'time' =>  $request->time,
                            'return_date' =>  $request->return_date,
                            'return_time' =>  $request->return_time,
                            'duration' =>  $request->duration,
                            'transport_id' => $request->transport_id,
                            'user_id' => auth()->user()->id,
                        ]);
                        Transport::where('id', $request->transport_id)->update([
                            'is_active' => false
                        ]);
                        return response()->json(['message' => 'Data has been successfully stored', 'data' => $data], 201);
                    } else {
                        return response()->json(['message' => 'luggages limit is  exceded '], 422);
                    }
                } else {
                    return response()->json(['message' => 'passengers limit exceded '], 422);
                }
            } else {
                return response()->json(['message' => 'transport  is not available '], 401);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getAllBooking()
    {
        try {
            $data =  TransportBooking::where('user_id', auth()->user()->id)->get();
            return response()->json(['message' => 'Data has been Fetched succcessfully', 'data' => $data], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transport $transport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransportRequest $request, Transport $transport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transport $transport)
    {
        //
    }
}