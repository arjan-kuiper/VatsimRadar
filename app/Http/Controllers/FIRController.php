<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FIRController extends Controller
{
    public function getFIRBoundaries($icao){
        $handle = fopen("../firboundaries.dat", "r") or die("Couldn't get handle");
        if ($handle) {
            $points = -1;
            $coords = Array();

            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $buffer = explode("|", $buffer);
                $buffer = str_replace("\r\n",'', $buffer);

                if($points == -1){
                    if($buffer[0] == $icao){
                        $points = $buffer[3];
                    }
                }else{
                    if($points != 0){
                        $coords[] = $buffer;
                        $points--;
                    }else{
                        return json_encode($coords);
                        break;
                    }
                }
            }
            fclose($handle);
            return json_encode(404);
        }
    }
}
