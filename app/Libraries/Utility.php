<?php

namespace App\Libraries;

class Utility
{
    public static function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $unit = 'meter')
    {
        if($unit == 'mile')
            $earthRadius = 3959 ;
        else
            $earthRadius = 6371000;

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    public static function getSquareMaxMinLatLong($latitude, $longitude, $distance, $unit = 'meter')
    {
        if($unit == 'mile')
            $earthRadius = 3959 ;
        else
            $earthRadius = 6371000;

        $maxLat = $latitude + rad2deg($distance / $earthRadius);
        $minLat = $latitude - rad2deg($distance / $earthRadius);

        $maxLon = $longitude + rad2deg($distance / $earthRadius / cos(deg2rad($latitude)));
        $minLon = $longitude - rad2deg($distance / $earthRadius / cos(deg2rad($latitude)));

        return [$maxLat, $minLat, $maxLon, $minLon];
    }
}
