<?php

$map = [];
$map['xMax'] = 11;
$map['yMax'] = 7;

$test = false;
if( $test){ 
    $data = file_get_contents('./testData.txt'); // 12
}else{
    $map['xMax'] = 101;
    $map['yMax'] = 103;
    $data = file_get_contents('./input.txt'); // 231019008
}

class Robot{

    private $position;
    private $speed;
    private $map;
    
    public function __construct($position, $speed, $map) {
        $this->position = $position;
        $this->speed = $speed;
        $this->map = $map;
    }

    public function getCurrentPosition(){
        return $this->position;
    }

    public function makeStep(){
        $nX = $this->position['X'] + $this->speed['X'];
        if( $nX>=0 && $nX<$this->map['xMax']){
            $this->position['X'] = $nX;

        }else if($nX<0){
            $this->position['X'] = $this->map['xMax']+$nX;

        }else if($this->map['xMax']<=$nX){
            $this->position['X'] = $nX - $this->map['xMax'];

        }else{
            echo "fail X";
            exit;
        }

        if( $this->position['X']<0 || $this->position['X']>$this->map['xMax']-1){
            print_r($nX);
            print_r($this->map['xMax']);
            echo "\n Fail X:  {$this->position['X']}";
            print_r($this->position);
            exit;
        }

        $nY = $this->position['Y'] + $this->speed['Y'];
        if( $nY>=0 && $nY<$this->map['yMax']){
            $this->position['Y'] = $nY;

        }else if($nY<0){
            $this->position['Y'] = $this->map['yMax']+$nY;

        }else if($this->map['yMax']<=$nY){
            $this->position['Y'] = $nY - $this->map['yMax'];

        }else{
            echo "fail Y\n";
            exit;
        }

        if( $this->position['Y']<0 || $this->position['Y']>$this->map['yMax']-1){
            echo "Fail Y: {$this->position['Y']}";
            print_r($this->position);
            exit;
        }
    }
}

$rows = explode( "\n", $data);

$robotsInit = [];
for($i=0; $i<count($rows); $i++){
    $arr = explode(" ", $rows[$i]); 
    $p = explode(",", $arr[0]);
    $position['X'] = explode("=", $p[0])[1];
    $position['Y'] = $p[1];

    $s = explode(",", $arr[1]);
    $speed['X'] = explode("=", $s[0])[1];
    $speed['Y'] = $s[1];

    $robotsInit[] = new Robot($position, $speed, $map);
}

$visualMap = [];
for($i=0; $i<$map['yMax']; $i++){
    for($m=0; $m<$map['xMax']; $m++){
        $visualMap[$i][$m] = '.';
    }
}

$intervals = [];
$intervals[0] = 0;
$intervals[1] = 0;
$intervals[2] = 0;
$intervals[3] = 0;

for($i=0; $i<count($robotsInit); $i++){
    for($m=0; $m<100;$m++){
        $robotsInit[$i]->makeStep();
    }
    $pos = $robotsInit[$i]->getCurrentPosition();

    if( $pos['X']<intval($map['xMax']/2) && $pos['Y']<intval($map['yMax']/2) ){
        $intervals[0]++;

    }else if( $pos['X']>intval($map['xMax']/2) && $pos['Y']<intval($map['yMax']/2) ){
        $intervals[1]++;

    }else if( $pos['X']<intval($map['xMax']/2) && $pos['Y']>intval($map['yMax']/2) ){
        $intervals[2]++;

    }else if( $pos['X']>intval($map['xMax']/2) && $pos['Y']>intval($map['yMax']/2) ){
        $intervals[3]++;

    }

    if( $visualMap[$pos['Y']][$pos['X']] == '.' ){
        $visualMap[$pos['Y']][$pos['X']] = 1;
    }else{
        $visualMap[$pos['Y']][$pos['X']] = $visualMap[$pos['Y']][$pos['X']] + 1;
    }
}

$sum = 1;
for($i=0; $i<count($intervals); $i++){
    $sum = $sum * $intervals[$i];
}

echo "Safety factor: $sum";

// Draw map
for($i=0; $i<$map['yMax']; $i++){
    $row = '';
    for($m=0; $m<$map['xMax']; $m++){
        $row = $row.$visualMap[$i][$m];
    }
    file_put_contents("log.txt", print_r($row."\n", true), FILE_APPEND);
}

?>
