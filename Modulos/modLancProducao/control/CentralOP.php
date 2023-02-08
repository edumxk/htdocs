<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modlancproducao/model/OP.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modlancproducao/model/Ajuste.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modlancproducao/model/Model.php');


if(isset($_POST['action'])){
    if($_POST['action']=='getOP'){
        echo json_encode(Controle::getOP());
    }
    if($_POST['action']=='editar'){
        $numop = $_POST['dataset'];
        echo json_encode(Controle::getAjustes($numop));
    }
}

class Controle{

    public static function getOP(){
        $array = Model::getOP();
        //echo json_encode($array);
        $ret = OP::novaOPLista($array);
        return $ret;
    }

    public static function getAjustes($numop){
        $array = Model::getAjustes($numop);
        $ret = Ajuste::novoAjusteLista($array);
        return $ret;
        
    }
}