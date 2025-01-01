<?php
$test = false;
if( $test){
    $data = file_get_contents('./testData.txt');
}else{
    $data = file_get_contents('./input.txt');
}

function filterData($data){
    
    $newData = '';
    $canRead = true;
    $buffer = '';
    $readBuffer = false;

    for($p=0; $p<strlen($data); $p++){
        $str = $data[$p];
        if( $str == 'd'){
            $buffer = '';
            $readBuffer = true;
        }

        if( $readBuffer){
            $buffer = $buffer.$str;
            if( strlen($buffer) == 2 && $buffer!='do'){
                $newData = $newData.$buffer;
                $readBuffer = false;
                $buffer = '';
                $canRead = true;
                continue;
            }

            // do()
            // don't()
            if( strlen($buffer) == 4 && $buffer!='do()' && $buffer!="don'" ){
                $newData = $newData.$buffer;
                $readBuffer = false;
                $buffer = '';
                $canRead = true;
                continue;
            }

            if( strlen($buffer) == 4 && $buffer=='do()'){
                $newData = $newData.$buffer;
                $readBuffer = false;
                $buffer = '';
                $canRead = true;
                continue;
            }

            if( strlen($buffer) == 7 && $buffer=="don't()"){
                $readBuffer = false;
                $buffer = '';
                $canRead = false;
                continue;
            }

        }else if( !$readBuffer && $canRead){
            $newData = $newData.$str;
        }
    }

    return $newData;
}

$data = filterData($data); 

file_put_contents('test.txt', $data);

$strRow = '';
$strArr = [];
$read = false;
$sum = 0;

function mul($strRow){
    $a = '';
    $b = '';
    $tmpStr = '';
    for($p=4; $p<strlen($strRow); $p++){
        if( $strRow[$p] == ','){
            $a = $tmpStr;
            $tmpStr = '';
            continue;
        }
        if( $strRow[$p] == ')'){
            $b = $tmpStr;
            break;
        }
        $tmpStr = $tmpStr.$strRow[$p];
    }   

    if( $a!='' && $b!=''
        && is_numeric($a) && is_numeric($b)
        && strlen(trim($a))==strlen($a)
        && strlen(trim($b))==strlen($b) ){
        return floatval($a) * floatval($b);
    }
    return 0;
}

for($p=0; $p<strlen($data); $p++){
    $str = $data[$p];

    if( $str == 'm'){
        if( $strRow!=''){
            $strArr[] = $strRow;
            $sum = $sum + mul($strRow); 
            $strRow = '';
        }
        $read = true;
    }

    if( $read){
        $strRow = $strRow.$str;
        if( strlen($strRow) == 4 && $strRow!='mul('){
            $strRow = '';
            $read = false;
        }
    }

    if( $p==strlen($data)-1
        && $strRow!='' 
        && substr($strRow, 0,4) == 'mul('){

        $strArr[] = $strRow;
        $sum = $sum + mul($strRow); 
    }
}

echo "Sum: $sum\n";

exit;

?>