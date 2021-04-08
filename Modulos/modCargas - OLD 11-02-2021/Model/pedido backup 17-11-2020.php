<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');


class Pedido{
    public $numcarga;
    public $data;
    public $rca;
    public $numped;
    public $numcar; //carregamento do winthor
    public $cod;
    public $cliente;
    public $consumidorfinal;
    public $pos;
    public $peso;
    public $valor;
    public $cidade;
    public $uf;
    public $praca;
    public $obs;
    //Para uso na distribuição de produtos
    public $prevPos;
    public $produtos = [];
    public $conferencia;



    public static function getListaPedidos(){

        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/ListaPedidos.json');
        $ret = json_decode($str, true);*/


        $sql = new SqlOra();

        $ret = $sql->select(
            "SELECT t.data, t.numped, t.codusur, t.nome, t.codcli, t.cliente, t.consumidorfinal, t.numcar, 
            t.posicao, t.peso, t.valor, t.nomecidade, t.uf, t.codpraca, t.praca,
                CASE WHEN TROCA = '0' THEN(
                     CASE WHEN EMPRESTIMO = '0' THEN(
                          CASE WHEN BONIFICACAO = '0' THEN(
                               CASE WHEN ENTREGA = '0' THEN (
                                    CASE WHEN SALDO = '0' THEN NULL ELSE SALDO END )ELSE ENTREGA END
                 )ELSE BONIFICACAO END)ELSE EMPRESTIMO END)ELSE TROCA END AS MOV,
                 nvl(numcarga,0) numcarga, c1.conferencia
          from (
                 SELECT kc.data, kc.numped, kc.codusur, ku.nome, kc.codcli, kl.cliente, kl.consumidorfinal, kc.posicao,
                        to_char(round(sum(kp.pesobruto * ki.qt), 2), '999999.99') peso,
                        to_char(round(sum(ki.pvenda * ki.qt), 2), '999999.99') valor,
                        kd.nomecidade, kd.uf, kc.codpraca, kc.numcar, kpr.praca,
                        CASE
                          when (upper(kc.obs) like '%RETIRA%') OR
                               (upper(kc.obs1) like '%RETIRA%') OR
                               (upper(kc.obs2) like '%RETIRA%') OR
                               (upper(kc.Obsentrega1) like '%RETIRA%') OR
                               (upper(kc.Obsentrega2) like '%RETIRA%') OR
                               (upper(kc.Obsentrega3) like '%RETIRA%') THEN 'CR' ELSE '0' END AS ENTREGA,
                        CASE
                          when (upper(kc.obs) like '%CTD%') OR
                               (upper(kc.obs1) like '%CTD%') OR
                               (upper(kc.obs2) like '%CTD%') OR
                               (upper(kc.Obsentrega1) like '%CTD%') OR
                               (upper(kc.Obsentrega2) like '%CTD%') OR
                               (upper(kc.Obsentrega3) like '%CTD%') THEN 'TR' ELSE '0' END AS TROCA,
                        CASE
                          when (upper(kc.obs) like '%SALDO%') OR
                               (upper(kc.obs1) like '%SALDO%') OR
                               (upper(kc.obs2) like '%SALDO%') OR
                               (upper(kc.Obsentrega1) like '%SALDO%') OR
                               (upper(kc.Obsentrega2) like '%SALDO%') OR
                               (upper(kc.Obsentrega3) like '%SALDO%') THEN 'SD' ELSE '0' END AS SALDO,
                        CASE
                          when (upper(kc.obs) like '%EMPRESTIMO%') OR
                               (upper(kc.obs1) like '%EMPRESTIMO%') OR
                               (upper(kc.obs2) like '%EMPRESTIMO%') OR
                               (upper(kc.Obsentrega1) like '%EMPRESTIMO%') OR
                               (upper(kc.Obsentrega2) like '%EMPRESTIMO%') OR
                               (upper(kc.Obsentrega3) like '%EMPRESTIMO%') THEN 'EMP' ELSE '0' END AS EMPRESTIMO,
                        CASE
                          when (upper(kc.obs) like '%BNF%') OR
                               (upper(kc.obs1) like '%BNF%') OR
                               (upper(kc.obs2) like '%BNF%') OR
                               (upper(kc.Obsentrega1) like '%BNF%') OR
                               (upper(kc.Obsentrega2) like '%BNF%') OR
                               (upper(kc.Obsentrega3) like '%BNF%') THEN 'BNF' ELSE '0' END AS BONIFICACAO,
                        nvl(ci.numcarga,0) numcarga
                   from kokar.pcpedc kc inner join kokar.pcusuari ku on kc.codusur = ku.codusur
                        inner join kokar.pcclient kl on kc.codcli = kl.codcli
                        inner join kokar.pcpedi ki on kc.numped = ki.numped
                        inner join kokar.pccidade kd on kl.codcidade = kd.codcidade
                        inner join kokar.pcprodut kp on ki.codprod = kp.codprod
                        inner join kokar.pcpraca kpr on kc.codpraca = kpr.codpraca
                        full join PARALELO.cargai ci on kc.numped = ci.numped
                        
                   where kc.posicao not in ('C', 'F')
                  group by kc.data, kc.numped, kc.codusur, ku.nome, kc.codcli, kl.cliente, kl.consumidorfinal, kc.posicao,
                           kd.nomecidade, kd.uf, kc.codpraca, kc.obs, kc.obs1, kc.obs2, kc.obsentrega1,
                           kc.obsentrega2, kc.obsentrega3, ci.numcarga, kc.numcar, kpr.praca
                  order by kc.codusur, kd.nomecidade, kl.cliente)t
     
                 left join (select numped, inicio, fim, case when fim is not null then 'FINAL' else(
                     case when inicio is not null then 'ABERTO' else 'PEND' end) end conferencia
                 from (
                 select c.numped, to_date(c.dtinicialcheckout) inicio, to_date(c.dtfinalcheckout) fim, 'WINTHOR'origem
                 from kokar.pcpedc c
                 where c.dtinicialcheckout is not null
     
                 union
     
                 select pc.numped, pc.data, decode(pc.final, 'S', to_date(sysdate), null), 'OFFLINE'origem
                 from paralelo.conferenciac pc
     
                 union
     
                 select pc.numped, pc.data, decode(pc.final, 'S', to_date(sysdate), null), 'AGRUPADO'origem
                 from paralelo.conferenciac pc
                 where pc.numped in (
                     select n.numped
                     from kokar.pcpedc c inner join kokar.pcnfcan n on c.numped = n.numpedagrup
                     and c.dtfinalcheckout is null
                 )
                 ))c1 on c1.numped = t.numped"
        );

        $lista = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new Pedido();
                $p->numcarga = $r['NUMCARGA'];
                $p->data = Formatador::formatarData($r['DATA']);
                $rca = $r['NOME'];
                $p->rca = Pedido::ajustaRca($rca);
                $p->numped = $r['NUMPED'];
                $p->numcar = $r['NUMCAR'];
                $p->cod = $r['CODCLI'];
                $p->cliente = $r['CLIENTE'];
                $p->consumidorfinal = $r['CONSUMIDORFINAL'];
                $p->pos = $r['POSICAO'];
                $p->peso = $r['PESO'];
                $p->valor = number_format($r['VALOR'],2,',','.');
                $p->cidade = $r['NOMECIDADE'];
                $p->uf = $r['UF'];
                $p->praca = $r['PRACA'];
                $p->obs = $r['MOV'];
                $p->conferencia = $r['CONFERENCIA'];
                // if($p->conferencia == null){
                //     $p->conferencia = 'PEND';
                // }
                if($p->obs == null){
                    $p->obs = '';
                }

                array_push($lista, $p);
            }
        }

        return $lista;
    }

    public static function getRcaPedidos($ped){
        $rca = [];

        foreach($ped as $p){
            array_push($rca, $p->rca);
        }
        $arr = array_unique($rca);
        $ret=[];
        foreach($arr as $p){
            array_push($ret, ['nome'=>$p]);
        }

        return $ret;

    }

    public static function getPendenciasPedidos($numped){
        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/pendencias.json');
        $arr = json_decode($str, true);*/
                
        $sql = new SqlOra();

        $arr = $sql->select("SELECT t.codprod, t.descricao, t.qt, t.peso,
            ke.qtestger-ke.qtbloqueada-ke.qtreserv as qtdisp
            from (
            select ki.codprod, 
                    kp.descricao,
                    sum(ki.qt) qt,
                    sum (ki.qt*kp.pesobruto) peso
            from cargai i inner join kokar.pcpedi ki on i.numped = ki.numped
                        inner join kokar.pcprodut kp on ki.codprod = kp.codprod
                        inner join kokar.pcpedc kc on kc.numped = ki.numped
            where kc.posicao in ('P','B')
            and kc.numped = :numped
            group by ki.codprod, kp.descricao
            )t inner join kokar.pcest ke on ke.codprod = t.codprod
            where qt>ke.qtestger-ke.qtbloqueada-ke.qtreserv
            order by t.codprod",array(":numped"=>$numped)
        );

        for($i = 0; $i<sizeof($arr); $i++){
            $arr[$i]['DESCRICAO'] =  utf8_encode($arr[$i]['DESCRICAO']);
        }
        return $arr;
    }



    public static function ajustaRca($nome){
        if(strpos($nome, 'KOKAR') !== false) {
            return '1 - KOKAR';
        }elseif(strpos($nome, 'WANDERLEI') !== false) {
            return '3 - WANDERLEI';
        }elseif(strpos($nome, 'DOMINGOS') !== false) {
            return '15 - DOMINGOS';
        }elseif(strpos($nome, 'MAURO') !== false) {
            return '27 - MAURO';
        }elseif(strpos($nome, 'JOILDO') !== false) {
            return '29 - JOILDO';
        }elseif(strpos($nome, 'FRANKLIN') !== false) {
            return '30 - FRANKLIN';
        }elseif(strpos($nome, 'RADAMES') !== false) {
            return '32 - RADAMES';
        }elseif(strpos($nome, 'FREDERICO') !== false) {
            return '33 - FREDERICO';
        }elseif(strpos($nome, 'DALMI') !== false) {
            return '35 - DALMI';
        }elseif(strpos($nome, 'GIAN') !== false) {
            return '36 - GIAN';
        }elseif(strpos($nome, 'ALEX') !== false) {
            return '37 - ALEX';
        }else {
            return $nome;
        }
    }

    public static function getListaPedidosDist(){

        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/ListaPedidos.json');
        $ret = json_decode($str, true);*/


        $sql = new SqlOra();

        $ret = $sql->select(
            "SELECT data, numped, codusur, nome, codcli, cliente, numcar, posicao, peso, valor, nomecidade, uf, codpraca, praca,
            CASE WHEN TROCA = '0' THEN(
                 CASE WHEN EMPRESTIMO = '0' THEN(
                      CASE WHEN BONIFICACAO = '0' THEN(
                           CASE WHEN ENTREGA = '0' THEN (
                                CASE WHEN SALDO = '0' THEN NULL ELSE SALDO END )ELSE ENTREGA END
             )ELSE BONIFICACAO END)ELSE EMPRESTIMO END)ELSE TROCA END AS MOV,
             nvl(numcarga,0) numcarga
      from (
             SELECT kc.data, kc.numped, kc.codusur, ku.nome, kc.codcli, kl.cliente, kc.posicao,
                    to_char(round(sum(kp.pesobruto * ki.qt), 2), '999999.99') peso,
                    to_char(round(sum(ki.pvenda * ki.qt), 2), '999999.99') valor,
                    kd.nomecidade, kd.uf, kc.codpraca, kc.numcar, kpr.praca,
                    CASE
                      when (upper(kc.obs) like '%RETIRA%') OR
                           (upper(kc.obs1) like '%RETIRA%') OR
                           (upper(kc.obs2) like '%RETIRA%') OR
                           (upper(kc.Obsentrega1) like '%RETIRA%') OR
                           (upper(kc.Obsentrega2) like '%RETIRA%') OR
                           (upper(kc.Obsentrega3) like '%RETIRA%') THEN 'CR' ELSE '0' END AS ENTREGA,
                    CASE
                      when (upper(kc.obs) like '%CTD%') OR
                           (upper(kc.obs1) like '%CTD%') OR
                           (upper(kc.obs2) like '%CTD%') OR
                           (upper(kc.Obsentrega1) like '%CTD%') OR
                           (upper(kc.Obsentrega2) like '%CTD%') OR
                           (upper(kc.Obsentrega3) like '%CTD%') THEN 'TR' ELSE '0' END AS TROCA,
                    CASE
                      when (upper(kc.obs) like '%SALDO%') OR
                           (upper(kc.obs1) like '%SALDO%') OR
                           (upper(kc.obs2) like '%SALDO%') OR
                           (upper(kc.Obsentrega1) like '%SALDO%') OR
                           (upper(kc.Obsentrega2) like '%SALDO%') OR
                           (upper(kc.Obsentrega3) like '%SALDO%') THEN 'SD' ELSE '0' END AS SALDO,
                    CASE
                      when (upper(kc.obs) like '%EMPRESTIMO%') OR
                           (upper(kc.obs1) like '%EMPRESTIMO%') OR
                           (upper(kc.obs2) like '%EMPRESTIMO%') OR
                           (upper(kc.Obsentrega1) like '%EMPRESTIMO%') OR
                           (upper(kc.Obsentrega2) like '%EMPRESTIMO%') OR
                           (upper(kc.Obsentrega3) like '%EMPRESTIMO%') THEN 'EMP' ELSE '0' END AS EMPRESTIMO,
                    CASE
                      when (upper(kc.obs) like '%BNF%') OR
                           (upper(kc.obs1) like '%BNF%') OR
                           (upper(kc.obs2) like '%BNF%') OR
                           (upper(kc.Obsentrega1) like '%BNF%') OR
                           (upper(kc.Obsentrega2) like '%BNF%') OR
                           (upper(kc.Obsentrega3) like '%BNF%') THEN 'BNF' ELSE '0' END AS BONIFICACAO,
                    nvl(ci.numcarga,0) numcarga
               from kokar.pcpedc kc inner join kokar.pcusuari ku on kc.codusur = ku.codusur
                    inner join kokar.pcclient kl on kc.codcli = kl.codcli
                    inner join kokar.pcpedi ki on kc.numped = ki.numped
                    inner join kokar.pccidade kd on kl.codcidade = kd.codcidade
                    inner join kokar.pcprodut kp on ki.codprod = kp.codprod
                    inner join kokar.pcpraca kpr on kc.codpraca = kpr.codpraca
                    full join PARALELO.cargai ci on kc.numped = ci.numped
                    
               where kc.posicao not in ('C', 'F')
                 --and kc.numped = 15003340
              group by kc.data, kc.numped, kc.codusur, ku.nome, kc.codcli, kl.cliente, kc.posicao,
                       kd.nomecidade, kd.uf, kc.codpraca, kc.obs, kc.obs1, kc.obs2, kc.obsentrega1,
                       kc.obsentrega2, kc.obsentrega3, ci.numcarga, kc.numcar, kpr.praca
              order by kc.codusur, kd.nomecidade, kl.cliente)"
        );

        $lista = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new Pedido();
                $p->numcarga = $r['NUMCARGA'];
                $p->data = Formatador::formatarData($r['DATA']);
                $rca = $r['NOME'];
                $p->rca = Pedido::ajustaRca($rca);
                $p->numped = $r['NUMPED'];
                $p->numcar = $r['NUMCAR'];
                $p->cod = $r['CODCLI'];
                $p->cliente = $r['CLIENTE'];
                $p->consumidorfinal = $r['CONSUMIDORFINAL'];
                $p->pos = $r['POSICAO'];
                $p->peso = $r['PESO'];
                $p->valor = number_format($r['VALOR'],2,',','.');
                $p->cidade = $r['NOMECIDADE'];
                $p->uf = $r['UF'];
                $p->praca = $r['PRACA'];
                $p->obs = $r['MOV'];
                if($p->obs == null){
                    $p->obs = '';
                }
                $p->produtos = $p->getProdutosPedido();

                array_push($lista, $p);
                //break;
            }
        }

        return $lista;
    }

        
    public function getProdutosPedido(){
    
        $sql = new SqlOra();
        $ret = $sql->select("SELECT ki.codprod cod, 
            to_char(round(ki.qt, 2), '999999.99') qt
            from kokar.pcpedi ki
            where ki.numped = :numped", array("numped"=>$this->numped)
        );


        return $ret;
        
    }
    
}

?>