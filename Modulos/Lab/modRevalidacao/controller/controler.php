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
        
        if($ret[0]["NUMLOTE"] == null){
            echo "LOTE NÃO EXISTE";
            return;
        }
        
        if($ret[0]["TEMPO"] == null)
            $ret[0]["TEMPO"] = 0;
        if($ret[0]["USUARIO"] == null)
            $ret[0]["USUARIO"] = "NÃO REVALIDADO";
        if($ret[0]["DTREV"] == null)
            $ret[0]["DTREV"] = "NÃO REVALIDADO";
        
        $numlote = $ret[0]["NUMLOTE"];
            $dados  = array(
                'numlote' => $ret[0]["NUMLOTE"],
                'dtrevalidacao' => $ret[0]["DTREV"],
                'codprod' => $ret[0]["CODPROD"],
                'descricao' => $ret[0]["DESCRICAO"],
                'usuario' => $ret[0]["USUARIO"],
                'fab' => $ret[0]["FAB"],
                'val' => $ret[0]["VAL"],
                'tempo' => $ret[0]["TEMPO"],
                'dtfab' => $ret[0]["DTFAB"],
                'dtval' => $ret[0]["DTVAL"]
            );
        //$dados = json_encode($dados);
        //echo $dados;
        $dadosArray = [];
        foreach($ret as $r){

            if($r["TEMPO"] == null)
                $r["TEMPO"] = 0;
            if($r["USUARIO"] == null)
                $r["USUARIO"] = "NÃO REVALIDADO";
            if($r["DTREV"] == null)
                $r["DTREV"] = "NÃO REVALIDADO";
            $dadosArray[] = [
                'numlote' => $r['NUMLOTE'],
                'dtrevalidacao' => $r['DTREV'],
                'codprod' => $r['CODPROD'],
                'descricao' => $r['DESCRICAO'],
                'usuario' => $r['USUARIO'],
                'fab' => $r['FAB'],
                'val' => $r['VAL'],
                'tempo' => $r['TEMPO'],
                'dtfab' => $r['DTFAB'],
                'dtval' => $r['DTVAL']
            ];
        }
        echo json_encode($dadosArray);
    }
}