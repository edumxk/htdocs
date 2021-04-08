<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/ratProdDao.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/produto.php');


    if(isset($_POST['action'])){
        // if($_POST['action']=='buscaProd'){
        //     NovaRatControle::setNovaRat($_POST['query']);
        // }
        if($_POST['action']=='getProdRat'){
            RatProdControle::getProdRat($_POST['query']);
        }
        if($_POST['action']=='produto'){
            RatProdControle::getProduto($_POST['query']);
        }
        if($_POST['action']=='produtoJson'){
            RatProdControle::getProdutoJson();
        }
        if($_POST['action']=='setProdRat'){
            RatProdControle::setProdRat($_POST['query']);
        }
        if($_POST['action']=='delProdRat'){
            RatProdControle::delProdRat($_POST['query']);
        }if($_POST['action']=='finalizarProd'){
            RatProdControle::finalizarProd($_POST['query']);
        }

    }

    class RatProdControle{

        public static function getRat($numrat){
            if($numrat==""){
                return null;
            }else{
                //echo 'teste';
                $rat = RatProdDao::getRat($numrat);
                $result;

                if(count($rat)>0){
                    $row = $rat[0];
                    $abertura = date_create($row['dtabertura']);
                    $encerramento = date_create($row['dtencerramento']);
                    $result['numrat'] = $row['numrat'];
                    $result['dtabertura'] = date_format($abertura, "d/m/Y");
                    $result['dtencerramento'] = date_format($encerramento, "d/m/Y");
                    $result['rca'] = $row['codusur1'];
                    $result['codcli'] = $row['codcli'];
                    $result['cliente'] = $row['cliente'];
                    $result['cnpj'] = $row['cgcent'];
                    $result['fantasia'] = $row['fantasia'];
                    $result['cidade'] = $row['municent'];
                    $result['uf'] = $row['estent'];              
                    $result['telefone'] = $row['telent'];              
                    $result['solicitante'] = $row['solicitante'];              
                    $result['solicitante_tel'] = $row['solicitante_tel'];              
                    $result['pintor'] = $row['pintor'];              
                    $result['pintor_tel'] = $row['pintor_tel'];        
                    $result['problema'] = $row['problema'];      


                    return $result;
                    
                }else{
                    return null;
                }
            }
        }


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


        /*BUSCA DE PRODUTO POR LOTE*/
        public static function getProduto($key){
            $produto;
            if($key['lote'] == ""){
                $produto = ratProdDao::getProdutoByCod($key['codprod']);
                $produto = $produto[0];
            }else{
                
                $produto = ratProdDao::getProdByLote($key['lote']);
            }
            
            

            if($produto>0){
                $p = new Produto();
                $p->codprod = $produto['CODPROD'];
                $p->produto = utf8_encode($produto['PRODUTO']);
                $p->numlote = $produto['NUMLOTE'];
                $p->dtFabricacao = $produto['DATAFABRICACAO'];
                $p->dtValidade = $produto['DTVALIDADE'];
                $p->pVenda = ratProdDao::getPvendaByCod($key['codcli'], $p->codprod);


                echo JSON_ENCODE($p);
            }
        }

        /*BUSCA DE PRODUTO POR NOME EM ARQUIVO JSON PARA TYPEAHEAD*/
        public static function getProdutoJson(){

            $json_file = file_get_contents("http://localhost/recursos/json/produto.json"); 

            $json_str = json_decode($json_file, true);
            

            echo $json_file;


        }







        /*PREENCHE TABELAS*/
        function getProdRat($key){
            $prodRat = RatProdDao::getProdRat($key);
;
            $arr = [];

            for ($i = 0; $i < sizeof($prodRat); $i++){
                $p = new Produto();
                $p->codprod = $prodRat[$i]['CODPROD'];
                $pr = RatProdDao::getProdutoByCod($p->codprod)[0]['PRODUTO'];
                $p->produto = utf8_encode($pr);
                $p->numlote = $prodRat[$i]['NUMLOTE'];
                $p->dtFabricacao = $prodRat[$i]['DATAFABRICACAO'];
                $p->dtValidade = $prodRat[$i]['DTVALIDADE'];
                $p->pVenda = $prodRat[$i]['PVENDA'];
                $p->qt = $prodRat[$i]['QT'];
                $p->Total();
                array_push($arr, $p);
            }

            echo JSON_ENCODE($arr);

        }
    
        function setProdRat($key){

            $numrat = $key['numrat'];
            $codcli = $key['codcli'];
            $codprod = $key['codprod'];
            $produto = $key['produto'];
            $numlote = $key['numlote'];
            $qt = $key['qt'];;
            $dtFabricacao;
            $dtValidade;
            $pVenda;

            if($numlote == ""){
                if($codprod == ""){
                    /*SE numlote e codprod for vazio*/
                    $produto = ratProdDao::getProdutoByNome($produto);
                    if($produto['CODPROD'] == null){
                        echo 'erro produto';
                        exit();
                    }
                    $codprod = $produto['CODPROD'];
                    $numlote = 0;
                    $dtFabricacao = '--';
                    $dtValidade = '--';
                    $pVenda = ratProdDao::getPvendaByCod($codcli, $codprod);
                }else{
                    /*SE numlote for vazio, mas codprod não for vazio*/
                    $produto = ratProdDao::getProdutoByNome($codprod);
                    $produto = $produto['PRODUTO'];
                    $numlote = 0;
                    $dtFabricacao = '--';
                    $dtValidade = '--';
                    $pVenda = ratProdDao::getPvendaByCod($codcli, $codprod);
                }
            
            }else{
                $produto = ratProdDao::getProdByLote($key['numlote']);
                $numrat = $key['numrat'];
                $codprod = $produto['CODPROD'];
                $numlote = $key['numlote'];
                $qt = $key['qt'];
                $dtFabricacao = $produto['DATAFABRICACAO'];
                $dtValidade = $produto['DTVALIDADE'];
                $pVenda = ratProdDao::getPvendaByLote($key['codcli'], $numlote);
            }




            echo JSON_ENCODE(RatProdDao::setProdRat($numrat, $codprod, $numlote, $qt, $dtFabricacao, $dtValidade, $pVenda));
        }
    
        function delProdRat($key){
            return RatProdDao::delProdRat($key['numrat'], $key['codprod'], $key['numlote']);
        }



        public static function setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor){
            
            return Rat::setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor);

        }

        public static function finalizarProd($numrat){
            return Rat::finalizarProd($numrat);
        }



        /*PREENCHE AS CLASSES*/
        function getProdRatModel($key){
            $prodRat = RatProdDao::getProdRat($key);

            $arr = [];

            for ($i = 0; $i < sizeof($prodRat); $i++){
                $p = new Produto();
                $p->codprod = $prodRat[$i]['codprod'];
                $pr = RatProdDao::getProdutoByCod($p->codprod)[0]['PRODUTO'];
                $p->produto = $pr;
                $p->numlote = $prodRat[$i]['numlote'];
                $p->dtFabricacao = $prodRat[$i]['datafabricacao'];
                $p->dtValidade = $prodRat[$i]['dtvalidade'];
                $p->pVenda = $prodRat[$i]['pvenda'];
                $p->qt = $prodRat[$i]['qt'];
                $p->Total();
                array_push($arr, $p);
            }

            return $arr;

        }


    }



?>