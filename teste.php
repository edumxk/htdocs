<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . '\model\SqlOracle2.php');

        // var_dump($_SERVER);

        $sql = new SqlOra();
        $ret =  $sql->select("SELECT pvenda FROM rati where numrat = 697 ");

        //var_dump($ret); 
        //number_format($ret[0]['PVENDA'],'4','.',',');
        // echo (float)$ret[0]['PVENDA'];  
        // if(is_numeric((float)$ret[0]['PVENDA'])){
        //     echo "Ok";
        // }

        

    function utf8Array($arr){

        $retorno = [];
        foreach($arr as $key=>$val){
            array_push($retorno, array(utf8_encode($key)=>utf8_encode($val)));
        }
        return $retorno;
    }


?>
