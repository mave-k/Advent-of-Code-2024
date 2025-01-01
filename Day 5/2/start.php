<?php

$test = false;
if( $test){
    $pages = file_get_contents('./testDataPages.txt');
    $rules = file_get_contents('./testDataRules.txt');
}else{
    $pages = file_get_contents('./pages.txt');
    $rules = file_get_contents('./rules.txt');
}

$rules = explode("\n", $rules);

$colRules = [];
for($r=0; $r<count($rules); $r++){
    $row = explode("|", $rules[$r]);
    $colRules[ trim($row[0]) ]['A'][ trim($row[1]) ] = $rules[$r]; 
    $colRules[ trim($row[1]) ]['B'][ trim($row[0]) ] = $rules[$r]; 
}

function getBeforeNumbers($arr, $position){
    $b = [];
    for( $p=0; $p<count($arr); $p++){
        if( $p!=$position ){
            $b[] = trim($arr[$p]);
        }else{
            return $b;
        }
    }
    return $b;
}

function getAfterNumbers($arr, $position){
    $a = [];
    for( $p=$position+1; $p<count($arr); $p++){
        $a[] = trim($arr[$p]);
    }
    return $a;
}

function isRuleOK($number, $a, $b, $colRules){
    if( isset($colRules[$number])){
        for( $p=0; $p<count($a); $p++){
            if( isset($colRules[$number]['B'][$a[$p]]) ){
                return false;
            }
        }
        
        for( $p=0; $p<count($b); $p++){
            if( isset($colRules[$number]['A'][$b[$p]]) ){
                return false;
            }
        }

        return true;

    }else{
        return true;
    }
}

function isValid($arrRow, $colRules){
    $b = [];
    $a = [];
    for( $p=0; $p<count($arrRow); $p++){
        $num = trim($arrRow[$p]);
        $b = getBeforeNumbers($arrRow, $p);
        $a = getAfterNumbers($arrRow, $p);
        if( !isRuleOK($num, $a, $b, $colRules)){
            return false;
        }
    }
    return true;
}

$pages = explode("\n", $pages);
$invalidPages = [];

for($p=0;$p<count($pages); $p++){
    $row = explode(",", $pages[$p]);
    if( count($row) < 2){
        continue;
    }
    if( !isValid($row, $colRules)){
        $invalidPages[] = $row;
    }
}

function mySortingRule($numA, $numB, $colRules){
    $numA = trim($numA);
    $numB = trim($numB);
    if( !isset($colRules[$numA]) && !isset($colRules[$numB]) ){
        return 0;
    }

    if( isset($colRules[$numA]['A'][$numB])){
        return -1;
    }

    if( isset($colRules[$numB]['A'][$numA]) ){
        return 1;
    }

    return 0;
    exit;
}

$sum = 0;
for($p=0;$p<count($invalidPages); $p++){
    while(!isValid($invalidPages[$p], $colRules)){
        usort($invalidPages[$p], function($a, $b) use ($colRules) {
            return mySortingRule($a, $b, $colRules);
        });
    }
    $sorted = $invalidPages[$p]; 
    $sum = $sum + trim($sorted[count($sorted)/2]);
}

echo "$sum";

?>