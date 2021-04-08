<?php
//require_once("SqlOracle.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');

class Vendas{

    public static function getPedidoPraca($numped){
        try{
            $sql = new SqlOra();

            return $sql->select("SELECT c.numped, c.codcli, l.cliente, 
            c.vltotal,
            p.codpraca, l.municcob, p.praca, r.regiao
            from kokar.pcpedc c inner join kokar.pcclient l on c.codcli = l.codcli
                          inner join kokar.pcpraca p on c.codpraca = p.codpraca
                          inner join kokar.pcregiao r on c.numregiao = r.numregiao
            where c.numped = :numped", array(":numped"=>$numped));
        }catch(Exception $e){
            return $e;
        }
    }

    public static function alteraPraca($codcli, $numped, $praca){
        $codpraca;
        $numregiao;
        try{
            $sql = new SqlOra();

            $ret = $sql->select("select c.codpraca, c.numregiao
                from kokar.pcpraca c
                where c.praca like :praca
                  and c.situacao = 'A'", array(":praca"=>$praca)
            );

            if(sizeof($ret)>0){
                $codpraca = $ret[0]['CODPRACA'];
                $numregiao = $ret[0]['NUMREGIAO'];
            }else{
                return 'erro praca';
            }

            $sql2 = new SqlOra();
            return $sql2->update("UPDATE kokar.pcpedc c set codpraca = :codpraca, numregiao = :numregiao where numped = :numped" ,array(
                ":codpraca"=>$codpraca,
                ":numregiao"=>$numregiao,
                ":numped"=>$numped)
            );

        }catch(Exception $e){
            return $e;
        }
    }       

    public static function atualizaJson(){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT p.codpraca, p.praca, p.numregiao, r.regiao
            from kokar.pcpraca p inner join kokar.pcregiao r on p.numregiao = r.numregiao
            where p.situacao = 'A'
            and p.numregiao in (1,2,3,4,6,7)
            and p.codpraca not in (80,335,1,126)
            order by p.praca");
        
        for($i = 0; $i<sizeof($ret); $i++){
            //echo $ret[$i];
            $ret[$i]['PRACA'] = utf8_encode($ret[$i]['PRACA']);
        }
        //echo json_encode($ret);
        //var_dump($ret);

        $fp = fopen('../json/pracas.json', 'w');
        fwrite($fp, json_encode($ret));
        fclose($fp);
    }
}

?>