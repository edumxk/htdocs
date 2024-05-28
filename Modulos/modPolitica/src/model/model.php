<?php
error_reporting(E_ALL & ~E_WARNING);
date_default_timezone_set('America/Araguaina');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/Cliente.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/Politica.php');

class ModelPoliticas{

    public static function getObs($codcli){
        $sql = new SqlOra();
        $ret = $sql->select("SELECT max(codhist)hist, obs from paralelo.polhistc where codcli = $codcli
                            group by obs order by hist desc");
        if(sizeof($ret)==0)
            return 'Insira uma Obs!';
        return $ret[0]['OBS'];

    }

    public static function getClientes(){
        $sql = new SqlOra();
        $clientes = [];
        try{
            $clientes = $sql->select("SELECT codcli, cliente, nomecidade cidade, uf, codusur, nome,
            DIAS, ultcompra, total, numregiao,
            CASE
              WHEN total = 0
                THEN 'N/A'
            WHEN extract(month from ULTCOMPRA) =
                extract(month from to_date(sysdate)) and
                extract(year from ULTCOMPRA) =
                extract(year from to_date(sysdate)) then
            'POSITIVO'
            WHEN DIAS <= 90 THEN
            'ATIVO'
            WHEN DIAS > 90 THEN
            'INATIVO'
            END AS STATUS,
            politica
            from (
            select count(p.numped) qt,
                            CASE WHEN MAX(p.data) IS NULL THEN
                            nvl(to_date(sysdate) - to_date(C.DTCADASTRO), null)
                            ELSE
                            nvl(to_date(sysdate) - to_date(max(p.data)), null) END AS dias,
                            CASE
                            WHEN to_date(max(p.data)) IS NOT NULL THEN
                            to_date(max(p.data))
                            WHEN to_date(max(p.data)) IS NULL THEN
                            to_date(C.DTCADASTRO)
                            END AS ultcompra,
                            c.codcli,
                            c.cliente,
                            c.fantasia,
                            m.nomecidade,
                            m.uf,
                            u.codusur,
                            u.nome,
                            nvl(l.ativo, 0) politica,
                            NVL(round(avg(p.vlatend), 2),0) total,
                            c.numregiaocli numregiao
                    from kokar.pcclient c
                    
                    left join kokar.pcpedc p
                        on c.codcli = p.codcli --and p.data > '01/01/2021'
                    left join kokar.pcusuari u
                        on c.codusur1 = u.codusur
                    left join kokar.pccidade m
                        on c.codcidade = m.codcidade
                    left join paralelo.pcpoliticas l
                        on l.codcli = c.codcli
                    where C.CODCLI not in (1,2,3,4)
                    and c.dtexclusao is null
                    group by c.codcli,
                            c.cliente,
                            u.codusur,
                            u.nome,
                            c.fantasia,
                            m.nomecidade,
                            m.uf,
                            l.ativo,
                            c.numregiaocli,
                            C.DTCADASTRO)
            order by DIAS, ultcompra desc, cliente");
        }
        catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        $ret = [];
        $politica = '';
        foreach($clientes as $r){
            switch($r['POLITICA']){
                case 0:
                    $politica = 'VAZIO';
                    break;
                case 1:
                    $politica = 'ATIVO';
                    break;
                case 2:
                    $politica = 'INATIVA';
                    break;
                case 3:
                    $politica = 'EXCLUIDA';
                    break;
            }
            array_push($ret, new Cliente(
                $r['CODCLI'], ucfirst(substr(($r['CLIENTE']), 0, 35)), $r['CIDADE'], $r['UF'], $r['CODUSUR'].'-'.explode(' ', $r['NOME'])[0],
                $r['DIAS'], $r['TOTAL'], $r['STATUS'], $politica, $r['NUMREGIAO'], $r['ULTCOMPRA']
            ));
        }
        return $ret;
    }

    public static function getPoliticas($codCli, $numRegiao, $codPerfil = null){
        $sql = new SqlOra();
        $ret = [];
        try{
            $ret = $sql->select("SELECT t1.codgrupo, t1.descricao, t1.codprod,
                    nvl((select percdesc from kokar.pcdesconto d inner join kokar.pcdescontoitem di on d.coddesconto = di.coddesconto 
                            where d.codcli = :codCli and di.valor_num = t1.codgrupo
                            and d.descricao like :codcli||'%'),0) percdesc,
                            nvl((select distinct dtfim from kokar.pcdesconto d inner join kokar.pcdescontoitem di on d.coddesconto = di.coddesconto 
                            where d.codcli = :codCli and di.valor_num = t1.codgrupo
                             and d.descricao like :codcli||'%'),sysdate) dtfim,
                            nvl(to_char(t.pvenda1-t.vlipi, '9999999.9999'),0) tabela,
                    nvl((select ativo from paralelo.pcpoliticas where codcli = :codCli),0) ativo,
                    cl.cliente, cl.codcli
                from 
                (
                    select c.codgrupo, c.descricao, min(i.coditem) codprod 
                    from kokar.pcgruposcampanhac c inner join kokar.pcgruposcampanhai i on c.codgrupo = i.codgrupo and c.tipo = 'GP'
                    where i.dtexclusao is null and c.dtexclusao is null    
                    group by c.codgrupo, c.descricao
                    order by c.descricao
                )t1 inner join kokar.pctabpr t on t.codprod = t1.codprod and t.numregiao = :numRegiao
                inner join kokar.pcclient cl on cl.codcli = :codCli
                ", [ ':codCli' => $codCli, ':numRegiao' => $numRegiao ]);
            
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        if($codPerfil != null){
            foreach($ret as $k => $r){
                $ret[$k]['CODPERFIL'] = $codPerfil;
            }
        }
        return $ret;
    }

    public static function atualizarPolitica($codCli, array $listaGrupo, $obs,  $codUser){
        $sql = new SqlOra();

        //Preenche a lista com associação entre novo desconto e coddesconto.
        $hora = date('H:i:s');
        $lista = [];
        $descontos = $sql->select("SELECT c.codgrupo GP, a.coddesconto, percdesc, c.descricao from (SELECT round(i.valor_num,0) GP , i.coddesconto, d.percdesc
        from kokar.pcdesconto d inner join kokar.pcdescontoitem i on d.coddesconto = i.coddesconto
        where d.codcli = :codCli and REGEXP_LIKE(SUBSTR(descricao, 1, 1), '^[0-9]$'))a
        right join kokar.pcgruposcampanhac c on c.codgrupo = a.GP and c.tipo = 'GP'
        order by GP",
            array(":codCli"=>$codCli)
        );

        foreach($descontos as $d):
            foreach($listaGrupo as $l):
                if($d['GP']==$l['codGrupo'] && $d['PERCDESC']!=$l['percDesc'])
                array_push($lista, ['CODDESCONTO' => $d['CODDESCONTO'], 'GP' =>$d['GP'], 'PERCDESC' =>$l['percDesc'], 'DESCANTIGO' => $d['PERCDESC'], 'TABELA' => $l['tabela'], 'descricao' => $d['DESCRICAO'] ]);
            endforeach;
        endforeach;
        //end;

        //Preencher o Log;
            // Ler a politica a ser atualizada e preencher o log antes de atualizar.
        $codHist = $sql->select("SELECT max(CODHIST) CODHIST FROM PARALELO.POLHISTC")[0]['CODHIST'] +1;
        $sql->insert("INSERT INTO PARALELO.POLHISTC C (CODHIST, CODCLI, CODUSER, DATA, HORA, OBS)
        VALUES($codHist ,:CODCLI, :CODUSER, TO_DATE(SYSDATE), :HORA, :OBS)",
        [':CODCLI' => $codCli, ':CODUSER' => $codUser, ':HORA' => $hora, ':OBS' => $obs]);

        $temp = 0;
        foreach($lista as $l): //inicia atualização de desconto na pcdesconto e atualiza o log.
            if($l['CODDESCONTO']!= null):
                $temp = $sql->update2("UPDATE kokar.PCDESCONTO SET PERCDESC = :PERCDESC WHERE CODDESCONTO  = :CODDESCONTO",
                [':PERCDESC' => $l['PERCDESC'], ':CODDESCONTO'=> $l['CODDESCONTO']]);
                echo $temp;
            endif;
            if($temp!='1'){
               // return 'erro ao salvar politica';
            }else
            echo $sql->insert("INSERT INTO PARALELO.POLHISTI C (CODHIST, CODGRUPO, DESCANT, DESCNOVO, TABELA, CODDESCONTO)
            VALUES(:CODHIST , :CODGRUPO, :DESCANT, :DESCNOVO, :TABELA, :CODDESCONTO)",
            [':CODHIST' => $codHist, ':CODGRUPO' => $l['GP'], ':DESCANT' => $l['DESCANTIGO'], 
            ':DESCNOVO' => $l['PERCDESC'], ':TABELA' => $l['TABELA'], ':CODDESCONTO' => $l['CODDESCONTO']]);
        endforeach;
        $lista2= [];
        foreach($lista as $r):
            if($r['DESCANTIGO']==null)
                array_push($lista2,['codgrupo' => $r['GP'],'desconto' => $r['PERCDESC'] , 'codcli' => $codCli, 'descricao' => $r['descricao'], 'tabela' => $r['TABELA'],
                'codhist' => $codHist
                ] );
        endforeach;
        if(sizeof($lista2)>0){
            echo json_encode($lista2);
            ModelPoliticas::criarGrupo($lista2);
        }
        else
            return 'lista vazia';
    }

    public static function buscaAlteracoes(){
        $sql = new SqlOra();
        
        return $sql->select("SELECT c.codhist, c.codcli, cl.cliente, r.nome, data||' '||hora datahora, c.obs  from paralelo.polhistc c
        inner join paralelo.ratuser r on r.coduser = c.coduser
        inner join kokar.pcclient cl on cl.codcli = c.codcli
        order by codhist desc");
    }

    public static function getHistorico($id){
        $sql = new SqlOra();
        
        return $sql->select("SELECT c.codhist, c.codgrupo, g.descricao, c.descant, c.descnovo, c.tabela from paralelo.polhisti c
        inner join kokar.pcgruposcampanhac g on g.codgrupo = c.codgrupo and g.tipo = 'GP'
        where codhist = $id
        order by codhist, descricao");
    }

    public static function criarGrupo($array){
        $codCli = $array[0]['codcli'];
        $arrPoliticas = [];

        $sql = new SqlOra();

        $cod = intval($sql->select("SELECT max(CODDESCONTO) COD from kokar.pcdesconto")[0]['COD'])+1;

        $pcDesconto = "INSERT ALL\n";
        
        $pcDescontoItem = "INSERT ALL\n";

        foreach($array as $g){
            array_push($arrPoliticas, ['codcli' => $codCli, 'codgrupo' => $g['codgrupo'], 'descricao' => utf8_encode($g['descricao']), 'percdesc' => $g['desconto'], 'codhist' => $g['codhist'], 'tabela' => $g['tabela']]);
        }
        foreach($arrPoliticas as $p){

            if($p['percdesc'] == 0){
                $p['percdesc'] = 35;
            }



        $pcDesconto .= "INTO kokar.pcdesconto
                    (CODDESCONTO, CODCLI, PERCDESC, DTINICIO, DTFIM, 
                    BASECREDDEBRCA, UTILIZADESCREDE, CODFUNCLANC, DATALANC, 
                    CODFUNCULTALTER, DATAULTALTER, ORIGEMPED, APLICADESCONTO, CREDITASOBREPOLITICA, 
                    TIPO, ALTERAPTABELA, PRIORITARIA, QUESTIONAUSOPRIORITARIA, CODFILIAL, 
                    APENASPLPAGMAX, APLICADESCSIMPLESNACIONAL, PRIORITARIAGERAL, CONSIDERACALCGIROMEDIC, PERCDESCMAX, 
                    SYNCFV, QTDAPLICACOESDESC, QTMINESTPARADESC, TIPOCONTACORRENTE, PERCFORNEC, 
                    DESCRICAO, PERCCUSTFORNEC)
                VALUES (".$cod.", ".$codCli.", ".$p['percdesc'].", to_date(sysdate), '31/12/2098', 
                    'N', 'N', 32, sysdate, 
                    32, to_date(sysdate), 'O', 'S', 'N', 
                    'C', 'N', 'S', 'N', 1, 
                    'N', 'T', 'S', 'N', 0, 
                    'S', 0, 0, 'R', 0, 
                    '".$codCli." - ".$p['descricao']."', 0)
                ";
            //Cria insert da tabela PCDESCONTOITEM;
            $temp = $p['codgrupo'];
            $pcDescontoItem .= " INTO kokar.pcdescontoitem (coddesconto, tipo, valor_num) VALUES (".$cod.", 'GP', ". $temp.") ";

            //insere no histórico
            $sql->insert("INSERT INTO PARALELO.POLHISTI C (CODHIST, CODGRUPO, DESCANT, DESCNOVO, TABELA, CODDESCONTO)
            VALUES(:CODHIST , :CODGRUPO, :DESCANT, :DESCNOVO, :TABELA, :CODDESCONTO)",
            [':CODHIST' =>$p['codhist'], ':CODGRUPO' =>  $temp, ':DESCANT' => 0, 
            ':DESCNOVO' => $p['percdesc'], ':TABELA' => $p['tabela'], ':CODDESCONTO' => $cod]);
            $cod++;
        }
        //Encerra as strings para inclusão das tabelas;
        $pcDesconto .="SELECT * from dual";
        $pcDescontoItem .="SELECT * from dual";

        $retorno = "";
        
        if($sql->select("SELECT CODCLI from pcpoliticas where codcli = $codCli")[0]['CODCLI'] != $codCli){
            $obs = utf8_decode("Primeira Política");
            $pcPolitica = "INSERT into pcpoliticas
                (codcli, ativo, observacao)
                values($codCli, 1, '$obs')";
            $retorno .= $sql->insertDirect($pcPolitica);
        }

        
        $retorno .= $sql->insertDirect($pcDesconto);
        $retorno .= $sql->insertDirect($pcDescontoItem);
        
        // return $retorno;

        //echo $codCli."\n";
        return sizeof($arrPoliticas);
    }

    public static function desativar($codCli, $codUser){
        $sql = new SqlOra();
        $hora = date('H:i:s');
    
        $arr1 = "UPDATE paralelo.pcpoliticas SET ATIVO = 2 where codcli = $codCli";
        $arr2 = "UPDATE kokar.pcdesconto SET dtfim = TO_DATE(SYSDATE)-1 where codcli = $codCli and REGEXP_LIKE(SUBSTR(descricao, 1, 1), '^[0-9]$')";
        $arr3 = "INSERT into paralelo.polhistc ( codhist, codcli, coduser, data, hora, obs) values ((select max(codhist)+1 codhistnovo from paralelo.polhistc), $codCli, $codUser, TO_DATE(SYSDATE), '$hora', 'Politica Desativada!') ";
        
        return(
            $sql->insertDirect($arr1).
            $sql->insertDirect($arr2).
            $sql->insertDirect($arr3));

    }

    public static function ativar($codCli, $codUser){
        $sql = new SqlOra();
        $hora = date('H:i:s');

    
        $arr1 = "UPDATE paralelo.pcpoliticas SET ATIVO = 1 where codcli = $codCli";
        $arr2 = "UPDATE kokar.pcdesconto SET dtfim = '31/12/2098' where codcli = $codCli and REGEXP_LIKE(SUBSTR(descricao, 1, 1), '^[0-9]$')";
        $arr3 = "INSERT INTO polhistc ( codhist, codcli, coduser, data, hora, obs) values ( (select max(codhist)+1 codhistnovo from paralelo.polhistc), $codCli, $codUser, TO_DATE(sysdate), '$hora', 'Politica Ativada!')";
        return(
        $sql->insertDirect($arr1).
        $sql->insertDirect($arr2).
        $sql->insertDirect($arr3));
        
        
    }

    public static function getCliente($codCli){
        $sql = new SqlOra();
        $arr = $sql->select("SELECT CLIENTE FROM KOKAR.PCCLIENT C WHERE C.CODCLI = $codCli")[0]['CLIENTE']; 
        return $arr;
    }

    public static function copiar($origem, $destino, $codUser){
        $sql = new SqlOra();
        $hora = date('H:i:s');
        $cliente = array_shift($sql->select("SELECT codcli||' - '|| cliente from kokar.pcclient where codcli = $origem")[0]);

        $arr3 = "INSERT into paralelo.polhistc ( codhist, codcli, coduser, data, hora, obs) 
        values ((select max(codhist)+1 codhistnovo from paralelo.polhistc), 
        $destino, $codUser, TO_DATE(SYSDATE), '$hora', 'Politica Copiada do Cliente $cliente')";
        
        $sql->insertDirect($arr3);
        $arr = $sql->update2("
        DECLARE
        T      NUMBER;
        BEGIN
        
            FOR T IN (
                SELECT d.CODDESCONTO, I.VALOR_NUM, PERCDESC FROM KOKAR.PCDESCONTO D 
                INNER JOIN KOKAR.PCDESCONTOITEM I ON I.CODDESCONTO = D.CODDESCONTO
                inner join kokar.pcgruposcampanhac c on c.codgrupo = i.valor_num and c.tipo = 'GP'
                WHERE D.CODCLI =:origem and  c.dtexclusao is null
                ) LOOP  
                
                INSERT INTO PARALELO.POLHISTI C (CODHIST, CODGRUPO, DESCANT, DESCNOVO, TABELA, CODDESCONTO)
                VALUES((SELECT MAX(CODHIST) FROM PARALELO.POLHISTC) , T.VALOR_NUM,

                (select d1.percdesc from kokar.pcdesconto d1 inner join kokar.pcdescontoitem di on d1.coddesconto = di.coddesconto 
                where d1.codcli = :destino and di.valor_num =  T.VALOR_NUM and d1.dtfim >= '01/01/2099'),
                 
                T.PERCDESC, 
                 
                (SELECT TABELA*((PERCDESC/100)-1)*-1 TABELA FROM(
                SELECT t1.codgrupo,                     
                    nvl((select percdesc from kokar.pcdesconto d inner join kokar.pcdescontoitem di on d.coddesconto = di.coddesconto 
                            where d.codcli = :destino and di.valor_num = t1.codgrupo and d.dtfim >= '01/01/2099' ),0) percdesc,
                    t.pvenda1-t.vlipi tabela
                from 
                (
                    select c.codgrupo, c.descricao, min(i.coditem) codprod 
                    from kokar.pcgruposcampanhac c inner join kokar.pcgruposcampanhai i on c.codgrupo = i.codgrupo and c.tipo = 'GP'
                    group by c.codgrupo, c.descricao
                    order by c.descricao
                )t1 inner join kokar.pctabpr t on t.codprod = t1.codprod 
                inner join kokar.pcclient cl on cl.codcli = :destino
                where t.pvenda > 0 AND t.numregiao = CL.NUMREGIAOCLI)WHERE CODGRUPO = T.VALOR_NUM) , T.CODDESCONTO);
                 
                 UPDATE KOKAR.PCDESCONTO C SET C.PERCDESC = T.PERCDESC
                WHERE CODDESCONTO IN (SELECT D.CODDESCONTO FROM KOKAR.PCDESCONTO D 
                INNER JOIN KOKAR.PCDESCONTOITEM I ON I.CODDESCONTO = D.CODDESCONTO
                WHERE D.CODCLI = :destino AND I.VALOR_NUM = T.VALOR_NUM);
        
            END LOOP;
         END;
        ", [':origem' => $origem, ':destino' => $destino]); 

        return $arr;
    }

    //Buscar Perfis de Politicas de Desconto
    public static function getPerfis(){
        $sql = new SqlOra();
        $arr = $sql->select("SELECT c.*, u.obs1 rca FROM PARALELO.POLPERFILC c 
        inner join kokar.pcusuari u on u.codusur = c.rca
        where c.ativo = 1
        order by c.codperfil, c.descricao");
        return $arr;
    }

    public static function getPerfisItem($codPerfil){
        $sql = new SqlOra();

        try{
                $arr = $sql->select("SELECT c.codgrupo, c.descricao, :codPerfil codperfil, NVL(i.desconto,0) DESCONTO from (select * from KOKAR.PCGRUPOSCAMPANHAC where DTEXCLUSAO IS NULL and tipo = 'GP')C
                                    left join (select * from PARALELO.POLPERFILI WHERE codperfil = :codPerfil)i on i.codgrupo = c.codgrupo 
                                    ORDER BY descricao", [':codPerfil' => $codPerfil]);
        }catch(Exception $e){
            $arr = [];
            echo $e->getMessage();
        }
            if(count($arr) == 0)
                return [];
        return $arr;
    }

    public static function buscarCliente($descricao){
        $descricao = str_replace(' ', '%', $descricao);
        $sql = new SqlOra();
        $arr = $sql->select("SELECT C.codcli, cliente, fantasia, D.NOMECIDADE || ' - ' || D.UF as cidadeUf FROM KOKAR.PCCLIENT C
                            inner join KOKAR.Pccidade d on D.CODCIDADE = c.Codcidade
                            inner join paralelo.pcpoliticas r on r.codcli = c.codcli AND R.ATIVO = 1
                            WHERE (CLIENTE LIKE :descricao or FANTASIA LIKE :descricao or c.codcli in (:codCli) ) and c.dtexclusao is null
                            ORDER BY CLIENTE", [':descricao' => '%'.mb_strtoupper($descricao).'%', ':codCli' => intval($descricao)]);
        return $arr;
    }

    public static function criarPerfil($dados){
        $sql = new SqlOra();
        $codPerfil = $sql->select("SELECT MAX(CODPERFIL)+1 CODPERFIL FROM PARALELO.POLPERFILC")[0]['CODPERFIL'];
        
        //verifica se $codPerfil é nulo
        if($codPerfil == null){
            $codPerfil = 1;
        }
        if($dados['codcli'] == null || $dados['codcli'] == ''){
            $dados['codcli'] = 0;
        }

        try{
            $arr = $sql->update2("INSERT INTO PARALELO.POLPERFILC (CODPERFIL, DESCRICAO, RCA, OBS, ATIVO, DATA, HORA, ORIGEM) 
            VALUES (:codPerfil, :descricao, :rca, :obs, 1, TO_DATE(SYSDATE), TO_CHAR(SYSDATE, 'HH24:MI:SS'), :codcli)",
                [':descricao' => $dados['descricao'],
                ':codPerfil' => $codPerfil,
                ':obs' => $dados['obs'],
                ':rca' => $dados['rca'],
                ':codcli' => $dados['codcli']]);

        }catch(Exception $e){
            echo 'Erro ao Criar Perfil, nenhum perfil adicionado';
            return $e->getMessage();
        }
        if($dados['codcli'] != 0){
            echo ModelPoliticas::copiarPolitica($codPerfil, $dados['codcli']);
        }
        if($arr == 1)
            return 'Perfil Criado';
        return 'Erro ao Criar Perfil, nenhum perfil adicionado';
    }
    
    public static function getRca(){
        $sql = new SqlOra();
        
        try{
            $arr = $sql->select("SELECT codusur codrca, obs1 NOME FROM KOKAR.PCUSUARI U 
            WHERE U.Dttermino IS NULL");
        }catch(Exception $e){
            echo 'Erro ao buscar RCAs';
            return $e->getMessage();
        }
        return $arr;
    }

    public static function copiarPolitica($codPerfil, $codCli){
        $sql = new SqlOra();
        try{
            $arr = $sql->update2("BEGIN
                                        FOR d in (select i.valor_num codgrupo, percdesc from kokar.pcdesconto d
                                        inner join kokar.pcdescontoitem i on i.coddesconto = d.coddesconto
                                        INNER JOIN KOKAR.PCGRUPOSCAMPANHAC C ON C.CODGRUPO = I.VALOR_NUM and c.tipo = 'GP'
                                        where codcli = :codCli AND C.DTEXCLUSAO IS NULL
                                        and d.dtfim >='01/jan/2098'
                                        and REGEXP_LIKE(SUBSTR(d.descricao, 1, 1), '^[0-9]$')
                                        order by valor_num) loop
                                            
                                            begin
                                            insert into paralelo.polperfili
                                            (codperfil, codgrupo, desconto)
                                            values (:codPerfil, d.codgrupo, d.percdesc);
                                            end;
                                            
                                            end loop;
                                        end;", [':codCli' => $codCli, ':codPerfil' => $codPerfil]);
        }catch(Exception $e){
            echo 'Erro ao copiar politica para perfil';
            return $e->getMessage();
        }
        return $arr;
    }

    public static function editarPoliticaPerfil($dados, $descricao = null, $obs = null, $codPerfil = null){

        $sql = new SqlOra();
        $ret = [];
        $politicas = [];
        try{
            $politicas = $sql->select("SELECT * FROM PARALELO.POLPERFILI WHERE CODPERFIL = :codPerfil", [':codPerfil' => $codPerfil]);
           
                if(count($dados) > 0)
                    foreach($dados as $k => $d){
                        if(count($politicas) > 0){
                            foreach($politicas as $p){
                                if($p['CODGRUPO'] == $d['codGrupo']){
                                    $dados[$k]['ativo'] = 1;
                                }
                            }
                        }
                    }
                    foreach($dados as $d){
                        if(isset($d['ativo'])){
                            if($d['desconto']>0)
                                $ret[] += $sql->update2("UPDATE paralelo.polperfili SET desconto = :desconto WHERE codperfil = :codperfil AND codgrupo = :codgrupo",
                                [':desconto' => $d['desconto'], ':codperfil' => $d['codPerfil'], ':codgrupo' => $d['codGrupo']]);
                            else
                                $ret[] += $sql->update2("DELETE FROM paralelo.polperfili WHERE codperfil = :codperfil AND codgrupo = :codgrupo",
                                [':codperfil' => $d['codPerfil'], ':codgrupo' => $d['codGrupo']]);
                        }else{
                            if($d['desconto']>0 && $d['desconto'] != null)
                                $ret[] += $sql->update2("INSERT INTO paralelo.polperfili (codperfil, codgrupo, desconto) VALUES (:codperfil, :codgrupo, :desconto)",
                                [':desconto' => $d['desconto'], ':codperfil' => $codPerfil, ':codgrupo' => $d['codGrupo']]);
                            else
                                $ret[] += $sql->update2("DELETE FROM paralelo.polperfili WHERE codperfil = :codperfil AND codgrupo = :codgrupo",
                                [':codperfil' => $codPerfil, ':codgrupo' => $d['codGrupo']]);
                        }
                    }
            
        }catch(Exception $e){
            echo 'Erro ao editar politica do perfil';
            return $e->getMessage();
        }
        try{
            if($descricao != null || $obs != null){
                $ret[] += $sql->update2("UPDATE paralelo.polperfilc SET descricao = :descricao, obs = :obs where codperfil = :codperfil",
                [':descricao' => $descricao, ':obs' => $obs, ':codperfil' => $codPerfil]);
            }
        }catch(Exception $e){
            echo 'Erro ao editar obs e descricao do perfil';
            return $e->getMessage();
        }
        return $ret;

    }

    public static function excluirPoliticaPerfil($codPerfil){
        $sql = new SqlOra();
        $ret=[];
        try{
            $ret[] = $sql->update2("UPDATE PARALELO.POLPERFILC SET ATIVO = 0 WHERE CODPERFIL = :codperfil", [':codperfil' => $codPerfil]);

        }catch(Exception $e){
            echo 'Erro ao excluir politica do perfil';
            return $e->getMessage();
        }
        return $ret;
        
    }

    public static function buscarClienteRca($codPerfil, $filtro = null){
        $sql = new SqlOra();
        
        //pega codRca do perfil;
        $codRca = $sql->select("SELECT RCA FROM PARALELO.POLPERFILC WHERE CODPERFIL = :codPerfil", [':codPerfil' => $codPerfil])[0]['RCA'];

        try{
            if($filtro == null){
                $arr = $sql->select("SELECT C.codcli, cliente, fantasia, D.NOMECIDADE || ' - ' || D.UF as cidadeUf, p.praca praca, c.codatv1 atividade, CASE WHEN DTULTCOMP IS NULL
                THEN 'N/A' 
                ELSE TO_CHAR(TO_DATE(SYSDATE) - TO_DATE(DTULTCOMP))END DIAS,
                CASE WHEN R.ATIVO IS NULL THEN 'S/P' WHEN R.ATIVO = 1 THEN 'A' ELSE 'I' END STATUS
                 FROM KOKAR.PCCLIENT C
                                    inner join KOKAR.Pccidade d on D.CODCIDADE = c.Codcidade
                                    left join paralelo.pcpoliticas r on r.codcli = c.codcli
                                    inner join kokar.pcpraca p on p.codpraca = c.codpraca
                                    WHERE c.codusur1 = :codRca and c.dtexclusao is null
                                    ORDER BY CLIENTE", [':codRca' => $codRca]);
            }else{
                $arr = $sql->select("SELECT C.codcli, cliente, fantasia, D.NOMECIDADE || ' - ' || D.UF as cidadeUf, p.praca praca, c.codatv1 atividade FROM KOKAR.PCCLIENT C
                                    inner join KOKAR.Pccidade d on D.CODCIDADE = c.Codcidade
                                    inner join kokar.pcpraca p on p.codpraca = c.codpraca
                                    WHERE c.codusur1 = :codRca and (CLIENTE LIKE :filtro or FANTASIA LIKE :filtro or c.codcli = number(:codCli) or nomecidade like :filtro) and c.dtexclusao is null
                                    ORDER BY CLIENTE", [':codRca' => $codRca, ':filtro' => '%'.mb_strtoupper($filtro).'%', ':codCli' => intval($filtro)]);
            }
        }catch(Exception $e){
            echo 'Erro ao buscar Clientes do RCAs';
            return $e->getMessage();
        }
        return $arr;
    }

    public static function getPerfilDescricaoObs($codPerfil){
        $sql = new SqlOra();
        try{
            $arr = $sql->select("SELECT DESCRICAO, OBS FROM PARALELO.POLPERFILC WHERE CODPERFIL = :codPerfil", [':codPerfil' => $codPerfil]);
        }catch(Exception $e){
            echo 'Erro ao buscar descrição e observação do perfil';
            return $e->getMessage();
        }
        return $arr[0];
    }

    public static function copiarPerfil($codPerfil, $clientes, $usuario = null){
        $sql = new SqlOra();
        $hora = date('H:i:s');

        $ret = [];
        //criando cabeçalhos
        foreach($clientes as $k => $c){
            try{
                $codHist = $sql->select("SELECT MAX(CODHIST)+1 CODHISTNOVO FROM PARALELO.POLHISTC")[0]['CODHISTNOVO'];
                //cabeçalho
                $ret[$k][] = $sql->insert("INSERT INTO paralelo.polhistc 
                                    ( codhist, codcli, coduser, data, hora, obs) 
                            values (:codHist, :codcli, :usuario, TO_DATE(SYSDATE), :hora, 'Politica Copiada do Perfil '|| :cliente)", 
                            [':codcli' => $c['codcli'], ':usuario' => $usuario, ':hora' => $hora, ':cliente' => $codPerfil, ':codHist' => $codHist]);
                
                $ret[$k][] = $sql->update2("DECLARE 
                CODIGODESC NUMBER;
                BEGIN
                  for d in( select t1.*, t2.*, g.descricao nomegrupo from(select codperfil, i.codgrupo, i.desconto descnovo from paralelo.polperfili i where codperfil = :codPerfil)t1
                                      left join (select d.percdesc descant, valor_num, d.coddesconto, 0 tabela, d.descricao from kokar.pcdesconto d 
                                      inner join kokar.pcdescontoitem di on di.coddesconto = d.coddesconto
                                      where d.codcli = :codCli)t2
                                      on t2.valor_num = t1.codgrupo
                                      inner join kokar.pcgruposcampanhac g on g.codgrupo = t1.codgrupo and g.tipo = 'GP'
                                      )loop
                      begin
                        
                      if d.coddesconto is null
                        then
                        BEGIN
                          SELECT MAX(CODDESCONTO)+1 INTO CODIGODESC FROM kokar.PCDESCONTO;
                          
                          INSERT INTO kokar.pcdesconto
                                    (CODDESCONTO, CODCLI, PERCDESC, DTINICIO, DTFIM, 
                                    BASECREDDEBRCA, UTILIZADESCREDE, CODFUNCLANC, DATALANC, 
                                    CODFUNCULTALTER, DATAULTALTER, ORIGEMPED, APLICADESCONTO, CREDITASOBREPOLITICA, 
                                    TIPO, ALTERAPTABELA, PRIORITARIA, QUESTIONAUSOPRIORITARIA, CODFILIAL, 
                                    APENASPLPAGMAX, APLICADESCSIMPLESNACIONAL, PRIORITARIAGERAL, CONSIDERACALCGIROMEDIC, PERCDESCMAX, 
                                    SYNCFV, QTDAPLICACOESDESC, QTMINESTPARADESC, TIPOCONTACORRENTE, PERCFORNEC, 
                                    DESCRICAO, PERCCUSTFORNEC)
                                VALUES (CODIGODESC, :codCli, d.descnovo, to_date(sysdate), '31/12/2098', 
                                    'N', 'N', 32, sysdate, 
                                    32, to_date(sysdate), 'O', 'S', 'N', 
                                    'C', 'N', 'S', 'N', 1, 
                                    'N', 'T', 'S', 'N', 0, 
                                    'S', 0, 0, 'R', 0, 
                                    ':codCli - '||d.nomegrupo, 0);
                                    
                           INSERT INTO kokar.pcdescontoitem i(i.coddesconto, i.tipo, i.valor_num) VALUES (CODIGODESC, 'GP', D.CODGRUPO);
                           
                           INSERT into paralelo.pcpoliticas (codcli, ativo, observacao)values(:codCli, 1, 'Primeira Política');
                                
                        end;
                      end if;
                          if d.coddesconto is null 
                            then 
                              d.coddesconto := CODIGODESC;
                          end if;
                        INSERT INTO PARALELO.POLHISTI I (CODHIST, CODGRUPO, DESCANT, DESCNOVO, TABELA, CODDESCONTO)
                                VALUES(:codHist, D.CODGRUPO, D.DESCANT, D.DESCNOVO, :tabela, D.CODDESCONTO);
                                 
                                 UPDATE KOKAR.PCDESCONTO C SET C.PERCDESC = D.DESCNOVO
                                WHERE CODDESCONTO = D.CODDESCONTO;
                         END;
                     END LOOP;
                END;", 
                            [':codPerfil' => $codPerfil, ':codCli' => $c['codcli'], ':codHist' => $codHist]); 
            }catch(Exception $e){
                echo 'Erro ao inserir historico de copia de perfil';
                return $e->getMessage();
            }

        }
        return $ret;
    }

    public static function getDadosPolitica($codPerfil, $codCli){
        $sql = new SqlOra();
        $dados = [];
        try{
            $dados = $sql->select("SELECT t1.*, t2.*, g.descricao nomegrupo, nvl(ta.tabela,0) tabela from(select codperfil, i.codgrupo, i.desconto descnovo, :codCli codCli from paralelo.polperfili i where codperfil = :codPerfil)t1
            left join (select d.percdesc descant, valor_num, d.coddesconto, d.descricao from kokar.pcdesconto d 
            inner join kokar.pcdescontoitem di on di.coddesconto = d.coddesconto
            where d.codcli = :codCli and d.descricao not like '%BLACK%')t2
            on t2.valor_num = t1.codgrupo
            inner join kokar.pcgruposcampanhac g on g.codgrupo = t1.codgrupo and g.dtexclusao is null and g.tipo = 'GP'
            left join (SELECT TABELA*((PERCDESC/100)-1)*-1 TABELA, codgrupo FROM
            (SELECT t1.codgrupo,                     
            nvl((select percdesc from kokar.pcdesconto dd inner join kokar.pcdescontoitem di on dd.coddesconto = di.coddesconto 
                  where dd.codcli = :codCli and di.valor_num = t1.codgrupo and dd.dtfim >= to_date('01/jan/2098')),0) percdesc,
                  t.pvenda1-t.vlipi tabela from  (
                  select cc.codgrupo, cc.descricao, min(ii.coditem) codprod 
                  from kokar.pcgruposcampanhac cc inner join kokar.pcgruposcampanhai ii on cc.codgrupo = ii.codgrupo and cc.tipo = 'GP'
                  where cc.dtexclusao is null AND ii.dtexclusao is null
                  group by cc.codgrupo, cc.descricao
                  order by cc.descricao
                  )t1 inner join kokar.pctabpr t on t.codprod = t1.codprod 
                  inner join kokar.pcclient cl on cl.codcli = :codCli
                  where t.pvenda > 0 AND t.numregiao = CL.NUMREGIAOCLI)A)TA
                  on TA.CODGRUPO = t1.CODGRUPO
                  where nvl(descnovo,0) != nvl(descant, 0)
                  order by nomegrupo", [':codPerfil' => $codPerfil, ':codCli' => $codCli]);
        }catch(Exception $e){
            echo 'Erro ao buscar dados da política getDadosPolitica';
            return $e->getMessage();
        }
        if(count($dados) == 0)
            return null;
        foreach($dados as $k => $d){
            $dados[$k]['DESCRICAO'] = Formatador::br_decode($d['DESCRICAO']);
            $dados[$k]['NOMEGRUPO'] = Formatador::br_decode($d['NOMEGRUPO']);
        }
        return $dados;
    }

    public static function distribuiPolitica($dados, $codUser = null){
        $sql = new SqlOra();
        $codDesconto = 0;
        $codHist = 0;
        $inserts = [];
        $updates = [];
        $flag1 = '';
        //loop de iteração para propagar politica por cliente;
        try{
            if(count($dados) > 0):
                foreach($dados as $dado){
                    if($dado != null):
                        foreach($dado as $d){
                            if($codHist == 0)
                                $codHist = $sql->select("SELECT MAX(CODHIST) codhist FROM PARALELO.POLHISTC")[0]['CODHIST'];
                            
                            if($d['CODDESCONTO'] == null){
                                if($codDesconto == 0)
                                    $codDesconto = $sql->select("SELECT MAX(CODDESCONTO)+1 coddesconto FROM KOKAR.PCDESCONTO")[0]['CODDESCONTO'];

                                $inserts[] = "INTO kokar.pcdesconto (CODDESCONTO, CODCLI, PERCDESC, DTINICIO, DTFIM, BASECREDDEBRCA, UTILIZADESCREDE, CODFUNCLANC, DATALANC, CODFUNCULTALTER, DATAULTALTER, ORIGEMPED, APLICADESCONTO, CREDITASOBREPOLITICA, TIPO, ALTERAPTABELA, PRIORITARIA, QUESTIONAUSOPRIORITARIA, CODFILIAL, APENASPLPAGMAX, APLICADESCSIMPLESNACIONAL, PRIORITARIAGERAL, CONSIDERACALCGIROMEDIC, PERCDESCMAX, SYNCFV, QTDAPLICACOESDESC, QTMINESTPARADESC, TIPOCONTACORRENTE, PERCFORNEC, DESCRICAO, PERCCUSTFORNEC) VALUES (" . $codDesconto . ", " . $d["CODCLI"] . ", " . $d["DESCNOVO"] . ", to_date(sysdate), '31/12/2098', 'N', 'N', 1, sysdate, 1, to_date(sysdate), 'O', 'S', 'N', 'C', 'N', 'S', 'N', 1, 'N', 'T', 'S', 'N', 0, 'S', 0, 0, 'R', 0, '" . $d['CODCLI'] . " - " . $d['NOMEGRUPO'] . "', 0)";    
                                
                                $inserts[] = "INTO kokar.pcdescontoitem (coddesconto, tipo, valor_num) VALUES (" . $codDesconto . " , 'GP',  " . $d['CODGRUPO'] . ")";
                                
                                if($flag1 != $d['CODCLI']){
                                    $codHist++;
                                    $inserts[] = "INTO paralelo.pcpoliticas (codcli, ativo, observacao) VALUES (" . $d['CODCLI'] . ", 1, 'Primeira Política')";
                                    $inserts[] = "INTO paralelo.polhistc (codhist, codcli, coduser, data, hora, obs) VALUES ($codHist, " . $d['CODCLI'] . ", $codUser, to_date(sysdate), to_char(sysdate, 'HH24:mi:ss'), 'Politica Inserida do Perfil ". $d["CODPERFIL"] ."')";
                                }
                                $inserts[] = "INTO paralelo.polhisti (codhist, codGrupo, descant, descnovo, tabela, coddesconto) VALUES ($codHist, " . $d['CODGRUPO'] . ", 0, " . $d['DESCNOVO'] . ", " . $d['TABELA'] . ", $codDesconto)";
                                $flag1 = $d['CODCLI'];
                                $codDesconto++;
                            }else{
                                if($d['DESCNOVO'] > 0)
                                    $updates[] = "UPDATE KOKAR.PCDESCONTO C SET C.PERCDESC = " . $d['DESCNOVO'] . " WHERE CODDESCONTO = " . $d['CODDESCONTO'];
                                else 
                                    $updates[] = "UPDATE KOKAR.PCDESCONTO C SET C.DTFIM = to_date(sysdate) WHERE CODDESCONTO = " . $d['CODDESCONTO'];
                                if($flag1 != $d['CODCLI']){
                                    $codHist++;
                                    $inserts[] = "INTO paralelo.polhistc (codhist, codcli, coduser, data, hora, obs) VALUES ($codHist, " . $d['CODCLI'] . ", $codUser, to_date(sysdate), to_char(sysdate, 'HH24:mi:ss'), 'Politica Inserida do Perfil ". $d["CODPERFIL"] ."')";
                                }
                                $inserts[] = "INTO paralelo.polhisti (codhist, codGrupo, descant, descnovo, tabela, coddesconto) VALUES ($codHist, " . $d['CODGRUPO'] . ", " . $d['DESCANT'] . ", " . $d['DESCNOVO'] . ", " . $d['TABELA'] . ", ". $d['CODDESCONTO'] . ")";
                                $flag1 = $d['CODCLI'];
                            }
                        }
                    endif;
                }
            endif;  
        }catch(Exception $e){
            echo 'Erro ao inserir politica distribuiPolitica';
            return $e->getMessage();
        }
        return ['inserts' => $inserts, 'updates' => $updates];
    }

    public static function salvaDadosAlteracao($insert = null, $update = null){
        $ret = ['inserts' => 0, 'updates' => 0];
      
        if($insert != null){
            $sql = new SqlOra();
            try{
                foreach($insert as $i){
                    $ret['inserts'] += $sql->insertDirect2("INSERT ALL " . $i . " SELECT * FROM dual");
                }
            }catch(Exception $e){
                echo 'Erro ao inserir politica salvaDadosAlteracao';
                return $e->getMessage();
            }
        }
        if($update != null){
            $sql = new SqlOra();
            try{
                foreach($update as $u){
                    $ret['updates'] += $sql->updateDirect($u);
                }
            }catch(Exception $e){
                echo 'Erro ao atualizar politica salvaDadosAlteracao';
                return $e->getMessage();
            }
        }
        return $ret;
    }
}