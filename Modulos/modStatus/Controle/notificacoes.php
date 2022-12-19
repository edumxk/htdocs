<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modStatus/Model/model.php');

if( isset($_POST['action'])){
    if($_POST['action']=='getNotificacoes'){
        echo json_encode(Model::getNotificacoes());
    }
    if($_POST['action']=='getStatusHistory'){
        $codProducao = $_POST['dados'];
        echo json_encode(Status::getStatusHistory($codProducao));
    }
}

if( isset($_POST['operador']) && $_POST['checkrequest']==$_SESSION['request']){ //update Status
    $dados = new Model();
    $dados->lote = $_POST['lote'];
    $dados->operador = $_POST['operador'];
    $dados->tanque = $_POST['tanque'];
    $dados->andamento = $_POST['andamento'];
    $dados->codfunc = $_SESSION;

    echo json_encode(Notificacao::atualizaStatus($dados));
}

if( isset($_POST['nome']) && $_POST['checkrequest']==$_SESSION['request']){ //cadastrar Operador
    $dados = new Notificacao();
    $dados->nome = $_POST['nome'];
    $dados->setor = $_POST['setor'];
    $dados->codFun = $_SESSION['coduser'];

    echo json_encode(Notificacao::cadastrar($dados));
}

class Notificacao{

    public static function getDados(){
        return model::getNotificacoes();
    }
    
    public static function atualizaStatus(Model $dados){

        $banco = model::getDadosByLote($dados);
        $banco->codfunc = $dados->codfunc['coduser'];
        $banco->operador = $dados->operador;
        $banco->andamento = $dados->andamento;
        $status = model::getStatus($banco->codProducao);
        $ret = $status;
        
        if( $dados->andamento != $status->andamento || ( $dados->andamento == 0 && $status->andamento == 0 )){
            if ($dados->andamento <= 1){
                    $ret = model::insertNewStatus($banco, $status);
                    echo ('andamento 0 ou 1');
            }else{
                $banco->numSeq = $status->numSeq;
                echo ('andamento 2 finalizado');
                $ret = model::setFimStatus($banco);
            }
        }
        return $ret;
    }

    public static function cadastrar($dados){
        if( strlen( $dados->nome ) > 50 )
            return 'O nome não pode ser maior que 50 caracteres.';
        return model::cadastrarOperador($dados);
    }
}