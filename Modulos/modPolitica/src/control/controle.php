<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/model.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/model/visualizarPoliticasModel.php');

if(isset($_POST['action'])){
    if($_POST['action']=='getClientes'){
        echo json_encode(ModelPoliticas::getClientes()); 
    }
    if($_POST['action']=='verPoliticas'){
        $cliente = $_POST['cliente'];
        $numregiao = $_POST['numregiao'];
        echo Controle::getPoliticas($cliente, $numregiao);
    }
    if($_POST['action']=='gravarPoliticas'){
        echo 'ok'; 
    }
}

class Controle{   
    public static function getClientes(){
        return ModelPoliticas::getClientes(); 
    }   
    public static function getPoliticas($cliente, $numregiao){
        $politicas = ModelPoliticas::getPoliticas($cliente, $numregiao); 
        $saida = [];
        foreach($politicas as $p):
            array_push( $saida, [$p["CODGRUPO"],  utf8_encode($p["DESCRICAO"]),  $p["PERCDESC"],
              $p["TABELA"] ] );
        endforeach;
        return json_encode($saida);
    }   
}