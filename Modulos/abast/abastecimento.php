<?php
require_once 'funcoes.php';
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Abastecimento
{
    public static function atualizaAbast($lista)
    {
        $sql = new SqlOra();

        return $sql->insert("UPDATE paralelo.abast set estfisico = :estfisico,
                                                qtdabast = :qtdabast,
                                                estfinal = :estfinal,
                                                dataabast = :dataabast,
                                                respabast = :respabast,
                                                horaabast = :horaabast
                            where numreq = :numreq", array(
            ":numreq" => $lista['numreq'],
            ":estfisico" => $lista['estfisico'],
            ":qtdabast" => $lista['qtdabast'],
            ":dataabast" => $lista['dataabast'],
            ":estfinal" => $lista['estfinal'],
            ":horaabast" => $lista['horaabast'],
            ":respabast" => $lista['respabast']
        ));
    }

    public static function getReq()
    {
        $sql = new SqlOra();
        return $sql->select("SELECT max(numreq) numreq from paralelo.abast");
    }

    public static function iniciarAbast($lista)
    {
        //Funcoes::printList($lista); 
        $sql = new SqlOra();                   //(numreq, datahora, codprod, setor, solicitante, qtdsolicitada)

        return $sql->select("SELECT incluir_abast(:numreq, (select sysdate from dual), :codprod, :setor, :solicitante, :qtdsolicitada,
        (SELECT NVL(QTESTGER-QTBLOQUEADA-QTRESERV,0) AS DISP from KOKAR.PCEST where codprod = :codprod),
        (SELECT NVL(QTBLOQUEADA,0) AS BLOQ from KOKAR.PCEST where codprod = :codprod),
        (SELECT NVL(QTRESERV,0) AS RESERV from KOKAR.PCEST where codprod = :codprod),
        (SELECT NVL(QTESTGER,0) AS TOTAL from KOKAR.PCEST where codprod = :codprod)) FROM DUAL", array(
            ":numreq" => $lista['numreq'],
            ":codprod" => $lista['codprod'],
            ":qtdsolicitada" => $lista['qtdsolicitada'],
            ":setor" => $lista['setor'],
            ":solicitante" => $lista['solicitante']
        ));
    }
}
