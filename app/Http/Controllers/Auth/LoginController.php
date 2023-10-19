<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\EmailRule;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LoginController extends Controller
{
    protected function validateRequest($data)
    {
        $usernameField = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $rules = [
            'username' => ['required',
                $usernameField === 'email' && config('app.env') === 'production' ? new EmailRule : null
            ],
            'password' => ['required',
                config('app.env') === 'production' ? new PasswordRule : null,
            ]
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

                if (config('app.env') == 'production') {
                    if ($usernameField == 'email') {
                        if ($user->email_verified_at == Null) {
                            Auth::logout();
                            return ResponseService::unauthorizedErrorResponse("Please verify your $usernameField first");
                        }
                    }

                    if ($usernameField == 'phone') {
                        if ($user->phone_verified_at == Null) {
                            Auth::logout();
                            return ResponseService::unauthorizedErrorResponse("Please verify your $usernameField first");
                        }
                    }
                }

                $token = $user->createToken('MyAppToken')->accessToken;

                $user['token'] = $token;

                return ResponseService::successResponse('Token created.', [
                    'user' => $user
                ]);
            }

            return ResponseService::unauthorizedErrorResponse('Credentials not matched.');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}
