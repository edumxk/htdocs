<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMapaProducao/Model/modelMapa.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='alterarStatus'){
           $codproducao = $_POST['codproducao'];
           echo Mapa::alterarStatus($codproducao);
        }
        if($_POST['action']=='getProducaoE'){
            $codproducao = $_POST['query'];
            echo json_encode(mapaControle::getProducaoEditar($codproducao));
        }
        if($_POST['action']=='getProduto'){
            $codigo = $_POST['cod'];
            echo Mapa::getProduto($codigo)[0]["DESCRICAO"];
        }
        if($_POST['action']=='excluir'){
            $codproducao = $_POST['dataset']['codproducao'];
            $codfun = $_POST['dataset']['codfun'];
            echo Mapa::excluir($codproducao, $codfun);
        }
        if($_POST['action']=='getPeso'){
            $codigo = $_POST['cod'];
            echo Mapa::getProduto($codigo)[0]["PESO"];
        }
        if($_POST['action']=='cadastrar'){
            $array = $_POST['query'];
            $codtanque =  $array["codtanque"];
            $dtprevisao = $array["dtprevisao"];
            $produtos = $array["produtos"];
            $codfun = $array["codfun"];
            $status = $array["status"];
            $dataini = $array["dataini"];
            $lote = $array["lote"];
            if($codtanque>0 && $dtprevisao != "" &&  $codfun>0)
                echo Mapa::cadastrar($array);
            else
                echo "FAIL";
        }
        if($_POST['action']=='editar'){
            $array = $_POST['query'];
            $codtanque =  $array["codtanque"];
            $dtprevisao = $array["dtprevisao"];
            //$produtos = $array["produtos"];
            $codfun = $array["codfun"];
            $codproducao = $array["codproducao"];
            /*$status = $array["status"];
            $dtfecha = $array["dtfecha"];
            $hrfecha = $array["hrfecha"];
            $lote = $array["lote"];*/
            if($codtanque>0 && $dtprevisao != "" &&  $codfun>0 && $codproducao>0)
                echo Mapa::editar($array);
            else
                echo "FAIL";
        }
        if($_POST['action']=='linhas'){
            $valor = $_POST['valor'];
            $arr =  Mapa::linhas($valor);
            $ret = '';
            foreach($arr as $r){
                echo "<option value='".$r['CODTANQUE']."'>T ".$r['CODTANQUE'].' | '.$r['CAPACIDADE'].' | '.$r['NOME']."</option><br>";
            }
            echo $ret;
         }
        if($_POST['action']=='linhas2'){
            $valor = $_POST['codproducao'];
            $arr =  Mapa::linhas2($valor);
            $ret = '';
            foreach($arr as $r){
                echo "<option value='".$r['CODTANQUE']."'>T ".$r['CODTANQUE'].' | '.$r['CAPACIDADE'].' | '.$r['NOME']."</option><br>";
            }
            echo $ret;
         }
        

    }

    class mapaControle{

        static function getProducao($data){
            $prod = Mapa::getProducao($data);
            //$lista = ['pedidosSemCarga'=>$pedidosSemCarga, 'pedidosComCarga'=>$pedidosComCarga, 'cargas'=>$cargas, 'rca'=>$rca];
           return ($prod);
    }
    static function getLinhas(){
        $arr = Mapa::getLinhas();
        $ret = [$arr[0]["LINHA"],$arr[1]["LINHA"],$arr[2]["LINHA"],$arr[3]["LINHA"],$arr[4]["LINHA"]];
        return ($ret );
    }
    static function getItem(){
        $arr = Mapa::getItem();
        return ($arr);
    }
    static function getItemH(){
        $arr = Mapa::getItemH();
        return ($arr);
    }
    static function getProducaoEditar($codproducao){
        $head = Mapa::getProducaoEditar($codproducao);
        
        $produtos = Mapa::getItem();
        $produtos2 = Mapa::getItemLote($head[0]->lote);
        $itens =[];
        foreach($produtos as $p):
            if($p->codproducao == $codproducao):
                array_push($itens, $p->codprod);
                array_push($itens, $p->qt);
            endif;
        endforeach;
        $arr = [$head, $itens];
        return $arr;
    }
    
}

?>