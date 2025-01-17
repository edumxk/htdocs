<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');


    // $codCli = 1127;
    // CalculaPol::Calcular($codCli);

    /* Quando precisar cadastrar em grandes grupos, cadastrar utilizando a versão calculaPolitcasManual.php*/


    Class CalculaPoliticas{

        public static function calcular($codCli){
            
            $arrPoliticas = [];

            $sql = new SqlOra();

            //BuscaAtividade
            $ativ = $sql->select("SELECT c.codatv1
                from kokar.pcclient c
                where c.codcli = :codCli", array(":codCli"=>$codCli)
            );

            //Busca todos os grupos
            $arrGrupos = $sql->select("SELECT codgrupo, descricao
                from kokar.pcgruposcampanhac
                order by codgrupo"
            );

            //Cria um array de Politicas com os grupos
            foreach($arrGrupos as $g){
                $p = new Politica($codCli, $g['CODGRUPO'], utf8_encode($g['DESCRICAO']));
                // $p->codRca = 27;
                array_push($arrPoliticas, $p);
            }
            
            //Busca os descontos realizados para o cliente;
            //Deve ficar ordenado por grupo;
            $arrRealizado = $sql->select("SELECT codcli, codgrupo, descricao, perdesc, sum(qt)cont, max(datasaida) datasaida, codusur
                    from (   
                        select c.codcli, cc.codgrupo, cc.descricao, i.perdesc perdesc, count(i.codprod) qt, max(c.data) datasaida, c.codusur
                        from kokar.pcgruposcampanhac cc inner join kokar.pcgruposcampanhai ci on cc.codgrupo = ci.codgrupo and cc.tipo = 'GP'
                            inner join kokar.pcpedi i on ci.coditem = i.codprod
                            inner join kokar.pcpedc c on c.numped = i.numped
                            inner join kokar.pcplpag pag on pag.codplpag = c.codplpag
                        where c.data >= '01/08/2019'
                        and c.codcob not in  ('BNF')
                        --and pag.pertxfim = 0
                        and c.dtcancel is null
                        and c.codcli = :codCli
                        and (c.obs||c.obs1||c.obs2 not like '%COMBO%'
                        or c.obs||c.obs1||c.obs2 not like '%CAMPANHA%'
                        or c.obs||c.obs1||c.obs2 not like '%DESCONTO%'
                        or c.obs||c.obs1||c.obs2 not like '%BLACK%')
                        group by c.codcli, cc.codgrupo, cc.descricao, i.perdesc, c.codusur
                )t1 group by codcli, codgrupo, descricao, perdesc, codusur
                order by codgrupo, cont, datasaida desc", array(":codCli"=>$codCli)
            );

            // echo json_encode($arrRealizado);
            // echo var_dump($arrPoliticas);

            $codRca = $sql->select("SELECT codusur1 from kokar.pcclient c where c.codcli = :codCli",
                array(":codCli"=>$codCli)
            );
            //Preenche o array de politicas com as informações da busca de descontos realizados;
            //Implementado de forma que ambos os arrays (politicas e descontos) sejam percorridos apenas uma vez;
            

            // echo $arrRealizado[0]['CODUSUR'];
            $inicio = 0;
            for($p=0; $p<sizeof($arrPoliticas); $p++){
                $arrPoliticas[$p]->codRca = $codRca[0]['CODUSUR1'];
                for($i=$inicio; $i<sizeof($arrRealizado); $i++){
                    if($arrPoliticas[$p]->codGrupo == $arrRealizado[$i]['CODGRUPO']){

                        if($arrRealizado[$i]['CONT'] > $arrPoliticas[$p]->cont){

                            $arrPoliticas[$p]->desconto = $arrRealizado[$i]['PERDESC'];
                            $arrPoliticas[$p]->cont = $arrRealizado[$i]['CONT'];

                        }

                    }elseif($arrPoliticas[$p]->codGrupo < $arrRealizado[$i]['CODGRUPO']){
                        $inicio = $i;
                        break;

                    }
                
                }
            }

            //Busca codigo de politica;
            $ret = $sql->select("SELECT max(CODDESCONTO) COD from kokar.pcdesconto");
            $cod = $ret[0]['COD'];

            //Inicias as strings para inclusão das tabelas;
            $pcDesconto = "INSERT ALL\n";
            $pcDescontoItem = "INSERT ALL\n";

            $i=0;

            foreach($arrPoliticas as $p){
                //Acresce o codigo de politica para cada nova inclusão;
                //O código é compartilhado por ambas as tabelas PCDESCONTO e PCDESCONTOITEM;
                $cod++;
                //Preenche o desconto quando zerado, com base na atividade do cliente;
                    //Construtoras = 25%;
                    //Demais = 35%;
                if($p->desconto == 0){
                    
                    if($p->codRca == 27){
                        //Regras gerais para clientes do RCA MAURO
                        if($p->codGrupo == 65 || $p->codGrupo == 67){
                            $p->desconto = 30;
                        }elseif($p->codGrupo == 64 || $p->codGrupo == 66){
                            $p->desconto = 35;
                        }elseif($p->codGrupo == 73){
                            $p->desconto = 37;
                        }elseif($p->codGrupo == 7 || $p->codGrupo == 8){
                            $p->desconto = 40;
                        }elseif($p->codGrupo == 21){
                            $p->desconto = 43;
                        }elseif($p->codGrupo == 81){
                            $p->desconto = 44;
                        }elseif($p->codGrupo == 22 || $p->codGrupo == 78 || $p->codGrupo == 80){
                            $p->desconto = 45;
                        }elseif($p->codGrupo == 76){
                            $p->desconto = 47;
                        }elseif($p->codGrupo == 20 || $p->codGrupo == 71){
                            $p->desconto = 48;
                        }elseif($p->codGrupo == 77){
                            $p->desconto = 49;
                        }elseif($p->codGrupo == 16 || $p->codGrupo == 19){
                            $p->desconto = 50;
                        }elseif($p->codGrupo == 17){
                            $p->desconto = 51;
                        }elseif($p->codGrupo == 70){
                            $p->desconto = 57;
                        }elseif($p->codGrupo == 79){
                            $p->desconto = 55;
                        }else{
                            if($ativ == 6){
                                $p->desconto = 25;
                            }else{
                                $p->desconto = 35;
                            }
                        }
                    }else{
                        if($ativ == 6){
                            $p->desconto = 25;
                        }else{
                            $p->desconto = 35;
                        }
                    }

                }

                // echo json_encode($arrPoliticas);

                //Cria insert da tabela PCDESCONTO;
                $pcDesconto .= "INTO kokar.pcdesconto
                        (CODDESCONTO, CODCLI, PERCDESC, DTINICIO, DTFIM, 
                        BASECREDDEBRCA, UTILIZADESCREDE, CODFUNCLANC, DATALANC, 
                        CODFUNCULTALTER, DATAULTALTER, ORIGEMPED, APLICADESCONTO, CREDITASOBREPOLITICA, 
                        TIPO, ALTERAPTABELA, PRIORITARIA, QUESTIONAUSOPRIORITARIA, CODFILIAL, 
                        APENASPLPAGMAX, APLICADESCSIMPLESNACIONAL, PRIORITARIAGERAL, CONSIDERACALCGIROMEDIC, PERCDESCMAX, 
                        SYNCFV, QTDAPLICACOESDESC, QTMINESTPARADESC, TIPOCONTACORRENTE, PERCFORNEC, 
                        DESCRICAO, PERCCUSTFORNEC)
                    VALUES (".$cod.", ".$codCli.", ".$p->desconto.", to_date(sysdate), '31/12/2099', 
                        'N', 'N', 32, sysdate, 
                        32, to_date(sysdate), 'O', 'S', 'N', 
                        'C', 'N', 'S', 'N', 1, 
                        'N', 'T', 'S', 'N', 0, 
                        'S', 0, 0, 'R', 0, 
                        '".$codCli." - ".$p->descricao."', 0)
                    ";
                //Cria insert da tabela PCDESCONTOITEM;
            
                $pcDescontoItem .= " INTO kokar.pcdescontoitem (coddesconto, tipo, valor_num) VALUES (".$cod.", 'GP', ".$p->codGrupo.") ";
                
                $i++;
            }

            //Encerra as strings para inclusão das tabelas;
            $pcDesconto .="SELECT * from dual";
            $pcDescontoItem .="SELECT * from dual";

            $obs = utf8_decode("Primeira Política");
            $pcPolitica = "INSERT into pcpoliticas
                (codcli, ativo, observacao)
                values($codCli, 1, '$obs')";

            $retorno = "";
            
            $retorno .= $sql->insertDirect($pcDesconto);
            $retorno .= $sql->insertDirect($pcDescontoItem);
            $retorno .= $sql->insertDirect($pcPolitica);
            
            // return $retorno;

            echo $codCli."\n";
            return sizeof($arrPoliticas);
        }


    }