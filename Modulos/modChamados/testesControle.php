<?php    

    if(isset($_POST['action'])){

        if($_POST['action']=='getCargas'){
            TesteControle::getCargas();
        }
        if($_POST['action']=='getCargasPeso'){
            TesteControle::getCargasPeso();
        }
        if($_POST['action']=='setPedido'){
            TesteControle::setPedido();
        }


    }

    class TesteControle{
        function getJson(){
            $json = file_get_contents("testes.json");
            $array = json_decode($json, true);
            return $array;
        }

        function getCargas(){
            $json = file_get_contents("testes.json");
            $array = json_decode($json, true);
            echo json_encode($array['cargas']);
        }

        function getCargasPeso(){
            $json = file_get_contents("testes.json");
            $array = json_decode($json, true);

            $ret = [];
            foreach($array['cargas'] as $c){
                $carga = $c['carga'];
                $peso = 0;
                foreach($array['pedidos'] as $p){
                    if($c['carga']==$p['carga']){
                        $peso+=$p['peso'];
                    }
                }
                array_push($ret, ['carga'=>$carga, 'peso'=>$peso]);
            }

            echo json_encode($ret);
        }

        function setPedido($numped, $peso, $carga){
            $json = file_get_contents("testes.json");
            $array = json_decode($json, true);

            $cargas = $array['cargas'];
            $pedidos = $array['pedidos'];

            $novo = ['cod'=>$numped, 'peso'=>$peso, 'carga'=>$carga];

            array_push($pedidos, $novo);

            echo json_encode($pedidos);
        }
    }
?>