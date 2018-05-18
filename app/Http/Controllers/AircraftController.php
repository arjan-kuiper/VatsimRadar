<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AircraftController extends Controller
{
    public function getAircraftImage($iata, $model){
        $baseURL = "/api/aircraft?url=https://www.aviationimagenetwork.com/Passenger-Airlines/Passenger-Airlines-";

        $handle = fopen("airlines.dat", "r") or die("Couldn't get handle");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $buffer = explode("-", $buffer);
                if (count($buffer) == 2) {
                    $buffer[1] = str_replace("\r\n", '', $buffer[1]);

                    if ($buffer[0] == $iata) {
                        $airline = str_replace(" ", "-", $buffer[1]);
                        if($airline == "Wizz-Air") { $airline = "Wiz-Air"; } // Fix for a typo by https://aviationimagenetwork.com
                        $baseURL = $baseURL . ucfirst(substr($airline, 0, 1)) . "/" . $airline . "/" . $model;

                        $rawHTML =
                            '<iframe id="iframe" src="' . $baseURL . '" ></iframe>' .
                            '<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>' .
                            '<script>' .
                                'document.getElementById(\'iframe\').onload= function() {' .
                                    'var frame = document.getElementById(\'iframe\').contentDocument;' .
                                    'var metas = frame.getElementsByTagName(\'meta\');' .

                                    'for (var i=0; i<metas.length; i++) {' .
                                        'if (metas[i].getAttribute("itemprop") == "image") {' .
                                            'console.log(metas[i].getAttribute("content"));' .
                                            'global = metas[i].getAttribute("content");' .
                                        '}' .
                                    '}' .
                                '};' .
                            '</script>';

                        $doc = new \DOMDocument();
                        $doc->loadHTML($rawHTML);
                        return($rawHTML);
                    }
                }
            }
            fclose($handle);
            return json_encode(404);
        }
    }

    public function processURL(Request $request){
        if($request->has('url')){
            echo file_get_contents($request->input('url'));
        }
    }

    private function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
