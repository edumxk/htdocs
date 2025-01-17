<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/ratProdDao.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/produto.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/formulario.php');


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
        if($_POST['action']=='produto2'){
            RatProdControle::getProduto2($_POST['query']);
        }
        if($_POST['action']=='produtoJson'){
            RatProdControle::getProdutoJson();
        }
        if($_POST['action']=='setProdRat'){
            RatProdControle::setProdRat($_POST['query']);
        }
        if($_POST['action']=='delProdRat'){
            RatProdControle::delProdRat($_POST['query']);
        }
        if($_POST['action']=='finalizarProd'){
            RatProdControle::finalizarProd($_POST['query']);
        }
        if($_POST['action']=='finalizarProdEspecial'){
            RatProdControle::finalizarProdEspecial($_POST['query']);
        }
        if($_POST['action']=='getForm'){
            $ret = [];
            $rat = Rat::getFormularioRat($_POST['query']);
            $ret[] = Rat::getFormularioC();
            $ret[] = Rat::getFormularioI();
            $ret = Formulario::getFormulario($ret);

            if(count($rat[0])==0){
                echo json_encode($ret);
                return;
            }
            foreach($ret as $r){
                foreach($rat[0] as $a){
                    foreach($r as $k => $v){
                        if($v->idPrincipal == $a['IDPRINCIPAL'] && $v->idOpcao == $a['IDOPCAO'])
                            $r[$k]->selected = $a['IDOPCAO'];
                        if( $r[$k]->selected == null)
                            $r[$k]->selected = 0;
                        
                    }
                }
            }
            if(count($rat[1])>0)
                $ret[] = $rat[1][0];
            echo json_encode($ret);
        }
        if($_POST['action']=='salvarForm'){
            echo json_encode(RatProdControle::salvarForm($_POST['query']));
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

            $result = 0;

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
            $produto='';
            if($key['lote'] == ""){
                $produto = ratProdDao::getProdutoByCod($key['codprod']);
                $produto = $produto[0];
            }else{
                $produto = ratProdDao::getProdByLote($key['lote'], $key['codprod'] );
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
        public static function getProduto2($key){
  
            $produto=[];
            $produto2=[];
     
            $produto = ratProdDao::getProdByLote2($key['lote']);
                foreach ($produto as $p){
                    array_push($produto2, $p);
                }
            
                $ret = [];
            
            if(sizeof($produto2)>0):
                foreach($produto2 as $p2){
                    $p = new Produto();
                    $p->codprod = $p2['CODPROD'];
                    $p->produto = utf8_encode($p2['PRODUTO']);
                    $p->numlote = $p2['NUMLOTE'];
                    $p->dtFabricacao = $p2['DATAFABRICACAO'];
                    $p->dtValidade = $p2['DTVALIDADE'];
                    $p->pVenda = ratProdDao::getPvendaByCod($key['codcli'], $p->codprod);
                    array_push($ret, $p);
                }
            endif;
                echo JSON_ENCODE($ret);
            
        }

        /*BUSCA DE PRODUTO POR NOME EM ARQUIVO JSON PARA TYPEAHEAD*/
        public static function getProdutoJson(){

            //verifica a data do arquivo da ultima alteração
            $file = $_SERVER["DOCUMENT_ROOT"] . '/recursos/json/produto.json';
            $filetime = filemtime($file);
            $now = time();
            $diff = $now - $filetime;

            if($diff > 604800){
                $produtos = ratProdDao::getProdutos();
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/recursos/json/produto.json', $produtos);
            }
            //retorna o arquivo json
            $json_file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/recursos/json/produto.json'); 
            
            $json_str = json_decode($json_file, true);
            

            echo $json_file;


        }

        /*PREENCHE TABELAS*/
        function getProdRat($key){
            $prodRat = RatProdDao::getProdRat($key);

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
                $produto = ratProdDao::getProdByLote($key['numlote'], $key['codprod']);
                $numrat = $key['numrat'];
                $codprod = $produto['CODPROD'];
                $numlote = $key['numlote'];
                $qt = $key['qt'];
                $dtFabricacao = $produto['DATAFABRICACAO'];
                $dtValidade = $produto['DTVALIDADE'];
                $pVenda = ratProdDao::getPvendaByLote($key['codcli'], $numlote,  $codprod);
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
        public static function finalizarProdEspecial($dados){
            return Rat::finalizarProdEspecial($dados);
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

        public static function salvarForm($array){
            $form = Formulario::getFormularioOpcoes($array);
            $rat = Rat::getFormularioRat($array['numrat'])[0];
            
            if( count($rat) == 0 )
                return rat::salvarForm($form, 0);
            else
                return rat::salvarForm($form, 1);
        }
    }

?>
