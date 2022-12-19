<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/lab/modMetodo/model/model.php');

    if(isset($_POST['action'])){
        if($_POST['action']=='duplicar'){
           $base = $_POST['dataset']['base'];
           $novo = $_POST['dataset']['novo'];
           $metodobase = $_POST['dataset']['metodobase'];
           $metodonovo = $_POST['dataset']['metodonovo'];
           echo Metodo::duplicar($base, $novo, $metodobase, $metodonovo);
        }
        if($_POST['action']=='metodo1'){
            $base = $_POST['dataset']['base'];
            $novo = $_POST['dataset']['novo'];
            $arr = (Metodo::tabela($base, $novo));
            $ret = '';
            foreach($arr as $r){
                if($r['CODPROD']==$base){
                    $ret = $ret . "<option value='".strval($r['METODO'])."'>Metodo ".strval($r['METODO'])."</option><br>";
                }
            }
            echo $ret;
         }
         if($_POST['action']=='metodo2'){
            $base = $_POST['dataset']['base'];
            $novo = $_POST['dataset']['novo'];
            $arr = (Metodo::tabela($base, $novo));
            $cont = 0;
            $cont2 = 1;
            $ret='';
            var_dump (($arr));
            foreach($arr as $r){
                
            if($r['CODPROD']==$novo){
                    $ret = $ret . "<option disabled value='".strval($r['METODO'])."'>Metodo ".strval($r['METODO'])."</option><br>";
                    $cont2 ++;
                }
            }
            $ret = $ret . "<option value='".strval($cont2)."'>Metodo ".strval($cont2)."</option><br>";
            echo $ret;
         }
    }

?>