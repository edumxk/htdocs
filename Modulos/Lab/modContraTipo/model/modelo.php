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
            WHERE C.METODO = $metodo AND C.CODPROD = $codprod
            ORDER BY P.DESCRICAO");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function getProduto()
    {
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT CODPROD, DESCRICAO
            FROM KOKAR.PCPRODUT P
            WHERE CODEPTO = 10001 AND CODSEC NOT IN (10013)
            ORDER BY DESCRICAO");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function getProduto2($id)
    {
        $sql = new SqlOra();
        try{
            return $sql->select("SELECT I.ID, I.CODPROD, P.DESCRICAO
            FROM CONTRATIPOI I
            INNER JOIN KOKAR.PCPRODUT P ON I.CODPROD = P.CODPROD
            WHERE I.ID = $id
            ORDER BY DESCRICAO");
             
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
    public static function setListaAlterados(array $arrayc, array $arrayi)
    {   
        $sql = new SqlOra();
        try{
            echo $sql->insert("INSERT INTO CONTRATIPOC (CODPROD1, CODPROD2, CODFUNALTER, DATA, METODO)
             VALUES(:codprod1, :codprod2, :codfun, :data, :metodo)",
             [':codprod1' => $arrayc['codprod1'], ':codprod2' => $arrayc['codprod2'], ':codfun' => $arrayc['codfun'], ':data' => $arrayc['data'], ':metodo' => $arrayc['metodo']]);
            $id =  $sql->select("SELECT MAX(ID) ID FROM CONTRATIPOC")[0]["ID"];
            try{
                foreach($arrayi as $p):
                    $sql->insert("INSERT INTO CONTRATIPOI (ID, CODPROD) 
                    VALUES($id, :codprod)", [':codprod' => $p ]);
                endforeach;
            
            }catch(Exception $e){
                echo 'Produtos Itens - Exceção capturada: ',  $e->getMessage(), "\n";
            }
        }catch(Exception $e){
            echo 'Cabeçalho - Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
    public static function getAlteracoes(){
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT C.ID, C.CODPROD1, C.CODPROD2, C.DATA, R.NOME, C.METODO FROM PARALELO.CONTRATIPOC C
            INNER JOIN PARALELO.RATUSER R
            ON R.CODUSER = C.CODFUNALTER
            order by id desc");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function getFormula($codprod, $metodo){
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT c.codprodmaster, p2.descricao descricaomaster, c.codprod, p.descricao, c.qt, numseq, fracaoumida, percformulacaototal percentual
            from KOKAR.pccomposicao c
            inner join KOKAR.pcprodut p on p.codprod = c.codprod
            inner join KOKAR.pcprodut p2 on p2.codprod = c.codprodmaster
            where c.codprodmaster = $codprod and metodo = $metodo
           order by numseq");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    
}                                         