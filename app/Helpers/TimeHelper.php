<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function timeToHuman($date)
    {
        if (!$date) return '';
        
        $date = Carbon::parse($date);
        $now = Carbon::now();
        
        if ($date->isToday()) {
            $diff = $date->diffForHumans(['parts' => 1]);
            return str_replace(['from now', 'ago'], ['en', 'atrás'], $diff);
        }
        
        if ($date->isYesterday()) {
            return 'ayer a las ' . $date->format('H:i');
        }
        
        if ($date->isTomorrow()) {
            return 'mañana a las ' . $date->format('H:i');
        }
        
        if ($date->year === $now->year) {
            return $date->isoFormat('D [de] MMMM [a las] H:mm');
        }
        
        return $date->isoFormat('D [de] MMMM [de] YYYY [a las] H:mm');
    }
}
