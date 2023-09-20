<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ResponseService;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class VerificationController extends Controller
{
    public function resendEmailVerification(Request $request)
    {
        try {
            $data = $request->all();

            $rules = [
                'email' => 'required|email|exists:users,email'
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $user = User::findByEmail($data['email']);
            VerificationService::sendEmailVerificationCode($user);

            return ResponseService::successResponse('Verifiation code sent successfully.');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }

    public function resendPhoneVerification(Request $request)
    {
        try {
            $data = $request->all();

            $rules = [
                'phone' => 'required|exists:users,phone'
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $user = User::findByPhone($data['phone']);
            VerificationService::sendPhoneVerificationCode($user);

            return ResponseService::successResponse('Verifiation code sent successfully.');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }


    public function verifyPhone(Request $request){
        {
            try {
                $data = $request->all();

                $rules = [
                    'phone' => 'required|exists:users,phone',
                    'code' => 'required|min:6|max:6'
                ];

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return ResponseService::validationErrorResponse($validator->errors()->first());
                }

                return VerificationService::verifyPhone($data['phone'], $data['code']);
            } catch (Throwable $th) {
                return ResponseService::errorResponse($th->getMessage());
            }
        }
    }
    public function verifyEmail(Request $request)
    {
        try {
            $data = $request->all();

            $rules = [
                'email' => 'required|email|exists:users,email',
                'code' => 'required|min:6|max:6'
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            return VerificationService::verifyEmail($data['email'], $data['code']);
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}
