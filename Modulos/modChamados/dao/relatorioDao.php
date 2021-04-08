<?php
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/controle/geralControle.php');


    class RelatorioDao{

        public static function getRelatorio($de, $ate){
            //$de = Formatador::formatarData($de);
            //echo $de;
            //$ret = [];

            $anual = RelatorioDao::getAnual(12);
            $ratRca = RelatorioDao::getRatRca($de, $ate);
            $ratPatologia = RelatorioDao::getRatPatologia($de, $ate);
            $ratCategoria = RelatorioDao::getRatCategoria($de, $ate);
            $ratCusto = RelatorioDao::getRatCusto($de, $ate);
            

            for($i = 0; $i<sizeof($ratRca); $i++){
                $ratRca[$i]['NOME'] = GeralControle::nomeRca($ratRca[$i]['NOME']);
            }

            $ret = ["anual"=>$anual, "ratRca"=>$ratRca, "ratPatologia"=>$ratPatologia, "ratCategoria"=>$ratCategoria, "ratCusto"=>$ratCusto];

            return $ret;


        }

        public static function getAnual($meses){
            $sql = new SqlOra();
            $ret = $sql->select("SELECT mes, ano, to_char(mes, '00')||'/'||ano data, sum(aprov) aprov, to_char(sum(valor), '99999999.99') valor, sum(rep) rep 
                from (
                    select extract(month from c.dtencerramento)mes, extract(year from c.dtencerramento)ano,
                        case atec when 'S' then 1 else 0 end aprov,
                        case atec when 'S' then sum(i.valor*i.qt) else 0 end valor,
                        case atec when 'N' then 1 else 0 end rep
                    from ratc c left join ratcorretivai i on c.numrat = i.numrat
                    where c.dtencerramento >= trunc(to_date(sysdate)-(30*:meses),'month')
                    group by c.dtencerramento, c.atec, c.numrat
                )
                group by mes, ano
                order by  ano asc, mes asc", array(":meses"=>$meses)
            );

            //echo json_encode($ret);

            /*$aprov=[];
            
            for($pp=0; $pp<12; $pp++){
                $input = false;
                foreach($ret as $an){
                    if($an['ENCERRAMENTO']==$pp+1){
                        array_push($aprov, (int)$an['APROV']);
                        $input = true;
                    }
                }
                if($input == false){
                    array_push($aprov, 0);
                }
            }

            $reprov=[];
            for($pp=0; $pp<12; $pp++){
                $input = false;
                foreach($ret as $an){
                    if($an['ENCERRAMENTO']==$pp+1){
                        array_push($reprov, (int)$an['REPROV']);
                        $input = true;
                    }
                }
                if($input == false){
                    array_push($reprov, 0);
                }
            }

            $retorno = ["aprov"=>$aprov, "reprov"=>$reprov];

        */
            return $ret;
        }


        public static function getRatRca($de, $ate){
            $sql = new SqlOra();
    
            return $sql->select("SELECT codusur,
                        nome, 
                        sum(procede) procede, 
                        sum(nao_procede) nao_procede, 
                        to_char(sum(valor), '999999.99') valor
                from (         
                select c.numrat, 
                        to_date(c.dtencerramento) dtencerramento, 
                        c.atec,
                        ku.codusur,
                        ku.nome,
                        case when c.atec = 'S' then 1 else 0 end procede,
                        case when c.atec = 'N' then 1 else 0 end nao_procede,
                        nvl(sum(i.valor*i.qt),0) valor
                from ratc c inner join kokar.pcclient kc on kc.codcli = c.codcli
                            inner join kokar.pcusuari ku on kc.codusur1 = ku.codusur
                            left join ratcorretivai i on c.numrat = i.numrat
                where to_date(c.dtencerramento) between to_date(:de, 'yyyy-mm-dd') and to_date(:ate, 'yyyy-mm-dd')
                  and c.atec in ('S','N') 
                group by c.numrat, 
                        to_date(c.dtencerramento), 
                        c.atec,
                        ku.codusur,
                        ku.nome
                )t1
                group by codusur, nome
                order by codusur", array(":de"=>$de, ":ate"=>$ate)
            );
            
        }

        public static function getRatPatologia($de, $ate){
            $sql = new SqlOra();
    
            return $sql->select("SELECT pt.patologia, count(pt.patologia) quant, to_char(sum(t1.total), '999999.99') total
                    from (
                    select c.numrat,
                        sum(i.valor*i.qt) total,
                        (select max(id) from ratalab where numrat = c.numrat) maxid
                    from ratc c inner join ratcorretivai i on c.numrat = i.numrat
                    where to_date(c.dtencerramento) between to_date(:de, 'yyyy-mm-dd') and to_date(:ate, 'yyyy-mm-dd') 
                    and c.adir = 'S'
                    group by c.numrat
                    )t1 inner join ratalab l on t1.numrat = l.numrat and t1.maxid = l.id
                        inner join ratpatologia pt on l.codpatologia = pt.codpatologia
                    group by patologia", array(":de"=>$de, ":ate"=>$ate)
            );
        }

        public static function getRatCategoria($de, $ate){
            $sql = new SqlOra();
    
            return $sql->select("SELECT ct.categoria, sum(i.qt) qt, to_char(sum(i.pvenda*i.qt), '999999.99') as total
                from ratc c inner join rati i on c.numrat = i.numrat
                            inner join kokar.pcprodut p on i.codprod = p.codprod
                            inner join kokar.pccategoria ct on ct.codsec = p.codsec 
                                                and ct.codcategoria = p.codcategoria
                where to_date(c.dtencerramento) between to_date(:de, 'yyyy-mm-dd') and to_date(:ate, 'yyyy-mm-dd') 
                and c.atec = 'S'
                group by ct.categoria, c.atec
                order by ct.categoria", array(":de"=>$de, ":ate"=>$ate)
            );
        }

        public static function getRatCusto($de, $ate){
            $sql = new SqlOra();
            
            return $sql->select("SELECT custo, count(custo) qt, to_char( sum(valor), '999999.99') valor from (
                SELECT concat(concat(i.tipo,' '),ct.custo) custo, i.valor*i.qt valor
                from ratc c inner join ratcorretivai i on c.numrat = i.numrat
                            inner join ratcusto ct on i.codcusto = ct.codcusto
                
                            where to_date(c.dtencerramento) between to_date(:de, 'yyyy-mm-dd') and to_date(:ate, 'yyyy-mm-dd') 
                and c.atec = 'S')
                group by custo
                order by custo", array(":de"=>$de, ":ate"=>$ate)
            );

        }


        public static function getAnaliticoPatologia($de, $ate){
            $sql = new SqlOra();
    
            return $sql->select("SELECT c.numrat, i.codprod, kp.descricao, i.numlote, kl.datafabricacao,
                i.qt*i.pvenda valor,  pt.patologia
                from ratc c inner join rati i on c.numrat = i.numrat
                            inner join (select numrat, max(id) max from ratalab group by numrat) l2 on l2.numrat = c.numrat
                            inner join ratalab l on l.numrat = c.numrat and l.id = l2.max 
                            inner join ratpatologia pt on pt.codpatologia = l.codpatologia
                            inner join kokar.pcprodut kp on i.codprod = kp.codprod
                            left join kokar.pclote kl on i.numlote = kl.numlote and i.codprod = kl.codprod
                where to_date(c.dtencerramento) between to_date(:de, 'yyyy-mm-dd') and to_date(:ate, 'yyyy-mm-dd') 
                and c.adir = 'S'
                order by pt.patologia, c.numrat, kp.descricao", array(":de"=>$de, ":ate"=>$ate)
            );
        }
    }



?>