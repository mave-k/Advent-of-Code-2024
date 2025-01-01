<?php

function makeCombinations($length){
    $arr = [];
    $arr[] = '+';
    $arr[] = '*';
    $arr[] = '|';
    if( $length>1){
        $r = 0;
        while($r<$length-1){
            $newArr = [];
            for( $i=0; $i<count($arr); $i++){
                $newArr[] = $arr[$i].'+';
                $newArr[] = $arr[$i].'*';
                $newArr[] = $arr[$i].'|';
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
        $r = intval(trim($numbers[0]));

        for( $n=1; $n<count( $numbers); $n++ ){
            $numbers[$n] = trim($numbers[$n]);
            $rules[$i][$ri] = trim($rules[$i][$ri]);

            if( $rules[$i][$ri] == '+'){
                $r = bcadd(strval($numbers[$n]), strval($r));
            
            }else if( $rules[$i][$ri] == '*'){
                $r = intval($r) * intval($numbers[$n]);

            }else if( $rules[$i][$ri] == '|'){
                $r = strval($r).strval($numbers[$n]);
                $r = intval($r);
            }
            
            $ri++;
        }

        if( strval($r) == strval($result) ){
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

for( $i=0; $i<count($rows); $i++ ){
    $row = explode( ": ", $rows[$i]);
    $result = intval(trim($row[0]));
    $numbers = explode( " ", $row[1]);

    $length = count($numbers);
    if( $length == 0){
        continue;
    }
    if( $length == 1 && intval(trim($result)) == intval(trim($numbers[0])) ){
        $sum = bcadd(strval($result), strval(intval($sum)));
        continue;
    }

    if( $length>1){
        $length = $length - 1;
        $rules = [];
        if( !isset($combinationStack["$length"])){
            $combinationStack["$length"] = makeCombinations($length);
        }
        if( isValidFormula($numbers, $combinationStack["$length"], $result)){
            $sum = bcadd(strval($result), strval(intval($sum)));
        }
    }
}

echo $sum;
?>
