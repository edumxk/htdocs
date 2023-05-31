<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/model.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/perfil.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/clientePerfil.php');

session_start();
$coduser = $_SESSION['coduser'];
$codsetor = $_SESSION['codsetor'];


if(isset($_POST['action'])){
    if($_POST['action']=='getPerfis'){
        $dados = PerfilController::getPerfis(); 
        echo json_encode($dados);
    }
    if($_POST['action']=='getPoliticaPerfil'){
        $dados = PerfilController::getPoliticaPerfil($_POST['codPerfil']); 
        echo json_encode($dados);
    }
    if($_POST['action']=='criarPerfil'){
        $dados = $_POST['dados'];
        $perfil = [];
        
        if($codsetor == 101 || $codsetor <= 1){
            //continue
        }else{
            echo json_encode(['erro'=>'Você não tem permissão para criar um perfil']); 
            return;
        }

        if(!isset($dados['descricao']) || !isset($dados['rca'])){
            echo json_encode(['erro'=>'Preencha todos os campos']);
            return;
        }
        //insere os dados em um array para criar o perfil
        $perfil['descricao'] = Formatador::br_encode($dados['descricao']);
        $perfil['rca'] = $dados['rca'];
        $perfil['obs'] = Formatador::br_encode($dados['obs']);
        //converte para number codcli
        $perfil['codcli'] = intval($dados['codcli']);

        $dados = ModelPoliticas::criarPerfil($perfil); 
        echo json_encode($dados);
    }

    if($_POST['action']=='buscarCliente'){
        $descricao = $_POST['descricao'];
        if(strlen($descricao)<3){
            echo json_encode(['erro'=>'Digite pelo menos 3 caracteres']);
            return;
        } 
        $dados = PerfilController::buscarCliente(Formatador::br_encode($descricao)); 
        echo json_encode($dados);
    }

    if($_POST['action']=='getRca'){
        $dados = ModelPoliticas::getRca(); 
        $rca = [];
        foreach($dados as $d){
            $rca[] = ['codrca'=>$d['CODRCA'], 'nome'=>utf8_decode($d['NOME'])];
        }
        echo json_encode($rca);
    }

    if($_POST['action']=='editarPoliticaPerfil'){
        if(isset($_POST['dados']))
            $dados = $_POST['dados'];
        else
            $dados = [];
        $descricao = $_POST['descricao'];
        $obs = $_POST['obs'];
        $codPerfil = $_POST['codPerfil'];
        //checa se dados é um array vazio
        if(($descricao)==''  || ($obs)==''){
            echo json_encode(['erro'=>'Preencha todos os campos']);
            return;
        }
        //converte obs e descricao para uppercase usando formatador
        $descricao = Formatador::br_encode($descricao);
        $obs = Formatador::br_encode($obs);
        //var_dump($dados);
        //return;
        echo json_encode(ModelPoliticas::editarPoliticaPerfil($dados, $descricao, $obs, $codPerfil));

    }

    if($_POST['action']=='excluirPoliticaPerfil'){
        $codPerfil = $_POST['codPerfil'];
        $senha = $_POST['senha'];
        //checa senha
        if($senha!='bucha'){
            echo json_encode(['erro'=>'Senha Incorreta']);
            return;
        }
        //checa se codPerfil é válido
        if(!is_numeric($codPerfil)){
            echo json_encode(['erro'=>'Código de Perfil Inválido']);
            return;
        }

        //envia para o banco a edição
        $dados = ModelPoliticas::excluirPoliticaPerfil($codPerfil);
        if( count($dados)==2 && $dados[1]==1){
           echo 1;
           return;
        }else
            echo json_encode($dados);
        echo 0;

    }

    if($_POST['action']=='getRcaClientes'){
        $codPerfil = $_POST['codPerfil'];
        
        if(isset($_POST['filtro']))
            $filtro = $_POST['filtro'];
        else 
            $filtro = null;
        
        if(!is_numeric($codPerfil)){
            echo json_encode(['erro'=>'Código de Perfil Inválido']);
            return;
        }


        $clientes = PerfilController::buscarClienteRca($codPerfil, $filtro);
        echo json_encode($clientes);
    }

    if($_POST['action']=='getPerfil-descricao-obs'){
        $codPerfil = $_POST['codPerfil'];
        if(!is_numeric($codPerfil)){
            echo json_encode(['erro'=>'Código de Perfil Inválido']);
            return;
        }
        $dados = ModelPoliticas::getPerfilDescricaoObs($codPerfil);
        //cria um array com os dados
        $dados = ['descricao'=>Formatador::br_decode($dados['DESCRICAO']), 'obs'=>Formatador::br_decode($dados['OBS'])];
        echo json_encode($dados);
    }

    if($_POST['action']=='copiarPerfil'){
        $codPerfil = $_POST['codPerfil'];
        $clientes = $_POST['clientes'];
        $usuario = $_SESSION['coduser'];
        //enviar requisição uma unica vez, checar token

        if(!is_array($clientes)){
            echo json_encode(['erro'=>'Erro ao copiar perfil']);
            return;
        }
        $dados = PerfilController::distribuiPolitica($codPerfil, $clientes, $usuario);
        
        if($dados['inserts'] > 0 || $dados['updates'] > 0){
            echo 'ok';
            return;
        }
        echo json_encode($dados);
    }

}

class PerfilController{

    public static function getPerfis(){
        $dados = ModelPoliticas::getPerfis();
        $perfis = [];
        if(is_array($dados) && count($dados)>0)
            foreach($dados as $d){
                $perfis[] = new Perfil($d['CODPERFIL'], Formatador::br_decode($d['DESCRICAO']), $d['RCA'], $d['REGIAO'], Politica::addPoliticas(ModelPoliticas::getPerfisItem($d['CODPERFIL'])));
            }
        else return 'erro no getPerfis';
        return $perfis;
    }

    public static function getPoliticaPerfil($codPerfil){
        $dados = Politica::addPoliticas(ModelPoliticas::getPerfisItem($codPerfil));
        if(count($dados)>0)
            return $dados;
        $dados = Politica::addPoliticas(ModelPoliticas::getPoliticas(12,1,$codPerfil));
        return $dados;
    }

    public static function criarPerfil($dados){
        if($dados['codcli'] == '') {
            //se não tiver cliente, cria perfil sem cliente
            $dados['obs'] = 'Politica criada sem cliente';
        return ModelPoliticas::criarPerfil($dados);

        }
            //se tiver cliente, cria perfil com cliente
        //return ModelPoliticas::criarPerfilCliente($dados);
    }

    public static function buscarCliente($descricao){
        $clientes = ModelPoliticas::buscarCliente($descricao);
        if(is_array($clientes) && count($clientes)>0)
            return ClientePerfil::addClientes($clientes); 
        else return 'erro no buscarCliente';
    }

    public static function buscarClienteRca($codPerfil, $filtro = null){
        $clientes = ModelPoliticas::buscarClienteRca($codPerfil, $filtro);
        if(is_array($clientes) && count($clientes)>0)
            return ClientePerfil::addClientes($clientes); 
        else return 'erro no buscarCliente';
    }

    public static function distribuiPolitica($codPerfil, $clientes, $usuario){
        $dados = [];
        $retorno = [];
        foreach($clientes as $cliente){
            $dados[] = ModelPoliticas::getDadosPolitica($codPerfil, $cliente['codcli']);
        }
  
        if(count($dados)>0 && is_array($dados)){
            $retorno = ModelPoliticas::distribuiPolitica($dados, $usuario);
            return ModelPoliticas::salvaDadosAlteracao($retorno['inserts'], $retorno['updates']);
        }
    }
}