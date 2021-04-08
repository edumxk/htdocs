<?php

    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modPracas/dao/vendas.php');

    $action=$_POST['action'];
    $key=$_POST['query'];

    if($action=='pedidoPraca'){
        getPedidoPraca($key);
    }
    if($action=='praca'){
        getPracas($key);
    }
    if($action=='alteraPraca'){
        alteraPraca($key);
    }


    /*buscar Praça do Pedido*/
    function getPedidoPraca($key){
        $return = Vendas::getPedidoPraca($key);

        if($return>0){
            for($i = 0; $i<sizeof($return[0]); $i++){
                $return[0]['MUNICCOB'] = utf8_decode($return[0]['MUNICCOB']);
            }
            echo JSON_ENCODE($return[0]);
        }/*else{
            echo json_encode($return);
        }*/
    }

    function getPracas($key){
        //echo $key;
        echo Vendas::atualizaJson();
        $data = array();
        $json_file = file_get_contents("http://localhost:8042/modulos/modPracas/json/pracas.json");   
        $json_str = json_decode($json_file, true);

        $array = $json_str;

        $data;
        if($array > 0){
            foreach($array as $a){
                array_push($data ,$a['PRACA']);
            }
        }
        echo json_encode($data);
    }

    function alteraPraca($key){
        echo Vendas::alteraPraca($key['codcli'], $key['numped'], $key['praca']);
    }

    

?>