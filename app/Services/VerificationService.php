<?php

namespace App\Services;

use App\Events\VerifyUserEmail;
use App\Models\User;

class VerificationService
{
    public static function sendEmailVerificationCode($user)
    {
        $emailVerificationCode = User::generateRandomCode();

        $user->update([
            'email_verification_code' => $emailVerificationCode,
            'email_verification_code_expires' => now()->addMinutes(config('auth.verification.expire'))
        ]);

        event(new VerifyUserEmail($user, $emailVerificationCode));
    }

    public static function sendPhoneVerificationCode($user)
    {
        $phoneVerificationCode = User::generateRandomCode();

        $user->update([
            'phone_verification_code' => $phoneVerificationCode,
            'phone_verification_code_expires' => now()->addMinutes(config('auth.verification.expire'))
        ]);

        // event(new VerifyUserPhone($user, $phoneVerificationCode));
    }

    public static function verifyEmail($email, $code)
    {
        $user = User::findByEmail($email);

        if (! $user) {
            return ResponseService::notFoundErrorResponse('Email address not found.');
        }

        if (! self::isEmailVerificationCodeValid($user, $code)) {
            return ResponseService::errorResponse('Verification code is either incorrect or expired.');
        }

        $user->update([
            'email_verification_code' => Null,
            'email_verification_code_expires' => Null,
            'email_verified_at' => now()
        ]);

        return ResponseService::successResponse('Email verified successfully.');
    }

    public static function isEmailVerificationCodeValid($user, $code)
    {
        if ($user->email_verification_code === $code) {

            $currentTime = now();
            if ($user->email_verification_code_expires && $currentTime <= $user->email_verification_code_expires) {
                return true;
            }

            return false;
        }
        return false;        
    }

    public static function isPhoneVerificationCodeValid($user, $code)
    {
        if ($user->phone_verification_code === $code) {

            $currentTime = now();
            if ($user->phone_verification_code_expires && $currentTime <= $user->phone_verification_code_expires) {
                return true;
            }

            return false;
        }
        return false;        
    }
}