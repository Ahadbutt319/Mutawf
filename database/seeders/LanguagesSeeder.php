<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                "name" => "Afrikans",
                "name_1" => "Afrikanns",
                "abbr" => "AF",
                "direction" => "ltr"
            ],
            [
                "name" => "Albanés",
                "name_1" => "Albanian",
                "abbr" => "SQ",
                "direction" => "ltr"
            ],
            [
                "name" => "Árabe",
                "name_1" => "Arabic",
                "abbr" => "AR",
                "direction" => "rtl"
            ],
            [
                "name" => "Armenio",
                "name_1" => "Armenian",
                "abbr" => "HY",
                "direction" => "ltr"
            ],
            [
                "name" => "Vasco",
                "name_1" => "Basque",
                "abbr" => "EU",
                "direction" => "ltr"
            ],
            [
                "name" => "Bengalí",
                "name_1" => "Bengali",
                "abbr" => "BN",
                "direction" => "ltr"
            ],
            [
                "name" => "Búlgaro",
                "name_1" => "Bulgarian",
                "abbr" => "BG",
                "direction" => "ltr"
            ],
            [
                "name" => "Catalán",
                "name_1" => "Catalan",
                "abbr" => "CA",
                "direction" => "ltr"
            ],
            [
                "name" => "Camboyano",
                "name_1" => "Cambodian",
                "abbr" => "KM",
                "direction" => "ltr"
            ],
            [
                "name" => "Chino (Mandarín)",
                "name_1" => "Chinese (Mandarin)",
                "abbr" => "ZH",
                "direction" => "ltr"
            ],
            [
                "name" => "Croata",
                "name_1" => "Croation",
                "abbr" => "HR",
                "direction" => "ltr"
            ],
            [
                "name" => "Checo",
                "name_1" => "Czech",
                "abbr" => "CS",
                "direction" => "ltr"
            ],
            [
                "name" => "Danés",
                "name_1" => "Danish",
                "abbr" => "DA",
                "direction" => "ltr"
            ],
            [
                "name" => "Holandés",
                "name_1" => "Dutch",
                "abbr" => "NL",
                "direction" => "ltr"
            ],
            [
                "name" => "Inglés",
                "name_1" => "English",
                "abbr" => "EN",
                "direction" => "ltr"
            ],
            [
                "name" => "Estonio",
                "name_1" => "Estonian",
                "abbr" => "ET",
                "direction" => "ltr"
            ],
            [
                "name" => "Fiji",
                "name_1" => "Fiji",
                "abbr" => "FJ",
                "direction" => "ltr"
            ],
            [
                "name" => "Finlandés",
                "name_1" => "Finnish",
                "abbr" => "FI",
                "direction" => "ltr"
            ],
            [
                "name" => "Francés",
                "name_1" => "French",
                "abbr" => "FR",
                "direction" => "ltr"
            ],
            [
                "name" => "Georgiano",
                "name_1" => "Georgian",
                "abbr" => "KA",
                "direction" => "ltr"
            ],
            [
                "name" => "Alemán",
                "name_1" => "German",
                "abbr" => "DE",
                "direction" => "ltr"
            ],
            [
                "name" => "Griego",
                "name_1" => "Greek",
                "abbr" => "EL",
                "direction" => "ltr"
            ],
            [
                "name" => "Gujarati",
                "name_1" => "Gujarati",
                "abbr" => "GU",
                "direction" => "ltr"
            ],
            [
                "name" => "Hebreo",
                "name_1" => "Hebrew",
                "abbr" => "HE",
                "direction" => "rtl"
            ],
            [
                "name" => "Hindi",
                "name_1" => "Hindi",
                "abbr" => "HI",
                "direction" => "ltr"
            ],
            [
                "name" => "Húngaro",
                "name_1" => "Hungarian",
                "abbr" => "HU",
                "direction" => "ltr"
            ],
            [
                "name" => "Islandés",
                "name_1" => "Icelandic",
                "abbr" => "IS",
                "direction" => "ltr"
            ],
            [
                "name" => "Indonesio",
                "name_1" => "Indonesian",
                "abbr" => "ID",
                "direction" => "ltr"
            ],
            [
                "name" => "Irlandés",
                "name_1" => "Irish",
                "abbr" => "GA",
                "direction" => "ltr"
            ],
            [
                "name" => "Italiano",
                "name_1" => "Italian",
                "abbr" => "IT",
                "direction" => "ltr"
            ],
            [
                "name" => "Japonés",
                "name_1" => "Japanese",
                "abbr" => "JA",
                "direction" => "ltr"
            ],
            [
                "name" => "Javanés",
                "name_1" => "Javanese",
                "abbr" => "JW",
                "direction" => "ltr"
            ],
            [
                "name" => "Coreano",
                "name_1" => "Korean",
                "abbr" => "KO",
                "direction" => "ltr"
            ],
            [
                "name" => "Latino",
                "name_1" => "Latin",
                "abbr" => "LA",
                "direction" => "ltr"
            ],
            [
                "name" => "Letón",
                "name_1" => "Latvian",
                "abbr" => "LV",
                "direction" => "ltr"
            ],
            [
                "name" => "Lituano",
                "name_1" => "Lithuanian",
                "abbr" => "LT",
                "direction" => "ltr"
            ],
            [
                "name" => "Macedonio",
                "name_1" => "Macedonian",
                "abbr" => "MK",
                "direction" => "ltr"
            ],
            [
                "name" => "Malayo",
                "name_1" => "Malay",
                "abbr" => "MS",
                "direction" => "ltr"
            ],
            [
                "name" => "Malayalam",
                "name_1" => "Malayalam",
                "abbr" => "ML",
                "direction" => "ltr"
            ],
            [
                "name" => "Maltés",
                "name_1" => "Maltese",
                "abbr" => "MT",
                "direction" => "ltr"
            ],
            [
                "name" => "Maorí",
                "name_1" => "Maori",
                "abbr" => "MI",
                "direction" => "ltr"
            ],
            [
                "name" => "Marathi",
                "name_1" => "Marathi",
                "abbr" => "MR",
                "direction" => "ltr"
            ],
            [
                "name" => "Mongol",
                "name_1" => "Mongolian",
                "abbr" => "MN",
                "direction" => "ltr"
            ],
            [
                "name" => "Nepalí",
                "name_1" => "Nepali",
                "abbr" => "NE",
                "direction" => "ltr"
            ],
            [
                "name" => "Noruego",
                "name_1" => "Norwegian",
                "abbr" => "NO",
                "direction" => "ltr"
            ],
            [
                "name" => "Persa",
                "name_1" => "Persian",
                "abbr" => "FA",
                "direction" => "rtl"
            ],
            [
                "name" => "Polaco",
                "name_1" => "Polish",
                "abbr" => "PL",
                "direction" => "ltr"
            ],
            [
                "name" => "Portugués",
                "name_1" => "Portuguese",
                "abbr" => "PT",
                "direction" => "ltr"
            ],
            [
                "name" => "Punjabi",
                "name_1" => "Punjabi",
                "abbr" => "PA",
                "direction" => "ltr"
            ],
            [
                "name" => "Quechua",
                "name_1" => "Quechua",
                "abbr" => "QU",
                "direction" => "ltr"
            ],
            [
                "name" => "Romanian",
                "name_1" => "Rumano",
                "abbr" => "RO",
                "direction" => "ltr"
            ],
            [
                "name" => "Ruso",
                "name_1" => "Russian",
                "abbr" => "RU",
                "direction" => "ltr"
            ],
            [
                "name" => "Samoano",
                "name_1" => "Samoan",
                "abbr" => "SM",
                "direction" => "ltr"
            ],
            [
                "name" => "Serbio",
                "name_1" => "Serbian",
                "abbr" => "SR",
                "direction" => "ltr"
            ],
            [
                "name" => "Eslovaco",
                "name_1" => "Slovak",
                "abbr" => "SK",
                "direction" => "ltr"
            ],
            [
                "name" => "Esloveno",
                "name_1" => "Slovenian",
                "abbr" => "SL",
                "direction" => "ltr"
            ],
            [
                "name" => "Español",
                "name_1" => "Spanish",
                "abbr" => "ES",
                "direction" => "ltr"
            ],
            [
                "name" => "Swahili",
                "name_1" => "Swahili",
                "abbr" => "SW",
                "direction" => "ltr"
            ],
            [
                "name" => "Sueco",
                "name_1" => "Swedish",
                "abbr" => "SV",
                "direction" => "ltr"
            ],
            [
                "name" => "Tamil",
                "name_1" => "Tamil",
                "abbr" => "TA",
                "direction" => "ltr"
            ],
            [
                "name" => "Tártaro",
                "name_1" => "Tatar",
                "abbr" => "TT",
                "direction" => "ltr"
            ],
            [
                "name" => "Telugu",
                "name_1" => "Telugu",
                "abbr" => "TE",
                "direction" => "ltr"
            ],
            [
                "name" => "Tailandés",
                "name_1" => "Thai",
                "abbr" => "TH",
                "direction" => "ltr"
            ],
            [
                "name" => "Tibetano",
                "name_1" => "Tibetan",
                "abbr" => "BO",
                "direction" => "ltr"
            ],
            [
                "name" => "Tonga",
                "name_1" => "Tonga",
                "abbr" => "TO",
                "direction" => "ltr"
            ],
            [
                "name" => "Turco",
                "name_1" => "Turkish",
                "abbr" => "TR",
                "direction" => "ltr"
            ],
            [
                "name" => "Ucraniano",
                "name_1" => "Ukranian",
                "abbr" => "UK",
                "direction" => "ltr"
            ],
            [
                "name" => "Urdu",
                "name_1" => "Urdu",
                "abbr" => "UR",
                "direction" => "rtl"
            ],
            [
                "name" => "Uzbek",
                "name_1" => "Uzbek",
                "abbr" => "UZ",
                "direction" => "ltr"
            ],
            [
                "name" => "Vietnamita",
                "name_1" => "Vietnamese",
                "abbr" => "VI",
                "direction" => "ltr"
            ],
            [
                "name" => "Galés",
                "name_1" => "Welsh",
                "abbr" => "CY",
                "direction" => "ltr"
            ],
            [
                "name" => "Xhosa",
                "name_1" => "Xhosa",
                "abbr" => "XH",
                "direction" => "ltr"
            ],
        ];

        DB::table('languages')->insert($languages);
    }
}
