<?php 

    include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
    include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/Model/rat.php');
    include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/Model/produto.php');
	include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/Recursos/finalRat.php');
	include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/Recursos/email/mailer.php');

    
    //echo json_encode($_SESSION);
    
    if(isset($_POST['action'])){
        if($_POST['action']=='finalizar'){
            RatControl::finalizar($_POST['query']);
        }
    }

    #$numrat = 589;
    class RatControl{

        public static function getRat($numrat){
            if($numrat==""){
                return null;
            }else{
                $result = Rat::getRat($numrat);
                //return $rat;
                $chamado = new Chamado();

                if(count($result)>0){
                    $row = $result[0];
                    $abertura = date_create($row['dtabertura']);
                    $encerramento = date_create($row['dtencerramento']);
                    $chamado->numRat = $row['numrat'];
                    $chamado->dtAbertura = date_format($abertura, "d/m/Y");
                    $chamado->dtEncerramento = date_format($encerramento, "d/m/Y");
                    $chamado->codRca = $row['codusur1'];
                    $chamado->codCli = $row['codcli'];
                    $chamado->razao = $row['cliente'];
                    $chamado->cnpj = $row['cgcent'];
                    $chamado->fantasia = $row['fantasia'];
                    $chamado->cidade = $row['municent'];
                    $chamado->uf = $row['estent'];              
                    $chamado->telCliente = $row['telent'];              
                    $chamado->solicitante = $row['solicitante'];              
                    $chamado->telSolicitante = $row['solicitante_tel'];              
                    $chamado->pintor = $row['pintor'];              
                    $chamado->telPintor = $row['pintor_tel'];        
                    $chamado->problema = $row['problema'];     
                    $chamado->prodFinal = $row['prodFinal'];     
                    $chamado->dtprodFinal = $row['dtprodFinal'];     
                    $chamado->ATecFinal = $row['atecFinal'];     
                    $chamado->dtATecFinal = $row['dtatecFinal'];     
                    $chamado->ALabFinal = $row['alabFinal'];     
                    $chamado->dtALabFinal = $row['dtalabFinal'];     
                    $chamado->acaoFinal = $row['acaoFinal'];     
                    $chamado->dtacaoFinal = $row['dtacaoFinal'];     
                    $chamado->produtos = RatControl::getProdRat($numrat); 


                    return $chamado;
                    
                }else{
                    return null;
                }
                
            }
        }


    


        /*REMOVER
        public static function getLista(){

            $rat = Rat::getLista();

            $lista = [];
           
            
            if(count($rat)>0){
                for($i=0; $i<count($rat); $i++){
                    $row = $rat[$i];
                    $abertura = date_create($row['dtabertura']);

                    $chamado = new Chamado();

                    $chamado->numRat = $row['numrat'];
                    $chamado->codCli = $row['codcli'];
                    $chamado->razao = $row['cliente'];
                    $chamado->dtAbertura = date_format($abertura, "d/m/Y");

                    array_push($lista, $chamado);
  
                }

                return $lista;
            }else{
                return null;
            }

        }*/

        public static function getNovoNumeroRat(){
            $numrat = Rat::getNovoNumeroRat();

            $result;

            if(count($numrat)>0){
                $row = $numrat[0];
                $result = $row['numrat'];
                return $result;
            }else{
                return null;
            }
        }

        public static function getProdRat($key){
            $prodRat = Rat::getProdRat($key);

            $arr = [];

            for ($i = 0; $i < sizeof($prodRat); $i++){
                $p = new Produto();
                $p->codprod = $prodRat[$i]['codprod'];
                $p->produto = utf8_encode($prodRat[$i]['produto']);
                $p->numlote = $prodRat[$i]['numlote'];
                $p->dtFabricacao = $prodRat[$i]['dtfabricacao'];
                $p->dtValidade = $prodRat[$i]['dtvalidade'];
                $p->pVenda = $prodRat[$i]['pvenda'];
                $p->qt = $prodRat[$i]['qt'];
                $p->Total();
                array_push($arr, $p);
            }

            return $arr;

        }
    


        public static function setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor){
            
            return Rat::setNovaRat($numrat, $codcli, $abertura, strtoupper($problema), strtoupper($solicitante), $telsolicitante, strtoupper($pintor), $telpintor);

        }

        public static function setProdRat($key){
            return Rat::setProdRat($key['numrat'], $key['codprod'], $key['numlote'], $key['qt']);
        }

        /*Funções para finalizar etapas*/
        public static function finalizar($key){
            session_start();
            $numrat = $key['numrat'];
            $coduser = $key['coduser'];
            $motivo = htmlspecialchars(strtoupper($key['motivo']));

            $tipo = $key['tipo'];

            if($_SESSION['codsetor'] == 0 || $_SESSION['codsetor'] == 3 || $_SESSION['codsector'] == 2){
                /*CodSec 2 temporario para acesso do Paulo enquanto Jociel viaja.*/
                switch ($tipo) {
                    case 'finalProdutos':
                        echo Rat::finalizarProd($numrat);
                        break;
                    case 'finalATec':
                        echo Rat::finalizarATec($numrat);
                        break;
                    case 'finalAcao':
                        echo Rat::finalizarAcao($numrat);
                        break;
                }
            }

            if($_SESSION['codsetor'] == 0 || $_SESSION['codsetor'] == 2){
                switch ($tipo) {
                    case 'finalALab':
                        echo Rat::finalizarALab($numrat);
                        break;
                }
            }

            if($_SESSION['codsetor'] == 0 || $_SESSION['codsetor'] == 2 || $_SESSION['codsector'] == 1){
                if($tipo == 'reabrir') {
                    Rat::reabrirRat($numrat);
                    Rat::logAprova($_SESSION['coduser'], $numrat, $tipo);
                }elseif($tipo == 'reprovar'){
                    Rat::reprovarRat($numrat, $coduser, $motivo);
                    Rat::logAprova($_SESSION['coduser'], $numrat, $tipo);
                }elseif($tipo == 'aprovar'){
                    Rat::aprovarRat($numrat, $_SESSION['coduser']);
                    PdfFinal::salvarArquivo($numrat);
                    Mailer::sendMail($numrat);
                    Rat::logAprova($_SESSION['coduser'], $numrat, $tipo);
                }
            }


        }
    }



?>