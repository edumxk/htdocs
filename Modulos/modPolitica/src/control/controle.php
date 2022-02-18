<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/model.php');

if(isset($_POST['action'])){
    if($_POST['action']=='getClientes'){
        echo json_encode(ModelPoliticas::getClientes()); 
    }
    if($_POST['action']=='verPoliticas'){
        $cliente = $_POST['cliente'];
        $numregiao = $_POST['numregiao'];
        echo json_encode(ModelPoliticas::getPoliticas($cliente, $numregiao));
    }
    if($_POST['action']=='gravarPoliticas'){
        echo 'ok'; 
    }
}

class Controle{   
    public static function getClientes(){
        return ModelPoliticas::getClientes(); 
    }   
}