<?php

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}


$str = '';
$basicData = [];
$sum = 0;

for($p=0; $p<strlen($data); $p++){
    if( $data[$p] == "\n"){
        $basicData[] = trim($str);
        $str = '';
    }else{
        $str = $str.$data[$p];
    }
    if( $p == strlen($data)-1){
        $basicData[] = trim($str);
    }
}

function isXMAS($arr, $r, $c, $option){
    if( $option == 1){
        /*  Searching:
            M * M
            * A *
            S * S
        */
        if( isset($arr[$r][$c+2]) && $arr[$r][$c+2] == 'M'
            &&
            isset($arr[$r+1][$c+1]) && $arr[$r+1][$c+1] == 'A'
            &&
            isset($arr[$r+2][$c]) && $arr[$r+2][$c] == 'S'
            &&
            isset($arr[$r+2][$c+2]) && $arr[$r+2][$c+2] == 'S'
        ){
            return true;
        }else{
            return false;
        }

    }else if( $option == 2){
        /*  Searching:
            S * M
            * A *
            S * M
        */
        if( isset($arr[$r][$c+2]) && $arr[$r][$c+2] == 'M'
            &&
            isset($arr[$r+1][$c+1]) && $arr[$r+1][$c+1] == 'A'
            &&
            isset($arr[$r+2][$c]) && $arr[$r+2][$c] == 'S'
            &&
            isset($arr[$r+2][$c+2]) && $arr[$r+2][$c+2] == 'M'
        ){
            return true;
        }else{
            return false;
        }

    }else if( $option == 3){
        /*  Searching:
            S * S
            * A *
            M * M
        */
        if( isset($arr[$r][$c+2]) && $arr[$r][$c+2] == 'S'
            &&
            isset($arr[$r+1][$c+1]) && $arr[$r+1][$c+1] == 'A'
            &&
            isset($arr[$r+2][$c]) && $arr[$r+2][$c] == 'M'
            &&
            isset($arr[$r+2][$c+2]) && $arr[$r+2][$c+2] == 'M'
        ){
            return true;
        }else{
            return false;
        }

    }else if( $option == 4){
        /*  Searching:
            M * S
            * A *
            M * S
        */
        if( isset($arr[$r][$c+2]) && $arr[$r][$c+2] == 'S'
            &&
            isset($arr[$r+1][$c+1]) && $arr[$r+1][$c+1] == 'A'
            &&
            isset($arr[$r+2][$c]) && $arr[$r+2][$c] == 'M'
            &&
            isset($arr[$r+2][$c+2]) && $arr[$r+2][$c+2] == 'S'
        ){
            return true;
        }else{
            return false;
        }
    }
}

for($c=0; $c<count($basicData); $c++){
    for($r=0; $r<count($basicData); $r++){
        if( $basicData[$r][$c] == 'M'){
            if( isXMAS($basicData, $r, $c, 1) || isXMAS($basicData, $r, $c, 4)){
                $sum = $sum + 1;
            }
        }else if( $basicData[$r][$c] == 'S'){
            if( isXMAS($basicData, $r, $c, 2) || isXMAS($basicData, $r, $c, 3)){
                $sum = $sum + 1;
            }
        }
    }
}

echo "Sum: $sum\n";

exit;

?>