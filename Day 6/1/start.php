<?php

class Guard{

    private $map;
    private $visited;
    private $actualPosition;
    private $dir;
    private $log;

    public function __construct($map, $actualPosition) {
        $this->log = [];
        $this->map = $map;
        $this->dir = 'UP';
        $this->visited = 0;
        $this->actualPosition = $actualPosition;
        $this->markOnMap($actualPosition[0], $actualPosition[1]);
    }

    private function markOnMap($r, $c){
        if( isset($this->map[$r][$c]) 
            && $this->map[$r][$c]!='#'
            && $this->map[$r][$c]!='X' ){

            $this->map[$r][$c] = 'X';
            $this->visited = $this->visited + 1;
            $this->log[] = array('Visited'=>$this->visited, 'Position'=>"[ $r, $c ]");
        }
    }

    public function walk(){
        while( !$this->isOutOfMap()){
            $this->makeStep();
        }
        return $this->visited;
    }

    private function isOutOfMap(){
        $r = $this->actualPosition[0];
        $c = $this->actualPosition[1];
        if( !isset($this->map[$r][$c])){
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

        $r = $this->actualPosition[0];
        $c = $this->actualPosition[1];
        $this->log[] = array('STEPBACK'=>true, 'Position'=>"[ $r, $c ]");
    } 

    private function isBarrier($r, $c){
        if( isset($this->map[$r][$c]) && $this->map[$r][$c] == '#'){
            return true;
        }
        return false;
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

    public function getLog(){
        return $this->log;
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


$g = new Guard($map, $startPosition);
echo $g->walk();

file_put_contents("log.txt", print_r($g->getLog(), true));
exit;

?>