<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');



class Cliente{

  public $cod;
  public $data;
  public $cliente;
  public $cidade;
  public $uf;
  public $numped;
  public $pos;
  public $valor;
  public $peso;
  public $mov;

  static function getClientes($codRca){
    $sql = new SqlOra();

    $query = "SELECT distinct c.codcli, l.cliente, d.nomecidade, d.uf
      from kokar.pcpedc c inner join kokar.pcpedi i on c.numped = i.numped
                    inner join kokar.pcprodut p on p.codprod = i.codprod
                    inner join kokar.pcclient l on l.codcli = c.codcli
                    inner join kokar.pccidade d on l.codcidade = d.codcidade
      where c.posicao not in ('C', 'F')
            and c.codusur = :codRca
      order by cliente";


    $ret = $sql->select($query, array(":codRca"=>$codRca));

    $arrRet = [];

    if(sizeof($ret)>0){
      foreach($ret as $r){

        $c = new Cliente();
        $c->cod = $r['CODCLI'];
        $c->cliente = $r['CLIENTE'];
        $c->cidade = $r['NOMECIDADE'];
        $c->uf = $r['UF'];

        array_push($arrRet, $c);
      }
    }

    return $arrRet;

  }

  

  static function getPedidos($codRca){
    $sql = new SqlOra();

    $query = "SELECT data, codcli, cliente, numped, posicao, valor, peso,        
          CASE WHEN TROCA = '0' 
            THEN(
          CASE WHEN ENTREGA = '0' 
              THEN (CASE WHEN SALDO = '0' THEN NULL ELSE SALDO END )
            ELSE ENTREGA END
            )  
            ELSE TROCA END AS MOV
      from (

      SELECT c.data, c.codcli, l.cliente, c.numped, c.posicao, 
        TO_CHAR(sum((i.pvenda-i.vlipi-i.st)*i.qt), '9999999.99') valor, 
        TO_CHAR(sum(i.qt*p.pesobruto), '9999999.99') peso,
        CASE when (upper(c.obs) like '%RETIRA%') OR
              (upper(c.obs1) like '%RETIRA%') OR
              (upper(c.obs2) like '%RETIRA%') OR
              (upper(c.Obsentrega1) like '%RETIRA%') OR
              (upper(c.Obsentrega2) like '%RETIRA%') OR
              (upper(c.Obsentrega3) like '%RETIRA%')
          THEN 'CR' ELSE '0' END AS ENTREGA
          ,CASE when (upper(c.obs) like '%CTD%') OR
              (upper(c.obs1) like '%CTD%') OR
              (upper(c.obs2) like '%CTD%') OR
              (upper(c.Obsentrega1) like '%CTD%') OR
              (upper(c.Obsentrega2) like '%CTD%') OR
              (upper(c.Obsentrega3) like '%CTD%')
          THEN 'TR' ELSE '0' END AS TROCA
          ,CASE when (upper(c.obs) like '%SALDO%') OR
              (upper(c.obs1) like '%SALDO%') OR
              (upper(c.obs2) like '%SALDO%') OR
              (upper(c.Obsentrega1) like '%SALDO%') OR
              (upper(c.Obsentrega2) like '%SALDO%') OR
              (upper(c.Obsentrega3) like '%SALDO%')
          THEN 'SD' ELSE '0' END AS SALDO
        from kokar.pcpedc c inner join kokar.pcpedi i on c.numped = i.numped
                      inner join kokar.pcprodut p on p.codprod = i.codprod
                      inner join kokar.pcclient l on l.codcli = c.codcli
        where c.posicao not in ('C', 'F')
          and c.codusur = :codRca
      group by c.data, c.codcli, l.cliente, c.numped, c.posicao,
      c.obs, c.obs1, c.obs2, c.Obsentrega1, c.Obsentrega2, c.Obsentrega3
      ) order by cliente, data";


    $ret = $sql->select($query, array(":codRca"=>$codRca));

    $arrRet = [];
    
    if(sizeof($ret)>0){
      foreach($ret as $r){

        $c = new Cliente();
        $c->cod = $r['CODCLI'];
        $c->data = $r['DATA'];
        $c->cliente = $r['CLIENTE'];
        $c->numped = $r['NUMPED'];
        $c->pos = $r['POSICAO'];
        $c->valor = $r['VALOR'];
        $c->peso = $r['PESO'];
        $c->mov = $r['MOV'];

        array_push($arrRet, $c);
      }
    }

    return $arrRet;
    
  }
}








?>