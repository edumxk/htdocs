<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/Lab/modContraTipo/model/modelo.php');
    if(isset($_POST['action'])){
        if($_POST['action']=='getFiltros'){
            return ContraTipoControle::getFiltros(); 
        }        
        if($_POST['action']=='getProduto'){
            $codigo = $_POST['cod'];
            echo ProdutoPesquisa::getProduto2($codigo);
        }        
        if($_POST['action']=='concluir'){
            $lista = $_POST['lista'];
            $cabecalho = $_POST['cabecalho'];
            
            echo ContraTipoModel::setListaAlterados($cabecalho, $lista);
        }        
        if($_POST['action']=='preencher'){
            $id = $_POST['id'];
            echo json_encode(ProdutoPesquisa::getProduto3($id));
        }        
        if($_POST['action']=='deletar'){
            $id = $_POST['id'];
            $senha = $_POST['senha'];
            ContraTipoModel::deletar($id, $senha);
        }        
        if($_POST['action']=='verFormula'){
            $codprod = $_POST['codprod'];
            $metodo = $_POST['metodo'];
            echo json_encode(ProdutoPesquisa::getFormula($codprod, $metodo));
        }        
    }
    class ProdutoPesquisa{
        protected $produto;
        protected $codprod;

        public function getCodprod(){
            return $this->codprod;
        }
        public function getDescricao(){
            return $this->produto;
        }
        public static function getProduto(){
            $arr = [];
            $ret = ContraTipoModel::getProduto();
            foreach($ret as $r){
                $c = new ProdutoPesquisa();
                $c->codprod = $r['CODPROD'] ;       
                $c->produto = utf8_encode($r['DESCRICAO']) ;
                array_push($arr, $c);
            }
            return $arr;
        }
        public static function getProduto2($codprod){
            $ret = ContraTipoModel::getProduto();
            foreach($ret as $r){
                if($codprod == $r['CODPROD'] )    
                return utf8_encode($r['DESCRICAO']) ;
            }
        }
        public static function getProduto3($id){
            $arr = [];
            $ret = ContraTipoModel::getProduto2($id);
            foreach($ret as $r){
                array_push( $arr , [ $r['CODPROD'] , utf8_encode( $r['DESCRICAO'] ), $r['FRACAOUMIDA'] ]);
            }
            return $arr;
        }
        public static function getFormula($codprod, $metodo){
            return ContraTipoModel::getFormula($codprod, $metodo);
        }
    }
    class ContraTipoControle{
        public $codsubcategoria;
        public $categoria;
        public $codcategoria;
        public $subcategoria;
        public $codprodmaster; 
        public $produto;
        public $codprod;


        public static function getFiltros(){
            $arr = [];
            $ret = ContraTipoModel::getFiltros();
               foreach($ret as $r){
                $c = new ContraTipoControle();
                $c->codcategoria = $r['CODCATEGORIA'] ;       
                $c->categoria = $r['CATEGORIA'] ;
                $c->codsubcategoria = $r['CODSUBCATEGORIA'] ;      
                $c->subcategoria = $r['CATEGORIA'].' '.$r['SUBCATEGORIA'] ;
                  
                array_push($arr, $c);
               }
            return $arr;
        }
        public static function getProdutos($codprod, $metodo){

            $arr = [];
            $ret = ContraTipoModel::getProdutos($codprod, $metodo);
               foreach($ret as $r){
                $c = new ContraTipoControle();
                $c->codprodmaster = $r['CODPRODMASTER'] ;       
                $c->produto = utf8_encode($r['DESCRICAO']) ;
                $c->categoria = utf8_encode($r['CATEGORIA']) ;      
                $c->codcategoria = $r['CODCATEGORIA'] ;
                  
                array_push($arr, $c);
               }
            return $arr;
        }
        public static function getAlteracoes(){
            $arr = [];
            $ret = ContraTipoModel::getAlteracoes();
                foreach($ret as $r){
                    array_push($arr, [$r['ID'], $r['CODPROD1'], $r['CODPROD2'],
                    utf8_encode($r['NOME']), $r['METODO'], $r['DATA']]);
                }
            return $arr;
        }
        
}