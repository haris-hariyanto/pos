<?php

namespace App\Helpers;

class Text
{
    public static function plain($str = '', $maxLength = 0, $preserveWord = false)
    {
        // Remove HTML tags
        $str = strip_tags($str);
        // Remove non-ASCII characters
        $str = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str);
        // Remove double whitespaces
        $str = preg_replace('/\s+/', ' ', $str);

        if ($maxLength > 0) {
            if ($preserveWord) {
                if (strlen($str) <= $maxLength) {
                    $spacePos = strlen($str);
                }
                else {
                    $spacePos = strpos($str, ' ', $maxLength);
                    if (!$spacePos) {
                        $spacePos = strlen($str);
                    }
                }

                $str = substr($str, 0, $spacePos);
            }
            else {
                $str = substr($str, 0, $maxLength);
            }
        }

        return $str;
    }

    public static function price($price = 0, $currency = '')
    {
        if (!empty($price) && !empty($currency)) {
            if ($currency == 'IDR') {
                return 'IDR ' . number_format($price, 0, '.', ',');
            }   
            else {
                return 'USD' . number_format($price, 0, '.', ',');
            }
        }
        else {
            return '-';
        }
    }
}