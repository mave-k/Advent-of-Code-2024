<?php

class Guard{

    private $map;
    private $bckpMap;
    private $actualPosition;
    private $bckpActualPosition;
    private $dir;
    private $logBarier;
    private $route;
    private $stuckInLoop;

    public function __construct($map, $actualPosition) {
        $this->logBarier = [];
        $this->route = [];
        $this->map = $map;
        $this->bckpMap = $map;
        $this->dir = 'UP';
        $this->actualPosition = $actualPosition;
        $this->bckpActualPosition = $actualPosition;
        $this->stuckInLoop = false;
        $this->markOnMap($actualPosition[0], $actualPosition[1]);
    }

    public function clearMap(){
        $this->logBarier = [];
        $this->route = [];
        $this->map = $this->bckpMap;       
        $this->dir = 'UP';
        $this->actualPosition = $this->bckpActualPosition;
        $this->stuckInLoop = false;
        $this->markOnMap($this->actualPosition[0], $this->actualPosition[1]);
    }

    public function testLoop(){
        while( !$this->isOutOfMap()){
            $this->makeStep();
            if( $this->stuckInLoop){
                return true;
            }
        }
        return false;
    }

    private function markOnMap($r, $c){
        if( isset($this->map[$r][$c]) 
            && $this->map[$r][$c]!='#'
            && $this->map[$r][$c]!='X' ){

            $this->route[] = array( 'r'=>$r, 'c'=>$c );
            $this->map[$r][$c] = 'X';
        }
    }

    public function setBarrier($r, $c){
        $this->map[$r][$c] = '#';
    }

    public function getRoute(){
        while( !$this->isOutOfMap()){
            $this->makeStep();
        }
        return $this->route;
    }

    private function isOutOfMap(){
        $r = $this->actualPosition[0];
        $c = $this->actualPosition[1];
        return !($c>= 0 && $c < 130 && $r >= 0 && $r < 130);
    }
    
    private function isBarrier($r, $c){
        if( isset($this->map[$r][$c]) && $this->map[$r][$c] == '#'){
            if( isset($this->logBarier[$r.','.'D'.$c.$this->dir]) ){
                $this->stuckInLoop = true;
            }else{
                $this->logBarier[$r.','.'D'.$c.$this->dir] = '';
            }
                        
            return true;
        }
        return false;
    }

    private function makeStep(){
        $r = $this->actualPosition[0];
        $c = $this->actualPosition[1];
        
        if( $this->isBarrier($r, $c)){

            $this->stepBack();
            $this->changeDir();

        }else{

            if( $this->dir == 'UP'){
                $this->actualPosition[0] = $this->actualPosition[0] - 1;

            }else if( $this->dir == 'RIGHT'){
                $this->actualPosition[1] = $this->actualPosition[1] + 1;
                
            }else if($this->dir == 'DOWN'){
                $this->actualPosition[0] = $this->actualPosition[0] + 1;
                
            }else if($this->dir == 'LEFT'){
                $this->actualPosition[1] = $this->actualPosition[1] - 1;
            }
            
            $this->markOnMap($this->actualPosition[0], $this->actualPosition[1]);            
        }
    }

    private function stepBack(){
        if( $this->dir == 'UP'){
            $this->actualPosition[0] = $this->actualPosition[0] + 1;
        
        }else if( $this->dir == 'RIGHT'){
            $this->actualPosition[1] = $this->actualPosition[1] - 1;

        }else if($this->dir == 'DOWN'){
            $this->actualPosition[0] = $this->actualPosition[0] - 1;

        }else if($this->dir == 'LEFT'){
            $this->actualPosition[1] = $this->actualPosition[1] + 1;

        }
    } 

    private function changeDir(){
        if( $this->dir == 'UP'){
            $this->dir = "RIGHT";

        }else if( $this->dir == 'RIGHT'){
            $this->dir = "DOWN";

        }else if($this->dir == 'DOWN'){
            $this->dir = "LEFT";

        }else if($this->dir == 'LEFT'){
            $this->dir = "UP";

        }
    }
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$map = [];
$str = '';
$startPosition = [];

for( $p=0; $p<strlen($data); $p++){
    if( $data[$p] == "^"){
        $startPosition[0] = count($map);
        $startPosition[1] = strlen($str);
    }
    if( $data[$p] == "\n"){
        $map[] = trim($str);
        $str = '';
    }else{
        $str = $str.$data[$p];
    }
    if( $p == strlen($data)-1){
        $map[] = trim($str);
    }
}


$colBarrierPositions = [];
$g = new Guard($map, $startPosition);
$route = $g->getRoute();
for( $p=0; $p<count($route); $p++){
    $g->clearMap();    
    $g->setBarrier($route[$p]['r'], $route[$p]['c']);
    if( $g->testLoop()){
        $colBarrierPositions[$route[$p]['r'].','.$route[$p]['c']] = 1;
    }
}

echo count($colBarrierPositions); // 1915
exit;

?>