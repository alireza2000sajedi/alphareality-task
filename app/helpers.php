<?php

if (!function_exists('imageSiteUrl')) {
    function imageSiteUrl($path): string
    {
        return url()->route('image', ['imagePath' => $path]);
    }
}

if (!function_exists('generateValidNationalCode')) {
    function generateValidNationalCode()
    {
        $nationalCode = '';

        for ($i = 0; $i < 9; $i++) {
            $nationalCode .= rand(0, 9);
        }

        $sum = 0;
        for ($j = 0; $j < 9; $j++) {
            $sum += (int) $nationalCode[$j] * (10 - $j);
        }

        $checkDigit = $sum % 11;

        if ($checkDigit < 2) {
            $checkDigit = 0;
        } else {
            $checkDigit = 11 - $checkDigit;
        }
        $nationalCode .= $checkDigit;

        return $nationalCode;
    }
}
