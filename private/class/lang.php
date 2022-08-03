<?php

namespace squareBracket;
use Arokettu\Pseudolocale\Pseudolocale;

class lang
{
    private $langData;
    private $langFile;

    function __construct($langFile = '')
    {
        if ($langFile) {
            $contents = file_get_contents($langFile);
            $this->langData = json_decode($contents, true);
        }

        $this->langFile = $langFile;
    }

    function translate($string, $placeholders = [])
    {
        if ($this->langFile) {
            if (isset($this->langData[$string]) && $this->langData[$string]) {
                $translatedString = $this->langData[$string];
            } else {
                $translatedString = $string;
            }
        } else {
            $translatedString = $string;
        }

        if ($this->langFile == 'lib/lang/qps-plocm.json') {
            return Pseudolocale::pseudolocalize(vsprintf($translatedString, $placeholders));
        }
        return vsprintf($translatedString, $placeholders);
    }
}

function __($string, $placeholders = [])
{
    global $lang;

    return $lang->translate($string, $placeholders);
}

require_once(dirname(__DIR__) . '/lang/language_names.php');