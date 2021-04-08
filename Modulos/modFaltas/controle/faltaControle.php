<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/dao/daoFalta.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/model/produto.php');
    //require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/novaFalta.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='delProd'){
            $dados = $_POST['query'];
            echo (daoFalta::delProd($dados['id'])[0]['ID']); 
        }
        if($_POST['action']=='enviarProd'){
            $dados = $_POST['query'];
            echo (daoFalta::enviarProd($dados['id'])[0]['ID']); 
        }
        if($_POST['action']=='finalizarProd'){
            faltaControle::finalizarProd($_POST['query']);
        }
        if($_POST['action']=='getnumfalta'){
            $temp = $_POST;
            var_dump(daoFalta::getFalta($temp));
        }
        if($_POST['action']=='incluirProd'){
            $dados = $_POST['query'];
             daoFalta::incluirProd($dados['numfalta'],$dados['codprod'],$dados['qt'],$dados['posicao'],$dados['motivo'],$dados['codcli'],$dados['numnota'],$dados['tipocusto'],$dados['dataf'],$dados['obs']);
        }
        if($_POST['action']=='incluirFaltaC'){
            $dados = $_POST['query'];
            echo daoFalta::incluirFaltaC($dados['codcli'], $dados['numnota'], $dados['numnotacusto'], $dados['motorista'], $dados['obs']);
        }
        
    }

    class faltaControle{


        public static function getRat($numrat){
            if($numrat==""){
                return null;
            }else{
                //echo 'teste';
                $rat = RatProdDao::getRat($numrat);
                $result=0;

                if(count($rat)>0){
                    $row = $rat[0];
                    $abertura = date_create($row['dtabertura']);
                    $result['numfalta'] = $row['numfalta'];
                    $result['dtabertura'] = date_format($abertura, "d/m/Y");           
                    $result['codcli'] = $row['codcli'];       
                    $result['problema'] = $row['problema'];      
                    return $result;
                    
                }else{
                    return null;
                }
            }
        }


        public static function getNovoNumeroRat(){
            $numrat = Rat::getNovoNumeroRat();

            $result=0;

            if(count($numrat)>0){
                $row = $numrat[0];
                $result = $row['numrat'];
                return $result;
            }else{
                return null;
            }

        }


    





  
        function delProdRat($key){
            return RatProdDao::delProdRat($key['numnota'], $key['codprod']);
        }



        public static function finalizarProd($numfalta){
            return Rat::finalizarProd($numfalta);
        }



        /*PREENCHE AS CLASSES*/
        function getProdModel($key){
            $prodRat = RatProdDao::getProd($key);

            $arr = [];

            for ($i = 0; $i < sizeof($prodRat); $i++){
                $p = new Produto();
                $p->codprod = $prodRat[$i]['codprod'];
                $pr = RatProdDao::getProdutoByCod($p->codprod)[0]['PRODUTO'];
                $p->produto = $pr;
                $p->qt = $prodRat[$i]['qt'];
                array_push($arr, $p);
            }

            return $arr;

        }


    }



?>