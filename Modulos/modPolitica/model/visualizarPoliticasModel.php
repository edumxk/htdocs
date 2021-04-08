<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

    if(isset($_POST['funcao'])){

        if($_POST['funcao'] == 'atualizarPolitica'){
            $codCli = $_POST['query']['codCli'];
            $codGrupo = $_POST['query']['codGrupo'];
            $desconto = $_POST['query']['desconto'];
            
            echo VisualizarPoliticas::atualizarPolitica($codCli, $codGrupo, $desconto);
        }elseif($_POST['funcao'] == 'atualizarObs'){
            $codCli = $_POST['query']['codCli'];
            $obs = $_POST['query']['obs'];
            
            echo VisualizarPoliticas::atualizarObs($codCli, $obs);
        }elseif($_POST['funcao'] == 'inativar'){
            $codCli = $_POST['query']['codCli'];
            
            echo VisualizarPoliticas::inativar($codCli);
        }elseif($_POST['funcao'] == 'ativar'){
            $codCli = $_POST['query']['codCli'];
            
            echo VisualizarPoliticas::ativar($codCli);
        }elseif($_POST['funcao'] == 'excluir'){
            $codCli = $_POST['query']['codCli'];
            
            echo VisualizarPoliticas::excluir($codCli);
        }
    }

    class VisualizarPoliticas{

        public static function infoPolitica($codCli){
            $sql = new SqlOra();

            $ret = $sql->select("SELECT codcli, ativo, observacao
                from pcpoliticas c
                where c.codcli = $codCli"
            );

            if(sizeof($ret)>0){
                return $ret[0];
            }else{
                return 0;
            }
        }


        public static function getCliente($codCli){
            $sql = new SqlOra();

            $ret = $sql->select("SELECT c.codcli, c.cliente, c.fantasia, d.nomecidade, d.uf, u.codusur, u.nome
                from kokar.pcclient c inner join kokar.pccidade d on c.codcidade = d.codcidade
                    inner join kokar.pcusuari u on u.codusur = c.codusur1
                where c.codcli = $codCli"
            );

            return $ret[0];
            
        }

        public function getPoliticas($codCli, $numRegiao){
            $listaPoliticas = [];

            $sql = new SqlOra();
        
            $ret = $sql->select("SELECT t1.codgrupo, t1.descricao, t1.codprod, t1.codgrupo,
                    nvl((select percdesc from kokar.pcdesconto d inner join kokar.pcdescontoitem di on d.coddesconto = di.coddesconto 
                            where d.codcli = $codCli and di.valor_num = t1.codgrupo),0) percdesc,
                    to_char(t.pvenda1-t.vlipi, '9999999.9999') tabela,
                    nvl((select ativo from pcpoliticas where codcli = $codCli),0) ativo
                from 
                (
                    select c.codgrupo, c.descricao, min(i.coditem) codprod 
                    from kokar.pcgruposcampanhac c inner join kokar.pcgruposcampanhai i on c.codgrupo = i.codgrupo
                        
                    group by c.codgrupo, c.descricao
                    order by c.descricao
                )t1 inner join kokar.pctabpr t on t.codprod = t1.codprod and t.numregiao = $numRegiao"
            );
        
            
            // echo sizeof($ret);
            return $ret;
        }

        public function atualizarPolitica($codCli, $codGrupo, $desconto){
            $sql = new SqlOra();
            
            $strRet = "";

            $ret = $sql->select("SELECT i.coddesconto, d.percdesc
                from kokar.pcdesconto d inner join kokar.pcdescontoitem i on d.coddesconto = i.coddesconto
                where d.codcli = :codCli
                and i.valor_num = :codGrupo",
                array(":codCli"=>$codCli, ":codGrupo"=>$codGrupo)
            );

            $codDesc = $ret[0]['CODDESCONTO'];
            $descontoOld = $ret[0]['PERCDESC'];

        
            //Copia politica para o LOG;

            /*$criaLog = "INSERT into kokar.pcdescontolog
                (coddesconto, codcli, percdesc, dtinicio, dtfim, basecreddebrca, utilizadescrede, 
                codfunclanc, datalanc, origemped, aplicadesconto, creditasobrepolitica, tipo, percdescfin, alteraptabela, 
                dtexclusao, codfuncexclusao, dataultalter, codfuncultalter, --AQUI
                codfilial, apenasplpagmax, aplicadescsimplesnacional, consideracalcgiromedic, codidentificador,
                percdescmax, qtini, qtfim, codauxiliar, qtdaplicacoesdesc, qtminestparadesc, coddescontoid, percfornec, descricao, numverba, perccustfornec)
                
                select coddesconto, codcli, percdesc, dtinicio, dtfim, basecreddebrca, utilizadescrede, 
                    codfunclanc, datalanc, origemped, aplicadesconto, creditasobrepolitica, tipo, percdescfin, alteraptabela, 
                    sysdate, 32, dataultalter, codfuncultalter, --AQUI
                    codfilial, apenasplpagmax, aplicadescsimplesnacional, consideracalcgiromedic, codidentificador,
                    percdescmax, qtini, qtfim, 0, 0, qtminestparadesc, coddescontoid, percfornec, descricao, numverba, 0
                from kokar.pcdesconto
                where coddesconto = $codDesc";

            $strRet .= $sql->insertDirect($criaLog);

            //Cria nova politica;
            
            $politicaAtualizada = "INSERT into kokar.pcdesconto
                (
                    coddesconto, codcli, percdesc, dtinicio, dtfim, 
                    basecreddebrca, utilizadescrede, codfunclanc, datalanc, codfuncultalter,
                    dataultalter, origemped, aplicadesconto, creditasobrepolitica, tipo,
                    alteraptabela, prioritaria, questionausoprioritaria, codfilial, apenasplpagmax,
                    aplicadescsimplesnacional, prioritariageral, consideracalcgiromedic, percdescmax, syncfv,
                    qtdaplicacoesdesc, qtminestparadesc, tipocontacorrente, percfornec, descricao, 
                    perccustfornec
                )
                select 
                    $codDesc+1, codcli, &percdesc, dtinicio, dtfim, 
                    d.basecreddebrca, utilizadescrede, codfunclanc, d.datalanc, codfuncultalter,
                    dataultalter, origemped, aplicadesconto, creditasobrepolitica, tipo,
                    alteraptabela, prioritaria, questionausoprioritaria, codfilial, apenasplpagmax,
                    aplicadescsimplesnacional, prioritariageral, consideracalcgiromedic, percdescmax, syncfv,
                    qtdaplicacoesdesc, qtminestparadesc, tipocontacorrente, percfornec, descricao, 
                    d.perccustfornec
                from kokar.pcdesconto d where coddesconto = $codDesc";
            
            $strRet .= $sql->insertDirect($politicaAtualizada);

                    

            //Exclui politica atiga;

            $deleteDesconto  = "DELETE kokar.pcdesconto  where coddesconto = $codDesc";
            $strRet .= $sql->insertDirect($deleteDesconto);*/
            

            $strRet .= $sql->update("UPDATE kokar.pcdesconto
                set percdesc = :desconto
                where coddesconto = :codDesc",
                array(":desconto"=>(float)$desconto, ":codDesc"=>$codDesc)
            );

            if($desconto <> $descontoOld){
                $pcPoliticasLog="INSERT into pcpoliticasLog
                    (codcli, data, codgrupo, old_desconto) 
                    values($codCli, sysdate, $codGrupo, $descontoOld)";

                $strRet .= $sql->insertDirect($pcPoliticasLog);
            }


            return $strRet;
        }

        public function atualizarObs($codCli, $obs){
            $sql = new SqlOra();
            
            $strRet = "";

            $obs = utf8_decode($obs);
            
            $ret = $sql->select("SELECT observacao
                from pcpoliticas
                where codcli = :codCli",
                array(":codCli"=>$codCli)
            );

            $obsOld = $ret[0]['OBSERVACAO'];


            return $sql->update("UPDATE paralelo.pcpoliticas p 
                set p.observacao = :obs 
                where p.codcli = :codCli",
                array(":obs"=>$obs, ":codCli"=>$codCli)
            );
            


            $pcPoliticasLog="INSERT into pcpoliticasLog
                (codcli, data, old_obs) 
                values($codCli, sysdate, $obsOld)";

            $strRet .= $sql->insertDirect($pcPoliticasLog);


            return $strRet;
        }

        public static function inativar($codCli){
            $sql = new SqlOra();

            $ret = "";
            $ret .= $sql->update("UPDATE kokar.pcdesconto d
                set d.aplicadesconto = 'N',
                    d.syncfv = 'N'
                where d.codcli = :codCli",
                array(":codCli"=>$codCli)
            );

            $ret .= $sql->update("UPDATE pcpoliticas d
                set d.ativo = 2
                where d.codcli = :codCli",
                array(":codCli"=>$codCli)
            );

            return $ret;

        }

        public static function ativar($codCli){
            $sql = new SqlOra();

            $ret = "";

            $ret .= $sql->update("UPDATE kokar.pcdesconto d
                set d.aplicadesconto = 'S',
                    d.syncfv = 'S'
                where d.codcli = :codCli",
                array(":codCli"=>$codCli)
            );

            $ret .= $sql->update("UPDATE pcpoliticas d
                set d.ativo = 1
                where d.codcli = :codCli",
                array(":codCli"=>$codCli)
            );

            return $ret;
        }

        public static function excluir($codCli){
            $sql = new SqlOra();

            $ret = 0;
            $ret1 = 0;
            $ret2 = 0;

            $strDeleteItem = "DELETE kokar.pcdescontoitem  where coddesconto in (select coddesconto from kokar.pcdesconto where codcli = $codCli)";
            $strDeleteC = "DELETE kokar.pcdesconto where codcli = $codCli";
            $strDeleteI = "DELETE paralelo.pcpoliticas where codcli = $codCli";

            $ret = $sql->insertDirect($strDeleteItem);
            if($ret == 0){
                $ret1=$sql->insertDirect($strDeleteC);
            }
            if($ret1 == 0){
                $ret2=$sql->insertDirect($strDeleteI);
            }

            return "$ret - $ret1 - $ret2";

        }

    }


?>