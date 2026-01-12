<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Tạo placeholder image SVG inline
     */
    public static function placeholder($width = 200, $height = 200, $text = 'No Image', $bgColor = '#eee', $textColor = '#999')
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="' . $bgColor . '"/>
            <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="' . $textColor . '" text-anchor="middle" dominant-baseline="middle">' . $text . '</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

