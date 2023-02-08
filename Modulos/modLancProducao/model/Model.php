<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Model{

public static function getOP():array
{
    $sql = new sqlOra();
    $ret= [];
    try{
        $ret = $sql->select("SELECT * from kokar.kokar_view_producao_custo",[]);
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

}