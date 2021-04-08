<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/corretivaDao.php');

class Corretiva{

    public $numrat;
    public $codcusto;
    public $custo;
    public $tipo;
    public $codprod;
    public $despesa;
    public $qt;
    public $valor;

    public static function getCorretiva($numrat){
        $lista = [];

        $ret = CorretivaDao::getCorretiva($numrat);

        if(sizeof($ret)>0){
            for($i = 0; $i<sizeof($ret); $i++){
                $row = $ret[$i];
                $co = new Corretiva();
                $co->numrat = $row['NUMRAT'];
                $co->codcusto = $row['CODCUSTO'];
                $co->custo = $row['CUSTO'];
                $co->tipo = $row['TIPO'];
                $co->codprod = $row['CODPROD'];
                $co->despesa = $row['DESPESA'];
                $co->qt = $row['QT'];
                $co->valor = $row['VALOR'];

                array_push($lista, $co);
            }
        }

        return $lista;
    }

}




?>