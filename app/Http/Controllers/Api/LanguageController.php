<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();

        return ResponseService::successResponse('Here are all the languages.',
            new LanguageResource($languages)
        );
    }

    public function changeLocale(Request $request)
    {
        
        try {
            $rules = [
                'local' => 'required|exists:languages,id'
            ];
    
            $data = $request->all();
    
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            session(['local' => $data['local']]);
            App::setlocale(session('local'));

        } catch (Throwable $th) {
            return ResponseService::errorResponse($th->getMessage());
        }
    }
}
