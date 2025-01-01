<?php

class Robot{

    private $map;
    private $actualPosition;
    private $direction;
    private $instructions;
    private $maxR;
    private $maxC;

    public function __construct( $map, $initPosition, $instructions){
        $this->map = $map;
        $this->actualPosition = $initPosition;
        $this->setRobotPosition($this->actualPosition['r'], $this->actualPosition['c']);
        $this->direction = ''; 
        $this->instructions = $instructions;
        $this->maxR = count($map);
        $this->maxC = count($map[0]);
    }

    public function start(){
        for($i=0; $i<count($this->instructions); $i++){
            $this->setRobotPosition($this->actualPosition['r'], $this->actualPosition['c']);
            $this->makeStep($this->instructions[$i]);
            $this->setRobotPosition($this->actualPosition['r'], $this->actualPosition['c']);
        }
    }

    private function makeStep($instruction){
        $newPosition = [];        
        if( $instruction == '<'){
            $this->direction = 'LEFT';
            $newPosition =  [
                                'r'=>$this->actualPosition['r'],
                                'c'=>$this->actualPosition['c']-1
                            ];

        }else if( $instruction == '>'){
            $this->direction = 'RIGHT';
            $newPosition =  [
                                'r'=>$this->actualPosition['r'],
                                'c'=>$this->actualPosition['c']+1
                            ];

        }else if( $instruction == '^'){
            $this->direction = 'UP';
            $newPosition =  [
                                'r'=>$this->actualPosition['r']-1,
                                'c'=>$this->actualPosition['c']
                            ];

        }else if( $instruction == 'v'){
            $this->direction = 'DOWN';
            $newPosition =  [
                                'r'=>$this->actualPosition['r']+1,
                                'c'=>$this->actualPosition['c'] 
                            ];

        }

        if(    $newPosition['r'] >= $this->maxR 
            || $newPosition['c'] >= $this->maxC 
            || $newPosition['r'] < 0 
            || $newPosition['c'] < 0 ){
            return false;
        }

        if( $this->isWall($newPosition['r'], $newPosition['c']) ){
            return false;

        }else if( $this->isBarier($newPosition['r'], $newPosition['c']) ){
            if($this->moveBarier($newPosition['r'], $newPosition['c'])){
                $this->actualPosition = $newPosition;
            };

        }else{            
            $this->actualPosition = $newPosition;
        }
    }

    private function setRobotPosition($r, $c){
        $this->map[$r][$c] = $this->map[$r][$c] == '@' ? '.' : '@';
    }

    private function isBarier($r, $c){
        return $this->map[$r][$c] == 'O';
    }

    private function isWall($r, $c){
        return $this->map[$r][$c] == '#';
    }

    private function moveBarier($fromR, $fromC){
        if( $this->direction == 'LEFT'){

            if( $this->map[$fromR][$fromC] == 'O' && $this->map[$fromR][$fromC-1] == '.'){
                $this->map[$fromR][$fromC] = '.';
                $this->map[$fromR][$fromC-1] = 'O';
                return true;
                
            }else if($this->map[$fromR][$fromC] == 'O' && $this->map[$fromR][$fromC-1] == 'O'){
                $start = $fromC;
                $row = [];
                $ch = $this->map[$fromR][$fromC];
                while( $ch=='O' ){
                    $row[] = $this->map[$fromR][$fromC--];
                    $ch = $this->map[$fromR][$fromC];
                }
                if( $this->map[$fromR][$fromC] == '.'){
                    $this->map[$fromR][$start--] = '.';
                    for($x=0; $x<count($row); $x++){
                        $this->map[$fromR][$start--] = 'O';
                    }
                    return true;
                }else{
                    return false;
                }
            }

        }else if( $this->direction == 'RIGHT'){
            if($this->map[$fromR][$fromC] == 'O' && $this->map[$fromR][$fromC+1] == '.'){
                $this->map[$fromR][$fromC] = '.';
                $this->map[$fromR][$fromC+1] = 'O';
                return true;

            }else if($this->map[$fromR][$fromC] == 'O' && $this->map[$fromR][$fromC+1] == 'O'){
                $start = $fromC;
                $row = [];
                $ch = $this->map[$fromR][$fromC];
                while( $ch=='O' ){
                    $row[] = $this->map[$fromR][$fromC++];
                    $ch = $this->map[$fromR][$fromC];
                }
                if( $this->map[$fromR][$fromC] == '.'){
                    $this->map[$fromR][$start++] = '.';
                    for($x=0; $x<count($row); $x++){
                        $this->map[$fromR][$start++] = 'O';
                    }
                    return true;
                }else{
                    return false;
                }
            }

        }else if( $this->direction == 'UP'){
            if( $this->map[$fromR][$fromC] == 'O' && $this->map[$fromR-1][$fromC] == '.'){
                $this->map[$fromR][$fromC] = '.';
                $this->map[$fromR-1][$fromC] = 'O';
                return true;

            }else if($this->map[$fromR][$fromC] == 'O' && $this->map[$fromR-1][$fromC] == 'O'){
                $start = $fromR;
                $row = [];
                $ch = $this->map[$fromR][$fromC];
                while( $ch=='O' ){
                    $row[] = $this->map[$fromR--][$fromC];
                    $ch = $this->map[$fromR][$fromC];
                }

                if( $this->map[$fromR][$fromC] == '.'){
                    $this->map[$start--][$fromC] = '.';
                    for($x=0; $x<count($row); $x++){
                        $this->map[$start--][$fromC] = 'O';
                    }
                    return true;
                }else{
                    return false;
                }
            }

        }else if( $this->direction == 'DOWN'){
            if( $this->map[$fromR][$fromC] == 'O' && $this->map[$fromR+1][$fromC] == '.'){
                $this->map[$fromR][$fromC] = '.';
                $this->map[$fromR+1][$fromC] = 'O';
                return true;

            }else if($this->map[$fromR][$fromC] == 'O' && $this->map[$fromR+1][$fromC] == 'O'){
                $start = $fromR;
                $row = [];
                $ch = $this->map[$fromR][$fromC];
                while( $ch=='O' ){
                    $row[] = $this->map[$fromR++][$fromC];
                    $ch = $this->map[$fromR][$fromC];
                }
                if( $this->map[$fromR][$fromC] == '.'){
                    $this->map[$start++][$fromC] = '.';
                    for($x=0; $x<count($row); $x++){
                        $this->map[$start++][$fromC] = 'O';
                    }
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function getSum(){
        $sum = 0;
        for($r=0; $r<count($this->map);$r++){
            for($c=0; $c<count($this->map[$r]);$c++){
                if( $this->map[$r][$c] == 'O'){
                    $sum += 100 * $r + $c;
                }
            }

        }
        return $sum;
    }

    public function getMap(){
        $arr = [];
        for($r=0; $r<count($this->map);$r++){
            $str = '';
            for($c=0; $c<count($this->map[$r]);$c++){
                $str = $str.$this->map[$r][$c];
            }
            $arr[] = $str;
        }
        return $arr;
    }
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt'); 
    $instructions = file_get_contents('./testInstructions.txt'); 
}else{
    $data = file_get_contents('./input.txt');
    $instructions = file_get_contents('./instructions.txt');
}

$instructions = explode( "\n", $instructions);
$arrInstructions = [];
for($r=0; $r<count($instructions);$r++){
    for($c=0; $c<strlen($instructions[$r]);$c++){
        if( trim($instructions[$r][$c]) == ""){
            continue;
        }
        $arrInstructions[] = $instructions[$r][$c];
    }
}

$map = [];
$rows = explode( "\n", $data);
for($r=0; $r<count($rows);$r++){
    $rows[$r] = trim($rows[$r]);
    for($c=0; $c<strlen($rows[$r]);$c++){
        if(trim($rows[$r][$c])=="" || trim($rows[$r][$c])=="\n"){
            continue;
        }
        if( $rows[$r][$c] == '@'){
            $initPosition = [ 'r'=>$r , 'c'=>$c ];
            $rows[$r][$c] = '.';
        }
        $map[$r][$c] = $rows[$r][$c];
    }
}

$r = new Robot($map, $initPosition, $arrInstructions);
$r->start();
file_put_contents("log.txt", print_r($r->getMap(), true));
echo "Sum of all boxes' GPS coordinates: {$r->getSum()}";

?>
