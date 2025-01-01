<?php

class AntiNodes{

    private $map;
    private $symbols;
    private $counter;
    private $test;

    function __construct($map, $symbols, $test){   
        $this->map = $map;
        $this->symbols = $symbols;
        $this->counter = [];
        $this->test = $test;
    }

    private function getDirection($c1, $c2, $r1, $r2){
        return array('C'=>$c2-$c1, 'R'=>$r2-$r1);
    }

    private function plus($c, $r, $direction){
        return array('C'=>$c+$direction['C'], 'R'=>$r+$direction['R']);
    }
    
    private function minus($c, $r, $direction){
        return array('C'=>$c-$direction['C'], 'R'=>$r-$direction['R']);
    }

    private function isEmptySpace($c, $r){
        return $this->isOnMap($c, $r) && $this->map[$r][$c] == '.';
    }

    public function isOnMap($c, $r){
        if($this->test){
            return $c >= 0 && $c < 12 && $r >= 0 && $r < 12;
        }
        return $c >= 0 && $c < 50 && $r >= 0 && $r < 50;
    }

    private function isSymbol($c, $r, $symbol){
        return isset($this->symbols[$this->map[$r][$c]]) && $this->symbols[$this->map[$r][$c]] != $symbol;
    }
    
    private function markOnMap($symbol, $c1, $c2, $r1, $r2){
        $dir = $this->getDirection($c1, $c2, $r1, $r2);
        $minus = $this->minus($c1, $r1, $dir);
        $plus = $this->plus($c2, $r2, $dir);

        if( $plus['C'] == $c1 && $plus['R'] == $r1
            && $minus['C'] == $c2 && $minus['R'] == $r2){
            $minus = $this->plus($c1, $r1, $dir);
            $plus = $this->minus($c2, $r2, $dir);
        }

        if( $this->isOnMap($minus['C'], $minus['R']) && $this->isSymbol($minus['C'], $minus['R'], $symbol)){
            $this->counter[$minus['C'].','.$minus['R']] = 1;
        }else if( $this->isOnMap($minus['C'], $minus['R']) && $this->isEmptySpace($minus['C'], $minus['R']) ){
            $this->map[$minus['R']][$minus['C']] = '#';
            $this->counter[$minus['C'].','.$minus['R']] = 1;
        }

        if( $this->isOnMap($plus['C'], $plus['R']) && $this->isSymbol($plus['C'], $plus['R'], $symbol)){
            $this->counter[$plus['C'].','.$plus['R']] = 1;
        }else if( $this->isOnMap($plus['C'], $plus['R']) && $this->isEmptySpace($plus['C'], $plus['R']) ){
            $this->map[$plus['R']][$plus['C']] = '#';
            $this->counter[$plus['C'].','.$plus['R']] = 1;
        }
    }

    function start(){
        foreach( $this->symbols as $index=>$arr){
            for($i=0; $i<count($arr); $i++){
                for($x=$i+1; $x<count($arr); $x++){
                    $this->markOnMap($arr[$i]['S'], $arr[$i]['C'], $arr[$x]['C'], $arr[$i]['R'], $arr[$x]['R']);
                }     
            }
        }
        return count($this->counter);
    }

    function getMap(){
        return $this->map;
    }
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$map = [];
$symbols = [];
$str = '';

for( $p=0; $p<strlen($data); $p++){
    if( $data[$p] == "\n"){
        $map[] = trim($str);
        $str = '';
    }else{
        $ch = trim($data[$p]);
        if( strlen($ch) == 0){
            continue;
        }
        $str = $str.$ch;
        if( $ch != "." ){
            $symbols[$ch][] = array('S'=>$str[strlen($str)-1], 'R'=> count($map), 'C'=> strlen($str)-1);
        }

        if( $p == strlen($data)-1){
            $map[] = trim($str);
        }
    }
}

$o = new AntiNodes($map, $symbols, $test);
echo "Unique locations: {$o->start()}";
file_put_contents("log.txt", print_r($o->getMap(), true));

?>