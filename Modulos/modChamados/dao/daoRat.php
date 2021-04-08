<?php
//require_once("../../model/Sql.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

class Rat{

    public static function getListaRat(){
        $sql = new SqlOra();

        return $sql->select(
            "SELECT rc.numrat, 
                    TO_CHAR(rc.dtabertura,'DD/MM/YYYY') as dtabertura,
                    TO_CHAR(rc.dtencerramento,'DD/MM/YYYY') as dtencerramento,
                    rc.codcli,
                    kc.cliente,
                    rc.prodfinal,
                    rc.alabfinal,
                    rc.acaofinal,
                    rc.dirfinal,
                    rc.atec,
                    rc.adir
            from ratc rc inner join kokar.pcclient kc on rc.codcli = kc.codcli
            order by rc.numrat"
        );
    }

    public static function getListaRatBusca($lote, $de, $ate){
        $sql = new SqlOra();
        if($lote == ''){
            try{
                return $sql->select(
                    "SELECT rc.numrat, 
                            TO_CHAR(rc.dtabertura,'DD/MM/YYYY') as dtabertura,
                            TO_CHAR(rc.dtencerramento,'DD/MM/YYYY') as dtencerramento,
                            rc.codcli,
                            kc.cliente,
                            rc.prodfinal,
                            rc.alabfinal,
                            rc.acaofinal,
                            rc.dirfinal,
                            rc.atec,
                            rc.adir
                    from ratc rc inner join kokar.pcclient kc on rc.codcli = kc.codcli
                    where dtencerramento between :de and :ate
                    order by rc.numrat",array(":de" => $de, ":ate" => $ate)
                );
            }
            catch(Exception $e){
                echo 'erro: '.$e;
            }
        }else{
            try{
                //echo 'teste';
                return $sql->select(
                    "SELECT rc.numrat, 
                            TO_CHAR(rc.dtabertura,'DD/MM/YYYY') as dtabertura,
                            TO_CHAR(rc.dtencerramento,'DD/MM/YYYY') as dtencerramento,
                            rc.codcli,
                            kc.cliente,
                            rc.prodfinal,
                            rc.alabfinal,
                            rc.acaofinal,
                            rc.dirfinal,
                            rc.atec,
                            rc.adir,
                            ri.numlote
                    from ratc rc inner join kokar.pcclient kc on rc.codcli = kc.codcli
                                 inner join rati ri on rc.numrat = ri.numrat
                    where dtabertura between :de and :ate
                      and ri.numlote like :lote
                    order by rc.numrat",array(":de" => $de, ":ate" => $ate, ":lote" => $lote)
                );
            }
            catch(Exception $e){
                echo 'erro: '.$e;
            }
        }
    }
    
    public static function getListaRatByCli($codcli){
        $sql = new SqlOra();

        return $sql->select(
            "SELECT rc.numrat, 
                    TO_CHAR(rc.dtabertura,'DD/MM/YYYY') as dtabertura,
                    TO_CHAR(rc.dtencerramento,'DD/MM/YYYY') as dtencerramento,
                    rc.codcli,
                    kc.cliente,
                    rc.prodfinal,
                    rc.alabfinal,
                    rc.acaofinal,
                    rc.dirfinal,
                    rc.atec,
                    rc.adir,
                    p.patologia
            from ratc rc inner join kokar.pcclient kc on rc.codcli = kc.codcli
                         inner join ratalab l on rc.numrat = l.numrat
                         inner join ratpatologia p on l.codpatologia = p.codpatologia
            where rc.codcli = :codcli
            order by rc.numrat",array(":codcli" => $codcli)
        );
    }

    public static function getRat($numrat){
        $sql = new SqlOra();

        return $sql->select(
            "SELECT rc.numrat, 
                    TO_CHAR(rc.dtabertura,'DD/MM/YYYY') as dtabertura,
                    TO_CHAR(rc.dtencerramento,'DD/MM/YYYY') as dtencerramento,
                    rc.codcli,
                    rc.solicitante,
                    rc.tel_solicitante,
                    rc.pintor,
                    rc.tel_pintor,
                    rc.problema,
                    --produtos,
                    rc.prodfinal,
                    rc.dtprodfinal,
                    rc.alabfinal,
                    rc.dtalabfinal,
                    rc.acaofinal,
                    rc.dtacaofinal,
                    rc.dirfinal,
                    rc.dtdirfinal,
                    rc.atec,
                    rc.adir,
                    ku.codusur,
                    ku.nome,
                    ku.telefone1,
                    kc.telent,
                    kc.cliente,
                    kc.emailnfe as email,
                    kc.cgcent,
                    kc.fantasia,
                    kcd.nomecidade,
                    kcd.uf,
                    rc.useraprova,
                    u.nome aprovacao,
                    ul.nome aprovalab,
                    ru.data dtrejeicao,
                    ru.motivo,
                    u2.nome userreprovacao

            from ratc rc inner join kokar.pcclient kc on rc.codcli = kc.codcli
                        inner join kokar.pcusuari ku on kc.codusur1 = ku.codusur
                        inner join kokar.pccidade kcd on kcd.codcidade = kc.codcidade
                        left join ratuser u on rc.useraprova = u.coduser
                        left join ratalab al on rc.numrat = al.numrat
                        left join ratuser ul on al.coduser = ul.coduser
                        left join ratrejeicao ru on ru.numrat = rc.numrat
                        left join ratuser u2 on ru.coduser = u.coduser
            where rc.numrat = :numrat
            order by rc.numrat",array(":numrat" => $numrat)
        );
    }

    public static function getALab($numrat){
        $sql = new SqlOra();

        $max = $sql->select("SELECT max(id) n FROM ratalab where numrat = :numrat",
            array(":numrat"=>$numrat)
        );
        
        if($max[0]['N']>0){
             

            $sql1 = new SqlOra();
            
            $ret = $sql1->select("SELECT rl.id, 
                    rl.numrat, 
                    --rl.data, 
                    TO_CHAR(rl.data, 'DD/MM/YYYY') as data,
                    rl.parecer, 
                    rl.codpatologia, 
                    rp.patologia,
                    ru.coduser, 
                    ru.nome,
                    rl.conforme, 
                    rl.final
                FROM ratalab rl inner join ratuser ru on rl.coduser = ru.coduser
                                left join ratpatologia rp on rl.codpatologia = rp.codpatologia
                WHERE numrat = :numrat and id = :id",
                array(":numrat"=>$numrat, ":id"=>$max[0]['N'])
            );

            
            return $ret;
        }else{
            
            return null;
        }
    }

    public static function getProdRat($numrat){

        $sql = new SqlOra();

        return $sql->select("SELECT i.codprod, 
                p.descricao as produto, 
                i.numlote, 
                l.datafabricacao, 
                l.dtvalidade, 
                to_char(i.pvenda, '9999999.999') pvenda, 
                i.qt
             from kokar.pcprodut p inner join rati i on p.codprod = i.codprod
                             left join kokar.pclote l on i.numlote = l.numlote and i.codprod = l.codprod
             where i.numrat = :numrat and p.codepto = 10000", array(":numrat"=>$numrat)
        );
        
    }

    public static function newALab($numrat, $data, $parecer, $codpatologia, $conforme, $coduser, $final){

        $final = 'S';

        $sql = new SqlOra();

        $id = $sql->select("SELECT max(id)id from ratalab
            where numrat = :numrat", array(":numrat"=>$numrat)
        );

        $np = $id[0]['ID'];

        try{

            return $sql->insert("INSERT INTO ratalab (id, numrat, data, parecer, codpatologia, conforme, coduser, final)
            values (:id, :numrat, to_date(:data, 'yyyy/mm/dd HH24:MI:SS'), :parecer, :codpatologia, :conforme, :coduser, :final)", array(
                    ":id"=>$np+1,
                    ":numrat"=>$numrat,
                    ":data"=>$data,
                    ":parecer"=>$parecer,
                    ":codpatologia"=>$codpatologia,
                    ":conforme"=>$conforme,
                    ":coduser"=>$coduser,
                    ":final"=>$final)
            );
        }catch (Exception $e) {
            return "erro";
        }

    }

    public static function finalizarProd($numrat){
        $sql = new SqlOra();

        $qt = $sql->select("SELECT count(codprod) qt from rati i where i.numrat = :numrat",array(":numrat"=>$numrat));

        echo json_encode($qt[0]['QT']);

        if($qt[0]['QT']>0){
            return $sql->insert("UPDATE ratc set prodfinal = 'S', dtprodfinal = sysdate
                where numrat = :numrat", array(
                ":numrat"=>$numrat)
            );
        }else{
            return null;
        }


    }

    public static function reabrirRat($numrat){
        $sql = new SqlOra();
        return $sql->insert("UPDATE ratc 
                set prodfinal = 'N', dtprodfinal = sysdate,
                    alabFinal = 'N',
                    useraprova = 0,
                    acaoFinal = 'N',
                    dirFinal = 'N',
                    dtdirfinal = null,
                    dtencerramento = null,
                    adir = 'P',
                    atec = 'P'
                where numrat = :numrat", array(
                ":numrat"=>$numrat)
        );
    }

    public static function aprovarRat($numrat, $coduser){
        $sql = new SqlOra();
        return $sql->insert("UPDATE ratc 
                set dirFinal = 'S',
                    dtencerramento = sysdate,
                    dtdirFinal = sysdate,
                    ADir = 'S',
                    useraprova = :coduser
                where numrat = :numrat", array(
                ":numrat"=>$numrat,
                ":coduser"=>$coduser)
        );
    }

    public static function reprovarRat($numrat, $coduser, $motivo){
        $sql = new SqlOra();

        $sql->insert("UPDATE ratc set acaofinal = 'N', dtacaofinal = null
                where numrat = :numrat", array(
                ":numrat"=>$numrat)
        );

        $id = $sql->select("SELECT nvl(max(id)+1,1) id from ratrejeicao where numrat = :numrat",
            array(":numrat"=>$numrat)
        );

        return $sql->insert("INSERT into ratrejeicao 
            values (:numrat, sysdate, :coduser, :motivo, :id)", array(
            ":numrat"=>$numrat, ":coduser"=>$coduser, ":motivo"=>$motivo, ":id"=>$id[0]['ID'])
        );
        
    }



    public static function finalizarALab($numrat){
        $sql = new SqlOra();

        $numparecer = $sql->select("SELECT max(id)id from ratalab
            where numrat = :numrat", array(":numrat"=>$numrat)
        );

        $np = $numparecer[0]['ID'];

        $sql->insert("UPDATE ratalab 
            set final = 'S'
            where numrat = :numrat and numparecer = :numparecer",
            array(":numrat"=>$numrat, ":id"=>$np)
        );

        $sql->insert("UPDATE ratc 
            set alabfinal = 'S'
            where numrat = :numrat",
            array(":numrat"=>$numrat)
        );
    }


    // public static function reabrirALab($numrat){
    //     $sql = new SqlOra();

    //     $numparecer = $sql->select("SELECT max(id)id from ratalab
    //         where numrat = :numrat", array(":numrat"=>$numrat)
    //     );

    //     $np = $numparecer[0]['ID'];

    //     $sql->insert("UPDATE ratalab 
    //         set final = 'N'
    //         where numrat = :numrat and id = :id",
    //         array(":numrat"=>$numrat, ":id"=>$np)
    //     );

    //     return $sql->insert("UPDATE ratc 
    //         set alabFinal = 'N'
    //         where numrat = :numrat",
    //         array(":numrat"=>$numrat)
    // );
    // }

    public static function finalizarAcao($numrat){
        $sql = new SqlOra();
        return $sql->insert("UPDATE ratc set acaofinal = 'S', dtacaofinal = sysdate
                where numrat = :numrat", array(
                ":numrat"=>$numrat)
        );
    }

    // public static function reabrirAcao($numrat){
    //     $sql = new SqlOra();
    //     return $sql->insert("UPDATE ratc set acaofinal = 'N', dtacaofinal = sysdate
    //             where numrat = :numrat", array(
    //             ":numrat"=>$numrat)
    //     );
    // }

    public static function setParecer($numrat, $parecer){
        $sql = new SqlOra();

        Rat::finalizarAcao($numrat);
        $dirFinal = 'P';

        if($parecer == 'N'){
            return $sql->insert("UPDATE ratc 
                    set atec = :parecer,
                        dirfinal = :dirfinal,
                        dtencerramento = to_date(sysdate)
                    where numrat = :numrat", array(
                    ":parecer"=>$parecer,
                    ":numrat"=>$numrat,
                    ":dirfinal"=>$dirFinal)
            );
        }else{
            return $sql->insert("UPDATE ratc 
                    set atec = :parecer
                    where numrat = :numrat", array(
                    ":parecer"=>$parecer, ":numrat"=>$numrat)
            );
        }



    }








    ///OLD
    public static function getLista(){
        /*
        Utilizado por:
        --listaRatControle.php
        */
        $sql = new Sql();

        return $sql->select("SELECT numrat, 
                dtabertura, 
                dtencerramento,
                codcli,
                cliente,
                rca,
                acaofinal
            from ratc c"
        );
    }

    public static function getNovoNumeroRat(){
        $sql = new Sql();

        return $sql->select("SELECT max(numrat)+1 as numrat from ratc");
    }

    public static function setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor){
        $sql = new Sql();


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

    /*public static function getProdRat($numrat){

        $sql = new Sql();

        return $sql->select("SELECT i.codprod, p.produto, i.numlote, l.dtfabricacao, l.dtvalidade, p.pvenda, i.qt
            from pcprodut p inner join rati i on p.codprod = i.codprod
                            inner join pclote l on i.numlote = l.numlote
            where i.numrat = :numrat and p.codepto = 10000", array(":numrat"=>$numrat)
        );
        
    }*/

    public static function setProdRat($numrat, $codprod, $numlote, $qt){
        $sql = new Sql();


        $ret = $sql->select("SELECT count(codprod) cont from rati 
            where numrat = :numrat 
              and codprod = :codprod
              and numlote = :numlote", array(
                    ":numrat"=>$numrat,
                    ":codprod"=>$codprod,
                    ":numlote"=>$numlote
        ));

        $cont = $ret[0]['cont'];

        if($cont > 0){
            return "existe";
        }else{

            $sql = new Sql();

            return $sql->insert("INSERT INTO rati (numrat, codprod, numlote, qt)
                values (:numrat, :codprod, :numlote, :qt)", array(
                    ":numrat"=>$numrat,
                    ":codprod"=>$codprod,
                    ":numlote"=>$numlote,
                    ":qt"=>$qt)
            );
        }
    }

    public static function delProdRat($numrat, $codprod, $numlote){
        $sql = new Sql();

        return $sql->delete("DELETE from rati
            where numrat = :numrat and codprod = :codprod and numlote = :numlote",array(
                ":numrat"=>$numrat,
                ":codprod"=>$codprod,
                ":numlote"=>$numlote
            )
        );
    }

    public static function getATec($numrat){
        $sql = new Sql();

        $max = $sql->select("SELECT max(numparecer) n FROM ratatec where numrat = :numrat",
            array(":numrat"=>$numrat)
        );

        if($max[0]['n']>0){
            

            $sql1 = new Sql();
            
            $ret = $sql1->select("SELECT numparecer, numrat, data, parecer, testes, r.codusur, u.nome, procedente, final
            FROM ratatec r inner join usuario u on r.codusur = u.codusur
            WHERE numrat = :numrat and numparecer = :numparecer",
                array(":numrat"=>$numrat, ":numparecer"=>$max[0]['n'])
            );

            return $ret;
        }else{
            return null;
        }
    }

    public static function newATec($numrat, $data, $parecer, $testes, $procedente, $codusur, $final){

        $sql = new Sql();

        return $sql->insert("INSERT INTO ratatec (numrat, data, parecer, testes, procedente, codusur, final)
        values (:numrat, :data, :parecer, :testes, :procedente, :codusur, :final)", array(
                ":numrat"=>$numrat,
                ":data"=>$data,
                ":parecer"=>$parecer,
                ":testes"=>$testes,
                ":procedente"=>$procedente,
                ":codusur"=>$codusur,
                ":final"=>$final)
        );

    }

    public static function getPatologias(){
        $sql = new SqlOra();

        return $sql->select("SELECT codpatologia, patologia FROM ratpatologia order by patologia");
    }

    public static function getPatologia($codpatologia){
        $sql = new SqlOra();

        return $sql->select("SELECT patologia FROM ratpatologia where codpatologia = :codpatologia", array(
            ":codpatologia"=>$codpatologia
        ));
    }

    public static function newPatologia($patologia){
        $sql = new SqlOra();

        $patologia = strtoupper($patologia);


        $cod = $sql->select("SELECT max(codpatologia) cod from ratpatologia");

        return $sql->insert("INSERT into ratpatologia (codpatologia, patologia) values (:codpatologia, :patologia)",array(
            ":codpatologia"=>$cod[0]['COD']+1,
            ":patologia"=>$patologia
        ));
    }


    public static function logAprova($codUser, $numRat, $acao){
        $pasta = $_SERVER["DOCUMENT_ROOT"] . "/Modulos/modChamados/Model/Log/";
        $myfile = fopen($pasta."logAprova.txt", "a") or die("Unable to open file!");
        fwrite($myfile, "Usuario: ".$codUser." - Rat: ".$numRat." - ".utf8_encode("Ação: ").$acao);
        fclose($myfile);
    }

    

}

?>