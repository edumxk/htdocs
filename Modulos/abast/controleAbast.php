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
        $tipo = $_POST['query']['tipo'];
        $lista = ['numreq'=>intval($numreq), 'estfisico'=>floatval($estfisico), 'qtdabast'=>floatval($qtdabast), 'estfinal'=>floatval($estfinal), 'dataabast'=>Funcoes::dataFormatUs($dataabast), 'respabast'=>$respabast, "horaabast"=> strval($horaabast), 'tipo'=>strval($tipo)];
        Abastecimento::atualizaAbast($lista);
        echo "ok";
    }
    
    if($_POST['action']=='novoAbastecimento'){
        $numreq = $_POST['query']['numreq'];
        $codprod = $_POST['query']['codprod'];
        $qtdsolicitada = $_POST['query']['qtdsolicitada'];
        $qtdsolicitada2 = $_POST['query']['qtdsolicitada2'];
        $setor = $_POST['query']['setor'];
        $solicitante = $_POST['query']['solicitante'];
        $lista = ['numreq'=>intval($numreq), 'codprod'=>intval($codprod), 'qtdsolicitada'=>floatval($qtdsolicitada), 'qtdsolicitada2'=>floatval($qtdsolicitada2), 'setor'=> $setor, 'solicitante'=> $solicitante];
        Abastecimento::iniciarAbast($lista);
        echo "ok";
    }
    if($_POST['action']=='buscaProd'){
        $codprod = $_POST['query'];
        $ret = Abastecimento::buscaProd($codprod);
        if(intval($codprod)>0 and intval($codprod)<=3528){
        echo $ret[0]["DESCRICAO"];
        }else{
        echo '';
        }
    }
    if($_POST['action']=='incluirDensidade'){
        $dados = $_POST['query'];
        $ret = Abastecimento::incluirDensidade($dados['codprod'], $dados['densidade'], $dados['emb1'], $dados['emb2'], $dados['emb3']);
        echo($ret[0]["PARALELO.INCLUIR_DENSIDADE(:CODPROD,:DENSIDADE,:EMB1,:EMB2,:EMB3)"]);
    }
    if($_POST['action']=='incluirResina'){
        $dados = $_POST['query'];
        echo (Abastecimento::incluirResina(Funcoes::dataFormatUs($dados['data']), $dados['tanque'], intval($dados['codprod']), floatval($dados['peso']), $dados['responsavel']));
        //echo($ret[0]["PARALELO.INCLUIR_ABASTRES(:DATA1,:TANQUE,:CODPROD,:PESO,:RESPONSAVEL)"]);
    }


    if($_POST['action']=='buscaReq'){
        $numreq = $_POST['query'];
        $ret = Abastecimento::buscaReq($numreq);
            echo (
                '<b>PRODUTO: </b>' . $ret[0]['CODPROD'] .' - '. $ret[0]['DESCRICAO'] .'<P><P>'.
                '<b>SOLICITANTE: </b>' . $ret[0]['SOLICITANTE'] .'<P><P>'.
                '<b>SETOR: </b>' . $ret[0]['SETOR'] .'<P><P>'.
                '<b>ESTOQUE DISPONIVEL: </b>'. $ret[0]['QTSISDISP'] .' KG'.'<P><P>'.
                '<b>ESTOQUE TOTAL: </b>'. $ret[0]['QTSISTOTAL'] .' KG'.'<P><P>'.
                '<b>QTD SOLICITADA: </b>'. $ret[0]['QTDSOLICITADA'] .' KG'.'<P>'
        );
    }
    if($_POST['action']=='buscaResina'){
        $numreq = Funcoes::dataFormatUs($_POST['query']);
        $ret = Abastecimento::buscaResina($numreq);
                echo'
                <table border="1" >
                <tr>
                <td> <h5><b>PRODUTO</b> </td>
                <td><h5><b>DATA PESO</b></td>
                <td><h5><b>TANQUE</b></td>
                <td><h5><b>PESO</b></td>
                <td><h5><b>EMISSOR</b></td>
                <td><h5><b>LANÇAMENTO</b></td>
                </tr> 
                ';
        foreach($ret as $r){

                echo ('<tr style="text-align: center">
                <td style="text-align: left"> <b> '. $r['CODPROD'] .' - '. $r['DESCRICAO'].'</td>
                <td> <b>'. $r['DATA'] .'</td>
                <td> <b>RESINA '. $r['TANQUE'] .'</td>
                <td><b>'. $r['PESO'] .' KG'.'</td>
                <td><b>'. $r['RESPONSAVEL'] .'</td>
                <td><b>'. $r['DTLANC'] .'</td>
                </tr>'
                );
            }
    }
    
    if($_POST['action']=='deletaProdEst'){
        $dados = $_POST['query'];
        Abastecimento::deletaProdEst($dados);
    }

}
?>