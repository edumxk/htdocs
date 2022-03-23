<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once($_SERVER["DOCUMENT_ROOT"]. '/Modulos/lab/modRevalidacao/model/model.php');

if(isset($_POST['action'])){
    if($_POST['action']=='revalidar'){
        $numlote = $_POST['query']['numlote'];
        $tempo = $_POST['query']['tempo'];
        $usuario = $_POST['query']['usuario'];
        $lista = ['numlote'=>strval($numlote), 'tempo'=>$tempo, 'usuario'=>$usuario];
        var_dump(Revalidacao::revalidar($lista));
    }
    
   

    if($_POST['action']=='buscaLote'){
        $numlote = $_POST['query'];
        $ret = Revalidacao::buscaLote($numlote);
            echo (
                '<b>DATA REVALIDAÇÃO: </b>'. $ret[0]['DTREV'] .'<P><P>'.
                '<b>PRODUTO: </b>' . $ret[0]['CODPROD'] .' - '. $ret[0]['DESCRICAO'] .'<P><P>'.
                '<b>FUNCIONÁRIO: </b>' . $ret[0]['USUARIO'] .'<P><P>'.
                '<b>FABRICAÇÃO: </b>' . $ret[0]['FAB'] .'<P><P>'.
                '<b>VALIDADE: </b>'. $ret[0]['VAL'] .'<P><P>'.
                '<b>TEMPO: </b>'. $ret[0]['TEMPO'] .' MESES'.'<P><P>'.
                '<b>FABRICAÇÃO ORIGINAL: </b>'. $ret[0]['DTFAB'] .'<P><P>'.
                '<b>VALIDADE ORIGINAL: </b>'. $ret[0]['DTVAL'] .'<P>'
        );
    }
}