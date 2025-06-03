<?php

if (!function_exists('getColorHex')) {
    function getColorHex(string $color): string
    {
        return match(strtolower($color)) {
            'coklat' => '#8B4513',
            'biru' => '#1E90FF',
            'merah' => '#FF0000',
            'hijau' => '#008000',
            'kuning' => '#FFFF00',
            'hitam' => '#000000',
            'putih' => '#FFFFFF',
            default => '#F3F4F6'
        };
    }
}

if (!function_exists('getContrastColor')) {
    function getContrastColor(string $color): string
    {
        return match(strtolower($color)) {
            'putih', 'kuning' => '#000000',
            default => '#FFFFFF'
        };
    }
}