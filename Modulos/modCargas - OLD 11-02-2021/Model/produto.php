<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Produto{
    public $cod;
    public $qt;

    public static function getEstoque(){
        $sql = new SqlOra();
        $ret = $sql->select("select ke.codprod cod, 
        to_char(round(ke.qtestger-ke.qtbloqueada, 2), '999999.99') qt
            from kokar.pcest ke inner join kokar.pcprodut kp
                            on ke.codprod = kp.codprod
            where kp.codepto = 10000
            order by ke.codprod"
        );

        return $ret;
    }


    
}

?>

