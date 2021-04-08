<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Model/carga.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Model/pedido.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Model/produto.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='getCargas'){
            CargasControle::getCargas();
        }
        if($_POST['action']=='getFaltas'){
                CargasControle::getFaltas();
            }
        if($_POST['action']=='setSemCarga'){

            CargasControle::setSemCarga($_POST['query']);
    
        }
        if($_POST['action']=='getPedidos'){
            CargasControle::getPedidos($_POST['query']);
        }
        if($_POST['action']=='setCarga'){
            $numpedido = $_POST['query']['pedido'];
            $numcargaOriginal = $_POST['query']['numcargaOriginal'];
            $numcargaNovo = $_POST['query']['numcargaNovo'];
            CargasControle::setCarga($numpedido, $numcargaOriginal, $numcargaNovo);
        }
        if($_POST['action']=='setNovaCarga'){
            $nome = $_POST['query']['nome'];
            $veiculo = $_POST['query']['veiculo'];
            $data = $_POST['query']['data'];
            CargasControle::setNovaCarga($nome, $veiculo, $data);
        }
        if($_POST['action']=='deleteCarga'){
            CargasControle::deleteCarga($_POST['query']);
        }
        if($_POST['action']=='getDadoCarga'){
            CargasControle::getDadoCarga($_POST['query']);
        }
        if($_POST['action']=='editarCarga'){
            $nome = $_POST['query']['nome'];
            $numcarga = $_POST['query']['numcarga'];
            $veiculo = $_POST['query']['veiculo'];
            $data = $_POST['query']['data'];
            CargasControle::editarCarga($numcarga, $nome, $veiculo, $data);
        }
        if($_POST['action']=='setGroupCarga'){
            $numcarga = $_POST['query']['numcarga'];
            $pedidos = $_POST['query']['pedidos'];
            CargasControle::setGroupCarga($numcarga, $pedidos);
        }
        if($_POST['action']=='getPendencias'){
            $nome = $_POST['query'];
            CargasControle::getPendencias($nome);
        }        
        if($_POST['action']=='getPendenciasPedido'){
            $numped = $_POST['query'];
            CargasControle::getPendenciasPedido($numped);
        }
        if($_POST['action']=='getPendenciasPedidoEspecial'){
            $numped = $_POST['query'];
            CargasControle::getPendenciasPedidoEspecial($numped);
        }
        if($_POST['action']=='travaCarga'){
            $numcarga = $_POST['query'];
            CargasControle::travaCarga($numcarga);
        }
        if($_POST['action']=='abreCarga'){
            $numcarga = $_POST['query'];
            CargasControle::abreCarga($numcarga);
        }
        if($_POST['action']=='getSaldoCarga'){
            $cargas = $_POST['query'];
            CargasControle::getSaldoCarga($cargas);
        }
        if($_POST['action']=='getDisponivel'){
            $carga = $_POST['query'];
            CargasControle::getDisponivel($carga);
        }
    }

    class CargasControle{

        function getPedidos($rca){
            $ped = Pedido::getListaPedidos();
            $pedidosSemCarga = [];
            $todosSemCarga = []; //Para listar todos os rcas no filtro de busca, mesmo quando apenas um for selecionado.
            $pedidosComCarga = [];
            foreach($ped as $p){
                if($p->numcarga==0){
                    array_push($todosSemCarga,$p);
                    if($rca == ""){
                        array_push($pedidosSemCarga,$p);
                    }else{
                        if($rca == $p->rca){
                            array_push($pedidosSemCarga,$p);
                        }
                    }
                }else{
                    array_push($pedidosComCarga,$p);
                }
            }
            $cargas = Carga::getListaCargas();
            $rca = Pedido::getRcaPedidos($todosSemCarga);

            $lista = ['pedidosSemCarga'=>$pedidosSemCarga, 'pedidosComCarga'=>$pedidosComCarga, 'cargas'=>$cargas, 'rca'=>$rca];

            echo json_encode($lista);
        }

        function getFaltas(){

            $falta = Falta::getFaltas();
            $ret=[];
            foreach($falta as $f)
            {
                array_push($ret,$f);
            }
            return $ret;
            
        }
        function getCargas(){
            return Carga::getListaCargas();
        }

        /*function setCarga($numped, $cargaOriginal, $cargaNovo){
            //Acho que não está sendo suado
            if($cargaOriginal > 0 && $cargaNovo == 0){
                echo Carga::setSemCarga($numped);
            }elseif($cargaOriginal == 0 && $cargaNovo >0){
                echo Carga::setCargaPedido($numped, $cargaNovo);
            }elseif($cargaOriginal > 0 && $cargaNovo >0){
                echo Carga::atualizaCarga($numped, $cargaNovo);
            }
        }*/

        function setSemCarga($numped){
            echo Carga::setSemCarga($numped);
        }

        function setGroupCarga($numcarga, $pedidos){
            if($numcarga > 0){
                echo Carga::setCargaPedidoGrupo($pedidos, $numcarga);
            }
        }
        
        function setNovaCarga($nome, $veiculo, $data){

            $nome = strtoupper($nome);
            $veiculo = strtoupper($veiculo);

            //$dtPrevisao = Formatador::formatarData($data);
            if($data !== ""){
                $d = explode('-',$data);
                $dtPrevisao = $d[2].'/'.$d[1].'/'.$d[0];
            }else{
                $dtPrevisao = null;
            }

            echo Carga::setNovaCarga($nome, $veiculo, $dtPrevisao);
            
        }

        function deleteCarga($numcarga){
            echo Carga::deleteCarga($numcarga);
        }

        function getDadoCarga($numcarga){
            echo json_encode(Carga::getDadoCarga($numcarga));
        }

        function editarCarga($numcarga, $nome, $veiculo, $data){
            $dtPrevisao = Formatador::formatarData($data);
            //echo $dtPrevisao;
            echo Carga::editarCarga($numcarga, $nome, $veiculo, $dtPrevisao);
        }

        /************FUNÇÕES PARA VIEW **************/

        function getPedidosView($rca){
            $ped = Pedido::getListaPedidos();
            $pedidosSemCarga = [];
            $todosSemCarga = []; //Para listar todos os rcas no filtro de busca, mesmo quando apenas um for selecionado.
            $pedidosComCarga = [];
            foreach($ped as $p){
                if($p->numcarga==0){
                    array_push($todosSemCarga,$p);
                    if($rca == ""){
                        array_push($pedidosSemCarga,$p);
                    }else{
                        if($rca == $p->rca){
                            array_push($pedidosSemCarga,$p);
                        }
                    }
                }else{
                    array_push($pedidosComCarga,$p);
                }
            }
            $cargas = Carga::getListaCargas();
            $rca = Pedido::getRcaPedidos($todosSemCarga);

            $lista = ['pedidosSemCarga'=>$pedidosSemCarga, 'pedidosComCarga'=>$pedidosComCarga, 'cargas'=>$cargas, 'rca'=>$rca];

            return $lista;
        }

        function getPendencias($numcarga){
            echo json_encode(Carga::getPendencias($numcarga));
        }

        function getPendenciasPedido($numped){
            //echo 'okok';
            echo json_encode(Pedido::getPendenciasPedidos($numped));
        }
        
        function getPendenciasPedidoEspecial($numped){
            //echo 'okok';
            echo json_encode(Pedido::getPendenciasPedidosEspecial($numped));
        }

        function getPedidosDist($rca){
            $ped = Pedido::getListaPedidosDist();
            $pedidosSemCarga = [];
            $todosSemCarga = []; //Para listar todos os rcas no filtro de busca, mesmo quando apenas um for selecionado.
            $pedidosComCarga = [];
            foreach($ped as $p){
                if($p->numcarga==0){
                    array_push($todosSemCarga,$p);
                    if($rca == ""){
                        array_push($pedidosSemCarga,$p);
                    }else{
                        if($rca == $p->rca){
                            array_push($pedidosSemCarga,$p);
                        }
                    }
                }else{
                    array_push($pedidosComCarga,$p);
                }
            }
            $cargas = Carga::getListaCargas();
            $rca = Pedido::getRcaPedidos($todosSemCarga);
            $estoque = Produto::getEstoque();

            foreach($cargas as $c){
                for($p=0; $p<sizeof($pedidosComCarga); $p++){
                    $aux = [];
                    if($pedidosComCarga[$p]->numcarga==$c->numcarga){

                        for($pi=0; $pi<sizeof($pedidosComCarga[$p]->produtos); $pi++){
                            for($i=0; $i<sizeof($estoque); $i++ ){
                                if($pedidosComCarga[$p]->produtos[$pi]['COD'] == $estoque[$i]['COD']){
                                    echo $pedidosComCarga[$p]->numped."\n";
                                    // array_push($aux, intval($pedidosComCarga[$p]->produtos[$pi]['QT']).' - '.intval($estoque[$i]['QT']));
                                    // $pedidosComCarga[$p]->prevPos = $aux;
                                    if(intval($pedidosComCarga[$p]->produtos[$pi]['QT']) <= intval($estoque[$i]['QT'])){
                                        $estoque[$i]['QT'] = intval($estoque[$i]['QT']) - intval(($pedidosComCarga[$p]->produtos[$pi]['QT']));
                                        $pedidosComCarga[$p]->prevPos = 'L';
                                    }else{
                                        $pedidosComCarga[$p]->prevPos = 'P';
                                        $i = sizeof($estoque);
                                    }
                                    
                                }
                                
                            }
                           
                        }
                    }

                    
                }
                //break;
            }

            //return $pedidosComCarga;;

            $lista = ['pedidosSemCarga'=>$pedidosSemCarga, 'pedidosComCarga'=>$pedidosComCarga, 'cargas'=>$cargas, 'rca'=>$rca];

            return $lista;
        }

        function travaCarga($numcarga){
            //echo 'okok';
            echo json_encode(Carga::travaCarga($numcarga));
        }
        function abreCarga($numcarga){
            //echo 'okok';
            echo json_encode(Carga::abreCarga($numcarga));
        }

        function getSaldoCarga($cargas){
            echo json_encode(Carga::getSaldoCarga($cargas));
        }

        function getDisponivel($carga){
            echo json_encode(Carga::getDisponivel($carga));
        }

    }




?>