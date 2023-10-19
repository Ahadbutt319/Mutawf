<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\NameRule;
use App\Rules\PasswordRule;
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
            'name' => ['required',
                config('app.env') === 'production' ? new NameRule : null,
            ],
            'email' => ['required_without:phone', 'nullable', 'email', 'unique:users,email,NULL,id',
                config('app.env') === 'production' ? new EmailRule : null,
            ],
            'phone' => 'required_without:email|nullable|unique:users,phone,NULL',
            'lat' => 'required',
            'lng' => 'required',
            'country' => 'required|exists:countries,name',
            'password' => ['required', 'confirmed',
                config('app.env') === 'production' ? new PasswordRule : null,
            ]
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
                'nationality_country_id' => $country->id,
                'role_id' => User::CUSTOMER_ROLE_ID,
                'password' => Hash::make($data['password'])
            ]);

            if ( isset($data['email'])) {
                VerificationService::sendEmailVerificationCode($user);
            } elseif ( isset($data['phone'])) {
                VerificationService::sendPhoneVerificationCode($user);
            }

            return ResponseService::successResponse('You are registered successfully. Please verify your email.', [
                'type' => isset($data['email']) ? 'email' : 'phone',
            ]);

        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }



}
