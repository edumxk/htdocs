<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/corretivaDao.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/ratProdDao.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/corretiva.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/produto.php');



    if(isset($_POST['action'])){
        if($_POST['action']=='getCorretiva'){
            CorretivaControle::getCorretiva($_POST['query']);
        }
        if($_POST['action']=='getProduto'){
            CorretivaControle::getProduto($_POST['query']);
        }
        if($_POST['action']=='setAcao'){
            CorretivaControle::setAcao($_POST['query']);
        }
        if($_POST['action']=='delAcao'){
            CorretivaControle::delAcao($_POST['query']);
        }
        if($_POST['action']=='setParecer'){
            CorretivaControle::setParecer($_POST['query']);
        }
        if($_POST['action']=='getCustos'){
            CorretivaControle::getCustos($_POST);
        }
        
    }

    class CorretivaControle{

        public static function getCorretiva($numrat){
            /*Função retorna um array de Ações em formato JSON para a view*/
            $ret = CorretivaDao::getCorretiva($numrat);
            $acoes = [];
            $total = 0;

            if(count($ret)>0){
                for($i=0; $i<sizeof($ret); $i++){
                    $c = new Corretiva();
                    $c->numrat = $ret[$i]['NUMRAT'];
                    $c->tipo = $ret[$i]['TIPO'];
                    $c->custo = utf8_encode($ret[$i]['CUSTO']);
                    $c->codprod = $ret[$i]['CODPROD'];
                    $c->despesa = utf8_encode($ret[$i]['DESPESA']);
                    $c->qt = $ret[$i]['QT'];
                    $c->valor = $ret[$i]['VALOR']*$ret[$i]['QT'];

                    array_push($acoes, $c);
                    $total +=$ret[$i]['VALOR']*$ret[$i]['QT'];

                }
            }


            $total = number_format($total, 2, ',', '.');
            $arr = ["acoes"=>$acoes, "total"=>$total];
            echo JSON_ENCODE($arr);

        }


        public static function getCorretivaIn($numrat){
            /*Função retorna um array de Ações para ser usado em outras funções internas*/
            $ret = CorretivaDao::getCorretiva($numrat);
            $arr = [];

            if(count($ret)>0){
                for($i=0; $i<sizeof($ret); $i++){
                    $c = new Corretiva();
                    $c->numrat = $ret[$i]['NUMRAT'];
                    $c->tipo = $ret[$i]['TIPO'];
                    $c->codprod = $ret[$i]['CODPROD'];
                    $c->despesa = $ret[$i]['DESPESA'];
                    $c->qt = $ret[$i]['QT'];
                    $c->valor = $ret[$i]['VALOR'];

                    array_push($arr, $c);
                }
            }

            return $arr;

        }

        public static function getProduto($codprod){
            //echo $codprod;
            $ret = CorretivaDao::getProduto($codprod);
            
            if(sizeof($ret)>0){
                $p = new Produto();
                $p->codprod = $ret[0]['CODPROD'];
                $p->produto = utf8_encode($ret[0]['DESCRICAO']);

                echo JSON_ENCODE($p);
            }else{
                echo 0;
            }
        }

        public static function setAcao($key){

            $acao = $key['acao'];
            $codprod = $key['codprod'];
            $numrat = $key['numrat'];
            $codcli = $key['codcli'];
            $custo = $key['custo'];
        
            echo json_encode($key);


            if($key['codprod']>0){
                $prod = CorretivaDao::getProduto($codprod);
                $c = new Corretiva();

                if(sizeof($prod)>0){
                $c->numrat = $numrat;
                $c->tipo = $acao;
                $c->codprod = $prod[0]['CODPROD'];
                $c->despesa = $prod[0]['DESCRICAO'];
                $c->qt = $key['qt'];
                $pvenda = RatProdDao::getPvendaFromRatI($numrat, $codprod);
                if($pvenda == 0){
                    $pvenda = RatProdDao::getPvendaByCod($codcli, $codprod);
                }
                
                $c->valor = $pvenda;
                $c->codcusto = $custo;


                }
            }else{
                if($acao=='CUSTEAR'){
                    $c = new Corretiva();
                    $c->numrat = $numrat;
                    $c->tipo = $acao;
                    $c->codprod = 0;
                    $c->despesa = strtoupper($key['produto']);
                    $c->qt = 1;
                    $c->valor = $key['qt'];
                    $c->codcusto = $custo;

                }else{
                    $prod = CorretivaDao::getProdutoByNome($key['produto']);
                    
                    $c = new Corretiva();
                    $c->numrat = $numrat;
                    $c->tipo = $acao;
                    $c->codprod = $prod[0]['CODPROD'];
                    $c->despesa = $prod[0]['DESCRICAO'];
                    $c->qt = $key['qt'];
                    $pvenda = RatProdDao::getPvendaByCod($codcli, $c->codprod);
                    $c->valor = $pvenda;
                    $c->codcusto = $custo;
                }

            }

            $arr = CorretivaControle::getCorretivaIn($numrat);


            foreach($arr as $a){
                if($c->despesa == $a->despesa && $c->tipo == $a->tipo){
                    echo 'EXISTE';
                    exit();
                }
            }
            //if($c->tipo == )
            $ret = CorretivaDao::setAcao($c->tipo, $c->numrat, $c->codprod, $c->despesa, $c->qt, $c->valor, $c->codcusto);
            echo json_encode($ret);
            exit();
            



        }

        public static function delAcao($data){

                echo JSON_ENCODE(CorretivaDao::delAcao($data['numrat'], $data['tipo'], utf8_decode($data['despesa'])));
    
        }





        public static function getRat($numrat){
            if($numrat==""){
                return null;
            }else{
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
                
                
                /*$json_file = file_get_contents("http://localhost/json/data.json");   
                $json_str = json_decode($json_file, true);

                $rats = $json_str['ratc'];

                return $rats[0];*/




                //echo JSON_ENCODE($rats[1]['numrat']);
                            
                //return $json_str['ratc'];
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



        /*PREENCHE TABELAS*/
        function getProdRat($key){
            $prodRat = RatProdDao::getProdRat($key);

            $arr = [];

            for ($i = 0; $i < sizeof($prodRat); $i++){
                $p = new Produto();
                $p->codprod = $prodRat[$i]['codprod'];
                $p->produto = $prodRat[$i]['produto'];
                $p->numlote = $prodRat[$i]['numlote'];
                $p->dtFabricacao = $prodRat[$i]['dtfabricacao'];
                $p->dtValidade = $prodRat[$i]['dtvalidade'];
                $p->pVenda = $prodRat[$i]['pvenda'];
                $p->qt = $prodRat[$i]['qt'];
                $p->Total();
                array_push($arr, $p);
            }

            echo JSON_ENCODE($arr);

        }
    
        function setProdRat($key){

            echo JSON_ENCODE(RatProdDao::setProdRat($key['numrat'], $key['codprod'], $key['numlote'], $key['qt']));
        }
    
        function delProdRat($key){
            echo JSON_ENCODE(RatProdDao::delProdRat($key['numrat'], $key['codprod'], $key['numlote']));
        }

        public static function setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor){
            
            return Rat::setNovaRat($numrat, $codcli, $abertura, $problema, $solicitante, $telsolicitante, $pintor, $telpintor);

        }

        public static function setParecer($key){
            echo Rat::setParecer($key['numrat'], $key['procedente']);
        }

        function getCustos(){
            $arr = CorretivaDao::getCustos();
            //var_dump($arr);
           for($i = 0; $i<sizeof($arr); $i++){
                $arr[$i]['CUSTO'] = utf8_encode($arr[$i]['CUSTO']);
            }
            //var_dump($arr);
            echo json_encode($arr);
        }
    }



?>