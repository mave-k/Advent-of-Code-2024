<?php

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

function findStr($str, $origStr){
    if( $str=='' || strlen($str)<4){
        return 0;
    }
    //echo "\nStr: $origStr\n";
    $count = substr_count($origStr, $str);
    //echo "hledam: $str, pocet: $count\n";
    return $count;
}

$str = '';
$basicData = []; // horizontal
$sum = 0;
for($p=0; $p<strlen($data); $p++){
    if( $data[$p] == "\n"){
        $sum = $sum + findStr('XMAS', $str);
        $sum = $sum + findStr('SAMX', $str);
        $basicData[] = trim($str);
        $str = '';
    }else{
        $str = $str.$data[$p];
    }
    if( $p == strlen($data)-1){
        $sum = $sum + findStr('XMAS', $str);
        $sum = $sum + findStr('SAMX', $str);
        $basicData[] = trim($str);
    }
}

$verticalData = []; // vertical
for($c=0; $c<count($basicData); $c++){
    $str = '';
    for($r=0; $r<count($basicData); $r++){
        $str = $str.$basicData[$r][$c];
    }
    $verticalData[] = $str;
    $sum = $sum + findStr('XMAS', $str);
    $sum = $sum + findStr('SAMX', $str);
}

/* 
SMER: ze spodu nahoru - na hodinach od 5 na 11
(1) 
================================================================================
[ 9, 0]
[ 9, 1][ 8, 0]
[ 9, 2][ 8, 1][ 7, 0]
[ 9, 3][ 8, 2][ 7, 1][ 6, 0]
[ 9, 4][ 8, 3][ 7, 2][ 6, 1][ 5, 0]
[ 9, 5][ 8, 4][ 7, 3][ 6, 2][ 5, 1][ 4, 0]
[ 9, 6][ 8, 5][ 7, 4][ 6, 3][ 5, 2][ 4, 1][ 3, 0]
[ 9, 7][ 8, 6][ 7, 5][ 6, 4][ 5, 3][ 4, 2][ 3, 1][ 2, 0]
[ 9, 8][ 8, 7][ 7, 6][ 6, 5][ 5, 4][ 4, 3][ 3, 2][ 2, 1][ 1, 0]
[ 9, 9][ 8, 8][ 7, 7][ 6, 6][ 5, 5][ 4, 4][ 3, 3][ 2, 2][ 1, 1][ 0, 0]
================================================================================
(2)
[ 0, 9]
[ 1, 9][ 0, 8]
[ 2, 9][ 1, 8][ 0, 7]
[ 3, 9][ 2, 8][ 1, 7][ 0, 6]
[ 4, 9][ 3, 8][ 2, 7][ 1, 6][ 0, 5]
[ 5, 9][ 4, 8][ 3, 7][ 2, 6][ 1, 5][ 0, 4]
[ 6, 9][ 5, 8][ 4, 7][ 3, 6][ 2, 5][ 1, 4][ 0, 3]
[ 7, 9][ 6, 8][ 5, 7][ 4, 6][ 3, 5][ 2, 4][ 1, 3][ 0, 2]
[ 8, 9][ 7, 8][ 6, 7][ 5, 6][ 4, 5][ 3, 4][ 2, 3][ 1, 2][ 0, 1]
*/
$diagonalData = []; // diagonalni data
for($r=0; $r<count($basicData);$r++){
    $str = '';    
    for($c=0; $c<count($basicData); $c++){
        if( count($basicData)-1-$c<0 || $r-$c <0){
            break;
        }
        $str = $str.$basicData[count($basicData)-1-$c][$r-$c];
    }
    $diagonalData[] = $str;
    $sum = $sum + findStr('XMAS', $str);
    $sum = $sum + findStr('SAMX', $str);

    if( $r<count($basicData)-1){
        $str = '';    
        for($c=0; $c<count($basicData); $c++){
            if( count($basicData)-1-$c<0 || $r-$c <0){
                break;
            }
            $str = $str.$basicData[$r-$c][count($basicData)-1-$c];
        }
        $diagonalData[] = $str;
        $sum = $sum + findStr('XMAS', $str);
        $sum = $sum + findStr('SAMX', $str);
    }
}

/* SMER: ze spodu vlevo nahoru doprava - na hodinach od 7 na 1
(1)
================================================================================
[ 0, 0 ]
[ 1, 0 ][ 0, 1 ]
[ 2, 0 ][ 1, 1 ][ 0, 2 ]
[ 3, 0 ][ 2, 1 ][ 1, 2 ][ 0, 3 ]
[ 4, 0 ][ 3, 1 ][ 2, 2 ][ 1, 3 ][ 0, 4 ]
[ 5, 0 ][ 4, 1 ][ 3, 2 ][ 2, 3 ][ 1, 4 ][ 0, 5 ]
[ 6, 0 ][ 5, 1 ][ 4, 2 ][ 3, 3 ][ 2, 4 ][ 1, 5 ][ 0, 6 ]
[ 7, 0 ][ 6, 1 ][ 5, 2 ][ 4, 3 ][ 3, 4 ][ 2, 5 ][ 1, 6 ][ 0, 7 ]
[ 8, 0 ][ 7, 1 ][ 6, 2 ][ 5, 3 ][ 4, 4 ][ 3, 5 ][ 2, 6 ][ 1, 7 ][ 0, 8 ]
[ 9, 0 ][ 8, 1 ][ 7, 2 ][ 6, 3 ][ 5, 4 ][ 4, 5 ][ 3, 6 ][ 2, 7 ][ 1, 8 ][ 0, 9 ]
================================================================================
(2)
[ 9, 1 ][ 8, 2 ][ 7, 3 ][ 6, 4 ][ 5, 5 ][ 4, 6 ][ 3, 7 ][ 2, 8 ][ 1, 9 ]
[ 9, 2 ][ 8, 3 ][ 7, 4 ][ 6, 5 ][ 5, 6 ][ 4, 7 ][ 3, 8 ][ 2, 9 ]
[ 9, 3 ][ 8, 4 ][ 7, 5 ][ 6, 6 ][ 5, 7 ][ 4, 8 ][ 3, 9 ]
[ 9, 4 ][ 8, 5 ][ 7, 6 ][ 6, 7 ][ 5, 8 ][ 4, 9 ]
[ 9, 5 ][ 8, 6 ][ 7, 7 ][ 6, 8 ][ 5, 9 ]
[ 9, 6 ][ 8, 7 ][ 7, 8 ][ 6, 9 ]
[ 9, 7 ][ 8, 8 ][ 7, 9 ]
[ 9, 8 ][ 8, 9 ]
[ 9, 9 ]
*/
$diagonalData = []; 
for($r=0; $r<count($basicData);$r++){
    $str = '';    
    for($c=0; $c<count($basicData); $c++){ // (1)        
        if( $r-$c <0){
            break;
        }
        $str = $str.$basicData[$r-$c][$c];
    }
    $diagonalData[] = $str;
    $sum = $sum + findStr('XMAS', $str);
    $sum = $sum + findStr('SAMX', $str);

    if($r>0){
        $str = '';    
        for( $c=0; $c<count($basicData); $c++){ // (2)
            if( count($basicData)-1-$c < 0 || $c+$r==count($basicData)){
                break;
            }            
            $str = $str.$basicData[count($basicData)-1-$c][$c+$r];
        }
        $diagonalData[] = $str;
        $sum = $sum + findStr('XMAS', $str);
        $sum = $sum + findStr('SAMX', $str);
    }
}

echo "Sum: $sum\n";

exit;

?>