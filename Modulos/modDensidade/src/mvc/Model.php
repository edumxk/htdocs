<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Model
{
    public function __construct()
    {
    }

    public static function getDados($data1, $data2)
    {
        $sql = new sqlOra();

        $ret = $sql->select("SELECT TA.NUMOP,
        TA.NUMLOTE,
        TA.CODPROD,
        TA.DESCRICAO,
        ROUND(TA.QTPRODUZIDA, 0) AS QT_PRODUZIDA,
        TA.QTPRODUZIDA * C.QT AS TOTAL_PESO,
        TA.QTPRODUZIDA * TA.LITRAGEM  AS PESO_ENVASADO,
        C.QT AS PESO_FORMULA,
        TA.PESOLIQ AS PESO_PRODUTO_LIQ,
        TA.LITRAGEM,
        Nvl(L.RESULTADOANALISE, 0) AS DENSIDADE,
        TS.CODPRODSA,
        TS.DESCRICAOSA,
        L.IDLAUDO,
        TA.NUMLOTE LOTE,
        TO_DATE(TA.DTFECHA) DATA,
        L.DESCRICAOPADRAO,
        L.VALORPADRAO FROM (SELECT C.NUMOP,
          C.NUMLOTE,
          C.DTFECHA,
          C.CODPRODMASTER AS CODPROD,
          PA.DESCRICAO,
          PA.LITRAGEM,
          PA.PESOLIQ,
          C.QTPRODUZIDA  FROM KOKAR.PCOPC C
          INNER JOIN KOKAR.PCPRODUT PA ON PA.CODPROD = C.CODPRODMASTER
        WHERE C.DTFECHA BETWEEN TO_DATE('$data1') AND TO_DATE('$data2') AND PA.CODEPTO IN (10000) AND C.POSICAO = 'F'
      AND C.DTCANCEL IS NULL ORDER BY C.DTFECHA, PA.DESCRICAO
      ) TA
        INNER JOIN (SELECT O.NUMLOTE,
          O.CODPRODMASTER AS CODPRODSA,
          P.DESCRICAO AS DESCRICAOSA,
          P.CODSEC,
          Max(O.DTLANC) AS DT_FECHAMENTO FROM KOKAR.PCOPC O
          INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = O.CODPRODMASTER
        WHERE O.DTFECHA BETWEEN TO_DATE('$data1') AND TO_DATE('$data2') AND P.CODEPTO IN (10001) AND O.POSICAO = 'F'
      AND O.DTCANCEL IS NULL GROUP BY O.NUMLOTE,
          O.CODPRODMASTER,
          P.DESCRICAO,
          P.CODSEC) TS ON TS.NUMLOTE = TA.NUMLOTE
        INNER JOIN (SELECT I.IDLAUDO,
          I.CODPROD,
          I.DESCRICAOPADRAO,
          I.VALORPADRAO,
          I.NUMLOTE,
          I.RESULTADOANALISE FROM KOKAR.PCLAUDOI I
        WHERE (I.DESCRICAOPADRAO LIKE '%DENSID%' OR I.DESCRICAOPADRAO LIKE '%MT%020%') AND Nvl(I.RESULTADOANALISE,
          0) BETWEEN '0' AND '3' AND I.RESULTADOANALISE NOT LIKE '%KU%' AND I.RESULTADOANALISE NOT LIKE '%CM%'
          AND I.RESULTADOANALISE NOT LIKE '\%') L ON L.NUMLOTE = TA.NUMLOTE
        INNER JOIN (SELECT PC.CODPRODMASTER,
          PC.CODPROD,
          P.DESCRICAO,
          PC.QT FROM KOKAR.PCCOMPOSICAO PC
          INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = PC.CODPROD AND ((P.DESCRICAO NOT LIKE '%CONCENTRADO%'
      AND P.DESCRICAO NOT LIKE '%PASTA%') OR PC.CODPROD = 2470) AND (PC.QT = 0.2 OR PC.QT >= 0.7)
      AND PC.CODPROD NOT IN (3571) AND PC.METODO = '1'
        WHERE P.CODSEC = 10013) C ON C.CODPRODMASTER = TA.CODPROD AND C.CODPROD = TS.CODPRODSA");
        return ($ret);
    }
    public static function getDados2($data1, $data2)
    {
    $sql = new sqlOra();

        $ret = $sql->select("SELECT
DISTINCT 
  CODPROD
  
FROM
  (--INICIO
  SELECT TA.NUMOP,
    TA.NUMLOTE,
    TA.CODPROD,
    TA.DESCRICAO DESCRICAO,
    ROUND(TA.QTPRODUZIDA, 0) AS QT_PRODUZIDA,
    TA.QTPRODUZIDA * C.QT AS TOTAL_PESO,
    TA.QTPRODUZIDA * TA.LITRAGEM  AS PESO_ENVASADO,
    C.QT AS PESO_FORMULA,
    TA.PESOLIQ AS PESO_PRODUTO_LIQ,
    TA.LITRAGEM,
    Nvl(L.RESULTADOANALISE, 0) AS DENSIDADE,
    TS.CODPRODSA,
    TS.DESCRICAOSA,
    TA.NUMLOTE,
    TO_DATE(TA.DTFECHA) DATA,
    L.DESCRICAOPADRAO FROM (SELECT C.NUMOP,
      C.NUMLOTE,
      C.DTFECHA,
      C.CODPRODMASTER AS CODPROD,
      PA.DESCRICAO,
      PA.LITRAGEM,
      PA.PESOLIQ,
      C.QTPRODUZIDA  FROM KOKAR.PCOPC C
      INNER JOIN KOKAR.PCPRODUT PA ON PA.CODPROD = C.CODPRODMASTER
    WHERE C.DTFECHA BETWEEN TO_DATE('$data1') AND TO_DATE('$data2') AND PA.CODEPTO IN (10000) AND C.POSICAO = 'F'
  AND C.DTCANCEL IS NULL ORDER BY C.DTFECHA, PA.DESCRICAO
  ) TA
    INNER JOIN (SELECT O.NUMLOTE,
      O.CODPRODMASTER AS CODPRODSA,
      P.DESCRICAO AS DESCRICAOSA,
      P.CODSEC,
      Max(O.DTLANC) AS DT_FECHAMENTO FROM KOKAR.PCOPC O
      INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = O.CODPRODMASTER
    WHERE O.DTFECHA BETWEEN TO_DATE('$data1') AND TO_DATE('$data2') AND P.CODEPTO IN (10001) AND O.POSICAO = 'F'
  AND O.DTCANCEL IS NULL GROUP BY O.NUMLOTE,
      O.CODPRODMASTER,
      P.DESCRICAO,
      P.CODSEC) TS ON TS.NUMLOTE = TA.NUMLOTE
    INNER JOIN (SELECT I.IDLAUDO,
      I.CODPROD,
      I.DESCRICAOPADRAO,
      I.VALORPADRAO,
      I.NUMLOTE,
      I.RESULTADOANALISE FROM KOKAR.PCLAUDOI I
    WHERE (I.DESCRICAOPADRAO LIKE '%DENSID%' OR I.DESCRICAOPADRAO LIKE '%MT%020%') AND Nvl(I.RESULTADOANALISE,
      0) BETWEEN '0' AND '3' AND I.RESULTADOANALISE NOT LIKE '%KU%' AND I.RESULTADOANALISE NOT LIKE '%CM%'
      AND I.RESULTADOANALISE NOT LIKE '\%') L ON L.NUMLOTE = TA.NUMLOTE
    INNER JOIN (SELECT PC.CODPRODMASTER,
      PC.CODPROD,
      P.DESCRICAO,
      PC.QT FROM KOKAR.PCCOMPOSICAO PC
      INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = PC.CODPROD AND ((P.DESCRICAO NOT LIKE '%CONCENTRADO%'
  AND P.DESCRICAO NOT LIKE '%PASTA%') OR PC.CODPROD = 2470) AND (PC.QT = 0.2 OR PC.QT >= 0.7)
  AND PC.CODPROD NOT IN (3571) AND PC.METODO = '1'
    WHERE P.CODSEC = 10013) C ON C.CODPRODMASTER = TA.CODPROD AND C.CODPROD = TS.CODPRODSA

  --FIM
    )");
  return $ret;  
  }
    public static function getDadosNovo($data1, $data2)
    {
      $sql = new sqlOra();

      $ret = $sql->select("SELECT  DISTINCT  TA.CODPROD,
      TA.DESCRICAO,
      C.QT AS PESO_FORMULA,
      TA.PESOLIQ AS PESO_PRODUTO_LIQ,
      TA.LITRAGEM,
      TS.CODPRODSA,
      TS.DESCRICAOSA,
      L.DESCRICAOPADRAO,
      L.VALORPADRAO FROM (SELECT C.NUMOP,
        C.NUMLOTE,
        C.DTFECHA,
        C.CODPRODMASTER AS CODPROD,
        PA.DESCRICAO,
        PA.LITRAGEM,
        PA.PESOLIQ,
        C.QTPRODUZIDA  FROM KOKAR.PCOPC C
        INNER JOIN KOKAR.PCPRODUT PA ON PA.CODPROD = C.CODPRODMASTER
      WHERE C.DTFECHA BETWEEN TO_DATE('$data1') AND TO_DATE('$data2') AND PA.CODEPTO IN (10000) AND C.POSICAO = 'F'
    AND C.DTCANCEL IS NULL ORDER BY C.DTFECHA, PA.DESCRICAO
    ) TA
      INNER JOIN (SELECT O.NUMLOTE,
        O.CODPRODMASTER AS CODPRODSA,
        P.DESCRICAO AS DESCRICAOSA,
        P.CODSEC,
        Max(O.DTLANC) AS DT_FECHAMENTO FROM KOKAR.PCOPC O
        INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = O.CODPRODMASTER
      WHERE O.DTFECHA BETWEEN TO_DATE('$data1') AND TO_DATE('$data2') AND P.CODEPTO IN (10001) AND O.POSICAO = 'F'
    AND O.DTCANCEL IS NULL GROUP BY O.NUMLOTE,
        O.CODPRODMASTER,
        P.DESCRICAO,
        P.CODSEC) TS ON TS.NUMLOTE = TA.NUMLOTE
      INNER JOIN (SELECT distinct
        I.CODPROD,
        I.DESCRICAOPADRAO,
        I.VALORPADRAO
        FROM KOKAR.PCLAUDOI I
      WHERE (I.DESCRICAOPADRAO LIKE '%DENSID%' OR I.DESCRICAOPADRAO LIKE '%MT%020%') AND Nvl(I.RESULTADOANALISE,
        0) BETWEEN '0' AND '3' AND I.RESULTADOANALISE NOT LIKE '%KU%' AND I.RESULTADOANALISE NOT LIKE '%CM%'
        AND I.RESULTADOANALISE NOT LIKE '\%' and i.dtlanc >'$data1') L ON L.codprod = ts.codprodsa
      INNER JOIN (SELECT PC.CODPRODMASTER,
        PC.CODPROD,
        P.DESCRICAO,
        PC.QT FROM KOKAR.PCCOMPOSICAO PC
        INNER JOIN KOKAR.PCPRODUT P ON P.CODPROD = PC.CODPROD AND ((P.DESCRICAO NOT LIKE '%CONCENTRADO%'
    AND P.DESCRICAO NOT LIKE '%PASTA%') OR PC.CODPROD = 2470) AND (PC.QT = 0.2 OR PC.QT >= 0.7)
    AND PC.CODPROD NOT IN (3571) AND PC.METODO = '1'
      WHERE P.CODSEC = 10013) C ON C.CODPRODMASTER = TA.CODPROD AND C.CODPROD = TS.CODPRODSA");
  return $ret;  
  }

}