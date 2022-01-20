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
            $ret = $sql->select("SELECT  CODPRODMASTER,
            DESCRICAO,
            QTPRODUZIDA,
            round((VLCUSTO_REQN+VLCUSTO_REQA-VLCUSTO_DEVA)/QTPRODUZIDA,2) CUSTOREAL
            , DTLANC
            , HORA
            , DTFECHA
            , round(litragem*QTPRODUZIDA,2) litragem
            , round(pesobruto*QTPRODUZIDA,2) pesobruto
            , round(pesoliq*QTPRODUZIDA,2) pesoliq
            
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
        --              AND NVL(NUMTRANSOP,0)>0
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
         /*, NVL((SELECT SUM(I.QTNECESSIDADE *                                       
                                      (                                               
                                       SELECT (DECODE(kp.custoindustria,        
                                                       'R',                         
                                                       E.custoreal,               
                                                       'F',                         
                                                       E.custofin,                
                                                       'P',                         
                                                       E.custorep,                
                                                       'C',                         
                                                       E.custocont,               
                                                       'U',                         
                                                       E.custoultent)) CUSTOREP   
                                         FROM KOKAR.PCEST E, KOKAR.PCCONSUM kp                        
                                        WHERE E.CODPROD = I.CODPROD           
                                          AND E.CODFILIAL = O.CODFILIAL       
                                                                                      
                                       ))/O.QTPRODUZIR                            
                             FROM KOKAR.PCOPI OI                                               
                            WHERE OI.NUMOP = O.NUMOP                           
                            AND OI.QTNECESSIDADE>0),                               
                           0) AS VLCUSTO_ESTR  */                                       
            , O.DTLANC
            , O.DTFECHA
         FROM KOKAR.PCOPC O
            , KOKAR.PCPRODUT P
            , KOKAR.PCOPI I
       
        WHERE O.NUMOP IS NOT NULL
          AND O.NUMOP = I.NUMOP
       AND O.DTFECHA BETWEEN :data1 AND :data2
       AND P.CODEPTO = :depto
       AND O.POSICAO = 'F' 
          AND O.CODPRODMASTER = P.CODPROD
       )ORDER BY DTFECHA, HORA",
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
                $p = new Producao();
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
  function feriados(){

      return [
          '2015-12-25',
          '2015-12-26',
          '2016-01-01',
          '2021-09-07',
          '2021-10-05',
          '2021-10-12',
          '2021-11-02',
          '2021-11-15',
          '2021-12-25'
          
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