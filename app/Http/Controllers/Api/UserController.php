<?php

namespace App\Http\Controllers\Api;

use App\Services\VerificationService;
use Illuminate\Support\Facades\Hash;
use App\Rules\PasswordRule;
use App\Http\Resources\UserResource;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Throwable;

class UserController extends Controller
{
    public function authData ()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        return ResponseService::successResponse('Here are all the user details',
            new UserResource($user)
        );

    }

    public function updateUser (Request $request)
    {
        try {
            $authId = auth()->user()->id;
            $rules = [
                'name' => 'required|string',
                'email' => 'required_without:phone|nullable|email|unique:users,email,NULL,' . $authId,
                'phone' => 'required_without:email|nullable|unique:users,phone,NULL,' . $authId,
            ];

            $data = $request->all();

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $user = User::find($authId);
            if (! $user) {
                return ResponseService::notFoundErrorResponse('User not found');
            }

            $user->update([
                'name' => $data['name'],
                'email' => $data['email'] ?? Null,
                'phone' => $data['phone'] ?? Null,
            ]);

            if ( isset($data['email'])) {
                VerificationService::sendEmailVerificationCode($user);
            } elseif ( isset($data['phone'])) {
                VerificationService::sendPhoneVerificationCode($user);
            }

            return ResponseService::successResponse('Profile updated successfully');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }

    }

    public function updatePassword(Request $request){
     $rules=[
        'oldPassword' =>'required',
        'password' => ['required', 'confirmed', new PasswordRule]
     ];


     $data = $request->all();
     $validator = Validator::make($data, $rules);

     if ($validator->fails()) {
        return ResponseService::validationErrorResponse($validator->errors()->first());
    }
    $id=auth()->user()->id;
    $user = User::find($id);
    if (! $user) {
        return ResponseService::notFoundErrorResponse('User not found');
    }

    if(Hash::check($data['oldPassword'], $user->password)) {
         $newPass = Hash::make($data['password']);
         $user->password=$newPass;
         $user->save();
         return ResponseService::successResponse('Password updated successfully');
    }
    else{
        return ResponseService::notFoundErrorResponse('Old password mismatch');
    }
    }
     public function getVerificationStatus(){
      $userStatus=auth()->user()->email_verified_at;
      try{
      if($userStatus)
      return ResponseService::successResponse('You are Verified!');
    else
    return ResponseService::notFoundErrorResponse('Please verify your email and phone number');
    }
      catch (Throwable $th) {
        return ResponseService::errorResponse($th->getMessage());
      }
   }
}
