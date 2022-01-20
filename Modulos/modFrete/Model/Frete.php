<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Frete{
    public $placa;
    public $rota;
    public $numcar;
    public $motorista;
    public $vlFrete;
    public $vlfretekg;
    public $peso;
    

    public static function Lista($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT PLACA,rota, numcar, NOME_GUERRA motorista,
                Sum(frete) AS vLFrete,
                Sum(TOTPESO) AS peso FROM (SELECT c.NUMNOTA,
                c.TOTPESO,
                Sum(i.VLFRETEKG * i.QT) AS frete,
                v.PLACA, c.numcar,
                e.NOME_GUERRA,
                r.DESCRICAO AS rota FROM kokar.pcpedc c
                INNER JOIN kokar.pcpedi i ON c.NUMPED = i.NUMPED
                left JOIN kokar.pccarreg cr ON cr.NUMCAR = c.NUMCAR
                left JOIN kokar.pcveicul v ON cr.CODVEICULO = v.CODVEICULO
                left JOIN kokar.pcempr e ON cr.CODMOTORISTA = e.MATRICULA
                left JOIN kokar.pcrotaexp r ON cr.CODROTAPRINC = r.CODROTA 
                WHERE c.DTFAT BETWEEN :data1 AND :data2 and c.codcob not in ('BNF')
                group by c.NUMNOTA, c.numcar, c.TOTPESO, v.PLACA, e.NOME_GUERRA, r.DESCRICAO)
                group by PLACA, rota, numcar, NOME_GUERRA order by placa, rota, numcar",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                $f->rota =  mb_encode_numericentity($r['ROTA'], array(0x80, 0xffff, 0, 0xffff), 'ASCII');
                $f->numcar =  $r['NUMCAR'];
                $f->motorista =  $r['MOTORISTA'];
                $f->vlFrete =   round($r['VLFRETE'],2);
                $f->peso = round($r['PESO'],2);
                $f->vlFretekg =  round($f->vlFrete / $f->peso,2); 
                array_push($lista, $f);   
            }
        return $lista;
    }
    public static function Despesas($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT DISTINCT p.CODPROJETO,
            (SubStr(p.PROJETO, (length(p.PROJETO) - 7), 3)) || (SubStr(p.PROJETO, (length(p.PROJETO) - 3), 4)) AS placa,
            c.CODCONTA,
            c.CONTA,
            sum(l.VALOR) totalcontas FROM kokar.pclanc l
            INNER JOIN kokar.pcconta c ON c.CODCONTA = l.CODCONTA
            left JOIN kokar.pcprojeto p ON p.CODPROJETO = l.CODPROJETO
          WHERE l.CODCONTA IN (401015, 401001, 401018, 401004, 401014, 401016,
            401020, 401023, 401030, 401027, 401032, 401019) AND l.dtcompetencia BETWEEN :data1 AND :data2
           GROUP BY p.CODPROJETO,
            c.CODCONTA,
            c.CONTA,
            p.PROJETO
            ORDER BY placa",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                $f->codconta =  $r['CODCONTA'];
                $f->conta = mb_encode_numericentity(($r['CONTA']), array(0x80, 0xffff, 0, 0xffff), 'ASCII');
                $f->totalcontas =  round($r['TOTALCONTAS'],2);
                array_push($lista, $f);   
            }
        return $lista;
    }
    public static function Placa($data1, $data2){
      $data1 = Formatador::formatador2($data1);
      $data2 = Formatador::formatador2($data2);
      $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT DISTINCT 
                v.PLACA FROM kokar.pcpedc c
                left JOIN kokar.pccarreg cr ON cr.NUMCAR = c.NUMCAR
                left JOIN kokar.pcveicul v ON cr.CODVEICULO = v.CODVEICULO
                WHERE c.DTFAT BETWEEN :data1 AND :data2 and c.codcob not in ('BNF')
                order by placa",
                    [":data1"=>$data1, ":data2"=>$data2]);
            }catch(Exception $e){
               echo 'Exceção capturada: '.  $e->getMessage(). "\n";
            }

            $lista = [];
            foreach($ret as $r){
                $f = new Frete();
                $f->placa = $r['PLACA'];
                array_push($lista, $f);   
            }
        return $lista;
    }
}

?>