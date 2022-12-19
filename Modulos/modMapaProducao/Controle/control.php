<?php


    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMapaProducao/Model/modelMapa.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMapaProducao/Model/ModelLote.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='alterarStatus'){
           $codproducao = $_POST['codproducao'];
           echo Mapa::alterarStatus($codproducao);
        }
        if($_POST['action']=='getProducaoE'){
            $codproducao = $_POST['codproducao'];
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
            $hrfecha = $array["hrfecha"];*/
            $lote = $array["lote"];
            if($lote != null){
                echo ModelLote::editar($array);
            }else if($codtanque>0 && $dtprevisao != "" &&  $codfun>0 && $codproducao>0)
                echo ModelLote::editar2($array);
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
        if($_POST['action']=='cadastrarPorLote'){
            $dados = [];
            $lote = $_POST['lote'];
            $tanque = $_POST['tanque'];
            $dtprevisao = $_POST['dtprevisao'];
            $hrprevisao = $_POST['hrprevisao'];
            $codfun = $_POST['codfun'];
            $dtinicio = $_POST['dtinicio'];
        
            $dados = ['lote' => $lote, 'tanque' => $tanque, 'dtprevisao' => $dtprevisao, 
            'hrprevisao' => $hrprevisao, 'codfun' => $codfun, 'dtinicio' => $dtinicio];
            
            echo json_encode(ModelLote::consultaLote($dados));
        }
        if($_POST['action']=='consultarProdutos'){
            $lote = $_POST['lote'];
            $codproducao = $_POST['codproducao'];
            $dados = ['lote' => $lote, 'codproducao' => $codproducao];
            echo json_encode(ModelLote::consultaLote($dados));
        }

        if($_POST['action']=='cadastrarNovo'){
            $array = $_POST['query'];
            $codtanque =  $array["codtanque"];
            $dtprevisao = $array["dtprevisao"];
            $codfun = $array["codfun"];
          
            if($codtanque>0 && $dtprevisao != "" &&  $codfun>0)
                echo ModelLote::cadastrarNovo($array);
            else
                echo "FAIL";
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
        $lote = $head[0]->lote;
        $produtos = ModelLote::getItemLote($codproducao, $lote);
    
        $arr = [$head, $produtos];
        return $arr;
    }
}
?>