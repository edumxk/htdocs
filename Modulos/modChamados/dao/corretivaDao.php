<?php
    //require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/Sql.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');

class CorretivaDao{
    
    public static function getCorretiva($numrat){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT c.numrat, u.codcusto, u.custo, c.tipo,  c.codprod, c.despesa, to_number(c.qt) qt, to_char(c.valor, '9999.99') valor
            FROM ratcorretivai c inner join ratcusto u on c.codcusto = u.codcusto
            WHERE c.numrat = :numrat",
                array(":numrat"=>$numrat)
        );

        return $ret;

    }

    public static function getProduto($codprod){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT codprod, descricao
            from kokar.pcprodut where codprod = :codprod", array(":codprod"=>$codprod)
        );

        return $ret;
    }

    public static function getProdutoByNome($nome){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT codprod, descricao
            from kokar.pcprodut where descricao like :nome", array(":nome"=>$nome)
        );

        return $ret;
    }

    public static function setAcao($tipo, $numrat, $codprod, $despesa, $qt, $valor, $custo){
        $sql = new SqlOra();

        try{
            $ret = $sql->insert('INSERT INTO ratcorretivai (numrat, tipo, codprod, despesa, qt, valor, codcusto)
                values (:numrat, :tipo, :codprod, :despesa, :qt, :valor, :custo)',array(
                    ":numrat"=>$numrat,
                    ":tipo"=>$tipo,
                    ":codprod"=>$codprod,
                    ":despesa"=>$despesa,
                    ":qt"=>$qt,
                    ":valor"=>$valor,
                    ":custo"=>$custo
            ));
            
            return 'ok';
                
        }
        catch(Exception $e){
            return 'erro: '+$e;
        }

    }

    public static function delAcao($numrat, $tipo, $despesa){
        $sql = new SqlOra();



            return $sql->delete("DELETE from ratcorretivai
                where numrat = :numrat and tipo like :tipo and despesa like :despesa",array(
                    ":numrat"=>$numrat,
                    ":tipo"=>$tipo,
                    ":despesa"=>$despesa
                )
            );


    }

    public static function getCustos(){
        $sql = new SqlOra();

        return $sql->select("SELECT codcusto, custo from ratcusto order by custo");
    }

    public static function getTroca($codprod, $numrat){
        $sql = new SqlOra();

        return $sql->select("select i.pvenda
                from rati i
                where i.numrat = :numrat
                and i.codprod = :codprod", array(":numrat"=>$numrat, ":codprod"=>$codprod));
    }

}

?>