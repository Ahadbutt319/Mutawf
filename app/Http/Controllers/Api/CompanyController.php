<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use GoogleTranslate;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'type' => 'nullable|max:255',
            'founded'
        ];

        //  Translation Example
        // $translatedText = GoogleTranslate::translate('Hello, world!', 'en', 'es');
        // $translatedText = GoogleTranslate::translate(['Hello, world!', 'Goodbye, world!'], 'en', 'es');
    }
}
