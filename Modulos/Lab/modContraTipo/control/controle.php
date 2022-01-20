<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/Lab/modContraTipo/model/modelo.php');
    

    if(isset($_POST['action'])){
        if($_POST['action']=='getFiltros'){
            echo (ContraTipoControle::getFiltros()); 
        }        
    }

    class ContraTipoControle{
        public $codsubcategoria;
        public $categoria;
        public $codcategoria;
        public $subcategoria;

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
    }