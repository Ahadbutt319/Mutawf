<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LoginController extends Controller
{
    protected function validateRequest($data)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        return Validator::make($data, $rules);
    }

    public function login(Request $request)
    {
        try {
            $data = $request->all();

            $validator = $this->validateRequest($data);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            // Determine the username field (email or phone)
            $usernameField = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            $credentials = [
                $usernameField => $data['username'],
                'password' => $data['password'],
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->$usernameField == 'email') {
                    if ($user->email_verified_at == Null) {
                        Auth::logout();
                        return ResponseService::unauthorizedErrorResponse("Please verify your $usernameField first");
                    }
                }

                if ($user->$usernameField == 'phone') {
                    if ($user->phone_verified_at == Null) {
                        Auth::logout();
                        return ResponseService::unauthorizedErrorResponse("Please verify your $usernameField first");
                    }
                }

                $token = $user->createToken('MyAppToken')->accessToken;

                return ResponseService::successResponse('Token created.', [
                    'token' => $token,
                    'user' => $user
                ]);
            }

            return ResponseService::unauthorizedErrorResponse('Credentials not matched.');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}
