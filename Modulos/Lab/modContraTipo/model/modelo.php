<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

class ContraTipoModel{

    public static function getFiltros()
    {
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT DISTINCT  P.CODCATEGORIA , CATEGORIA, p.codsubcategoria, sg.subcategoria
            FROM KOKAR.PCPRODUT P 
            INNER JOIN KOKAR.PCCATEGORIA G 
            ON G.CODSEC = P.CODSEC 
            AND P.CODCATEGORIA = G.CODCATEGORIA
            inner join KOKAR.pcsubcategoria sg on sg.codsubcategoria= p.codsubcategoria
            and sg.codcategoria = p.codcategoria
            INNER JOIN KOKAR.PCCOMPOSICAO C ON C.CODPRODMASTER = P.CODPROD
            AND METODO = 1
            WHERE P.CODSEC = 10013 AND CODEPTO = 10001
            ORDER BY CATEGORIA, subcategoria");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getProdutos($codprod, $metodo)
    {
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT C.CODPRODMASTER, C.CODPROD, C.METODO, P.DESCRICAO , CA.CATEGORIA, CA.CODCATEGORIA
            FROM KOKAR.PCCOMPOSICAO C 
            INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = C.CODPRODMASTER
            INNER JOIN KOKAR.PCCATEGORIA CA ON CA.CODSEC = P.CODSEC AND CA.CODCATEGORIA = P.CODCATEGORIA
            WHERE C.METODO = $metodo AND C.CODPROD = $codprod --and c.codprodmaster < 1000
            ORDER BY P.DESCRICAO");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    
}                                         