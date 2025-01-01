<?php

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$rows = explode( " ", $data);

function blink($arr){
    $newArr = [];

    for($i=0; $i<count($arr); $i++){
        $arr[$i] = strval(trim($arr[$i]));
        if( $arr[$i] == "0"){
            $newArr[] = 1;
            
        }else if( strlen($arr[$i])%2 == 0){
            $mid = strlen($arr[$i])/2;
            $newArr[] = intval( substr($arr[$i], 0, $mid) );
            $newArr[] = intval( substr($arr[$i], $mid) );

        }else{
            $newArr[] = intval($arr[$i])*2024;
        }
    }
    return $newArr;
}

$blinkCount = 25;
for($i=0; $i<$blinkCount; $i++){
    $rows = blink($rows);
}

print_r(count($rows));

?>
