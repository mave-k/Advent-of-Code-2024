<?php
//$data = file_get_contents('./testData.txt');
$data = file_get_contents('./input.txt');

$arr = explode("\r\n", $data);

$arrLeft = [];
$arrRight = [];
$arrRightCountAppears = [];

// make arrays
for($i=0; $i<count($arr);$i++){
    $arrLine = explode('   ', $arr[$i]);
    
    if( isset($arrLine[0]) && trim($arrLine[0])!=''){
        $arrLeft[] = trim($arrLine[0]);
    }

    if( isset($arrLine[1]) && trim($arrLine[1])!=''){
        $arrRight[] = trim($arrRight[1]);

        if( isset( $arrRightCountAppears[trim($arrLine[1])] )){
            $arrRightCountAppears[trim($arrLine[1])] = $arrRightCountAppears[trim($arrLine[1])] + 1;
        }else{
            $arrRightCountAppears[trim($arrLine[1])] = 1;
        }
    }
}

if( count($arrLeft)!=count($arrRight)){
    echo "The arrays have different lengths.";
    exit;
}

function getCountOfAppears( $number, $arrRightCountAppears){
    if( isset($arrRightCountAppears[$number])){
        return $arrRightCountAppears[$number];
    }
    return 0;
}

$sum = 0;
for($i=0; $i<count($arrLeft); $i++){
    $sum += $arrLeft[$i] * getCountOfAppears($arrLeft[$i], $arrRightCountAppears);
}

print("The final length: $sum" );
exit;

?>