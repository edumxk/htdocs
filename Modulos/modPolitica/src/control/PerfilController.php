<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/model.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/perfil.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/clientePerfil.php');


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

        if(!isset($dados['descricao']) || !isset($dados['rca'])){
            echo json_encode(['erro'=>'Preencha todos os campos']);
            return;
        }
        //insere od dados em um array para criar o perfil
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
        $dados = $_POST['dados'];
        //checa se dados é um array vazio
        if(count($dados)==0){
            echo json_encode(['erro'=>'Preencha todos os campos']);
            return;
        }

        //envia para o banco a edição
        echo json_encode(ModelPoliticas::editarPoliticaPerfil($dados));

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
}