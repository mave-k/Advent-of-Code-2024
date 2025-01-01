<?php

class TrailFinder{

    private $map;
    private $highests;

    public function __construct($map, $highests) {
        $this->map = $map;
        $this->highests = $highests;
    }

    public function startSearching(){
        $sum = 0;

        for( $i=0; $i<count($this->highests); $i++){
            $ret = $this->find($i);
            if( $ret == false){
                continue;
            }
            $sum+=count($ret);
        }

        return $sum;
    }

    private function find($index, $maxHeight = 9){
        $stack = [];
        $stack[] = [$this->highests[$index]];
        $stackIndex = 0;

        while( $maxHeight > -1){
            $maxHeight--;
            $rowStack = [];
            $checkDuplicity = [];

            for( $i=0; $i<count($stack[$stackIndex]); $i++){
                $ret = $this->getNeighbordsByHeight(  $stack[$stackIndex][$i]['r']
                                                    , $stack[$stackIndex][$i]['c']
                                                    , $maxHeight );
                
                if( count($ret)>0){
                    for( $x=0; $x<count($ret); $x++){
                        if( isset($checkDuplicity[ $ret[$x]['r'].$ret[$x]['c'] ])){
                            continue;
                        }
                        $rowStack[] = $ret[$x];
                        $checkDuplicity[ $ret[$x]['r'].$ret[$x]['c'] ] = $ret[$x];
                    }
                }                
            }
            
            if( count( $rowStack) == 0 || $maxHeight<0){
                return count($stack) == 10 ? $stack[9] : false;
            }   

            $stack[] = $rowStack;
            $stackIndex++;
        }
    }

    private function getNeighbordsByHeight($posR, $posC, $height){
        if( $posR == -1 || $posC == -1){
            return [];
        }
        $arrNeighbords = $this->getNeighbords($posR, $posC);
        $ret = [];

        for( $i=0; $i<count($arrNeighbords); $i++){
            if(     $arrNeighbords[$i]['r']  == -1 
                ||  $arrNeighbords[$i]['c']  == -1 ){
                continue;
            }

            if( $arrNeighbords[$i]['value'] == $height ){
                $ret[] = $arrNeighbords[$i];
            }
        }
        
        return $ret;
    }

    private function getNeighbords($posR, $posC){
        $ret = [];
        
        $up = isset($this->map[$posR-1][$posC]) ? $this->map[$posR-1][$posC] : -1;
        if( $up>-1){
            $oArr['dir'] = "UP";
            $oArr['value'] = $up;
            $oArr['r'] = $posR-1;
            $oArr['c'] = $posC;  
            $ret[] = $oArr;
        }

        $down = isset($this->map[$posR+1][$posC]) ? $this->map[$posR+1][$posC] : -1;
        if( $down>-1){
            $oArr['dir'] = "DOWN";
            $oArr['value'] = $down;
            $oArr['r'] = $posR+1;
            $oArr['c'] = $posC;
            $ret[] = $oArr;
        }
        
        $right = isset($this->map[$posR][$posC+1]) ? $this->map[$posR][$posC+1] : -1;
        if( $right>-1){
            $oArr['dir'] = "RIGHT";
            $oArr['value'] = $right;
            $oArr['r'] = $posR;
            $oArr['c'] = $posC+1;
            $ret[] = $oArr;
        }
        
        $left = isset($this->map[$posR][$posC-1]) ? $this->map[$posR][$posC-1] : -1; 
        if( $left>-1){
            $oArr['dir'] = "LEFT";
            $oArr['value'] = $left;
            $oArr['r'] = $posR;
            $oArr['c'] = $posC-1;
            $ret[] = $oArr;
        }

        return $ret;
    }
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$rows = [];
$highests = [];
$str = "";

for( $i=0; $i<strlen($data); $i++){

    if( $data[$i] == "\n"){
        $rows[] = $str;
        $str = "";
        continue;
    }

    $str = $str.$data[$i];

    if( $data[$i] == 9){
        $h = [];
        $h['r'] = count($rows);
        $h['c'] = strlen($str)-1;
        $highests[] = $h;
    }

    if( $i == strlen($data)-1){
        $rows[] = $str;
        $str = "";
    }
}

$th = new TrailFinder($rows, $highests);
echo $th->startSearching();

?>