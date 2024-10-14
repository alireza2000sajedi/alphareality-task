<?php

if (!function_exists('imageSiteUrl')) {
    function imageSiteUrl($path): string
    {
        return url()->route('image', ['imagePath' => $path]);
    }
}
