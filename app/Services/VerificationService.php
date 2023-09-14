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

        if ($user->email_verification_code != $code) {
            return ResponseService::errorResponse('Verification code is incorrect.');
        }

        $currentTime = now();

        if (! $user->email_verification_code_expires || $currentTime > $user->email_verification_code_expires) {
            return ResponseService::errorResponse('Verification code is expired.');
        }

        $user->update([
            'email_verification_code' => Null,
            'email_verification_code_expires' => Null,
            'email_verified_at' => $currentTime
        ]);

        return ResponseService::successResponse('Email verified successfully.');
    }
}