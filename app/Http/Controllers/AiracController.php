<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiracController extends Controller
{
    public function getFixes($fixcollection){
        $fixes = explode(",", $fixcollection);
        $returnMe = [];

        $handle = fopen("airac_fixes.dat", "r") or die("Couldn't get handle");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $buffer = preg_replace("/^\S*/", "", $buffer);
                preg_match("/(\w{5})([-\s]\d{1,3}.\d{6})([-\s]+\d{1,3}.\d{6})/", $buffer, $matches, PREG_OFFSET_CAPTURE);

                $result = Array();
                for($i = 1; $i < count($matches); $i++){
                    $result[] = $matches[$i][0];
                }

                if(isset($result[0])) {
                    for($i = 0; $i < count($fixes); $i++){
                        if($fixes[$i] == $result[0]){
                            $returnMe[] = $result;
                        }
                    }
                }
            }
            fclose($handle);

            if(!empty($returnMe))
                return json_encode($returnMe);
            return json_encode(404);
        }
    }
}
