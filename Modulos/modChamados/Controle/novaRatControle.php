<?php 
    //require_once("dao/novaRatDao.php");
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/novaRatDao.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/ATec.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/ALab.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='novaRat'){
            NovaRatControle::setNovaRat($_POST['query']);
        }
    }



    class NovaRatControle{
       
        public static function getClientes(){
            /*
            Utilizado por:
            --listaClientes.php
            */
            /*$json_file = file_get_contents("recursos/json/clientes.json");   
            $json_str = json_decode($json_file, true);

            $cliente = $json_str['cliente'];
            return $cliente;*/

            $cliente = NovaRatDao::getClientes();
            return $cliente;
            
        }

        public static function getClienteByCod($codcli){

            
            $cliente = NovaRatDao::getClienteByCod($codcli);

            if(count($cliente)>0){
                $row = $cliente[0];
                $result['codcli'] = $row['CODCLI'];
                $result['cliente'] = $row['CLIENTE'];
                $result['fantasia'] = $row['FANTASIA'];
                $result['cnpj'] = $row['CGCENT'];
                $result['cidade'] = $row['NOMECIDADE'];
                $result['uf'] = $row['UF'];
                $result['telefone'] = $row['TELENT'];
                $result['rca'] = $row['NOME'];
                $result['telRca'] = $row['TELEFONE1'];

                return $result;
            }else{
                return null;
            }

            $cliente = $json_str['cliente'][0];

            return $cliente;

        }

        
        /*
        public static function getRat($numrat){
            if($numrat==""){
                return null;
            }else{
                $rat = NovaRatDao::getRat($numrat);
                $result;

                if(count($rat)>0){
                    $row = $rat[0];
                    $abertura = date_create($row['dtabertura']);
                    $encerramento = date_create($row['dtencerramento']);

                    $chamado = new Chamado();
                    $chamado->numrat = $row['numrat'];
                    $chamado->dtAbertura = date_format($abertura, "d/m/Y");
                    $chamado->dtEncerramento = date_format($encerramento, "d/m/Y");

                    $chamado->codRca = 0;
                    $chamado->rca = $row['rca'];
                    $chamado->telRca = $row['telrca'];
                    $chamado->codCli = $row['codcli'];
                    $chamado->telCliente = $row['telefone'];
                    $chamado->razao = $row['cliente'];
                    $chamado->cnpj = $row['cnpj'];
                    $chamado->fantasia = $row['fantasia'];
                    $chamado->cidade = $row['cidade'];
                    $chamado->uf = 0;
                    $chamado->solicitante = $row['solicitante'];
                    $chamado->telSolicitante = $row['telsolicitante'];
                    $chamado->pintor = $row['pintor'];
                    $chamado->telPintor = $row['telpintor'];
                    //$chamado->produtos;
                    $chamado->prodFinal = $row['prodfinal'];
                    $chamado->dtprodFinal = $row['dtprodfinal'];
                    $chamado->problema = $row['problema'];

                    $at = new ATec();
                    $al = new ALab();

                    $at->getATec($chamado->numRat);
                    $al->getALab($chamado->numRat);
                    
                    $chamado->ATec = $at;
                    $chamado->ALab = $al;


                    /*$chamado->ATec;
                    $chamado->ATecFinal;
                    $chamado->dtAtecFinal;
                    $chamado->ALab;
                    $chamado->ALabFinal;
                    $chamado->dtALabFinal;*/
                   /* $chamado->corretiva;
                    $chamado->acaoFinal;
                    $chamado->dtacaoFinal;




                    return $chamado;
                    
                }else{
                    return null;
                }
            }
        }*/
        









        public static function getNovoNumeroRat(){
            $numrat = NovaRatDao::getNovoNumeroRat();

            $result;

            if(count($numrat)>0){
                $row = $numrat[0];
                $result = $row['NUMRAT'];
                return $result;
            }else{
                return null;
            }
        }

        

        public static function setNovaRat($dataset){
            echo NovaRatDao::setNovaRat($dataset['numrat'], $dataset['codcli'], strtoupper(utf8_decode($dataset['solicitante']))
                    ,$dataset['telSolicitante'],strtoupper(utf8_decode($dataset['pintor'])),$dataset['telPintor'],strtoupper(utf8_decode($dataset['problema']))
            );

        }


        
    }
?>