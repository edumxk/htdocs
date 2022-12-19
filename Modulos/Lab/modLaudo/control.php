<?php
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='buscar'){
            
            $lote = $_POST['query'];
            getDados($lote);
        }
        if($_POST['action']=='salvar'){
            
            $dados = $_POST['query'];
            salvar($dados);
        }

    }

    function salvar($dados){
        $sql = new SqlOra();
        $ret = 0;
        $rows = [];
        foreach($dados as $d){
            $rows[] = [$d[3] => $d[2]] ;
        }

        foreach($rows as $n){
            $valor = $n[key($n)];  
            $chave =  key($n);

            $ret += $sql->update2("UPDATE KOKAR.PCLAUDOI set RESULTADOANALISE = '$valor' where ROWID = '$chave'",[]);
        }

       echo $ret;

    }

    function getDados($lote){

        
        $sql = new SqlOra();
        $ret = [];
        $temp  = [];
        $retorno = $sql->select("SELECT DESCRICAOPADRAO, VALORPADRAO, RESULTADOANALISE, NUMLOTE, ROWID FROM KOKAR.PCLAUDOI where numlote like :lote",[':lote' => $lote]);
        
        
        foreach ($retorno as $r){
            $temp = [];
            foreach($r as $k => $s){
                if($k == "DESCRICAOPADRAO" or $k == "VALORPADRAO")
                    $temp[] = [$k => utf8_encode($s)];
                else
                    $temp[] = [$k => $s];
            }
            $ret[] = $temp;
        } 

        echo json_encode($ret);
    }   