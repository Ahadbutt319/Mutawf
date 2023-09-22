<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Validator;
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
      $user = USER::find($id);
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

            return ResponseService::successResponse('Profile updated successfully');
        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }

    }
}