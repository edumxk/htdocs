<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Politicas{
    public static function getPoliticasGeral(){
        $sql = new sqlOra();
        $ret = $sql->select("SELECT CODPROD, DESCRICAO, SUM(QT) QT, QTMAX, QTMIN, ESTOQUE,
        case when dtfim < TO_DATE(sysdate) then
          'N' else 'S' end as Valido
         FROM(
            SELECT DISTINCT D.DTFIM, i.codprod, p.descricao, i.qt, d.qtdaplicacoesdesc QTMAX, d.qtminestparadesc QTMIN, e.qtestger-e.qtreserv-e.qtbloqueada estoque  from kokar.pcpedi i
                    inner join kokar.pcpedc c on c.numped = i.numped 
                    inner join kokar.pcregiao r on r.numregiao = c.numregiao
                    inner join kokar.pcprodut p on p.codprod = i.codprod
                    INNER join kokar.pcdesconto d on d.codprod = i.codprod --AND I.CODDESCONTO = D.CODDESCONTO
                    inner join kokar.pcest e on e.codprod = i.codprod
                    WHERE i.data > '27/09/2021' and c.posicao not in ('C') and c.numped not in (15009567) 
                    and p.descricao like '%ECONOMICO%'  and i.perdesc > 54 and d.dtinicio < '10/10/2021')
                    group by codprod, descricao, QTMAX, QTMIN, ESTOQUE,dtfim
                    order by descricao");
        return $ret;
    }
    public static function getPoliticasPrem(){
        $sql = new sqlOra();
        $ret = $sql->select("SELECT CODPROD, DESCRICAO, SUM(QT) QT, QTMAX, QTMIN, ESTOQUE,
        case when dtfim < TO_DATE(sysdate) then
          'N' else 'S' end as Valido
         FROM(
             SELECT DISTINCT d.dtfim, i.codprod, p.descricao, i.qt, d.qtdaplicacoesdesc QTMAX, d.qtminestparadesc QTMIN, e.qtestger-e.qtreserv-e.qtbloqueada estoque  from kokar.pcpedi i
                     inner join kokar.pcpedc c on c.numped = i.numped 
                     inner join kokar.pcregiao r on r.numregiao = c.numregiao
                     inner join kokar.pcprodut p on p.codprod = i.codprod
                     INNER join kokar.pcdesconto d on d.codprod = i.codprod --AND I.CODDESCONTO = D.CODDESCONTO
                     inner join kokar.pcest e on e.codprod = i.codprod
                     WHERE i.data >= '30/09/2021' and c.posicao not in ('C') and p.descricao like '%PREMIUM%'
                     and i.perdesc > 54 and d.dtinicio < '10/10/2021')
                     group by codprod, descricao, QTMAX, QTMIN, ESTOQUE,dtfim
                     order by descricao
        ");
        return $ret;
    }
    public static function getPoliticasStand(){
        $sql = new sqlOra();
        $ret = $sql->select("SELECT CODPROD, DESCRICAO, SUM(QT) QT, QTMAX, QTMIN, ESTOQUE,
        case when dtfim < TO_DATE(sysdate) then
          'N' else 'S' end as Valido
         FROM(
            SELECT DISTINCT D.DTFIM, i.codprod, p.descricao, i.qt, d.qtdaplicacoesdesc QTMAX, d.qtminestparadesc QTMIN, e.qtestger-e.qtreserv-e.qtbloqueada estoque  from kokar.pcpedi i
                    inner join kokar.pcpedc c on c.numped = i.numped 
                    inner join kokar.pcregiao r on r.numregiao = c.numregiao
                    inner join kokar.pcprodut p on p.codprod = i.codprod
                    INNER join kokar.pcdesconto d on d.codprod = i.codprod --AND I.CODDESCONTO = D.CODDESCONTO
                    inner join kokar.pcest e on e.codprod = i.codprod
                    WHERE i.data >= '08/10/2021' and c.posicao not in ('C') and p.descricao like '%STANDARD%'
                    and i.perdesc > 54 and d.dtinicio < '10/10/2021')
                    group by codprod, descricao, QTMAX, QTMIN, ESTOQUE,dtfim
                    order by descricao");
        return $ret;
    }
    public static function getPoliticasAnalitico(){
        $sql = new sqlOra();
        $ret = $sql->select("SELECT numped, posicao, MES , data, codcli, codprod, descricao, sum(qt) qt, desconto, pvenda, qtmax, qtmin, estoque from (
            SELECT distinct i.numped, I.POSICAO,EXTRACT(MONTH FROM TO_DATE(C.DATA)) MES,c.data ||' '|| REPLACE(to_char(c.hora,'00')||':'||to_char(c.minuto,'00'), ' ','') data, i.codcli, i.codprod, p.descricao, i.qt, i.perdesc DESCONTO, i.pvenda, d.qtdaplicacoesdesc QTMAX, d.qtminestparadesc QTMIN, e.qtestger-e.qtreserv-e.qtbloqueada estoque  
            from kokar.pcpedi i
                    inner join kokar.pcpedc c on c.numped = i.numped 
                    inner join kokar.pcregiao r on r.numregiao = c.numregiao
                    inner join kokar.pcprodut p on p.codprod = i.codprod
                    inner join kokar.pcdesconto d on d.codprod = i.codprod
                    inner join kokar.pcest e on e.codprod = i.codprod
                    and i.data > '27/09/2021' and c.posicao not in ('C') and c.numped not in (15009567, 29008948)
                    AND I.perdesc >=30 and d.dtinicio < '10/10/2021')
                   group by numped,data,codcli,codprod,descricao, POSICAO,desconto, pvenda, qtmax, qtmin, estoque, MES
                    order by MES desc, DATA DESC, descricao");
        return $ret;
    }
    public static function getPoliticasAnalitico2(){
        $sql = new sqlOra();
        $ret = $sql->select("SELECT t1.codprod, descricao, qtmax, NVL(qt,0) QT, estoque, qtmax-NVL(qt,0) qtdisp ,
         case when dtfim < TO_DATE(sysdate) then
          'N' else 'S' end as Valido
        from     
       (SELECT D.DTFIM, d.codprod, p.descricao,  d.qtdaplicacoesdesc QTMAX,
               d.qtminestparadesc QTMIN, e.qtestger-e.qtreserv-e.qtbloqueada estoque  
               from kokar.pcdesconto D
               inner join kokar.pcprodut p on p.codprod = d.codprod
               inner join kokar.pcest e on e.codprod = d.codprod
               where D.CODPROD IS NOT NULL and d.dtinicio < '10/10/2021'
               group by D.DTFIM, d.codprod, p.descricao, d.qtdaplicacoesdesc, d.qtminestparadesc, e.qtestger, e.qtreserv, e.qtbloqueada
               order by descricao)t1
               left join(
                         SELECT i.codprod, sum(i.qt) qt from kokar.pcpedi i
                         inner join kokar.pcdesconto d on d.coddesconto = i.coddesconto
                         where i.data > '27/09/2021' and d.codprod is not null
                         and i.perdesc > 54 and d.dtinicio < '10/10/2021'
                         group by i.codprod)t2
                         on t1.codprod = t2.codprod");
        return $ret;
    }
}