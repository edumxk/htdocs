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
    public $vlparc;
    public $qtparc;
    public $st;
    public $ivafonte;
    public $fontest;
    public $calculast;
    public $isentodifal;
    public $primeiracompra;
    public $hora;
    
    
    
    
    
    public static function getListaPedidos(){

        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/ListaPedidos.json');
        $ret = json_decode($str, true);*/


        $sql = new SqlOra();

        $ret = $sql->select(
            "SELECT t.data,t.hora, t.numped, t.codusur, t.nome, t.codcli, t.cliente, t.consumidorfinal, nvl(dtprimcompra,'01/01/9999') primeiracompra, t.numcar, st, usaivafontediferenciado, calculast, clientefontest, isentodifaliquotas,
            t.posicao, t.peso, to_char(round(t.valor, 2), '999999.99') valor, status, t.nomecidade, t.uf, t.codpraca, t.praca, round(t.valor/qtparc,2) vlparc, qtparc,
                        
                CASE WHEN TROCA = '0' THEN(
                     CASE WHEN EMPRESTIMO = '0' THEN(
                          CASE WHEN BONIFICACAO = '0' THEN(
                               CASE WHEN ENTREGA = '0' THEN (
                                    CASE WHEN SALDO = '0' THEN NULL ELSE SALDO END )ELSE ENTREGA END
                 )ELSE BONIFICACAO END)ELSE EMPRESTIMO END)ELSE TROCA END AS MOV,
                 nvl(numcarga,0) numcarga, c1.conferencia
          from (
                 SELECT distinct kc.data, to_char(to_date(kc.hora||to_char(kc.minuto,'00'), 'hh24mi'),'hh24:mi') hora, kc.numped, kc.codusur, ku.nome, kc.codcli, kl.cliente, kl.consumidorfinal, kl.dtprimcompra, kc.posicao,
                        to_char(round(sum(kp.pesobruto * ki.qt), 2), '999999.99') peso, pc.status,
                        sum(ki.pvenda * ki.qt) valor, round(sum(nvl(ki.st,0)),2) st, kl.usaivafontediferenciado, kl.calculast, kl.clientefontest, kl.isentodifaliquotas,
                        kd.nomecidade, kd.uf, kc.codpraca, kc.numcar, kpr.praca,REGEXP_COUNT(kpl.descricao,'/',1,'i')+1 qtparc, 
                        CASE
                          when (upper(kc.obs) like '%RETIRA%') OR
                               (upper(kc.obs1) like '%RETIRA%') OR
                               (upper(kc.obs2) like '%RETIRA%') OR
                               (upper(kc.Obsentrega1) like '%RETIRA%') OR
                               (upper(kc.Obsentrega2) like '%RETIRA%') OR
                               (upper(kc.Obsentrega3) like '%RETIRA%') THEN 'CR' ELSE '0' END AS ENTREGA,
                        CASE
                          when (upper(kc.obs) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                               (upper(kc.obs1) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                               (upper(kc.obs2) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                               (upper(kc.Obsentrega1) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                               (upper(kc.Obsentrega2) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                               (upper(kc.Obsentrega3) like '%CTD%' or upper(kc.obs) like '%RAT%') THEN 'TR' ELSE '0' END AS TROCA,
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
                        inner join kokar.pcplpag kpl on kpl.codplpag = kc.codplpag
                        full join PARALELO.cargai ci on kc.numped = ci.numped
                        full join PARALELO.cargac pc on pc.numcarga = ci.numcarga
                        
                   where kc.posicao not in ('C', 'F')
                  group by kc.data, kc.hora,kc.minuto,kc.numped, kc.codusur, ku.nome, kc.codcli, kl.cliente, kl.consumidorfinal, kc.posicao,
                           kd.nomecidade, kd.uf, kc.codpraca, kc.obs, kc.obs1, kc.obs2, usaivafontediferenciado, calculast, clientefontest, isentodifaliquotas, kc.obsentrega1,
                           kc.obsentrega2, kc.obsentrega3, ci.numcarga, pc.status, kc.numcar, kpr.praca,kpl.descricao, kl.dtprimcompra
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
                 ))c1 on c1.numped = t.numped");

        $lista = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new Pedido();
                $p->numcarga = $r['NUMCARGA'];
                $p->data = Formatador::formatarData($r['DATA']);
                $p->hora = $r['HORA'];
                $rca = $r['NOME'];
                $p->rca = Pedido::ajustaRca($rca, $r['CODUSUR']);
                $p->numped = $r['NUMPED'];
                $p->numcar = $r['NUMCAR'];
                $p->cod = $r['CODCLI'];
                $p->cliente = $r['CLIENTE'];
                $p->consumidorfinal = $r['CONSUMIDORFINAL'];
                $p->pos = $r['POSICAO'];
                $p->peso = $r['PESO'];
                $p->valor = $r['VALOR'];
                $p->cidade = $r['NOMECIDADE'];
                $p->uf = $r['UF'];
                $p->praca = $r['PRACA'];
                $p->obs = $r['MOV'];
                $p->conferencia = $r['CONFERENCIA'];
                $p->vlparc = $r['VLPARC'];
                $p->qtparc = $r['QTPARC'];
                $p->st = $r['ST'];
                $p->ivafonte = $r['USAIVAFONTEDIFERENCIADO'];
                $p->calculast = $r['CALCULAST'];
                $p->fontest = $r['CLIENTEFONTEST'];
                $p->isentodifal = $r['ISENTODIFALIQUOTAS'];
                $p->primeiracompra = $r['PRIMEIRACOMPRA'];
                $p->status = $r['STATUS'];
                
                

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

    public static function getPendenciasPedidosEspecial($numped){
        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/pendencias.json');
        $arr = json_decode($str, true);*/
                
        $sql = new SqlOra();

        $ret = $sql->select("SELECT * from (SELECT t.codprod, t.descricao, t.qt, t.peso,
        ke.qtestger-ke.qtbloqueada-ke.qtreserv as qtdisp,
        CASE 
          WHEN T.QT <= ke.qtestger-ke.qtbloqueada-ke.qtreserv
            OR T.POSICAO IN ('L','M')
          THEN 'S' ELSE 'N' END AS DISP,
        CASE 
          WHEN T.QT > ke.qtestger-ke.qtbloqueada-ke.qtreserv
            AND T.POSICAO IN ('P','B')
          THEN (ke.qtestger-ke.qtbloqueada-ke.qtreserv-T.QT)*T.PESOBRUTO*(-1) ELSE 0 END AS PESOP
        
        from (
        select ki.codprod, 
                kp.descricao,
                sum(ki.qt) qt,
                sum (ki.qt*kp.pesobruto) peso,
                KP.PESOBRUTO,
                KI.POSICAO
                
        from PARALELO.cargai i inner join kokar.pcpedi ki on i.numped = ki.numped
                    inner join kokar.pcprodut kp on ki.codprod = kp.codprod
                    inner join kokar.pcpedc kc on kc.numped = ki.numped
        where kc.posicao in ('P','B','L','M')
        and kc.numped = :numped
        group by ki.codprod, kp.descricao, KP.PESOBRUTO, KI.POSICAO
        )t inner join kokar.pcest ke on ke.codprod = t.codprod
        GROUP BY t.codprod, t.descricao, t.qt, t.peso, ke.qtestger, ke.qtbloqueada, ke.qtreserv, T.PESOBRUTO, T.POSICAO
        order by t.descricao)t1
        left join(
select codprodp, qtprod, t3.codproducao, previsao, status from
((select codprod codprodp, sum(qt) qtprod from paralelo.mproducaoi
where status not in ('S','F') and dtexclusao is null
group by codprod)t2       
INNER JOIN
( select distinct * from (
   select  First_Value(li.codproducao) OVER (PARTITION BY li.codprod ORDER BY dtproducao, horaproducao) codproducao, codprod
   from paralelo.mproducaoc lc
   inner join paralelo.mproducaoi li on li.codproducao = lc.codproducao 
   where lc.status not in ('S', 'F')and lI.dtexclusao is null
   order by codprod, dtproducao, horaproducao)
 )t3 on t3.codprod = t2.codprodp)
 inner join(
        select (replace(to_char(extract(day from dtproducao),'00')||'/'||
        to_char(extract(month from dtproducao),'00'),' ','')||' - '|| horaproducao) previsao, codproducao, status
        from paralelo.mproducaoc where status not in('F', 'B'))t4 on t4.codproducao = t3.codproducao
 )t5
 on t5.codprodp = t1.codprod  
 order by descricao",array(":numped"=>$numped)
        );

        for($i=0; $i<sizeof($ret); $i++){
            $ret[$i]['DESCRICAO'] = utf8_encode($ret[$i]['DESCRICAO']);
            switch ( $ret[$i]['STATUS']):
                case ('A'):
                     $ret[$i]['STATUS'] = "ABERTURA/OP";
                    break;
                case ('P'):
                     $ret[$i]['STATUS'] = "PESAGEM";
                    break;
                case ('D'):
                     $ret[$i]['STATUS'] = "DISPERSÃO/BASE";
                    break;
                case ('L'):
                     $ret[$i]['STATUS'] = "LABORATÓRIO";
                    break;
                case ('C'):
                     $ret[$i]['STATUS'] = "COR";
                    break;
                case ('E'):
                     $ret[$i]['STATUS'] = "ENVASE";
                    break;
                case ('B'):
                     $ret[$i]['STATUS'] = "CORREÇÃO";
                    break;
                case ('F'):
                     $ret[$i]['STATUS'] = "FINALIZADO";
                    break;
                case ('S'):
                     $ret[$i]['STATUS'] = "AGUARDANDO";
                    break;
            endswitch;
            if($ret[$i]['QTPROD'] == null){
               
                $ret[$i]['STATUS'] ='';
                $ret[$i]['QTPROD'] ='';
                $ret[$i]['PREVISAO'] ='';
            } 
        }
        
        return $ret;
    }

    public static function getFaltasCliente($cod){
        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/pendencias.json');
        $arr = json_decode($str, true);*/
                
        $sql = new SqlOra();

        $arr = $sql->select("SELECT I.codprod,I.NUMNOTA, P.descricao, I.QT qtfalta,
        E.QTESTGER - E.QTBLOQUEADA - E.QTRESERV AS estoque, I.data, I.motivo
        FROM PARALELO.FALTAI I
        INNER JOIN kokar.PCPRODUT P 
        ON P.CODPROD = I.CODPROD
        INNER JOIN kokar.PCEST E ON E.CODPROD = I.CODPROD
        WHERE I.CODCLI = :COD AND I.POSICAO = 'P'
        ORDER BY I.CODCLI, I.DATA, P.DESCRICAO",array(":COD"=>$cod)
        );
        for($i = 0; $i<sizeof($arr); $i++){
            $arr[$i]['DESCRICAO'] =  utf8_encode($arr[$i]['DESCRICAO']);
            $arr[$i]['MOTIVO'] =  utf8_encode($arr[$i]['MOTIVO']);
        }
        return $arr;
    }


    public static function ajustaRca($nome, $codusur){
        /*
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
        }elseif(strpos($nome, 'HIGOR') !== false) {
            return '38 - HIGOR';
        }elseif(strpos($nome, 'HANNA') !== false) {
            return '39 - HANNA';
        }else {
            return $nome;
        }*/
        //retorna codusur e primeiro nome
        if($codusur == 45){
            return '45 - PEPE';
        }
        if($codusur == 44){
            return '44 - CONDOR';
        }
        return $codusur . ' - ' . explode(' ', $nome)[0];
    }

    public static function getListaPedidosDist(){

        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/ListaPedidos.json');
        $ret = json_decode($str, true);*/


        $sql = new SqlOra();

        $ret = $sql->select(
            "SELECT data, numped, codusur, nome, codcli, cliente, numcar, posicao, peso, valor, nomecidade, uf, codpraca, praca, codcob
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
                    kd.nomecidade, kd.uf, kc.codpraca, kc.numcar, kpr.praca, kc.codcob
                    CASE
                      when (upper(kc.obs) like '%RETIRA%') OR
                           (upper(kc.obs1) like '%RETIRA%') OR
                           (upper(kc.obs2) like '%RETIRA%') OR
                           (upper(kc.Obsentrega1) like '%RETIRA%') OR
                           (upper(kc.Obsentrega2) like '%RETIRA%') OR
                           (upper(kc.Obsentrega3) like '%RETIRA%') THEN 'CR' ELSE '0' END AS ENTREGA,
                    CASE
                      when (upper(kc.obs) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                           (upper(kc.obs1) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                           (upper(kc.obs2) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                           (upper(kc.Obsentrega1) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                           (upper(kc.Obsentrega2) like '%CTD%' or upper(kc.obs) like '%RAT%') OR
                           (upper(kc.Obsentrega3) like '%CTD%' or upper(kc.obs) like '%RAT%') THEN 'TR' ELSE '0' END AS TROCA,
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
                       kc.obsentrega2, kc.obsentrega3, ci.numcarga, kc.numcar, kpr.praca, kc.codcob
              order by kc.codusur, kd.nomecidade, kl.cliente)"
        );

        $lista = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new Pedido();
                $p->numcarga = $r['NUMCARGA'];
                $p->data = Formatador::formatarData($r['DATA']);
                $p->hora = $r['HORA'];
                $rca = $r['NOME'];
                $p->rca = Pedido::ajustaRca($rca, $r['CODUSUR']);
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
class Falta{
    public $clientef;
    public $posicaof;

    public static function getFaltas(){
        
        $sql = new SqlOra();
        $ret = $sql->select("SELECT distinct CODCLI, POSICAO
        from paralelo.faltaI 
        where posicao = 'P'
        ORDER BY CODCLI");

        $lista = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new Falta();
                $p->clientef = $r['CODCLI'];
                $p->posicaof = $r['POSICAO'];
        
        array_push($lista, $p);
            }
        }
        return ($lista);
    }
}

?>