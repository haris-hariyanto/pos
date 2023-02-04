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
                return 'USD ' . number_format($price, 0, '.', ',');
            }
        }
        else {
            return '-';
        }
    }

    public static function placeholder($str, $data = [])
    {
        foreach ($data as $search => $replace) {
            $replace = self::plain($replace);
            $str = str_replace($search, $replace, $str);
        }
        return $str;
    }

    public static function readableTime($time)
    {
        $date = date('d', $time);
        $year = date('Y', $time);
        $month = date('m', $time);

        $monthText = __('January');
        switch ($month) {
            case '01':
                $monthText = __('January');
                break;
            case '02':
                $monthText = __('February');
                break;
            case '03':
                $monthText = __('March');
                break;
            case '04':
                $monthText = __('April');
                break;
            case '05':
                $monthText = __('May');
                break;
            case '06':
                $monthText = __('June');
                break;
            case '07':
                $monthText = __('July');
                break;
            case '08':
                $monthText = __('August');
                break;
            case '09':
                $monthText = __('September');
                break;
            case '10':
                $monthText = __('October');
                break;
            case '11':
                $monthText = __('November');
                break;
            case '12':
                $monthText = __('December');
                break;
            default:
                $monthText = __('January');
                break;
        }

        return $date . ' ' . $monthText . ' ' . $year . ', ' . date('H:i', $time);
    }
}