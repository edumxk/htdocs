<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Producao{

    public static function getDia($data1, $data2, $depto){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
      try{
          $ret = $sql->select("SELECT DISTINCT O.DTFECHA
    FROM KOKAR.PCOPC O
    INNER JOIN KOKAR.PCPRODUT P ON O.CODPRODMASTER = P.CODPROD
    WHERE O.DTFECHA BETWEEN :data1 AND :data2
    AND P.CODEPTO = :depto
    AND O.POSICAO = 'F'
    ORDER BY DTFECHA",
                [":data1"=>$data1, ":data2"=>$data2, ":depto"=>$depto]);
    return $ret;
            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
        return "FAIL";
        //return 'teste';
    }
    public static function getProducao($data1, $data2, $depto){
        $data1 = Formatador::formatador2($data1);
        $data2 = Formatador::formatador2($data2);
        /*$data1 = new DateTime($data1);
        $data2 = new DateTime($data2);
        $dateRange = array();
            while($data1 <= $data2){
            $dateRange[] = date_format($data1, 'd/m/Y');
            $data1 = $data1->modify('+1day');
        }*/
        $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT a.*, (cp.pesoliq*a.qtproduzida) pesoliq from
            (SELECT  CODPRODMASTER,
                        DESCRICAO,
                        QTPRODUZIDA,
                        round((VLCUSTO_REQN+VLCUSTO_REQA-VLCUSTO_DEVA)/QTPRODUZIDA,2) CUSTOREAL
                        , DTLANC
                        , HORA
                        , DTFECHA
                        , METODO 
                        , round(litragem*QTPRODUZIDA,2) litragem
                        , round(pesobruto*QTPRODUZIDA,2) pesobruto
                        FROM (SELECT DISTINCT O.NUMOP
                        , O.CODPRODMASTER
                        , O.DTPREVINICIO
                        , P.DESCRICAO
                        , p.litragem
                        , p.pesobruto
                        , p.pesoliq
                        , O.NUMLOTE
                        , DTFECHA||' '||(SELECT HORA FROM (SELECT distinct M.HORALANC||':'||M.MINUTOLANC HORA, SUM(QT) QT
                                 FROM KOKAR.PCMOV M
                                 WHERE M.NUMOP = O.NUMOP AND M.CODPROD = o.codprodmaster AND M.CODOPER='EP' GROUP BY CODPROD, M.HORALANC, M.MINUTOLANC) WHERE QT > 0) HORA
                        , NVL(O.QTPRODUZIR,0) QTPRODUZIR
                        , NVL(O.QTPRODUZIDA,0) QTPRODUZIDA
                        , ROUND(NVL((SELECT SUM(QT*PUNIT)
                                 FROM KOKAR.PCMOV
                                WHERE NUMOP=O.NUMOP
                                  AND CODFUNCREQ IS NOT NULL
                                  AND NUMTRANSAVULSA IS NULL
                                  AND CODOPER='SP'
                                  AND dtcancel is null 
                                  AND NVL(QT,0)>0),0),2) AS VLCUSTO_REQN
                        , NVL((SELECT SUM(QT*PUNIT)
                                 FROM KOKAR.PCMOV
                                WHERE NUMOP=O.NUMOP
                                  AND NUMTRANSAVULSA IS NOT NULL
                                  AND CODOPER IN ('RA','SV','SP')
                                  AND NVL(QT,0)>0),0) AS VLCUSTO_REQA
                        , NVL((SELECT SUM(QT*PUNIT)
                                 FROM KOKAR.PCMOV
                                WHERE NUMOP=O.NUMOP
                                  AND NUMTRANSAVULSA IS NOT NULL
                                  AND CODOPER IN ('EX','EV')
                                  AND NVL(QT,0)>0),0) AS VLCUSTO_DEVA                                    
                        , O.DTLANC
                        , O.DTFECHA
                        , O.METODO
                     FROM KOKAR.PCOPC O
                        , KOKAR.PCPRODUT P
                        , KOKAR.PCOPI I
                   
                    WHERE O.NUMOP IS NOT NULL
                      AND O.NUMOP = I.NUMOP
                   AND O.DTFECHA BETWEEN :data1 AND :data2
                   AND P.CODEPTO = :depto
                   AND O.POSICAO = 'F' 
                      AND O.CODPRODMASTER = P.CODPROD
                   ))a        
                   left join
                            (select c1.codprodmaster, c1.qt PESOLIQ, c1.metodo from kokar.pccomposicao c1 
                            inner join kokar.pcprodut p1 on c1.codprod = p1.codprod and p1.codepto = 10001 and p1.codsec IN (10012, 10013))cp
                            on cp.codprodmaster = a.codprodmaster and cp.metodo = a.metodo
                   
                   ORDER BY DTFECHA, HORA",
                [":data1"=>$data1, ":data2"=>$data2, ":depto"=>$depto]);
                return $ret;
            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
        return "FAIL";
        //return 'teste';
        }

        public static function getEmProducao(){
       
        $sql = new sqlOra(); 
        $ret=[];
        
          try{
                  $ret = $sql->select("SELECT * from(SELECT c.codproducao, t.codtanque, c.dtabertura, c.horaabertura,  c.dtproducao, c.horaproducao,
                  m.linha , t.capacidade, m.operador, M.COD, c.status, to_date(sysdate)-c.dtabertura dias
                  from paralelo.mproducaoc c
                  right join paralelo.mtanques t on t.codtanque = c.codtanque
                  inner join paralelo.metasprodc m on m.cod = t.codlinha
                  WHERE ((c.status !='F' and c.dtabertura <=  sysdate))and c.dtexclusao is null
                  order by c.dtfecha, c.dtproducao, c.horaproducao)t1
                  inner join(                  
                  SELECT i.codproducao, i.codprod, p.descricao produto,c.categoria, r.descricao cor, 'COD: '||i.codprod ||' | '||p.embalagem embalagem, 
                          I.QT, p.pesoliq PESO, p.litragem
                          from paralelo.mproducaoi i
                          inner join kokar.pcprodut p on i.codprod = p.codprod
                          left join kokar.pccor r on r.codcor = p.codcor
                          inner join kokar.pccategoria c on c.codsec = p.codsec and c.codcategoria = p.codcategoria
                          order by codproducao, embalagem)t2 on t1.codproducao = t2.codproducao
                          where status not in ('S')");
            

              $mapa = [];
              if(sizeof($ret)>0):
                  foreach($ret as $r):
                  $m = new Producao();
                  $m->codproducao = $r['CODPRODUCAO'];
                  $m->cod = $r['COD'];
                  $m->codtanque = $r['CODTANQUE'];
                  $m->capacidade = $r['CAPACIDADE'];
                  $m->tempo = $r['DIAS'];
                  $m->dtproducao = $r['DTPRODUCAO'].' '.$r['HORAPRODUCAO'];
                  $m->dtabertura = $r['DTABERTURA'].' '.explode(':',$r['HORAABERTURA'])[0].':'.explode(':',$r['HORAABERTURA'])[1];
                  $m->codprod = $r['CODPROD'];
                  $m->produto = $r['EMBALAGEM'];
                  $m->qt = $r['QT'];
                  $m->peso = $r['PESO'];
                  $m->litragem = $r['LITRAGEM'];
                  $m->descricao = $r['PRODUTO'];
                  
  
                  switch ($r['STATUS']):
                      case ('A'):
                          $m->status = "ABERTURA/OP";
                          break;
                      case ('P'):
                          $m->status = "PESAGEM";
                          break;
                      case ('D'):
                          $m->status = "DISPERSÃO/BASE";
                          break;
                      case ('L'):
                          $m->status = "LABORATÓRIO";
                          break;
                      case ('C'):
                          $m->status = "COR";
                          break;
                      case ('E'):
                          $m->status = "ENVASE";
                          break;
                      case ('B'):
                          $m->status = "CORREÇÃO";
                          break;
                      case ('F'):
                          $m->status = "FINALIZADO";
                          break;
                      case ('S'):
                          $m->status = "AGUARDANDO";
                          break;
                  endswitch;
                  array_push($mapa, $m);      
              endforeach; 
          endif;           
                      
          return $mapa;      
          }catch(Exception $e){
              echo 'Exceção capturada: ',  $e->getMessage(), "\n";
          }
      
          return "FAIL";
        }
        public static function getProdMensal($data){
            $data = Formatador::formatador2($data);
            
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
                left join paralelo.agrupamentosa a on a.codcategoria = p.codcategoria
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
                    $p = new Producao();
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

    public static function getDiasUteis($dtInicio, $dtFim, $feriados = []) {
      $tsInicio = strtotime($dtInicio);
      $tsFim = strtotime($dtFim);
  
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
  
  function feriados($data){
    $ano = explode('-',$data)[0];
      return [
        $ano.'-01-01',
        $ano.'-04-02',
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
  public static function getPesoM($data){
       
    $data = Formatador::formatador2($data);
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
    
}