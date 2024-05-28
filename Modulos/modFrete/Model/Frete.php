<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Frete{
    public $placa;
    public $rota;
    public $numcar;
    public $motorista;
    public $vlFrete;
    public $vlFretekg;
    public $peso;
    public $codconta;
    public $conta;
    public $coduser;
    

    public static function Lista($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT PLACA, SUM(vLFrete) vLFrete, SUM(PESO) PESO FROM (
              SELECT PLACA, Sum(frete) AS vLFrete, 
                          Sum(TOTPESO) AS peso FROM (SELECT c.NUMNOTA,
                          c.TOTPESO,
                          Sum(i.VLFRETEKG * i.QT) AS frete,
                          case when nome_guerra like 'TRANSUL'
                            THEN 'TRANSP'
                              WHEN V.PLACA LIKE 'AAA9999' AND NOME_GUERRA LIKE 'MOTORISTA'
                                THEN 'RETIRA'
                                  WHEN V.PLACA IS NULL
                                    THEN 'RETIRA'
                                      WHEN V.PLACA LIKE 'AAA9999' 
                                        THEN 'RETIRA'
                                      ELSE V.PLACA END AS PLACA, 
                          c.numcar,
                          e.NOME_GUERRA,
                          r.DESCRICAO AS rota FROM kokar.pcpedc c
                          INNER JOIN kokar.pcpedi i ON c.NUMPED = i.NUMPED
                          left JOIN kokar.pccarreg cr ON cr.NUMCAR = c.NUMCAR
                          left JOIN kokar.pcveicul v ON cr.CODVEICULO = v.CODVEICULO
                          left JOIN kokar.pcempr e ON cr.CODMOTORISTA = e.MATRICULA
                          left JOIN kokar.pcrotaexp r ON cr.CODROTAPRINC = r.CODROTA 
                          WHERE c.DTFAT BETWEEN &data1 AND &data2 and c.codcob not in ('BNF')
                          group by c.NUMNOTA, c.numcar, c.TOTPESO, v.PLACA, e.NOME_GUERRA, r.DESCRICAO)
                          where frete > 0
                          group by PLACA 
                          union all 
                          select PLACA, 0 AS FRETE, 0 AS PESO from KOKAR.pcveicul v where v.proprio = 'S' and v.tipoveiculo2 = 0
                          UNION ALL
                          SELECT 'TRANSP' PLACA, 0 VLFRETE, 0 PESO FROM DUAL UNION
                          SELECT 'RETIRA' PLACA, 0 VLFRETE, 0 PESO FROM DUAL UNION
                          SELECT 'FROTA' PLACA, 0 VLFRETE, 0 PESO FROM DUAL
                          order by placa desc
                          )group by PLACA 
                          order by placa desc",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $receita = [];
            $peso = [];
            $vlkg = [];

            foreach($ret as $r){
              $receita[$r['PLACA']] = $r['VLFRETE'];
              $peso[$r['PLACA']] = $r['PESO'];
              if($r['PESO'] == 0) $vlkg[$r['PLACA']] = 0;
              else $vlkg[$r['PLACA']] = ($r['VLFRETE'] / $r['PESO']);
            }
        return [$receita, $peso, $vlkg];
    }
    public static function Despesas($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT * from
            (SELECT 
            case 
              when (SUBSTR(placa, 7)) between '0' and '9'
                then placa 
                when codprojeto = 33
                  then 'TRANSP'  
                else 'FROTA' END AS PLACA, CODCONTA, CONTA, nvl(TOTALCONTAS, 0) TOTALCONTAS FROM(
            SELECT DISTINCT p.CODPROJETO, p.PROJETO,
        (SubStr(p.PROJETO, (length(p.PROJETO) - 7), 3)) || (SubStr(p.PROJETO, (length(p.PROJETO) - 3), 4)) AS placa,
        c.CODCONTA,
        c.CONTA,
        sum(l.VALOR) totalcontas FROM kokar.pclanc l
        INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
        left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
      WHERE l.CODCONTA IN (401013,
        100027, 100026, 100028, 100029, 100032, 100030, 100031, 100033, 100036, 
        401026, 401024, 401008, 401009, 401007, 401020, 401029, 401028, 401025, 401017, 401021, 401023, 401003,
        401001, 401014, 401019, 401022, 401015, 401035, 401016, 401030, 401018, 401012, 401031, 401001, 401005,
        401010, 401011, 401027, 401006, 401034, 200026, 401032, 401004
        ) AND l.dtcompetencia BETWEEN &data1 AND &data2
        and l.tipolanc = 'C'
        GROUP BY p.CODPROJETO,c.CODCONTA, c.CONTA,p.PROJETO
        ORDER BY placa))
        pivot (
        sum(totalcontas) for placa in ('TRANSP'
                                      ,'RETIRA'  
                                      ,'QWF7C64' 
                                      ,'QWE8J67'
                                      ,'QWB3156'
                                      ,'QKL9A72' 
                                      ,'QKL6267' 
                                      ,'QKL0472'
                                      ,'QKH7395' 
                                      ,'QKG3794' 
                                      ,'OYB4655' 
                                      ,'FROTA'   ))
                                      order by conta",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            
        return $ret;
    }
    public static function Placa($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT T.PLACA, EM.NOME_GUERRA MOTORISTA from (SELECT PLACA from KOKAR.pcveicul v
            where v.proprio = 'S'
                        UNION ALL
                        SELECT 'RETIRA' PLACA FROM DUAL
                        UNION ALL
                        SELECT 'TRANSP' PLACA FROM DUAL
                        UNION ALL
                        SELECT 'FROTA' PLACA FROM DUAL
                        )t
                        LEFT join 
            
                        (select e.matricula, e.nome, e.nome_guerra, e.codveiculo, v.placa from kokar.pcempr e
                        left join kokar.pcveicul v on v.codveiculo = e.codveiculo
                        where e.situacao = 'A' and e.tipo = 'M')em
                        on em.placa = t.placa
                        where t.placa in ('TRANSP',
                        'RETIRA' ,
                        'QWF7C64' ,
                        'QWE8J67' ,
                        'QWB3156' ,
                        'QKL9A72' ,
                        'QKL6267' ,
                        'QKL0472' ,
                        'QKH7395' ,
                        'QKG3794' ,
                        'OYB4655' ,
                        'FROTA')
                        order by placa DESC");
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                $f->motorista = $r['MOTORISTA'];
                array_push($lista, $f);   
            }
        return $lista;
    }

    public static function getContas($data1, $data2 ){
        $data1 = Formatador::formatador2($data1);
        $data2 = Formatador::formatador2($data2);
        $sql = new sqlOra();
            try{
                $ret = $sql->select("SELECT DISTINCT c.CODCONTA, c.CONTA FROM kokar.pclanc l
            INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
            left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
        WHERE l.CODCONTA IN (401013,
            100027, 100026, 100028, 100029, 100032, 100030, 100031, 100033, 100036, 
            401026, 401024, 401008, 401009, 401007, 401020, 401029, 401028, 401025, 401017, 401021, 401023, 401003,
            401001, 401014, 401019, 401022, 401015, 401035, 401016, 401030, 401018, 401012, 401031, 401001, 401005,
            401010, 401011, 401027, 401006, 401034, 200026, 401032, 401004
            ) AND l.dtcompetencia BETWEEN :data1 AND :data2
            and l.tipolanc = 'C'
            GROUP BY c.CODCONTA, c.CONTA
            ORDER BY c.CODCONTA",
                        [":data1"=>$data1, ":data2"=>$data2]);
                }catch(Exception $e){
                 echo 'Exceção capturada: '.  $e->getMessage(). "\n";
                }
    
                $lista = [];
                foreach($ret as $r){
                    $f = new Frete();
                    $f->codconta = $r['CODCONTA'];
                    $f->conta = mb_encode_numericentity(($r['CONTA']), array(0x80, 0xffff, 0, 0xffff), 'ASCII');
                    array_push($lista, $f);   
                }
            return $lista;
    }

    public static function getDespesas2($data1, $data2 ){
        $data1 = Formatador::formatador2($data1);
        $data2 = Formatador::formatador2($data2);
        $ret = [];
        $sql = new sqlOra();
            try{
                $ret = $sql->select("SELECT * from
                (SELECT 
                case 
                  when (SUBSTR(placa, 7)) between '0' and '9'
                    then placa 
                    when codprojeto = 33
                      then 'TRANSP'  
                    else 'FROTA' END AS PLACA, CODCONTA, CONTA, nvl(TOTALCONTAS, 0) TOTALCONTAS FROM(
                SELECT DISTINCT p.CODPROJETO, p.PROJETO,
            (SubStr(p.PROJETO, (length(p.PROJETO) - 7), 3)) || (SubStr(p.PROJETO, (length(p.PROJETO) - 3), 4)) AS placa,
            c.CODCONTA,
            c.CONTA,
            sum(l.VALOR) totalcontas FROM kokar.pclanc l
            INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
            left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
          WHERE l.CODCONTA IN (
            200016, 893004, 893005, 401032
            ) AND l.dtcompetencia BETWEEN &data1 AND &data2
            and l.tipolanc = 'C'
            GROUP BY p.CODPROJETO,c.CODCONTA, c.CONTA,p.PROJETO
            ORDER BY placa))
            pivot (
            sum(totalcontas) for placa in ('TRANSP'
                                          ,'RETIRA'  
                                          ,'QWF7C64' 
                                          ,'QWE8J67'
                                          ,'QWB3156'
                                          ,'QKL9A72' 
                                          ,'QKL6267' 
                                          ,'QKL0472'
                                          ,'QKH7395' 
                                          ,'QKG3794' 
                                          ,'OYB4655' 
                                          ,'FROTA'   ))
                                          order by conta",
                        [":data1"=>$data1, ":data2"=>$data2]);
                }catch(Exception $e){
                 echo 'Exceção capturada: '.  $e->getMessage(). "\n";
                }
    
            return $ret;
    }

    public static function getDespesasNovo($data1, $data2){
      $sql = new sqlOra();

      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);

      $ret = [];

      try{
        $ret = $sql->select("SELECT placa, codconta, conta, valor, valorc, minkm, maxkm, maxkm-minkm kmrodado, litragem ,(maxkm-minkm)/litragem km_l,
                                  --case when maxkm-minkm > 0 then valor/(maxkm-minkm) else null end vl_l , case when maxkm-minkm > 0 then valorc/(maxkm-minkm) else null end vl_lc ,
                                  valor/litragem valor_litro, valorc/litragem valorc_litro
                                  from(
                                  select placa, codconta, conta, sum(valor)valor, sum(valorc) valorc, min(km) minkm, max(km)maxkm, sum(litragem)litragem from(
                                  SELECT codprojeto,
                                    case 
                                      when (SUBSTR(placa, 7)) between '0' and '9'
                                        then placa 
                                        when codprojeto = 33
                                          then 'TRANSP'  
                                        when placa = 'AAA9999'
                                          then 'RETIRA'
                                        else 'FROTA' END AS PLACA, CODCONTA, CONTA, valor, case when valor < 0 then valorc*-1 else valorc end valorc, case when km = 0 then null else km end km,
                                          case when valor < 0 then litragem*-1 else litragem end litragem, nvl(valorc,0)/nvl(litragem,-1)vldisel FROM(
                                    
                                    
                                    SELECT DISTINCT p.CODPROJETO, p.PROJETO,
                                (SubStr(p.PROJETO, (length(p.PROJETO) - 7), 3)) || (SubStr(p.PROJETO, (length(p.PROJETO) - 3), 4)) AS placa,
                                c.CODCONTA,
                                c.CONTA,
                                l.VALOR,
                                TO_NUMBER(REGEXP_SUBSTR(l.historico2, 'LITRAGEM: (\d+,\d+)', 1, 1, NULL, 1)) AS litragem,
                                TO_NUMBER(REGEXP_SUBSTR(l.historico2, 'KM: (\d+)', 1, 1, NULL, 1)) AS km,
                                TO_NUMBER(REGEXP_SUBSTR(l.historico2, 'VALOR: (\d+,\d+)', 1, 1, NULL, 1)) AS valorc, 
                                v.codveiculo, v.descricao
                                
                                FROM kokar.pclanc l
                                INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
                                left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
                                left join kokar.pcveicul v on v.placa like REGEXP_REPLACE(SUBSTR(p.projeto, INSTR(p.projeto, '-') + 1), '\s', '')
                            
                              WHERE l.CODCONTA IN (401013,
                                100027, 100026, 100028, 100029, 100032, 100030, 100031, 100033, 100036, 
                                401026, 401024, 401008, 401009, 401007, 401020, 401029, 401028, 401025, 401017, 401021, 401023, 401003,
                                401001, 401014, 401019, 401022, 401015, 401035, 401016, 401030, 401018, 401012, 401031, 401001, 401005,
                                401010, 401011, 401027, 401006, 401034, 200026, 401032, 401004
                                ) AND l.dtcompetencia BETWEEN :data1 AND :data2
                                and l.tipolanc = 'C'
                              -- GROUP BY p.CODPROJETO,c.CODCONTA, c.CONTA, p.PROJETO, v.codveiculo, v.descricao
                                ORDER BY placa
                                
                                )) 
                                group by placa, codconta, conta
                                order by conta, placa)", [":data1"=>$data1, ":data2"=>$data2]);
        }catch(Exception $e){
          echo 'Exceção capturada: '.  $e->getMessage(). "\n";
        }
        return $ret;
      
    }

    public static function getKms($data1, $data2){
      $sql = new sqlOra();

      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);

      $ret = [];
      $retorno = [];

      try{
        $ret = $sql->select("SELECT p.codprojeto, valor, dados, v.placa from(
          select codprojeto, valor, dados
          from (
          
          select l.codprojeto, sum(valor)valor, historico2 dados
          from kokar.pclanc l where l.codconta in (401015) and l.dtcompetencia between &data1 and &data2
          group by l.codprojeto, historico2
  
          
          )p where p.valor > 0)l
          left join kokar.pcprojeto p on p.codprojeto = l.codprojeto
          left join kokar.pcveicul v on v.placa = REGEXP_REPLACE(SUBSTR(p.projeto, INSTR(p.projeto, '-') + 1), '\s', '')
        ", [":data1"=>$data1, ":data2"=>$data2]);
      }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
      }
      foreach($ret as $r){
        array_push($retorno, [
          'valor'=>$r['VALOR'],
          'dados'=> self::getDadosFromString($r['DADOS']),
          'placa'=>$r['PLACA'],
        ]);
      }
      return $retorno;
    }

    public static function getDadosFolha($data1, $data2){
      $sql = new sqlOra();

      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);

      $ret = [];

      try{
        $ret = $sql->select("SELECT *
        from (select placa, codtipo, t.descricao tipo, SUM(valor) VALOR
                from PARALELO.FRETELANCAMENTOS f
               inner join kokar.pcempr e
                  on e.matricula = f.codmotorista
               inner join paralelo.fretetipolanc t
                  on t.id = f.codtipo
                left join kokar.pcveicul v
                  on v.codveiculo = e.codveiculo
                  where competencia between :data1 and :data2
                group by placa, codtipo, t.descricao
               ) pivot(sum(nvl(valor,0)) for placa in('TRANSP' AS TRANSP,
                                              'RETIRA' AS RETIRA,
                                              'QWF7C64' AS QWF7C64,
                                              'QWE8J67' AS QWE8J67,
                                              'QWB3156' AS QWB3156,
                                              'QKL9A72' AS QKL9A72,
                                              'QKL6267' AS QKL6267,
                                              'QKL0472' AS QKL0472,
                                              'QKH7395' AS QKH7395,
                                              'QKG3794' AS QKG3794,
                                              'OYB4655' AS OYB4655,
                                              'FROTA' AS FROTA ))
                order by codtipo", [":data1"=>$data1, ":data2"=>$data2]);
      }catch(Exception $e){
        echo 'Exceção capturada Folha: '.  $e->getMessage(). "\n";
      }
      foreach($ret as &$r){
        $r['TIPO'] = Formatador::br_decode($r['TIPO']);
      }
      
      return $ret;
    }

    public static function getDadosFromString($string){
      // Extrair o valor da litragem
      if (preg_match('/LITRAGEM ARLA:\s*([\d,]+)/i', $string, $matches)) {
        $litragemArla = str_replace(',', '.', $matches[1]); // Substituir vírgula por ponto
        $litragemArla = (double) $litragemArla; // Converter para double
    } else {
        $litragemArla = 0; // Valor não encontrado
    }
      if (preg_match('/LITRAGEM:\s*([\d,]+)/i', $string, $matches)) {
          $litragem = str_replace(',', '.', $matches[1]); // Substituir vírgula por ponto
          $litragem = (double) $litragem; // Converter para double
      } else {
          $litragem = 0; // Valor não encontrado
      }

      // Extrair o valor do KM
      if (preg_match('/KM:\s*([\d]+)/i', $string, $matches)) {
          $km = (double) $matches[1]; // Converter para double
          if($km <= 0){
            $km = null;
          }
      } else {
          $km = null; // Valor não encontrado
      }

      // Extrair o valor
      if (preg_match('/VALOR:\s*([\d,]+)/i', $string, $matches)) {
          $valor = str_replace(',', '.', $matches[1]); // Substituir vírgula por ponto
          $valor = (double) $valor; // Converter para double
      } else {
          $valor = 0; // Valor não encontrado
      }

      // Exibir os valores
      return ['litragem'=>$litragem, 'km'=>$km, 'valor'=>$valor, 'litragemArla'=>$litragemArla];
    }

    public static function getVeiculos(){
      $sql = new sqlOra();

      $ret = [];

      try{
        $ret = $sql->select("SELECT v.codveiculo, v.descricao, v.placa
        from kokar.pcveicul v
        left join kokar.pctipoveiculo t on t.codtipoveiculo = v.codtipoveiculo
        where v.proprio = 'S'
        order by v.descricao");
      }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
      }
      return $ret;
    }

    public static function getKmInicial($data1, $data2){
      
      $sql = new sqlOra();

      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);

      $ret = [];

      try{
        $ret = $sql->select("SELECT * from
        (select p.placa, kmini from 
        
        (SELECT PLACA from KOKAR.pcveicul v where v.proprio = 'S'
        UNION ALL
        SELECT 'RETIRA' PLACA FROM DUAL
        UNION ALL
        SELECT 'TRANSP' PLACA FROM DUAL
        UNION ALL
        SELECT 'FROTA' PLACA FROM DUAL
        order by placa DESC)p
        left join
        (select k.codveiculo, v.placa,  min(valor)kmini, max(valor)kmmax from paralelo.lancamentoskm k
        inner join kokar.pcveicul v on v.codveiculo = k.codveiculo
        where k.competencia between to_date(&data1)-1 and &data2
        group by k.codveiculo, v.placa)k
        on p.placa = k.placa) pivot(MIN(nvl(kmini,0)) 
        for placa in('TRANSP' AS TRANSP,
                    'RETIRA' AS RETIRA,
                    'QWF7C64' AS QWF7C64,
                    'QWE8J67' AS QWE8J67,
                    'QWB3156' AS QWB3156,
                    'QKL9A72' AS QKL9A72,
                    'QKL6267' AS QKL6267,
                    'QKL0472' AS QKL0472,
                    'QKH7395' AS QKH7395,
                    'QKG3794' AS QKG3794,
                    'OYB4655' AS OYB4655,
                    'FROTA' AS FROTA ))
                    UNION ALL
SELECT * from
        (select p.placa, kmmax from 
        
        (SELECT PLACA from KOKAR.pcveicul v where v.proprio = 'S'
        UNION ALL
        SELECT 'RETIRA' PLACA FROM DUAL
        UNION ALL
        SELECT 'TRANSP' PLACA FROM DUAL
        UNION ALL
        SELECT 'FROTA' PLACA FROM DUAL
        order by placa DESC)p
        left join
        (select k.codveiculo, v.placa,  min(valor)kmini, max(valor)kmmax from paralelo.lancamentoskm k
        inner join kokar.pcveicul v on v.codveiculo = k.codveiculo
        where k.competencia between to_date(&data1)-1 and &data2
        group by k.codveiculo, v.placa)k
        on p.placa = k.placa) pivot(MIN(nvl(kmmax,0)) 
        for placa in('TRANSP' AS TRANSP,
                    'RETIRA' AS RETIRA,
                    'QWF7C64' AS QWF7C64,
                    'QWE8J67' AS QWE8J67,
                    'QWB3156' AS QWB3156,
                    'QKL9A72' AS QKL9A72,
                    'QKL6267' AS QKL6267,
                    'QKL0472' AS QKL0472,
                    'QKH7395' AS QKH7395,
                    'QKG3794' AS QKG3794,
                    'OYB4655' AS OYB4655,
                    'FROTA' AS FROTA ))", [":data1"=>$data1, ":data2"=>$data2]);
      }catch(Exception $e){
        echo 'Exceção capturada: '.  $e->getMessage(). "\n";
      }
      return $ret;
    }
}

?>