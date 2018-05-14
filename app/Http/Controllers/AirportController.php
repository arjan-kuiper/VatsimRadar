<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function getAirport($airport){
        $handle = fopen("airports.dat", "r") or die("Couldn't get handle");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $buffer = str_replace('"', '', $buffer);

                $parsedAirport = explode(",", $buffer);
                if($parsedAirport[5] == $airport){
                    return json_encode([$parsedAirport[6], $parsedAirport[7]]);
                }
            }
            fclose($handle);
            return json_encode(404);
        }
    }
}
