<?php

function makeCombinations($length){
    $arr = [];
    $arr[] = '+';
    $arr[] = '-';
    if( $length>1){
        $r = 0;
        while($r<$length-1){
            $newArr = [];
            for( $i=0; $i<count($arr); $i++){
                $newArr[] = $arr[$i].'+';
                $newArr[] = $arr[$i].'-';
            }

            $arr = $newArr;
            $r++;
        }
        return $newArr;
    }else{
        return $arr;
    }
}

function isValidFormula($numbers, $rules, $result){
    for( $i=0; $i<count( $rules); $i++ ){
        $ri = 0;
        $r = $numbers[0];
        for( $n=1; $n<count( $numbers); $n++ ){
            if( $rules[$i][$ri] == '+'){
                $r += floatval(trim($numbers[$n]));
            }else{
                $r = $r * floatval(trim($numbers[$n]));
            }
            $ri++;
        }
        if($r == $result){
            return true;
        }
    }
    return false;
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$rows = explode( "\n", $data);
$sum = 0;

$combinationStack = [];

for( $i=0; $i<count( $rows); $i++ ){
    $row = explode( ": ", $rows[$i]);
    $result = floatval( trim($row[0]));
    $numbers = explode( " ", $row[1]);

    $length = count($numbers);
    if( $length == 0){
        continue;
    }
    if( $length == 1 && $result == floatval( trim($numbers[0])) ){
        $sum += $result; 
        continue;
    }

    if( $length>1){
        $length = $length-1;
        $rules = [];
        if( !isset($combinationStack["$length"])){
            $combinationStack["$length"] = makeCombinations($length);
        }
        if( isValidFormula($numbers, $combinationStack["$length"], $result)){
            $sum += $result;
        }
    }
}

print_r($sum);

?>