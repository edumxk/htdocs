<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');



class Carga{
    public $numcarga;
    public $nome;
    public $peso;
    public $dtAbertura;
    public $veiculo;
    public $valor;
    public $dtPrevisao;
    public $dtProducao;
    public $qtPedidos;
    public $fechado;

    public function getPesoValor(){
        // $str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/CargaPesoValor.json');
        // $json = json_decode($str, true);

        // $subarr = [];
        // foreach($json as $j){
        //     if($j['NUMCARGA']==$this->numcarga){
        //         $subarr = $j;
        //     }
        // }
        // $ret = [$subarr];
        
        //$ret = [$json];

        $sql = new SqlOra();
        $ret = $sql->select("SELECT count(distinct kc.numped) quant, 
            to_char(nvl(sum(ki.pvenda*ki.qt),0), '999999.99') valor, 
            to_char(nvl(sum(kp.pesobruto*ki.qt),0), '999999.99') peso
            from cargai ci inner join kokar.pcpedc kc on kc.numped = ci.numped
                        inner join kokar.pcpedi ki on kc.numped = ki.numped
                        inner join kokar.pcprodut kp on ki.codprod = kp.codprod
            where ci.numcarga = :numcar
            and kc.posicao not in ('C', 'F')",array("numcar"=>$this->numcarga)
        );




        
        $qt = $ret[0]['QUANT'];
        $peso = $ret[0]['PESO'];
        $valor = $ret[0]['VALOR'];
        
        $lista = ['qt'=>$qt, 'peso'=>$peso, 'valor'=>$valor];

        return $lista;
    }



    public static function getListaCargas(){
        // $str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/ListaCargas.json');
        // $ret = json_decode($str, true);
        

        $sql = new SqlOra();

        $ret = $sql->select("SELECT c.numcarga,
                                    nome,
                                    TO_DATE(dtabertura, 'DD/MM/YY')dtabertura,
                                    veiculo,
                                    TO_DATE(dtprevisao, 'DD/MM/YY')dtprevisao,
                                    TO_DATE(dtproducao, 'DD/MM/YY')dtproducao,
                                    fechado,
                                    TO_DATE(DTSAIDA, 'DD/MM/YY')dtsaida,
                                    TO_DATE(DTFECHAMENTO, 'DD/MM/YY')dtfechamento,
                                    c.STATUS
                                    
                            from cargac c 
                            order by dtprevisao, nome desc"
        );

        $lista = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new Carga();
                $p->numcarga = $r['NUMCARGA'];
                $p->nome = $r['NOME'];
                $p->dtAbertura = $r['DTABERTURA'];
                $p->veiculo = $r['VEICULO'];
                $p->status = $r['STATUS'];
                $ret = $p->getPesoValor();
                $p->peso = $ret['peso'];
                $p->valor = $ret['valor'];
                $p->dtPrevisao = Formatador::formatarData($r['DTPREVISAO']);
                $p->dtProducao = Formatador::formatarData($r['DTPRODUCAO']);
                $p->dtSaida = Formatador::formatarData($r['DTSAIDA']);
                $p->dtFechamento = Formatador::formatarData($r['DTFECHAMENTO']);
                $p->dtPainel = $p->dtAbertura;
               
                /*
                witch($p->status){
                     case 'A':
                        $p->dtPainel = $p->dtAbertura;
                        break;
                    case 'P':
                        $p->dtPainel = $p->dtProducao;
                        break;
                    case 'S':
                        $p->dtPainel = $p->dtProducao;
                        break;
                    case 'F':
                        $p->dtPainel = $p->dtProducao;
                        break;
                }
                   

                /*if($r['NOME'] == 'RENACOR' || $r['NOME'] == 'RETIRA'){
                    $p->dtPrevisao = Formatador::formatarData(date('d/m/Y')); 
                }else{
                    $p->dtPrevisao = Formatador::formatarData($r['DTPREVISAO']);
                }*/
                
                $p->qtPedidos = $ret['qt'];
                $p->fechado = $r['FECHADO'];


                array_push($lista, $p);
            }
        }

        return $lista;
    }

    public static function getPendencias($numcarga){
        /*$str = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/modulos/modcargas/recursos/json/pendencias.json');
        $arr = json_decode($str, true);*/
                
        $sql = new SqlOra();

        $ret = $sql->select("SELECT * from (SELECT codprod, descricao, qt,  qtdisp, TO_CHAR(peso-(qtdisp*pesobruto), '999999990.999')peso
        from (
        select ki.codprod, 
                kp.descricao,
                kp.pesobruto,
                sum(ki.qt) qt,
                sum (ki.qt*kp.pesobruto) peso,
                ke.qtestger-ke.qtbloqueada-ke.qtreserv as qtdisp
        from paralelo.cargai i 
        inner join kokar.pcpedi ki on i.numped = ki.numped
        inner join kokar.pcprodut kp on ki.codprod = kp.codprod
        inner join kokar.pcest ke on ke.codprod = kp.codprod
        where ki.posicao in ('P','B')-- and qt>ke.qtestger-ke.qtbloqueada-ke.qtreserv
        and numcarga = :numcarga
        group by ki.codprod, kp.pesobruto, kp.descricao, ke.qtestger-ke.qtbloqueada-ke.qtreserv
        ) where qtdisp < qt 
        order by descricao)t1
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
 order by descricao",array(":numcarga"=>$numcarga)
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

    //Mudar de carga para SEM CARGA
    public static function setSemCarga($numped){
        $sql = new SqlOra();
        return $sql->delete("DELETE from cargai where numped = :numped", array(":numped"=>$numped));
    }

    //Mudar de SEM CARGA para CARGA NOVA;
    public static function setCargaPedido($numped, $cargaNovo){
        $sql = new SqlOra();
        return $sql->insert("INSERT into cargai (numcarga, numped)
            values (:numcarga, :numped)", array(
            ":numcarga"=>$cargaNovo, ":numped"=>$numped)
        );
    }
    //Mudar de SEM CARGA para CARGA NOVA em Grupo;
    public static function setCargaPedidoGrupo($numped, $cargaNovo){
        //return json_encode($numped);
        $sql = new SqlOra();
        try{
            foreach($numped as $p){
                $sql->delete("DELETE from cargai where numped = :numped", array(":numped"=>$p));
                $sql->insert("INSERT into cargai (numcarga, numped)
                    values (:numcarga, :numped)", array(
                    ":numcarga"=>$cargaNovo, ":numped"=>$p)
                );
            }
            return "ok";
        }catch(Exception $e){
            return $e;
        }
    }

    //Essa parte é para mudar de uma CARGA para outra;
    public static function atualizaCarga($numped, $cargaNovo){
        $sql = new SqlOra();
        return $sql->insert("UPDATE cargai set numcarga = :numcarga
            where numped = :numped", array(
            ":numped"=>$numped,":numcarga"=>$cargaNovo)
        );
    }

    public static function setNovaCarga($nome, $veiculo, $data){
        $sql = new SqlOra();

        try{
            $maxId = $sql->select("SELECT nvl(max(numcarga),0)+1 numcarga from cargac c");
            $numcarga =  $maxId[0]['NUMCARGA'];

            return $sql->insert("INSERT into cargac (numcarga, nome, dtabertura, veiculo, dtprevisao)
                values (:numcarga, :nome, to_date(sysdate), :veiculo, to_date(:dtprevisao,'DD/MM/YYYY'))", array(
                ":numcarga"=>$numcarga, ":nome"=>$nome, ":veiculo"=>$veiculo, ":dtprevisao"=>$data)
            );
        }catch(Exception $e){
            return $e;
        }
    }

    public static function deleteCarga($numcarga){
        $sql = new SqlOra();

        try{
            $sql->delete("DELETE from cargac where numcarga = :numcarga", array(":numcarga"=>$numcarga));
            return $sql->delete("DELETE from cargai where numcarga = :numcarga", array(":numcarga"=>$numcarga));

        }catch(Exception $e){
            return "erro";  
        }

    }

    public static function getDadoCarga($numcarga){

        /*$str = file_get_contents('../recursos/json/DadosCarga.json');
        return json_decode($str, true);*/
        $sql = new SqlOra();

        return $sql->select("SELECT numcarga,
                    nome,
                    veiculo,
                    TO_DATE(dtprevisao, 'DD/MM/YY') dtprevisao
            from cargac c where c.numcarga = :numcarga",
            array(":numcarga"=>$numcarga)
        );
    }

    public static function editarCarga($numcarga, $nome, $veiculo, $data){
        $sql = new SqlOra();
        return $sql->insert("UPDATE cargac 
                set nome = :nome,
                    veiculo = :veiculo,
                    dtprevisao = TO_DATE(:dtprevisao, 'DD/MM/YY'),
                    dtproducao = TO_DATE(:dtprevisao, 'DD/MM/YY')-1
            where numcarga = :numcarga", array(
            ":numcarga"=>$numcarga,
            ":nome"=>$nome,
            ":veiculo"=>$veiculo,
            ":dtprevisao"=>$data)
        );
    }
    //Fecha a carga
    public static function travaCarga($numcarga){
        $sql = new SqlOra();
        return $sql->update("update cargac set fechado = 1 where numcarga = :numcarga", array(":numcarga"=>$numcarga));
    }
    //Reabre a carga
    public static function abreCarga($numcarga){
        $sql = new SqlOra();
        return $sql->update("update cargac set fechado = 0 where numcarga = :numcarga", array(":numcarga"=>$numcarga));
    }
    //Checa se carga está aberta
    public static function statusCarga($numcarga){
        $sql = new SqlOra();
        $ret = $sql->select("SELECT fechado from cargac where numcarga = :numcarga", array(":numcarga"=>$numcarga));
        return $ret;
    }
    public static function getSaldoCarga($cargas)
    {
        $sql = new SqlOra();
        $varCargas = "";
        foreach ($cargas as $c) {
            if (strlen($varCargas) == 0) {
                $varCargas .= $c;
            } else {
                $varCargas .= ',' . $c;
            }
        }

        $ret = $sql->select(
            "SELECT distinct t1.*,t5.*, d.codprod promo from (SELECT codprod, descricao, pesobruto, qt, qtest, TO_CHAR(pesopendente-(qtest*pesobruto), '999999990.999')pesopendente
            from (
            SELECT i.codprod, p.descricao,p.pesobruto, sum(i.qt) qt, e.qtestger-e.qtreserv-e.qtbloqueada as qtest,
            sum(i.qt*p.pesobruto) as pesopendente
            
            from paralelo.cargac cc inner join paralelo.cargai ci on cc.numcarga = ci.numcarga
                inner join kokar.pcpedi i on ci.numped = i.numped
                inner join kokar.pcprodut p on p.codprod = i.codprod
                inner join kokar.pcpedc c on c.numped = i.numped
                inner join kokar.pcest e on e.codprod = i.codprod
            where cc.numcarga in($varCargas)
              and c.posicao in ('P')
            group by i.codprod, p.descricao, p.pesobruto, e.qtestger-e.qtreserv-e.qtbloqueada)
            where qt > qtest    
            )t1
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
     left join kokar.pcdesconto d on d.codprod = t1.codprod and d.dtfim >= to_date(sysdate)
     order by t1.descricao"

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

    public static function getDisponivel($carga){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT distinct numped from (
            select numped , 
                   case when ((qt/multiplo)/3) <= qtestd then 1
                   else 0 end as teste
                       from (
                       select c.numped, i.codprod, i.qt/p.multiplo qt, p.multiplo,
                                                 
                       case when (e.qtestger-e.qtbloqueada-e.qtreserv)/p.multiplo < 1 then 0 else ((e.qtestger-e.qtbloqueada-e.qtreserv)/p.multiplo) end as qtestd
                                       
                       from kokar.pcpedc c inner join kokar.pcpedi i on c.numped = i.numped
                           inner join paralelo.cargai ci on ci.numped = c.numped
                           inner join paralelo.cargac cc on cc.numcarga = ci.numcarga
                           inner join kokar.pcest e on e.codprod = i.codprod
                           inner join kokar.pcprodut p on p.codprod = e.codprod
                       where c.posicao = 'P'
                       and cc.numcarga = :carga
                                  ))
                   where teste  = 1", array(":carga"=>$carga)  
        );
        return $ret;
    }   

    public static function getFaltas($codcli){
        $sql = new SqlOra();

        $ret = $sql->select("SELECT distinct numped from (
            select numped , 
                   case when ((qt/multiplo)/3) <= qtestd then 1
                   else 0 end as teste
                       from (
                       select c.numped, i.codprod, i.qt/p.multiplo qt, p.multiplo,
                                                 
                       case when (e.qtestger-e.qtbloqueada-e.qtreserv)/p.multiplo < 1 then 0 else ((e.qtestger-e.qtbloqueada-e.qtreserv)/p.multiplo) end as qtestd
                                       
                       from kokar.pcpedc c inner join kokar.pcpedi i on c.numped = i.numped
                           inner join paralelo.cargai ci on ci.numped = c.numped
                           inner join paralelo.cargac cc on cc.numcarga = ci.numcarga
                           inner join kokar.pcest e on e.codprod = i.codprod
                           inner join kokar.pcprodut p on p.codprod = e.codprod
                       where c.posicao = 'P'
                       and cc.numcarga = :carga
                                  ))
                   where teste  = 1", array(":carga"=>$codcli)  
        );
        return $ret;
    } 
}
?>