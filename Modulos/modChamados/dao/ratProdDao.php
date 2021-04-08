<?php
    //require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/Sql.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');

class ratProdDao{
    
    public static function getRat($numrat){
        $sql = new SqlOra();

        return $sql->select("SELECT 
            c.codcli,
            c.cliente,
            c.cgcent,
            c.fantasia,
            c.municent,
            c.estent,
            c.codusur1,
            c.telent,
            rc.*
            from kokar.pcclient c inner join ratc rc on c.codcli = rc.codcli
            where rc.numrat =:numrat", array(
                ":numrat"=>$numrat
        ));
    }

    public static function getLista(){

        $sql = new SqlOra();

        return $sql->select("SELECT 
            rc.numrat,
            c.codcli,
            c.cliente,
            rc.dtabertura
            from kokar.pcclient c inner join ratc rc on c.codcli = rc.codcli"
        );
    }

    public static function getNovoNumeroRat(){
        $sql = new SqlOra();

        return $sql->select("SELECT max(numrat)+1 as numrat from ratc");
    }

    public static function setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor){
        $sql = new SqlOra();


        return $sql->insert("INSERT INTO ratc (numrat, codcli, dtabertura, problema, solicitante, solicitante_tel, pintor, pintor_tel)
            VALUES (:numrat, :codcli, :abertura, :problema, :solicitante, :telsolicitante, :pintor, :telpintor)",array(
                ":numrat"=>$numrat,
                ":codcli"=>$codcli,
                ":abertura"=>$abertura,
                ":problema"=>$problema,
                ":solicitante"=>$solicitante,
                ":telsolicitante"=>$telsolicitante,
                ":pintor"=>$pintor,
                ":telpintor"=>$telpintor           
            )
        );

    }

    public static function getProdRat($numrat){

        $sql = new SqlOra();

        return $sql->select("SELECT ri.codprod, ri.numlote, kl.datafabricacao, kl.dtvalidade, to_char(ri.pvenda, '00000.00') pvenda, ri.qt
            from rati ri left join kokar.pclote kl on ri.numlote = kl.numlote and ri.codprod = kl.codprod
            where ri.numrat = :numrat", array(":numrat"=>$numrat)
        );
        
    }

    public static function getProdInRat($numrat){

        $sql = new SqlOra();

        return $sql->select("SELECT i.codprod,
                p.descricao,
                i.qt,
                i.numlote
        from rati i inner join kokar.pcprodut p on i.codprod = p.codprod
        where i.numrat = :numrat", array(":numrat"=>$numrat)
        );
        
    }

    public static function setProdRat($numrat, $codprod, $numlote, $qt, $datafabricacao, $dtvalidade, $pvenda){
        $sql = new SqlOra();

        if($numlote==""){
            $numlote = 0;
        }

            $ret = $sql->select("SELECT count(codprod) cont from rati where numrat = :numrat 
                    and codprod = :codprod
                    and numlote = :numlote", array(
                        ":numrat"=>$numrat,
                        ":codprod"=>$codprod,
                        ":numlote"=>$numlote
            ));

            //$retId = $sql->select("SELECT count(codprod) cont from rati where numrat = :numrat", array(":numrat"=>$numrat));

            //echo json_encode($ret);
            $cont = $ret[0]['CONT'];
            
            if($cont > 0){
                return "existe";
            }else{
                //echo "okokok";
                $sql = new SqlOra();


                return $sql->insert("INSERT INTO rati (id, numrat, codprod, numlote, qt, pvenda)
                    values (:id, :numrat, :codprod, :numlote, :qt, :pvenda)", array(
                        ":id"=>$id+1,
                        ":numrat"=>$numrat,
                        ":codprod"=>$codprod,
                        ":numlote"=>$numlote,
                        ":qt"=>$qt,
                        ":pvenda"=>$pvenda)
                );
            }



    }

    public static function delProdRat($numrat, $codprod, $numlote){
        $sql = new SqlOra();

        
        return $sql->delete("DELETE from rati
            where numrat = :numrat and codprod = :codprod and numlote = :numlote",array(
                ":numrat"=>$numrat,
                ":codprod"=>$codprod,
                ":numlote"=>$numlote
            )
        );
    }

    public static function getProdByLote($numlote){
        $sql = new SqlOra();


        $ret = $sql->select("SELECT p.codprod, 
                p.descricao produto, 
                l.numlote, 
                l.datafabricacao, 
                l.dtvalidade
            FROM kokar.pcprodut p INNER JOIN kokar.pclote l ON p.codprod = l.codprod
            WHERE p.codepto = 10000
            and l.numlote = :numlote", array(":numlote"=>$numlote)
        );

        if(sizeof($ret)>0){
            return $ret[0];
        }else{
            return null;
        }
    }

    public static function getPvendaFromRatI($numrat, $codprod){
        $sql = new SqlOra();
        $ret = $sql->select("SELECT pvenda
            from rati i
            where i.numrat = :numrat
            and i.codprod = :codprod", array(":numrat"=>$numrat, ":codprod"=>$codprod)
        );

        return $ret[0]['PVENDA'];

    }

    public static function getPvendaByCod($codcli, $codprod){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT max(numnota) numnota
        from kokar.pcmov m inner join kokar.pcprodut p on m.codprod = p.codprod
        where m.codcli = :codcli
        and p.codcategoria in (select codcategoria from kokar.pcprodut where codprod = :codprod)
        and p.embalagem in (select embalagem from kokar.pcprodut where codprod = :codprod)
        and m.codoper in ('S','SB')", array(":codcli"=>$codcli, ":codprod"=>$codprod)
        );
        //echo 'teste';

        if($ret[0]['NUMNOTA']==null){
            $sql = new SqlOra();

            $val = $sql->select("select p.pvenda1 pvenda
                from kokar.pctabpr p
                where p.numregiao = (select l.numregiaocli from kokar.pcclient l where l.codcli = :codcli)
                and p.codprod = :codprod", array(":codcli"=>$codcli, ":codprod"=>$codprod)
            );
            return $val[0]['PVENDA'];
        }else{

            $sql = new SqlOra();

            $val = $sql->select("SELECT max(case m.codoper when 'S' then m.punit when 'SB' then m.pbonific end) pvenda
            from kokar.pcmov m inner join kokar.pcprodut p on m.codprod = p.codprod
            where m.numnota = :numnota
              and p.codcategoria in (select codcategoria from kokar.pcprodut where codprod = :codprod)
              and p.embalagem in (select embalagem from kokar.pcprodut where codprod = :codprod)", array(":numnota"=> $ret[0]['NUMNOTA'],":codprod"=>$codprod)
            );

            return $val[0]['PVENDA'];
        }
    }
    /**Clone da função anterior */
    public static function getPvendaByLote($codcli, $numlote){
        $sql = new SqlOra();

        $val = $sql->select("SELECT km.punit+nvl(km.vlfrete,0) as PVENDA
        from kokar.pcmov km 
        where km.codprod = (select codprod from kokar.pclote l where l.numlote = :numlote) 
        and km.dtmov >= (select max(o.dtlanc) from kokar.pcopc o where o.numlote = :numlote and o.dtcancel is null) 
        and km.codoper = 'S'
        and km.codcli = :codcli
        and rownum = 1
        order by km.numnota desc", array(":numlote"=> $numlote,":codcli"=>$codcli)
        );

        return $val[0]['PVENDA'];
    }



    public static function getProdutoByCod($codprod){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT codprod, descricao produto, 0 numlote, '--' datafabricacao, '--' dtvalidade
            from kokar.pcprodut p
            where p.codprod = :codprod", array(":codprod"=>$codprod)
        );
        //var_dump($ret[0]);
        return $ret;
    }

    public static function getProdutoByNome($produto){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT codprod, descricao produto,0 numlote, '--' datafabricacao, '--' dtvalidade
            from kokar.pcprodut p
            where p.descricao = :produto", array(":produto"=>$produto)
        );
        return $ret[0];
    }
    



}

?>