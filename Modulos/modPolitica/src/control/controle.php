<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/model.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/Historico.php');

if(isset($_POST['action'])){
    if($_POST['action']=='getClientes'){
        echo json_encode(ModelPoliticas::getClientes()); 
    }
    if($_POST['action']=='getObs'){
        $cliente = $_POST['cliente'];
        echo json_encode(ModelPoliticas::getObs($cliente)); 
    }
    if($_POST['action']=='verPoliticas'){
        $cliente = $_POST['cliente'];
        $numregiao = $_POST['numregiao'];
        echo Controle::getPoliticas($cliente, $numregiao);
    }
    if($_POST['action']=='atualizarPolitica'){
        $codCli = $_POST['codCli'];
        $listaGrupo = $_POST['desconto']; 
        $obs = $_POST['obs'];
        $codUser = $_POST['codUser'];
        echo json_encode(ModelPoliticas::atualizarPolitica($codCli, $listaGrupo, $obs, $codUser));
    }
    if($_POST['action']=='buscaAlteracoes'){
        Controle::buscaAlteracoes();
    }
    if($_POST['action']=='getHistorico'){
        $id = $_POST['id'];
        echo json_encode(Controle::getHistorico($id));
    }
    if($_POST['action']=='ativar'){
        $codcli = $_POST['codcli'];
        $codUser = $_POST['coduser'];
        echo json_encode(ModelPoliticas::ativar($codcli, $codUser));
    }
    if($_POST['action']=='desativar'){
        $codcli = $_POST['codcli'];
        $codUser = $_POST['coduser'];
        echo json_encode(ModelPoliticas::desativar($codcli, $codUser));
    }
    if($_POST['action']=='getCliente'){
        $codcli = $_POST['codCli'];
        //$codUser = $_POST['coduser'];
        echo (ModelPoliticas::getCliente($codcli));
    }
    if($_POST['action']=='copiar'){
        $origem = $_POST['origem'];
        $destino = $_POST['destino'];
        $codUser = $_POST['codUser'];
        echo json_encode(ModelPoliticas::copiar($origem, $destino, $codUser));
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
            if($p["TABELA"] > 0){
            array_push( $saida, [ $p["CODGRUPO"],  utf8_encode($p["DESCRICAO"]),  $p["PERCDESC"],
              $p["TABELA"], utf8_encode($p['CLIENTE']), $p['ATIVO'] ] );
            }
        endforeach;
        return json_encode($saida);
    } 
    
    public static function buscaAlteracoes(){
        $dados = ModelPoliticas::buscaAlteracoes();
        return ($dados);
    }

    public static function getHistorico($id){
        $dados = ModelPoliticas::getHistorico($id);
        $ret = [];
        if(sizeof($dados)>0):
            foreach($dados as $d):
                array_push($ret, new Historico($d['CODHIST'], $d['CODGRUPO'], $d['DESCRICAO'], $d['DESCANT'], $d['DESCNOVO'], $d['TABELA']));
            endforeach;
        endif;
        return $ret;
    }
}