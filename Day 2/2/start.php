<?php

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

$arr = explode("\r\n", $data);

function isReportSafe($firstNumber, $secondNumber, $A, $B){
    if( $firstNumber == $secondNumber || $A == $B){
        return false;
    }

    if( $firstNumber < $secondNumber && $A > $B ){
        return false;
    }
    
    if( $firstNumber > $secondNumber && $A < $B ){
        return false;
    }

    if( abs($A-$B)>3 ){
        return false;
    }

    return true;
}

function couldBeSafe($arrLine){
    $reportIsSafe = true;

    for( $i=0; $i<count($arrLine); $i++){
        $copyArray = $arrLine;
        unset($copyArray[$i]);
        $copyArray = array_values($copyArray);

        $reportIsSafe = true;
        for( $c=0; $c<count($copyArray); $c++){
            if( $c+1 > count($copyArray)-1){
                break;
            }
            if( !isReportSafe($copyArray[0], $copyArray[1], $copyArray[$c], $copyArray[$c+1])){
                $reportIsSafe = false;
                break;
            }
        }

        if( $reportIsSafe){
            return true;
        }
    } 

    return $reportIsSafe;
}

$safeReports = 0;
for( $i=0; $i<count($arr); $i++){
    $arrLines = explode(' ', $arr[$i]);
    if( count($arrLines) < 2){
        continue;
    }

    $reportIsSafe = true;

    for( $c=0; $c<count($arrLines); $c++){
        if( $c+1 > count($arrLines)-1){
            break;
        }
        if( !isReportSafe($arrLines[0], $arrLines[1], $arrLines[$c], $arrLines[$c+1])){
            $reportIsSafe = couldBeSafe($arrLines);
            break;
        }
    }

    if( $reportIsSafe){
        $safeReports +=1;
    }
}

echo "Safe reports: $safeReports";
?>