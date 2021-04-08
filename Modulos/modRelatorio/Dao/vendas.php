<?php
//require_once("SqlOracle.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');

class Vendas{

    
    public static function getPesoMes(){
        try{
            $sql = new SqlOra();

            return $sql->select("select dtfat, to_char(sum(n.totpeso), '9999999999.99') peso
                        from kokar.pcnfsaid n
                        where n.dtfat >= sysdate-60
                        and n.dtcancel is null
                        group by n.dtfat
                        order by dtfat");
        }catch(Exception $e){
            return $e;
        }
    }

    public static function getCargasDia($dtfat){
        try{
            $sql = new SqlOra();

            return $sql->select("select n.numcar, c.codmotorista, c.codveiculo, e.nome, v.descricao
            from kokar.pcnfsaid n inner join kokar.pccarreg c on n.numcar = c.numcar
                 inner join kokar.pcempr e on e.matricula = c.codmotorista
                 inner join kokar.pcveicul v on c.codveiculo = v.codveiculo
            where n.dtfat = :dtfat
            and n.dtcancel is null
            and n.numcar > 0
            group by n.numcar, c.codmotorista, c.codveiculo, e.nome, v.descricao",array(":dtfat"=>$dtfat));
        }catch(Exception $e){
            echo $e;
        }
    }
    
    public static function getFaturamentosDia($dtfat){
        try{
            $sql = new SqlOra();

            return $sql->select("select n.numcar, 
                        u.nome, 
                        n.numnota,
                        n.codcli,
                        cl.cliente,
                        to_char(n.vltotal, '999999999.99') vltotal,
                        to_char(n.totpeso, '999999999.99') totpeso,
                        cd.nomecidade,
                        cd.uf
                from kokar.pcnfsaid n inner join kokar.pcclient cl on n.codcli = cl.codcli
                                    inner join kokar.pcusuari u on n.codusur = u.codusur
                                    inner join kokar.pccidade cd on cl.codcidade = cd.codcidade
                where n.dtfat = :dtfat
                and n.dtcancel is null",array(":dtfat"=>$dtfat));
        }catch(Exception $e){
            return $e;
        }
    }

    /*public static function getPedidoPraca($numped){
        try{
            $sql = new SqlOra();

            return $sql->select("SELECT c.numped, c.codcli, l.cliente, c.vltotal, p.codpraca, l.municcob, p.praca
            from pcpedc c inner join pcclient l on c.codcli = l.codcli
                          inner join pcpraca p on c.codpraca = p.codpraca
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
                from pcpraca c
                where c.praca like :praca and c.situacao like 'A'", array(":praca"=>$praca)
            );

            if(sizeof($ret)>0){
                $codpraca = $ret[0]['CODPRACA'];
                $numregiao = $ret[0]['NUMREGIAO'];
            }else{
                return 'erro praca';
            }

            $sql2 = new SqlOra();
            return $sql2->update("UPDATE pcpedc c set codpraca = :codpraca, numregiao = :numregiao where numped = :numped" ,array(
                ":codpraca"=>$codpraca,
                ":numregiao"=>$numregiao,
                ":numped"=>$numped)
            );

        }catch(Exception $e){
            return $e;
        }
    }   */    
}

?>