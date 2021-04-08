<?php
class Funcoes{
    function printList(array $lista)
    {
        foreach($lista as $i => $e)
            echo "$i $e - " . PHP_EOL;
    }
    function printObj($lista)
    {
        foreach($lista as $i => $e)
            var_dump ($i, $e) . PHP_EOL;
    }
    
    function dataFormatUs(string $data){
        $arr = explode('-',$data);
        $ret = $arr[2].'/'.$arr[1].'/'.$arr[0];
        return $ret;
    }   
}