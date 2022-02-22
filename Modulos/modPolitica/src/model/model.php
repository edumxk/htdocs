<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/Cliente.php');

class ModelPoliticas{

    public static function getClientes(){
        $sql = new SqlOra();
        $clientes = [];
        try{
            $clientes = $sql->select("SELECT codcli, cliente, nomecidade cidade, uf, codusur, nome,
            DIAS, ultcompra, total, numregiao,
            CASE
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
            from (select count(p.numped) qt,
                            nvl(to_date(sysdate) - to_date(max(p.data)), null) dias,
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
                            round(avg(p.vlatend), 2) total,
                            c.numregiaocli numregiao
                    from kokar.pcclient c
                    LEFT join kokar.pcpedc p
                        on c.codcli = p.codcli and p.data > '01/01/2020'
                    inner join kokar.pcusuari u
                        on c.codusur1 = u.codusur
                    inner join kokar.pccidade m
                        on c.codcidade = m.codcidade
                    left join paralelo.pcpoliticas l
                        on l.codcli = c.codcli
                    where p.codcob not in ('BNF')
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

    public static function getPoliticas($codCli, $numRegiao){
        $sql = new SqlOra();
        try{
            return $sql->select("SELECT t1.codgrupo, t1.descricao, t1.codprod, t1.codgrupo,
                    nvl((select percdesc from kokar.pcdesconto d inner join kokar.pcdescontoitem di on d.coddesconto = di.coddesconto 
                            where d.codcli = $codCli and di.valor_num = t1.codgrupo),0) percdesc,
                    to_char(t.pvenda1-t.vlipi, '9999999.9999') tabela,
                    nvl((select ativo from pcpoliticas where codcli = $codCli),0) ativo,
                    cl.cliente
                from 
                (
                    select c.codgrupo, c.descricao, min(i.coditem) codprod 
                    from kokar.pcgruposcampanhac c inner join kokar.pcgruposcampanhai i on c.codgrupo = i.codgrupo
                        
                    group by c.codgrupo, c.descricao
                    order by c.descricao
                )t1 inner join kokar.pctabpr t on t.codprod = t1.codprod and t.numregiao = $numRegiao
                inner join kokar.pcclient cl on cl.codcli = $codCli
                where t.pvenda > 0"
            );
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }



}