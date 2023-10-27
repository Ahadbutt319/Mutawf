<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        try{
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required|nullable|max:255',
            'founded_at' => 'required|date_format:Y-m-d',
            'is_active' =>'required',
        ];

        $data=$request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ResponseService::validationErrorResponse($validator->errors()->first());
        }
        $company=Company::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'type' => $data['type'] ?? Null,
            'founded_at' => $data['founded_at'],
            'is_active' => $data['is_active'],
            'owner_id' => auth()->user()->id,
        ]);
        return ResponseService::successResponse('Your Company has been registered',$company);
    } catch (Throwable $th) {
        return ResponseService::errorResponse($th->getMessage());
    }
    }
}
