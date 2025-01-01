<?php

function discover($rows, $cols){
    $visited = [];
    $stackSymbols = [];

    for($r=0; $r<count($rows); $r++){
        for($c=0; $c<$cols; $c++){
            $index = "R$r"."C$c";
            if( isset($visited[$index])){
                continue;
            }

            $symbol = [
                'CH' => $rows[$r][$c], 
                'P' => []             
            ];
            getSymbolMap( $symbol, $r, $c, $rows, $cols, $visited);
            $stackSymbols[] = $symbol;
        }
    }

    $sum = 0;

    for($r=0; $r<count($stackSymbols); $r++){
        $sumBorders = 0;
        for($s=0; $s<count($stackSymbols[$r]['P']); $s++){
            $sumBorders +=$stackSymbols[$r]['P'][$s]['borders'];
        }
        $sum += count($stackSymbols[$r]['P'])*$sumBorders;
    }

    return $sum;
}

function getSymbolMap( &$symbol, $r, $c, $rows, $colsLength, &$visited){
    $index = "R$r"."C$c";
    if( isset($visited[$index]) || $rows[$r][$c] != $symbol['CH']){
        return false;
    }

    $visited[$index] = true;
    $symbol['P'][] = ['index' => $index, 'borders'=> getBorders($r, $c, $rows, $symbol['CH'], $colsLength)];
    
    if( $r>0){ // UP
        getSymbolMap( $symbol, $r-1, $c, $rows, $colsLength, $visited);
    }

    if( $c+1 < $colsLength){ // RIGHT
        getSymbolMap( $symbol, $r, $c+1, $rows, $colsLength, $visited);
    }

    if( $c>0){ // LEFT
        getSymbolMap( $symbol, $r, $c-1, $rows, $colsLength, $visited);
    }

    if( $r+1<count($rows)){ // DOWN
        getSymbolMap( $symbol, $r+1, $c, $rows, $colsLength, $visited);
    }
}

function getBorders($r, $c, $rows, $ch, $colsLength){
    $borders = 0;

    if( $r-1>=0 && $rows[$r-1][$c] != $ch){ // UP
        $borders++;

    }else if( $r-1<0 ){
        $borders++;
    }
    
    if( $c+1<$colsLength && $rows[$r][$c+1] != $ch){ // RIGHT
        $borders++;

    }else if( $c+1>=$colsLength ){
        $borders++;
    }
    
    if( $c-1>=0 && $rows[$r][$c-1] != $ch){ // LEFT
        $borders++;

    }else if( $c-1<0 ){
        $borders++;
    }
    
    if( $r+1<count($rows) && $rows[$r+1][$c] != $ch){ // DOWN
        $borders++;

    }else if( $r+1>=count($rows) ){
        $borders++;
    }

    return $borders;
}

$test = false;
if( $test){
    $data = file_get_contents('./testData.txt'); // 1930
}else{
    $data = file_get_contents('./input.txt'); // 1573474
}

$rows = explode( "\n", $data);
for($r=0; $r<count($rows);$r++){
    $rows[$r] = trim($rows[$r]);
}

echo "Total price of fencing all regions: ".discover($rows, strlen($rows[0]));
?>
