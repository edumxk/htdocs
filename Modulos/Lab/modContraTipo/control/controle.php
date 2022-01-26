<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/Lab/modContraTipo/model/modelo.php');
    if(isset($_POST['action'])){
        if($_POST['action']=='getFiltros'){
            return ContraTipoControle::getFiltros(); 
        }        
    }
        

    class ContraTipoControle{
        public $codsubcategoria;
        public $categoria;
        public $codcategoria;
        public $subcategoria;
        public $codprodmaster; 
        public $produto;


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
    }