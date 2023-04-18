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
                            THEN 'TRANSUL'
                              WHEN V.PLACA LIKE 'AAA9999' AND NOME_GUERRA LIKE 'MOTORISTA'
                                THEN 'RETIRA'
                                  WHEN V.PLACA IS NULL
                                    THEN 'FROTA'
                                      WHEN V.PLACA LIKE 'AAA9999' 
                                        THEN 'FROTA'
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
                          select PLACA, 0 AS FRETE, 0 AS PESO from KOKAR.pcveicul v where v.proprio = 'S'
                          )group by PLACA order by placa desc",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                //$f->rota =  mb_encode_numericentity($r['ROTA'], array(0x80, 0xffff, 0, 0xffff), 'ASCII');
                //$f->numcar =  $r['NUMCAR'];
                //$f->motorista =  $r['MOTORISTA'];
                $f->vlFrete =   ($r['VLFRETE']);
                $f->peso = ($r['PESO']);
                if($f->peso == 0) $f->vlFretekg =  0 ;
                else $f->vlFretekg = ($f->vlFrete / $f->peso); 
                array_push($lista, $f);   
            }
        return $lista;
    }
    public static function Despesas($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT codprojeto,
            case 
              when (SUBSTR(placa, 7)) between '0' and '9'
                then placa 
                when codprojeto = 33
                  then 'TRANSUL'  
                when placa = 'AAA9999'
                  then 'RETIRA'
                else 'FROTA' END AS PLACA, CODCONTA, CONTA, TOTALCONTAS FROM(
            SELECT DISTINCT p.CODPROJETO, p.PROJETO,
        (SubStr(p.PROJETO, (length(p.PROJETO) - 7), 3)) || (SubStr(p.PROJETO, (length(p.PROJETO) - 3), 4)) AS placa,
        c.CODCONTA,
        c.CONTA,
        sum(l.VALOR) totalcontas FROM kokar.pclanc l
        INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
        left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
      WHERE l.CODCONTA IN (
        100027, 100026, 100028, 100029, 100032, 100030, 100031, 100033, 100036, 
        401026, 401024, 401008, 401009, 401007, 401020, 401029, 401028, 401025, 401017, 401021, 401023, 401003,
        401001, 401014, 401019, 401022, 401015, 401035, 401016, 401030, 401018, 401012, 401031, 401001, 401005,
        401010, 401011, 401027, 401006, 401034, 200026, 401032, 401004
        ) AND l.dtcompetencia BETWEEN :data1 AND :data2
        GROUP BY p.CODPROJETO,c.CODCONTA, c.CONTA,p.PROJETO
        ORDER BY placa)",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                $f->projeto = $r['CODPROJETO'];
                $f->codconta =  $r['CODCONTA'];
                $f->conta = mb_encode_numericentity(($r['CONTA']), array(0x80, 0xffff, 0, 0xffff), 'ASCII');
                $f->totalcontas =  ($r['TOTALCONTAS']);
                array_push($lista, $f);   
            }
        return $lista;
    }
    public static function Placa($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT PLACA from KOKAR.pcveicul v where v.proprio = 'S'
            UNION ALL
            SELECT 'RETIRA' PLACA FROM DUAL
            UNION ALL
            SELECT 'TRANSUL' PLACA FROM DUAL
            UNION ALL
            SELECT 'FROTA' PLACA FROM DUAL
            order by placa DESC");
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                //$f->motorista = $r['MOTORISTA'];
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
        WHERE l.CODCONTA IN (
            100027, 100026, 100028, 100029, 100032, 100030, 100031, 100033, 100036, 
            401026, 401024, 401008, 401009, 401007, 401020, 401029, 401028, 401025, 401017, 401021, 401023, 401003,
            401001, 401014, 401019, 401022, 401015, 401035, 401016, 401030, 401018, 401012, 401031, 401001, 401005,
            401010, 401011, 401027, 401006, 401034, 200026, 401032, 401004
            ) AND l.dtcompetencia BETWEEN :data1 AND :data2
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
                      then 'TRANSUL'  
                    else 'FROTA' END AS PLACA, CODCONTA, CONTA, nvl(TOTALCONTAS, 0) TOTALCONTAS FROM(
                SELECT DISTINCT p.CODPROJETO, p.PROJETO,
            (SubStr(p.PROJETO, (length(p.PROJETO) - 7), 3)) || (SubStr(p.PROJETO, (length(p.PROJETO) - 3), 4)) AS placa,
            c.CODCONTA,
            c.CONTA,
            sum(l.VALOR) totalcontas FROM kokar.pclanc l
            INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
            left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
          WHERE l.CODCONTA IN (
            100027, 100026, 100028, 100029, 100032, 100030, 100031, 100033, 100036, 
            401026, 401024, 401008, 401009, 401007, 401020, 401029, 401028, 401025, 401017, 401021, 401023, 401003,
            401001, 401014, 401019, 401022, 401015, 401035, 401016, 401030, 401018, 401012, 401031, 401001, 401005,
            401010, 401011, 401027, 401006, 401034, 200026, 401032, 401004
            ) AND l.dtcompetencia BETWEEN &data1 AND &data2
            GROUP BY p.CODPROJETO,c.CODCONTA, c.CONTA,p.PROJETO
            ORDER BY placa))
            pivot (
            sum(totalcontas) for placa in ('TRANSUL'
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
}

?>