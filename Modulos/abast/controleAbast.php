<?php
require_once 'abastecimento.php';
require_once 'funcoes.php';
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

if(isset($_POST['action'])){
    if($_POST['action']=='atualizaAbast'){
        $numreq = $_POST['query']['numreq'];
        $estfisico = $_POST['query']['estfisico'];
        $qtdabast = $_POST['query']['qtdabast'];
        $estfinal = $_POST['query']['estfinal'];
        $dataabast = $_POST['query']['dataabast'];
        $respabast = $_POST['query']['respabast'];
        $horaabast = $_POST['query']['horaabast'];
        $lista = ['numreq'=>intval($numreq), 'estfisico'=>floatval($estfisico), 'qtdabast'=>floatval($qtdabast), 'estfinal'=>floatval($estfinal), 'dataabast'=>Funcoes::dataFormatUs($dataabast), 'respabast'=>$respabast, "horaabast"=> strval($horaabast)];
        Abastecimento::atualizaAbast($lista);
        echo "ok";
    }
    
    if($_POST['action']=='novoAbastecimento'){
        $numreq = $_POST['query']['numreq'];
        $codprod = $_POST['query']['codprod'];
        $qtdsolicitada = $_POST['query']['qtdsolicitada'];
        $setor = $_POST['query']['setor'];
        $solicitante = $_POST['query']['solicitante'];
        $lista = ['numreq'=>intval($numreq), 'codprod'=>intval($codprod), 'qtdsolicitada'=>floatval($qtdsolicitada), 'setor'=> $setor, 'solicitante'=> $solicitante];
        Abastecimento::iniciarAbast($lista);
        echo "ok";
    }

}
?>