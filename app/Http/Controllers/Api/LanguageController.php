<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();

        return ResponseService::successResponse('Here are all the languages.',
            new LanguageResource($languages)
        );
    }
}
