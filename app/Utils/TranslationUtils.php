<?php

namespace App\Utils;

class TranslationUtils
{
    public static function translateWords(string $input, array $translations): string
    {
        $words = explode(' ', $input);

        $translatedWords = array_map(function ($word) use ($translations) {
            return $translations[$word] ?? $word;
        }, $words);

        $translatedString = implode(' ', $translatedWords);

        return $translatedString;
    }
}