<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php';
require_once '../model/Lancamento.php';
require_once '../model/Frete.php';
session_start();
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {

        $dados = $_POST['data'];
        $dados['coduser'] = $_SESSION['coduser'];
        $dados['data'] = Formatador::formatador2($dados['data']);
        $dados = lancamento::save($dados);
        echo json_encode($dados);
    }
    if ($_POST['action'] == 'saveKm') {

        $dados = $_POST['lancamento'];
        $dados['coduser'] = $_SESSION['coduser'];
        $dados['data'] = Formatador::formatador2($dados['data']);
        $dados = lancamento::saveKm($dados);
        echo json_encode($dados);
    }


    if ($_POST['action'] == 'getDadosCombustivel') {
        $datas = $_POST['datas'];

        $kms = Frete::getDespesasNovo($datas[0], $datas[1]);

        $dados = [];
        foreach ($kms as $k) {
            $dados[$k["PLACA"]][] = Frete::getDadosFromString($k['HISTORICO2']);
        }
        echo json_encode($dados);
    }
    if ($_POST['action'] == 'getMotoristas') {
        $dados = Lancamento::getMotoristas();
        foreach ($dados as &$d) {
            $d['NOME'] = Formatador::br_decode($d['NOME']);
        }
        echo json_encode($dados);
    }
    if ($_POST['action'] == 'getTipoLancamentos') {
        $dados = Lancamento::getTipoLancamentos();
        foreach ($dados as &$d) {
            $d['DESCRICAO'] = Formatador::br_decode($d['DESCRICAO']);
        }
        echo json_encode($dados);
    }
    if ($_POST['action'] == 'getLancamentos') {
        $dados = Lancamento::getLancamentos(Formatador::formatador2($_POST['data']));
        foreach ($dados as &$d) {
            $d['NOME'] = Formatador::br_decode($d['NOME']);
            $d['DESCRICAO'] = Formatador::br_decode($d['DESCRICAO']);
        }
        echo json_encode($dados);
    }
    if ($_POST['action'] == 'delete') {
        $dados = Lancamento::delete($_POST['id']);
        echo json_encode($dados);
    }
    if($_POST['action'] == 'deleteLancamentoKm'){
        $dados = Lancamento::deleteLancamentoKm(($_POST['id']));
        echo json_encode($dados);
    }
    
    if ($_POST['action'] == 'getVeiculos') {
        $dados = Frete::getVeiculos();
        echo json_encode($dados);
    }
    if ($_POST['action'] == 'getLancamentosKm') {
        $dados = Lancamento::getLancamentosKm(Formatador::formatador2($_POST['data']));
        echo json_encode($dados);
    }	
}
