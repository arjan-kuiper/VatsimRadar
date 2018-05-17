<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AircraftController extends Controller
{
    public function getAircraftImage($iata, $model){
        $baseURL = "https://www.aviationimagenetwork.com/Passenger-Airlines/Passenger-Airlines-";
        $handle = fopen("airlines.dat", "r") or die("Couldn't get handle");

        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $buffer = explode("-", $buffer);
                if(count($buffer) == 2){
                    $buffer[1] = str_replace("\r\n",'', $buffer[1]);

                    if($buffer[0] == $iata){
                        $airline = str_replace(" ", "-", $buffer[1]);
                        $baseURL = $baseURL . ucfirst(substr($airline, 0, 1)) . "/" . $airline . "/" . $model;


                        return json_encode(204);
                    }
                }
            }
            fclose($handle);
            return json_encode(404);
        }
    }
}
