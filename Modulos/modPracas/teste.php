<?php
//require_once("SqlOracle.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');


        try{
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

            $fp = fopen('json/pracas.json', 'w');
            fwrite($fp, json_encode($ret));
            fclose($fp);
        }catch(Exception $e){
            return $e;
        }
    