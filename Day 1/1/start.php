<?php
$data = file_get_contents('./input.txt');

$arr = explode("\r\n", $data);

$arrLeft = [];
$arrRight = [];

// make arrays
for($i=0; $i<count($arr);$i++){
    $arrLine = explode('   ', $arr[$i]);
    
    if( isset($arrLine[0]) && trim($arrLine[0])!=''){
        $arrLeft[] = trim($arrLine[0]);
    }

    if( isset($arrLine[1]) && trim($arrLine[1])!=''){
        $arrRight[] = trim($arrLine[1]);
    }
}

// sort 
sort($arrLeft);
sort($arrRight);

if( count($arrLeft)!=count($arrRight)){
    echo "The arrays have different lengths.";
    exit;
}

$sum = 0;
for($i=0; $i<count($arrLeft); $i++){
    $sum += abs($arrLeft[$i]-$arrRight[$i]);
}

print("The final length: $sum" );
exit;

?>