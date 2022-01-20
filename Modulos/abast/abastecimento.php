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
                                                horaabast = :horaabast,
                                                tipo = :tipo
                            where numreq = :numreq", array(
            ":numreq" => $lista['numreq'],
            ":estfisico" => $lista['estfisico'],
            ":qtdabast" => $lista['qtdabast'],
            ":dataabast" => $lista['dataabast'],
            ":estfinal" => $lista['estfinal'],
            ":horaabast" => $lista['horaabast'],
            ":respabast" => $lista['respabast'],
            ":tipo" => $lista['tipo']
            
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

        return $sql->select("SELECT incluir_abast(:numreq, (select sysdate from dual), :codprod, :setor, :solicitante, :qtdsolicitada, :qtdsolicitada2,
        (SELECT NVL(QTESTGER-QTBLOQUEADA-QTRESERV,0) AS DISP from KOKAR.PCEST where codprod = :codprod),
        (SELECT NVL(QTBLOQUEADA,0) AS BLOQ from KOKAR.PCEST where codprod = :codprod),
        (SELECT NVL(QTRESERV,0) AS RESERV from KOKAR.PCEST where codprod = :codprod),
        (SELECT NVL(QTESTGER,0) AS TOTAL from KOKAR.PCEST where codprod = :codprod)) FROM DUAL", array(
            ":numreq" => $lista['numreq'],
            ":codprod" => $lista['codprod'],
            ":qtdsolicitada" => $lista['qtdsolicitada'],
            ":qtdsolicitada2" => $lista['qtdsolicitada2'],
            ":setor" => $lista['setor'],
            ":solicitante" => $lista['solicitante']
        ));
    }
    public static function buscaProd($codprod)
    {
        $sql = new SqlOra(); 

        return $sql->select("SELECT descricao from kokar.pcprodut where codprod = :codprod", [":codprod" => $codprod]);
    }
    public static function incluirDensidade($codprod, $densidade, $emb1, $emb2, $demb3)
    {
    $sql = new SqlOra(); 

    return $sql->select("SELECT paralelo.incluir_densidade(:codprod, :densidade, :emb1, :emb2, :emb3) from dual
    ", [":codprod" => $codprod, ":densidade" => $densidade, ":emb1" => $emb1, ":emb2" => $emb2, ":emb3" => $demb3]);
    }
    public static function buscaReq($numreq)
    {
        $sql = new SqlOra(); 
    return $sql->select("SELECT numreq, p.codprod, p.descricao, a.solicitante, A.SETOR, a.qtdsolicitada, a.qtsistotal, a.qtsisdisp from paralelo.abast a
    inner join kokar.pcprodut p on p.codprod = a.codprod
    where numreq = :numreq", [":numreq" => $numreq]);
    }
    public static function buscaResina($data)
    {
        $sql = new SqlOra(); 
    return $sql->select("SELECT p.codprod, p.descricao, a.data, a.tanque, a.peso, a.responsavel, a.dtlanc from paralelo.abastres a
    inner join kokar.pcprodut p on p.codprod = a.codprod
    where extract(month from a.data) = extract(month from TO_DATE(:data))
    and extract(year from a.data) = extract(year from TO_DATE(:data))
    order by data", [":data" => $data]);
    }
    public static function buscaProdEst()
    {
        $sql = new SqlOra(); 
    return $sql->select("SELECT a.codprod, p.descricao, a.densidade, a.densidade, a.qt1, a.qt2, a.qt3 from paralelo.abastest a
    inner join kokar.pcprodut p on p.codprod = a.codprod");
    }
    public static function deletaProdEst($codprod)
    {
        $sql = new SqlOra(); 
        return $sql->select("DELETE from paralelo.abastest where codprod = :codprod", [":codprod" => $codprod]);
    }
    public static function peso($codprod)
    {
        $sql = new SqlOra(); 
        return $sql->select("SELECT qt1 from paralelo.abastest where codprod = :codprod", [":codprod" => $codprod]);
    }
    public static function incluirResina($data, $tanque, $codprod, $peso, $responsavel)
    {
    $sql = new SqlOra(); 
    $temp = $sql->select("SELECT data, tanque from paralelo.abastres");
    $valid = 0;
        foreach($temp as $a){
            if ($a['DATA'] == $data && $a['TANQUE'] == $tanque)
                $valid = 1;
        }
        if($valid == 0){
            $sql->select("SELECT paralelo.incluir_abastres(:data1, :tanque, :codprod, :peso, :responsavel) from dual", [':data1'=>$data, ':codprod'=>$codprod, ':tanque'=>$tanque, ':peso'=>$peso, ':responsavel'=>$responsavel]);
            return "LANÇAMENTO DIA $data , RESINA $tanque INSERIDA COM SUCESSO!";
        }else 
            return "A DATA $data JÁ FOI LANÇADA PARA A RESINA $tanque, VERIFIQUE!";
    }
}
