<?php

namespace App\Traits;

trait MPDFTrait
{
    protected function mpdf($configs = [])
    {
        ini_set("pcre.backtrack_limit", "5000000");

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('assets/fonts'),
            ]),
            'fontdata' => $fontData + [
                'pyidaungsu' => [
                    'R' => 'Pyidaungsu-2.5.3_Regular.ttf',
                    // 'B' => 'pyidaungsu_regular.ttf',
                    'useKashida' => 75,
                    'useOTL' => 0xFF
                ]
            ],
            'default_font' => 'pyidaungsu',
        ]);
        $mpdf->showImageErrors = true;
        $mpdf->useSubstitutions = false;
        $mpdf->autoScriptToLang = false;
        $mpdf->autoLangToFont = false;

        return $mpdf;
    }
}
