<?php

function getFreeSpace( $arr, $size){
    while($size>0){
        $arr[] = "X";
        $size--;
    }
    return $arr;
}

function getFileBlock( $arr, $size, $fileID){
    while($size>0){
        $arr[] = strval($fileID);
        $size--;
    }
    return $arr;
}

function getNumberFromEnd($lastIndex, $blocks){
    $ret['number'] = -1;
    $ret['lastIndex'] = -1;

    for($i=$lastIndex; $i>-1; $i--){
        if( $blocks[$i]!="X"){
            $ret['number'] = $blocks[$i];
            $ret['lastIndex'] = $i;
            return $ret;
        }
    }

    return $ret;
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$blocks = [];
$fileID = 0;
$freeSpace = false;

for($i=0; $i<strlen($data); $i++){
    if( $freeSpace ){
        $blocks = getFreeSpace( $blocks, intval($data[$i]) );
    }else{
        $blocks = getFileBlock( $blocks, intval($data[$i]), $fileID);
        $fileID++;
    }
    $freeSpace = !$freeSpace;
}

$lastIndex = count($blocks)-1;
$newBlocks = $blocks; 

for($i=0; $i<count($newBlocks) && $i<$lastIndex; $i++){
    if( $newBlocks[$i] == 'X' ){
        $ret = getNumberFromEnd($lastIndex, $blocks);
        if( $ret['number'] == -1){
            break;
        }
        $newBlocks[$i] = $ret['number']; 
        $lastIndex = $ret['lastIndex'];
        $newBlocks[intval($lastIndex)] = "X";
        $lastIndex--;
    }    
}

$checkSum = 0;
for($i=0; $i<count($newBlocks); $i++){
    if( $newBlocks[$i] != 'X' ){
        $checkSum = $checkSum + ($i * intval($newBlocks[$i]));
    }
}

file_put_contents("log.txt", print_r($newBlocks, true));
print_r($checkSum); // 6283404590840

?>