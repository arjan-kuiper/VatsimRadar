<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AircraftImageController extends Controller
{
    public function getAircraftImage($aircraftType, $airline){
        $image = glob("img/planes/" . $aircraftType . "/" . $airline . "/img.jpg");
        if(count($image) > 0){
            return json_encode("/" . $image[0]);
        }
        return json_encode(404);
    }
}
