<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ResponseService;
use App\Services\VerificationService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    protected function validateRequest($data)
    {
        $rules = [
            'username' => 'required'
        ];

        return Validator::make($data, $rules);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $data = $request->all();
    
            $validator = $this->validateRequest($data);
    
            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            // Determine the username field (email or phone)
            $usernameField = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            if ($usernameField === 'email') {
                $user = User::findByEmail($data['username']);
                if (! $user) {
                    return ResponseService::notFoundErrorResponse('No record found.');
                }
                
                VerificationService::sendEmailVerificationCode($user);
            } else {
                $user = User::findByPhone($data['username']);
                if (! $user) {
                    return ResponseService::notFoundErrorResponse('No record found.');
                }

                VerificationService::sendPhoneVerificationCode($user);
            }

            return ResponseService::successResponse('Verification code sent.');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}
