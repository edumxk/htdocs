<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/rat.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/ATec.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/ALab.php');

    //session_start();


    class ListaRatControl{
       
        public static function getListaRat(){
            /*
            Utilizado por:
            --ListaRat.php
            */
            //echo 'setor '.$_SESSION['codsetor'];

            $setor = $_SESSION['codsetor'];
            $rat = Chamado::getListaRat();
            //echo json_encode($rat);
            $lista = [];

            if(count($rat)>0){
                foreach($rat as $chamado){
                    //echo $chamado;


                    if($chamado->dtEncerramento == null){
                        
                        //Se não encerrado;
                        if($chamado->prodFinal == 'N'){
                            //Se produto final não lançado;
                            //Departamento Técnico
                            if($setor == 0 || $setor == 3 || $setor == 2){
                                /*Cod 2 (laboratorio) temporario enquanto jociel viaja*/
                                array_push($lista, $chamado);
                            }
                        }elseif($chamado->alabFinal == 'N'){
                            //Se ALab é nulo ou "Não";
                            //Laboratório
                            if($setor == 0 || $setor == 2){
                                array_push($lista, $chamado);
                            }
                        }elseif($chamado->acaoFinal == 'N'){
                            //Se Ação Final não lançada;
                            //Departamento Técnico;
                            if($setor == 0 || $setor == 3 || $setor == 2){
                                /*Cod 2 (laboratorio) temporario enquanto jociel viaja*/
                                array_push($lista, $chamado);
                            }
                        }elseif($chamado->dirFinal == 'N'){
                            //Se Analise de Diretoria não Realizada;
                            //Diretoria;
                            if($setor == 0 || $setor == 1 || $setor == 2 || $setor == 4){
                                array_push($lista, $chamado);
                            }
                        }
                    }/*elseif($chamado->ADir == 'S'){
                        //Se Aprovado pela Diretoria;
                        //Apenas TI para testes
                        if($setor == 0 ){
                            array_push($lista, $chamado);
                        }
                    }*/

                }
                return $lista;
            }else{
                return null;
            }
        }

        public static function getListaRatGeral(){
            $setor = $_SESSION['codsetor'];
            $rat = Chamado::getListaRat();

            $lista = [];
           
            if(count($rat)>0){
                foreach($rat as $chamado){
                    array_push($lista, $chamado);
                }
            }
            return $lista;
        }

        public static function getListaBusca($lote, $de, $ate, $pend, $proc, $naoproc){
            
            if($de == ''){
                $de = '2000-01-01';
            }
            if($ate == ''){
                $ate = '2999-12-31';
            }

            $de1 = explode('-',$de);
            $ate1 = explode('-',$ate);


            $data1 = $de1[2]."/".$de1[1]."/".$de1[0];
            $data2 = $ate1[2]."/".$ate1[1]."/".$ate1[0];

            $rat = Chamado::getListaRatBusca($lote, $data1, $data2);

            $lista = [];

            if(count($rat)>0){
                //echo $pend."   -   ".$proc."   -   ".$naoproc;

                if($pend != 'S' && $naoproc != 'S' && $proc != 'S' ){
                    foreach($rat as $chamado){
                        array_push($lista, $chamado);
                    }
                }else{
                    foreach($rat as $chamado){
                        if($pend == 'S'){
                            if($chamado->getStatus() == 'PENDENTE'){
                                array_push($lista, $chamado);
                            }
                        }
                        if($proc == 'S'){
                            if($chamado->getStatus() == 'PROCEDENTE'){
                                array_push($lista, $chamado);
                            }
                        }
                        if($naoproc == 'S'){
                            if($chamado->getStatus() == 'NÃO PROCEDENTE'){
                                array_push($lista, $chamado);
                            }
                        }
                        
                    }
                }
                
            }

            //echo $lote." - ".$data1." - ".$data2;
            return $lista;


        }

        public static function getListaRatCTD(){
            $setor = $_SESSION['codsetor'];
            $rat = Chamado::getListaRat();

            $lista = [];
           
            if(count($rat)>0){
                foreach($rat as $chamado){
                    if($chamado->ADir == 'S'){
                        array_push($lista, $chamado);
                    }
                    
                }
            }
            return $lista;
        }

        public static function getListaRatByCli($codcli){
            $rat = Chamado::getListaRatByCli($codcli);

            $lista = [];
           
            if(count($rat)>0){
                foreach($rat as $chamado){
                    array_push($lista, $chamado);
                }
            }
            return $lista;
        }


        public static function getDuracao($abertura, $encerramento){
            if($encerramento == 0){
                $hj = new DateTime(date("Y/m/d"));
                $dt = explode("/",$abertura );
                $ab = new DateTime($dt[2]."/".$dt[1]."/".$dt[0]);
                
                $diff = $ab->diff($hj);
                return $diff->format('%r%a');
            }else{
                $dt = explode("/",$abertura );
                $ab = new DateTime($dt[2]."/".$dt[1]."/".$dt[0]);

                $dt = explode("/",$encerramento );
                $fe = new DateTime($dt[2]."/".$dt[1]."/".$dt[0]);

                $diff = $ab->diff($fe);
                return $diff->format('%r%a');
            }
        }

    }
?>