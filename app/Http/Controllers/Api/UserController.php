<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function authData () {
      $id = auth()->user()->id;
      $user = USER::find($id);
        return ResponseService::successResponse('Here are all the user details',
            new UserResource($user)
        );

    }
}
