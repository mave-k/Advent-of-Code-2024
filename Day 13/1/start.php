<?php


$test = false;
if( $test){ // 480
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$rows = explode( "\n", $data);

$buttons = [];
$task = [];
for($i=0; $i<count($rows); $i++){
    if( trim($rows[$i]) == ''){
        $buttons[] = $task;
        $task = [];
    }else{
        $arr = explode(":", trim($rows[$i]));
        if( $arr[0] == 'Button A'){
            $row = explode("Button A:", trim($rows[$i]));
            $xy = explode(",", trim($row[1]));
            $task['AX'] = explode("X+", trim($xy[0]))[1]; 
            $task['AY'] = explode("Y+", trim($xy[1]))[1];

        }else if( $arr[0] == 'Button B'){
            $row = explode("Button B:", trim($rows[$i]));
            $xy = explode(",", trim($row[1]));
            $task['BX'] = explode("X+", trim($xy[0]))[1]; 
            $task['BY'] = explode("Y+", trim($xy[1]))[1];

        }else{
            $row = explode("Prize:", trim($rows[$i]));
            $xy = explode(",", trim($row[1]));
            $task['RX'] = explode("X=", trim($xy[0]))[1]; 
            $task['RY'] = explode("Y=", trim($xy[1]))[1];

        }     
    }
    if($i == count($rows)-1){
        $buttons[] = $task;
        $task = [];
    }
}

class Calc{

    private $task;
    private $cheaperTask;
    private $priceButtonA;
    private $priceButtonB;

    public function __construct($priceButtonA, $priceButtonB) {
        $this->task = null;
        $this->cheaperTask = 0;
        $this->priceButtonA = $priceButtonA;
        $this->priceButtonB = $priceButtonB; 
    }

    public function play($task){
        $this->task = $task;
        $this->cheaperTask = 0;

        $buttonA = [];
        $buttonB = [];
        for($i=0; $i<100; $i++){
            $ax = $this->task['AX']*$i;
            $bx = $this->task['BX']*$i;
            $buttonA[$ax] = $this->task['AY']*$i;
            $buttonB[$bx] = $this->task['BY']*$i;
        }

        foreach($buttonA as $x=>$y){
            $diff = intval($task['RX']) - intval($x);
            if($diff<0){
                break;
            }
            if( isset($buttonB[$diff]) && (intval($y)+intval($buttonB[$diff]) == intval($task['RY'])) ){
                $sum = ($x/$this->task['AX']) * $this->priceButtonA;
                $sum += ($diff/$this->task['BX']) * $this->priceButtonB;
                
                if( $this->cheaperTask == 0 || $sum < $this->cheaperTask){
                    $this->cheaperTask = $sum;
                }
            }
        }

        return $this->cheaperTask;
    } 
}

$s = new Calc(3,1);
$sum = 0;
for($i=0; $i<count($buttons); $i++){
    $sum += $s->play($buttons[$i]);
}

echo $sum; // 34787

?>
