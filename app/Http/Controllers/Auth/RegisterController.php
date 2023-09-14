<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Services\ResponseService;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RegisterController extends Controller
{

    protected function validateRequest($data)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required_without:phone|email|unique:users,email',
            'phone' => 'required_without:email|unique:users,phone',
            'lat' => 'required',
            'lng' => 'required',
            'country' => 'required|exists:countries,name',
            'password' => 'required|confirmed'
        ];

        return Validator::make($data, $rules);
    }

    public function register(Request $request)
    {
        try {
            $data = $request->all();
    
            $validator = $this->validateRequest($data);
    
            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }
    
            $country = Country::where('name', $data['country'])->first();

            
    
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? Null,
                'phone' => $data['phone'] ?? Null,
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'country_id' => $country->id,
                'nationality_id' => $country->id,
                'role_id' => User::CUSTOMER_ROLE_ID,
                'password' => Hash::make($data['password'])
            ]);
    
            VerificationService::sendEmailVerificationCode($user);

            return ResponseService::successResponse('You are registered successfully. Please verify your email.');

        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}