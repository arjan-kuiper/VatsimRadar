<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MapRenderController extends Controller
{
    private $statusURL = "http://status.vatsim.net/";
    private $content;
    private $servers = false;
    private $allData;

    public function __construct(){

    }

    public function getServers($forDB = false){
        Cache::remember('vatsimServers', 1, function(){
            $this->content = str_replace("\r\n", "\n", file_get_contents($this->statusURL));
            preg_match_all("/url0=(.*)/", $this->content, $this->servers);
            return json_encode($this->servers[1]) . PHP_EOL;
        });

        if(!Cache::has('vatsimData')){
            $this->parseAll();
            app('App\Http\Controllers\PositionController')->store(Cache::get('vatsimData'));
            app('App\Http\Controllers\PositionStatsController')->incrementCycles();
        }

        $this->allData = Cache::get('vatsimData');

        return response()->json($this->allData);
    }

    private function parseAll(){
        $servers = json_decode(Cache::get('vatsimServers'), true);
        shuffle($servers); // We need randomness when retrieving info from servers
        foreach($servers as $server){
            if($this->parse($server)){
                break;
            }
        }
    }

    private function parse($url){
        $clients_container = Array();
        $data = file_get_contents($url);
        $data = str_replace("\r\n", "\n", $data);
        if(!$data){
            return false;
        }
        if(!strpos($data, ";   END")){
            return false;
        }

        preg_match("/!CLIENTS:(.*?)" . "\n" . ";" . "\n" . ";" . "\n/s", $data, $clients_container);
        if(!isset($clients_container[1])){
            return false;
        }
        $clients = "";
        preg_match_all("/(.*?):" . "\n" . "/", $clients_container[1], $clients);
        if(!isset($clients[1])){
            return false;
        }
        $clients = $clients[1];
        preg_match("/; !CLIENTS section -(.*?):" . "\n" . ";/", $data, $clients_tpl);
        if(!isset($clients_tpl[1])){
            return false;
        }
        $clients_final = array();
        $tpl_array = explode(":", trim($clients_tpl[1]));
        foreach($clients as $item){
            $cl_array = explode(":", trim($item));
            $this->fixArrayEncoding($cl_array);
            $combined = @array_combine($tpl_array, $cl_array);
            if(!$combined){
                continue;
            }
            if ($combined && is_array($combined)) {
                $combined["planned_remarks"] = wordwrap($combined["planned_remarks"], 40);
                $combined["planned_route"]   = wordwrap($combined["planned_route"], 40);
                $combined["atis_message"]    = wordwrap($combined["atis_message"], 40);
                $clients_final[]             = $combined;
            }
        }

        Cache::put('vatsimData', $clients_final, 1);
        return response()->json($clients_final);
    }

    private function fixArrayEncoding(&$arr)
    {
        foreach ($arr as $key => $val) {
            $arr[$key] = $this->toUTF8($arr[$key]);
        }
    }

    private function toUTF8($str)
    {
        if (!((bool) preg_match('//u', $str))) {
            $resultUTF8 = utf8_encode($str);
        } else {
            $resultUTF8 = $str;
        }
        return str_replace(utf8_encode(chr(0x5E) . chr(0xA7)), "\n", $resultUTF8);
    }
}
