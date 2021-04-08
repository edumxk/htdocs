<?php

//require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/Sql.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');

class NovaRatDao{
    
    public static function getRat($numrat){
        /*
        Utilizado por:

        */
        $sql = new Sql();

        return $sql->select("SELECT c.*
            from ratc c
            where c.numrat =:numrat", array(
                ":numrat"=>$numrat
            )
        );
    }


    public static function getClienteByCod($codcli){
        /*Utilizado por:
        --novaRatControle.php
        */

        //Substituir por SqlOra
        $sql = new SqlOra();

        return $sql->select("SELECT 
                c.codcli,
                c.cliente,
                c.fantasia,
                c.cgcent,
                d.nomecidade,
                d.uf,
                c.telent,
                u.codusur,
                u.nome,
                u.telefone1
            from kokar.pcclient c inner join kokar.pcusuari u on c.codusur1 = u.codusur
                            inner join kokar.pccidade d on c.codcidade = d.codcidade
            where c.codcli = :codcli", array(
                ":codcli"=>$codcli
            )
        );

    
    }


    public static function getCliente($codcli){
        $sql = new SqlOra();

        return $sql->select("SELECT 
            c.codcli,
            c.cliente,
            c.fantasia,
            c.cgcent,
            c.municent,
            c.estent,
            c.telent,
            u.codusur,
            u.nome,
            u.telefone1,
            d.nomecidade,
            d.uf
            from pcclient c inner join pcusuari u on c.codusur1 = u.codusur
                            inner join pccidade d on c.codcidade = d.codcidade
            and c.codcli = :codcli",array(":codcli"=>$codcli)
        );
    }

    public static function getClientes(){
        $sql = new SqlOra();

        return $sql->select("SELECT
            c.codcli,
            c.cliente,
            c.fantasia,
            c.cgcent,
            d.nomecidade,
            d.uf,
            u.nome
            from kokar.pcclient c inner join kokar.pcusuari u on c.codusur1 = u.codusur
                                inner join kokar.pccidade d on c.codcidade = d.codcidade
            where c.codcli>4
        ");
    }





    public static function getNovoNumeroRat(){
        $sql = new SqlOra();

        return $sql->select("SELECT nvl(max(numrat),0)+1 as numrat from ratc");
    }


    public static function setNovaRat($numrat, $codcli, 
        $solicitante, $solicitante_tel, $pintor, $pintor_tel, $problema){

        try{
            $sql = new SqlOra();

        
        
            return $sql->insert("INSERT 
                INTO ratc (numrat, dtabertura, codcli, solicitante, tel_solicitante, pintor, tel_pintor, problema) 
                VALUES (:numrat, sysdate, :codcli, :solicitante, :solicitante_tel, :pintor, :pintor_tel, :problema)",
                array(":numrat"=>$numrat, ":codcli"=>$codcli,
                    ":solicitante"=>$solicitante, ":solicitante_tel"=>$solicitante_tel,
                    ":pintor"=>$pintor, ":pintor_tel"=>$pintor_tel, ":problema"=>$problema)
            );
        }catch(Exception $e){
            return $e;
        }
    }




    

}

?>