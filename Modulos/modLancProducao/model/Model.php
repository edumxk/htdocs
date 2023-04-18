<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Model{

public static function getOP():array
{
    $sql = new sqlOra();
    $ret= [];
    try{
        $ret = $sql->select("SELECT * from kokar.kokar_view_producao_custo where posicao not in ('F', 'C')",[]);
    }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
    }
    return $ret;
}

public static function getAjustes($numop):array
{
    $sql = new sqlOra();
    $ret= [];
    try{
        $ret = $sql->select("SELECT numop, a.codprod, p.descricao, p.codepto, a.punit, a.codoper, case when a.codoper = 'S'
        then qt
    when a.codoper = 'E'
        then qt*-1 end as qt,
        case when a.codoper = 'S'
          then (qt*punit)
            when a.codoper = 'E'
              then (qt*punit*(-1))
                end as valor
        from kokar.pcavulsa a
        inner join kokar.pcprodut p
        on p.codprod = a.codprod
        where codepto = 10001 and numop = :numop
        and a.dtlanc > '01/01/2022'
        order by descricao",[':numop' => $numop]);
    }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
    }

    return $ret;
}

public static function getProduto($codprod):array
{
    $sql = new sqlOra();
    $ret= [];
    try{
        $ret = $sql->select("SELECT P.DESCRICAO, ROUND(E.QTESTGER - E.QTBLOQUEADA - E.QTRESERV, 3) ESTOQUE
        FROM KOKAR.PCPRODUT P
        INNER JOIN KOKAR.PCEST E ON E.CODPROD = P.CODPROD
        WHERE P.DTEXCLUSAO IS NULL
        AND CODEPTO IN (10000, 10001) AND P.CODPROD = :codprod",[':codprod' => $codprod]);
    }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
    }
    return $ret[0];
}

public static function getProdutos():array
{
    $sql = new sqlOra();
    $ret= [];
    try{
        $ret = $sql->select("SELECT P.CODPROD, P.DESCRICAO ||' | EST: ' || TO_CHAR(ROUND(E.QTESTGER - E.QTBLOQUEADA - E.QTRESERV, 3)) DESCRICAO FROM KOKAR.PCPRODUT P
        INNER JOIN KOKAR.PCEST E ON E.CODPROD = P.CODPROD
        WHERE P.DTEXCLUSAO IS NULL
        AND P.CODEPTO IN (10000, 10001) order by P.descricao",[]);
    }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
    }
    return $ret;

}

public static function addItem(array $produtos):string{
    $sql = new sqlOra();
    $ret= '';
    
    foreach($produtos as $p){
        if($p['codprod'] == '' || $p['qt'] == '' )
            return 'Erro: Código do produto ou quantidade não informado';
        $sql->insert("INSERT INTO KOKAR.PCAVULSA (NUMOP, CODPROD, PUNIT, QT, CODOPER, DTLANC, CODUSU)",[]);
    }
}

}