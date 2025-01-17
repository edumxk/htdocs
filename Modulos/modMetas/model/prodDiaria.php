<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Metas{
    public $data;
    public $t1;
    public $t2;
    public $t3;
    public $t4;
}
class ProdDiaria{
    public $cod;
    public $linha;
    public $numOp;
    public $qtOp;
    public $codProd;
    public $descricao;
    public $qtProduzida;
    public $meta1;
    public $operador;


    public static function getProdMensalNovo($data){
        $data = ProdDiaria::formatador($data);
        
        $sql = new sqlOra();
        $ret = $sql->select("SELECT a.COD, M.NOME TIPO, NVL(A.TOTAL,0)TOTAL, M.META1 FROM(
            SELECT COD, SUM(TOTAL) TOTAL FROM(
            SELECT CASE WHEN TIPO = 'TINTAS' AND TOTAL < 2600
            THEN 2
              WHEN TIPO = 'TINTAS' AND TOTAL >= 2600
            THEN 1
              WHEN TIPO = 'TEXTURAS'
            THEN 3
              WHEN TIPO = 'MASSAS'
            THEN 4
              WHEN TIPO = 'SOLVENTES'
            THEN 5
              WHEN TIPO = 'NONE'
            THEN 6 END AS COD, SUM(TOTAL) TOTAL FROM(
            SELECT SUM(PESOLIQ) TOTAL, NUMLOTE, TIPO FROM (            
            SELECT codprod, descricao, qt, peso_padrao_formula, qt * peso_padrao_formula pesoliq,
            unidade, categoria, codcategoria, numlote, numop, tipo, dtfecha
            from (select c.codprodmaster codprod, p.descricao, p.codepto,
            p.codsec, p.codcategoria, g.categoria, c.numlote, c.numop,dtfecha,
            c.metodo, cp.qt peso_padrao_formula, p.unidade,
            case when c.qtproduzida is null then
              c.qtproduzir else c.qtproduzida end as qt, a.tipo
            from kokar.pcopc c
            inner join kokar.pcprodut p on
            p.codprod = c.codprodmaster
            inner join kokar.pccategoria g on
            g.codcategoria = p.codcategoria
            and g.codsec = p.codsec
            left join
            (select c1.codprodmaster, c1.qt, c1.metodo from kokar.pccomposicao c1 
            inner join kokar.pcprodut p1 on c1.codprod = p1.codprod and p1.codepto = 10001 and p1.codsec IN (10012, 10013))cp
            on cp.codprodmaster = c.codprodmaster and cp.metodo = c.metodo
            left join paralelo.agrupamentosa a on a.codcategoria = p.codlinhaprod
            where extract (month from c.dtfecha) = extract (month from to_date(:dtFecha))
            and extract (year from c.dtfecha) = extract (year from to_date(:dtFecha))
              and c.posicao = 'F' 
              and p.codepto = 10000
              order by DTFECHA, numlote, descricao))GROUP BY NUMLOTE, TIPO)
              GROUP BY TIPO, TOTAL)
              GROUP BY COD)A
                        RIGHT JOIN PARALELO.METASPROD M ON A.COD = M.COD
                        order by cod",
            array(":dtFecha"=>$data)
        );


        $arrRet = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new ProdDiaria();
                $p->cod = $r['COD'];
                $p->linha = $r['TIPO'];
                //$p->qtOp = $r['QT'];
                $p->qtProduzida = $r['TOTAL'];
                $p->meta1 = $r['META1'];
                array_push($arrRet, $p);
            }
        }

        return $arrRet;
        //return 'teste';
    }

    public static function getProdMensal($data){
        $data = ProdDiaria::formatador($data);
        
        $sql = new sqlOra();
        $ret = $sql->select("SELECT M.COD, M.NOME TIPO, A.QT, NVL(A.TOTAL,0)TOTAL, M.META1 FROM(                
            SELECT tipo, cod, count(numop)qt, TO_CHAR(sum(qtproduzida),'9999999.99') total
            from (
            select a.tipo, c.numop, c.qtproduzida, 
                case when a.tipo = 'TINTAS' then
                    case when c.qtproduzida <= 2700 then 2 else 1 end
                when a.tipo = 'TEXTURAS' then 3 
                when a.tipo = 'MASSAS' then 4
                when a.tipo = 'SOLVENTES' then 5 
                end cod
            from kokar.pcopc c inner join PARALELO.kkagrupamentosa a on a.codprod = c.codprodmaster
            where extract (month from c.dtfecha) = extract (month from to_date(:dtFecha))
                    and extract (year from c.dtfecha) = extract (year from to_date(:dtFecha))
                    and c.dtcancel is null AND A.TIPO NOT LIKE 'PASTAS'
                    AND C.NUMOP NOT IN (54421, 54622)--OPS COM PROBLEMAS
            ) group by tipo, cod
            order by cod)A
            RIGHT JOIN PARALELO.METASPROD M ON A.COD = M.COD
            order by cod",
            array(":dtFecha"=>$data)
        );

       
        $arrRet = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new ProdDiaria();
                $p->cod = $r['COD'];
                $p->linha = $r['TIPO'];
                $p->qtOp = $r['QT'];
                $p->qtProduzida = $r['TOTAL'];
                $p->meta1 = $r['META1'];
                array_push($arrRet, $p);
            }
        }

        return $arrRet;
        //return 'teste';
    }



    public static function getProdResumo($data){
        $mes = ProdDiaria::mes($data);
        $ano = ProdDiaria::ano($data);

        $data = ProdDiaria::formatador($data);

       

        $sql = new sqlOra();
        
        $resultado = $sql->select("SELECT meta from paralelo.metasprodi t where extract(month from to_date(t.competencia)) = $mes and extract(year from to_date(t.competencia)) = $ano",[]);

        if(sizeof($resultado) < 1):
            $ret = $sql->select("SELECT m.COD, M.NOME TIPO, NVL(A.TOTAL,0)TOTAL, M.META1,MC.OPERADOR FROM(
                SELECT COD, SUM(TOTAL) TOTAL FROM(
                SELECT CASE WHEN TIPO = 'TINTAS' AND TOTAL < 2600
                THEN 2
                  WHEN TIPO = 'TINTAS' AND TOTAL >= 2600
                THEN 1
                  WHEN TIPO = 'TEXTURAS'
                THEN 3
                  WHEN TIPO = 'MASSAS'
                THEN 4
                  WHEN TIPO = 'SOLVENTES'
                THEN 5
                  WHEN TIPO = 'NONE'
                THEN 6 END AS COD, SUM(TOTAL) TOTAL FROM(
                SELECT SUM(PESOLIQ) TOTAL, NUMLOTE, TIPO FROM (            
                SELECT codprod, descricao, qt, peso_padrao_formula, qt * peso_padrao_formula pesoliq,
                unidade, categoria, codcategoria, numlote, numop, tipo, dtfecha
                from (select c.codprodmaster codprod, p.descricao, p.codepto,
                p.codsec, p.codlinhaprod codcategoria, g.descricao categoria, c.numlote, c.numop,dtfecha,
                c.metodo, cp.qt peso_padrao_formula, p.unidade,
                case when c.qtproduzida is null then
                  c.qtproduzir else c.qtproduzida end as qt, a.tipo
                from kokar.pcopc c
                inner join kokar.pcprodut p on
                p.codprod = c.codprodmaster
                inner join kokar.pclinhaprod g on
                g.codlinha = p.codlinhaprod
                left join
                (select c1.codprodmaster, c1.qt, c1.metodo from kokar.pccomposicao c1 
                inner join kokar.pcprodut p1 on c1.codprod = p1.codprod and p1.codepto = 10001 and p1.codsec IN (10012, 10013))cp
                on cp.codprodmaster = c.codprodmaster and cp.metodo = c.metodo
                left join paralelo.agrupamentosa a on a.codcategoria = p.codlinhaprod
                where  c.dtfecha = to_date(:dtFecha)
    
                  and c.posicao = 'F' 
                  and p.codepto = 10000
                  order by DTFECHA, numlote, descricao))GROUP BY NUMLOTE, TIPO)
                  GROUP BY TIPO, TOTAL)
                  GROUP BY COD)A
                            RIGHT JOIN PARALELO.METASPROD M ON A.COD = M.COD
                            INNER JOIN PARALELO.METASPRODC MC ON M.COD = MC.COD
                            order by cod",
                array(":dtFecha"=>$data)
            );
        else:
            $ret = $sql->select("SELECT M.CODLINHA cod, mc.linha TIPO, NVL(A.TOTAL,0)TOTAL, M.META meta1, MC.OPERADOR
            FROM(
                SELECT COD, SUM(TOTAL) TOTAL FROM(
                SELECT CASE WHEN TIPO = 'TINTAS' AND TOTAL < 2600
                THEN 2
                  WHEN TIPO = 'TINTAS' AND TOTAL >= 2600
                THEN 1
                  WHEN TIPO = 'TEXTURAS'
                THEN 3
                  WHEN TIPO = 'MASSAS'
                THEN 4
                  WHEN TIPO = 'SOLVENTES'
                THEN 5
                  WHEN TIPO = 'NONE'
                THEN 6 END AS COD, SUM(TOTAL) TOTAL FROM(
                SELECT SUM(PESOLIQ) TOTAL, NUMLOTE, TIPO FROM (            
                SELECT codprod, descricao, qt, peso_padrao_formula, qt * peso_padrao_formula pesoliq,
                unidade, categoria, codcategoria, numlote, numop, tipo, dtfecha
                from (select c.codprodmaster codprod, p.descricao, p.codepto,
                p.codsec, p.codcategoria, g.categoria, c.numlote, c.numop,dtfecha,
                c.metodo, cp.qt peso_padrao_formula, p.unidade,
                case when c.qtproduzida is null then
                  c.qtproduzir else c.qtproduzida end as qt, a.tipo
                from kokar.pcopc c
                inner join kokar.pcprodut p on
                p.codprod = c.codprodmaster
                inner join kokar.pccategoria g on
                g.codcategoria = p.codcategoria
                and g.codsec = p.codsec
                left join
                (select c1.codprodmaster, c1.qt, c1.metodo from kokar.pccomposicao c1 
                inner join kokar.pcprodut p1 on c1.codprod = p1.codprod and p1.codepto = 10001 and p1.codsec IN (10012, 10013))cp
                on cp.codprodmaster = c.codprodmaster and cp.metodo = c.metodo
                left join paralelo.agrupamentosa a on a.codcategoria = p.codlinhaprod
                where  c.dtfecha = to_date(:dtFecha)
    
                  and c.posicao = 'F' 
                  and p.codepto = 10000
                  order by DTFECHA, numlote, descricao))GROUP BY NUMLOTE, TIPO)
                  GROUP BY TIPO, TOTAL)
                  GROUP BY COD)A
                     right JOIN PARALELO.METASPRODI M ON A.COD = M.CODLINHA 
                     right JOIN PARALELO.METASPRODC MC ON M.CODLINHA = MC.COD
                    where  
                    extract(month from to_date(competencia)) = extract(month from to_date(:dtfecha)) 
                    and extract(year from to_date(competencia)) = extract(year from to_date(:dtfecha))
                      
                     order by M.CODLINHA",
                array(":dtFecha"=>$data)
            );
        endif;

        $arrRet = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new ProdDiaria();
                $p->cod = $r['COD'];
                $p->linha = $r['TIPO'];
                //$p->qtOp = $r['QT'];
                $p->qtProduzida = $r['TOTAL'];
                $p->meta1 = $r['META1'];
                $p->operador = $r['OPERADOR'];
                
                array_push($arrRet, $p);
            }
        }

        return $arrRet;
        return 'TESTE';
    }


    function formatador($data){
        $d = explode('-', $data);
        $saida = $d[2].'/'.$d[1].'/'.$d[0];
        return $saida;
    }

    function mes($data){
        $d = explode('-', $data);
        $saida = $d[1];
        return $saida;
    }

    function ano($data){
        $d = explode('-', $data);
        $saida = $d[0];
        return $saida;
    }




    public static function getFaturado($data){
       
        $data = ProdDiaria::formatador($data);
        $sql = new sqlOra();
        $ret = $sql->select("SELECT t1.mes, t1.faturado-nvl(dev.vldevol,0) faturado, t2.meta_mes, t2.meta_acum, round(((faturado-nvl(dev.vldevol,0))/meta_mes)*100,2) perc_meta, round((faturado/meta_acum)*100,2) perc_acum from
        (SELECT avg(extract(month from vv.DTSAIDA))mes, round(Sum(vv.VLVENDA - vv.ICMSRETIDO - vv.VLIPI),2) AS faturado
        FROM kokar.view_vendas_resumo_faturamento vv
        WHERE extract(month from vv.DTSAIDA) = extract(month from to_date(:data))
        and extract(year from vv.DTSAIDA) = extract(year from to_date(:data))
        AND Nvl(vv.VLBONIFIC, 0) = 0 AND Nvl(vv.VLDEVOLCLI, 0) = 0)t1
        
        inner join(
        SELECT avg(extract(month from mt.DATA))mes,
            sum (mt.vlvendaprev) meta_mes, 
            sum(case when mt.data <= sysdate then mt.vlvendaprev else 0 end) meta_acum
        FROM kokar.pcmetarca mt
        WHERE extract(month from mt.DATA) = extract(month from to_date(:data))
        and extract(year from mt.DATA) = extract(year from to_date(:data))
        AND mt.vlvendaprev > 0) t2 on t1.mes = t2.mes 
        LEFT JOIN (
 /* DEVOLUÇÃO */
     SELECT nvl(d1.mes,0) mes, nvl(round(nvl(d1.vldevol,0)+nvl(d2.vldevol,0),2),0) as vldevol from
       (SELECT extract(month from f.DTENT) mes, Sum(f.VLDEVOLUCAO - f.VLST - f.VLIPI) AS vldevol
       FROM kokar.view_devol_resumo_faturamento f
       WHERE extract(month from f.DTENT) = extract(month from to_date(:data))
        and extract(year from f.DTENT) = extract(year from to_date(:data))
       GROUP BY extract(month from f.DTENT))d1 full join 
     (SELECT extract(month from da.DTENT) mes, sum(da.VLDEVOLUCAO - da.VLST - da.VLIPI) as vldevol
     FROM kokar.view_devol_resumo_faturavulsa da
    WHERE extract(month from da.DTENT) = extract(month from to_date(:data))
        and extract(year from da.DTENT) = extract(year from to_date(:data))
     GROUP BY extract(month from da.DTENT))d2 on d1.mes = d2.mes )dev on dev.mes = t1.mes",
        array(":data"=>$data));
        
        return $ret;
    }
    public static function getPesoM($data){
       
        $data = ProdDiaria::formatador($data);
        $sql = new sqlOra();
        $ret = $sql->select("SELECT SUM(P.PESOLIQ*C.QTPRODUZIDA) PESOLIQ
        , SUM(P.PESOBRUTO*C.QTPRODUZIDA) PESOBRUTO
        FROM kokar.PCOPC C
        , kokar.PCPRODUT P
    WHERE 
    P.CODPROD = C.CODPRODMASTER
    AND extract(month from C.DTFECHA) = extract(month from to_date(:data))
    AND extract(year from C.DTFECHA) = extract(year from to_date(:data))
    AND P.CODEPTO = 10000
    AND C.POSICAO = 'F'",
    
        array(":data"=>$data));
        
        return $ret;
    }
    public static function getPesoD($data){
       
        $data = ProdDiaria::formatador($data);
        $sql = new sqlOra();
        $ret = $sql->select("SELECT SUM(P.PESOLIQ*C.QTPRODUZIDA) PESOLIQ
        , SUM(P.PESOBRUTO*C.QTPRODUZIDA) PESOBRUTO
     FROM kokar.PCOPC C
        , kokar.PCPRODUT P
    WHERE 
    P.CODPROD = C.CODPRODMASTER
    AND C.DTFECHA = to_date(:data)
    AND P.CODEPTO = 10000
    AND P.CODCATEGORIA NOT IN (140,141,125,109,147,131,128,130,129,127,131,132,141,145,146)
    AND C.NUMOP NOT IN (54421, 54622, 55549) --ops com problema
    AND C.POSICAO = 'F'",
        array(":data"=>$data));
        
        return $ret;
    }
    public static function inserirMeta($array){
       
        $meta = new Metas();
        $meta->data = $array['data'];
        $meta->t1 = $array['tintas5000'];
        $meta->t2 = $array['tintas2000'];
        $meta->t3 = $array['texturas'];
        $meta->t4 = $array['massas'];
        
        $sql = new sqlOra();
        $ret = $sql->select("SELECT SUM(P.PESOLIQ*C.QTPRODUZIDA) PESOLIQ
        , SUM(P.PESOBRUTO*C.QTPRODUZIDA) PESOBRUTO
        FROM kokar.PCOPC C
        , kokar.PCPRODUT P
    WHERE 
    P.CODPROD = C.CODPRODMASTER
    AND extract(month from C.DTFECHA) = extract(month from to_date(:data))
    AND extract(year from C.DTFECHA) = extract(year from to_date(:data))
    AND P.CODEPTO = 10000
    AND C.POSICAO = 'F'",
    
        array(":data"=>$array));
        
        return $ret;
    }
    static function getPrimeiroDiaMes($data) {
        $d = explode('-', $data);
        $saida = $d[0].'-'.$d[1].'-01';
        return $saida;

    }
    static function getUltimoDiaMes($data) {
       
        $d = explode('-', $data);
        /* Validação proximo ano bisexto */
        if($d[1]=='02'){
            if($d[0]=='2024')
                $d[2] = 29;
            else 
                $d[2] = 28;
        }
        else if($d[1]%2==1&&$d[1]<8){
            $d[2] = 31;
        }else if($d[1]%2==0&&$d[1]<8){
            $d[2] = 30;
        }else if($d[1]%2==0&&$d[1]>=8){
            $d[2] = 31;
        }else if($d[1]%2==1&&$d[1]>=8){
            $d[2] = 30;
        }
        
        $saida = $d[0].'-'.$d[1].'-'.$d[2];
        
        return $saida;
    }

    static function getDiasUteis($dtInicio, $dtFim, $feriados = []) {
        $tsInicio = strtotime($dtInicio);
        $tsFim = strtotime($dtFim);
        //echo($tsInicio);
        $quantidadeDias = 0;
        while ($tsInicio <= $tsFim) {
            // Verifica se o dia é igual a sábado ou domingo, caso seja continua o loop
            $diaIgualFinalSemana = (date('D', $tsInicio) === 'Sat' || date('D', $tsInicio) === 'Sun');
            // Verifica se é feriado, caso seja continua o loop
            $diaIgualFeriado = (count($feriados) && in_array(date('Y-m-d', $tsInicio), $feriados));
    
            $tsInicio += 86400; // 86400 quantidade de segundos em um dia
    
            if ($diaIgualFinalSemana || $diaIgualFeriado) {
                continue;
            }
    
            $quantidadeDias++;
        }
    
        return $quantidadeDias;
    }
    static function feriados($data){
        $ano = explode('-',$data)[0];
        return [
            $ano.'-01-01',
            $ano.'-04-07',
            $ano.'-04-21',
            $ano.'-05-01',
            $ano.'-09-07',
            $ano.'-10-05',
            //$ano.'-10-12',
            $ano.'-11-02',
            $ano.'-11-15',
            /* Férias coletivas */
           '2022-12-21',
            $ano.'-12-22',
            $ano.'-12-23',
            $ano.'-12-24',
            $ano.'-12-25',
            $ano.'-12-26',
            $ano.'-12-27',
            $ano.'-12-28',
            $ano.'-12-29',
            $ano.'-12-30',
            $ano.'-12-31',
            /* Fim Férias coletivas */
            /* Feriados dinamicos */

            '2022-04-15',
            /* Feriados dinamicos */
            ];
    } 

}








?>
