<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\PasswordRule;
use Illuminate\Support\Facades\Validator;
use App\Services\ResponseService;
use App\Services\VerificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Throwable;

class ResetPasswordController extends Controller
{
    protected function validateRequest($data)
    {
        $usernameField = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $rules = [
            'username' => ['required', 
                $usernameField === 'email' && config('app.env') === 'production' ? new EmailRule : null
            ],
            'code' => 'required|min:6|max:6',
            'password' => ['required', 'confirmed',
                config('app.env') === 'production' ? new PasswordRule : null,
            ]
        ];

        return Validator::make($data, $rules);
    }

    protected function updatePassword($user, $password)
    {
        $user->update([
            'password' => Hash::make($password)
        ]);
    }

    public function resetPassword(Request $request)
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

                $isCodeValid = VerificationService::isEmailVerificationCodeValid($user, $data['code']);

                if ($isCodeValid) {
                    $this->updatePassword($user, $data['password']);
                } else {
                    return ResponseService::errorResponse('Verification code is either incorrect or expired.');
                }

            } else {
                $user = User::findByPhone($data['username']);
                if (! $user) {
                    return ResponseService::notFoundErrorResponse('No record found.');
                }

                $isCodeValid = VerificationService::isPhoneVerificationCodeValid($user, $data['code']);

                if ($isCodeValid) {
                    $this->updatePassword($user, $data['password']);
                } else {
                    return ResponseService::errorResponse('Verification code is either incorrect or expired.');
                }
            }

            return ResponseService::successResponse('Password updated successfully.');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}
