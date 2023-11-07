<?php
namespace App\Http\Controllers\api\superadmin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class SuperadmiController extends Controller
{
    public function customerslist()
    {
        try {
            $role = auth()->user()->role->role;
            if ($role === 'admin') {
                $records = CustomerResource::collection(User::where('role_id',1)->get());
                return response()->json([
                    'code' => 200,
                    'message' => 'data fetched successfully',
                    'data' => $records,
                ], 200);
            } else {
                return response()->json([
                    'error' => 'you are not authorized',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
}
