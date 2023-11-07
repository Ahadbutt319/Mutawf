<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\AgentPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function getpackages()
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' =>  AgentPackage::with('Keys')->with('Images')->get()
                ], 200);
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function detailpackage(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer' || $user->role->role === 'admin') {
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' =>  AgentPackage::where('id', $request->id)->with('Keys')->with('Images')->get()
                ], 200);
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
    public function searchpackage(Request $request)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->role->role === 'customer') {
                $data = AgentPackage::where('package_name', 'LIKE', '%' . $request->search . '%')->with('Keys')->with('Images')->get();
                return response()->json([
                    'code' => 200,
                    'message' => 'Packages fetched successfully',
                    'packages' => $data
                ], 200);
            }
            else{
               return response()->json([
                'error' => 'you are not authorized',
               ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'data' => null], 500);
        }
    }
}
