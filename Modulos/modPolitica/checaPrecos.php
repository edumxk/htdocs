<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');


    $codCli = 226;
    echo json_encode(CalculaPol::Calcular($codCli));
    

    Class CalculaPol{



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
                array_push($arrPoliticas, $p);
            }
            


            //Busca os descontos realizados para o cliente;
            //Deve ficar ordenado por grupo;
            $arrRealizado = $sql->select("SELECT codcli, codgrupo, descricao, perdesc, sum(qt)cont
                from (   
                    select c.codcli, cc.codgrupo, cc.descricao, i.perdesc perdesc, count(i.codprod) qt
                    from kokar.pcgruposcampanhac cc inner join kokar.pcgruposcampanhai ci on cc.codgrupo = ci.codgrupo
                        inner join kokar.pcpedi i on ci.coditem = i.codprod
                        inner join kokar.pcpedc c on c.numped = i.numped
                        inner join kokar.pcplpag pag on pag.codplpag = c.codplpag
                    where c.data >= '01/08/2019'
                    and c.codcob = '237'
                    and pag.pertxfim = 0
                    and c.dtcancel is null
                    and c.codcli = :codCli
                    group by c.codcli, cc.codgrupo, cc.descricao, i.perdesc
                )t1 group by codcli, codgrupo, descricao, perdesc
                order by codgrupo, cont", array(":codCli"=>$codCli)
            );

            // echo json_encode($arrRealizado);
            // echo var_dump($arrPoliticas);

            //Preenche o array de politicas com as informações da busca de descontos realizados;
            //Implementado de forma que ambos os arrays (politicas e descontos) sejam percorridos apenas uma vez;
            


            $inicio = 0;
            for($p=0; $p<sizeof($arrPoliticas); $p++){
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

            return $arrPoliticas;

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
                    if($ativ == 6){
                        $p->desconto = 25;
                    }else{
                        $p->desconto = 35;
                    }
                }
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
                        'N', 'T', 'N', 'N', 0, 
                        'S', 0, 0, 'R', 0, 
                        '".$codCli." - ".$p->descricao."', 0)
                    ";
                //Cria insert da tabela PCDESCONTOITEM;
            
                $pcDescontoItem .= " INTO kokar.pcdescontoitem (coddesconto, tipo, valor_num) VALUES (".$cod.", 'GP', ".$p->codGrupo.") ";
                echo $i;
                $i++;
            }

            //Encerra as strings para inclusão das tabelas;
            $pcDesconto .="SELECT * from dual";
            $pcDescontoItem .="SELECT * from dual";

            $obs = utf8_decode("Primeira Política");
            $pcPolitica = "INSERT into pcpoliticas
                (codcli, ativo, observacao)
                values($codCli, 1, '$obs')";

            
            // $retorno = "";
            // $retorno .= $sql->insertDirect($pcDesconto);
            // $retorno .= $sql->insertDirect($pcDescontoItem);
            // $retorno .= $sql->insertDirect($pcPolitica);
            // // return $retorno;
            // return sizeof($arrPoliticas);
        }


    }
    


    class Politica{
        public $codCli;
        public $codGrupo;
        public $descricao;
        public $desconto;
        public $cont;


        function __construct($codCli, $codGrupo, $descricao){
            $this->codCli = $codCli;
            $this->codGrupo = $codGrupo;
            $this->descricao = $descricao;
            $this->desconto = 0;
            $this->cont = 0;
        }




    }


?>