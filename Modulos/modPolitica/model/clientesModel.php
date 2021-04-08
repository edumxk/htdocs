<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

    class Clientes{

        public function getListaRca(){

            $sql = new SqlOra();
            $ret = $sql->select("SELECT u.codusur, u.nome
                from kokar.pcusuari u
                where u.dttermino is null
                or u.dttermino >= sysdate
                order by u.codusur"
            );

            for($i=0; $i<sizeof($ret); $i++){
                // echo json_encoe($ret[$i]['CLIENTE'] ;
                $ret[$i]['NOME'] = utf8_encode(Clientes::nomeRca($ret[$i]['NOME']));
            }

            return $ret;
            
        }

        public function getClientesMes(){

            $sql = new SqlOra();
            $ret = $sql->select("SELECT codcli, cliente, nomecidade, uf, codusur, nome nomerca, ultcompra, ativo, to_char(total, '99999999.9999') total, numregiao
                    from (
                    select count(p.numped) qt, max(p.data) ultcompra, c.codcli, c.cliente, c.fantasia, 
                        m.nomecidade, m.uf, u.codusur, u.nome, nvl(l.ativo,0) ativo, sum(p.vlatend) total,
                        p.numregiao
                    from kokar.pcclient c inner join kokar.pcpedc p on c.codcli = p.codcli
                        inner join kokar.pcusuari u on c.codusur1 = u.codusur
                        inner join kokar.pccidade m on c.codcidade = m.codcidade
                        left join paralelo.pcpoliticas l on l.codcli = c.codcli
                    where p.codcob not in ('BNF')
                        and extract(month from p.data) = extract(month from sysdate)
                        and extract(year from p.data) = extract(year from sysdate)
                    group by c.codcli, c.cliente, u.codusur, u.nome, c.fantasia, m.nomecidade, m.uf, l.ativo, p.numregiao
                
                    )t
                order by ativo desc, cliente"
            );
    
            for($i=0; $i<sizeof($ret); $i++){
                // echo json_encoe($ret[$i]['CLIENTE'] ;
                $ret[$i]['CLIENTE'] = utf8_encode($ret[$i]['CLIENTE']);
                $ret[$i]['NOMECIDADE'] = utf8_encode($ret[$i]['NOMECIDADE']);
                $ret[$i]['NOMERCA'] = Clientes::nomeRca($ret[$i]['NOMERCA']);
            }
            return $ret;
        }


        public function getClientesAtivos(){

            $sql = new SqlOra();
            $ret = $sql->select("SELECT codcli, cliente, nomecidade, uf, codusur, nome nomerca, ultcompra, ativo, to_char(total, '99999999.9999') total, numregiao 
                from (
                    select count(p.numped) qt, max(p.data) ultcompra, c.codcli, c.cliente, c.fantasia, 
                        m.nomecidade, m.uf, u.codusur, u.nome, nvl(l.ativo,0) ativo, sum(p.vlatend) total,
                        p.numregiao
                    from kokar.pcclient c inner join kokar.pcpedc p on c.codcli = p.codcli
                        inner join kokar.pcusuari u on c.codusur1 = u.codusur
                        inner join kokar.pccidade m on c.codcidade = m.codcidade
                        left join paralelo.pcpoliticas l on l.codcli = c.codcli
                    where p.codcob not in ('BNF')
                        and p.data between add_months(trunc(sysdate,'mm'),-2) and last_day(add_months(trunc(sysdate,'mm'),-1))
                        and c.codcli not in (select distinct codcli from kokar.pcpedc cc where cc.data between trunc(sysdate, 'month') and last_day(trunc(sysdate, 'month')))
                    group by c.codcli, c.cliente, u.codusur, u.nome, c.fantasia, m.nomecidade, m.uf, l.ativo, p.numregiao
                                    
                )t
                order by ativo desc, ultcompra desc, cliente"
            );
    
            for($i=0; $i<sizeof($ret); $i++){
                // echo json_encoe($ret[$i]['CLIENTE'] ;
                $ret[$i]['CLIENTE'] = utf8_encode($ret[$i]['CLIENTE']);
                $ret[$i]['NOMECIDADE'] = utf8_encode($ret[$i]['NOMECIDADE']);
                $ret[$i]['NOMERCA'] = Clientes::nomeRca($ret[$i]['NOMERCA']);
            }
            return $ret;
        }

        public function getClientesInativos(){

            $sql = new SqlOra();
            $ret = $sql->select(
                "SELECT codcli, cliente, nomecidade, uf, codusur, nome nomerca, ultcompra, ativo, to_char(total, '99999999.9999') total, numregiao
                from (
                    select count(p.numped) qt, max(p.data) ultcompra, c.codcli, c.cliente, c.fantasia, 
                        m.nomecidade, m.uf, u.codusur, u.nome, nvl(l.ativo,0) ativo, sum(p.vlatend) total, p.numregiao
                    from kokar.pcclient c inner join kokar.pcpedc p on c.codcli = p.codcli
                        inner join kokar.pcusuari u on c.codusur1 = u.codusur
                        inner join kokar.pccidade m on c.codcidade = m.codcidade
                        left join paralelo.pcpoliticas l on l.codcli = c.codcli
                    where p.codcob not in ('BNF')
                        and p.data < add_months(trunc(sysdate,'mm'),-2)
                        and c.codcli not in (select distinct codcli from kokar.pcpedc cc where cc.data > add_months(trunc(sysdate,'mm'),-2))
                        
                    group by c.codcli, c.cliente, u.codusur, u.nome, c.fantasia, m.nomecidade, m.uf, l.ativo, p.numregiao
                )t 
                
                union 
                
                select cl.codcli, cl.cliente, cd.nomecidade, cd.uf, cu.codusur, cu.nome, null, pl.ativo ,'0', cl.numregiaocli
                from kokar.pcclient cl inner join kokar.pccidade cd on cl.Codcidade = cd.codcidade
                     inner join kokar.pcusuari cu on cu.codusur = cl.codusur1
                     left join paralelo.pcpoliticas pl on cl.codcli = pl.codcli
                     
                where cl.dtultcomp is null
                  and cl.dtcadastro >= '01/05/2020'
                  and cl.tipofj = 'J'
                order by ativo desc, ultcompra desc, cliente"
            );
    
            for($i=0; $i<sizeof($ret); $i++){
                // echo json_encoe($ret[$i]['CLIENTE'] ;
                $ret[$i]['CLIENTE'] = utf8_encode($ret[$i]['CLIENTE']);
                $ret[$i]['NOMECIDADE'] = utf8_encode($ret[$i]['NOMECIDADE']);
                $ret[$i]['NOMERCA'] = Clientes::nomeRca($ret[$i]['NOMERCA']);
            }
            return $ret;
        }





        public function nomeRca($nome){
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
                return '33 - RADAMES';
            }elseif(strpos($nome, 'ALEX') !== false) {
                return '37 - ALEX';
            }else {
                return $nome;
            }
        }
    }


?>