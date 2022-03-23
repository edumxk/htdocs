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
                if($r['METODOBASE']!= null)
                echo "<option value='".strval($r['METODOBASE'])."'>Metodo ".strval($r['METODOBASE'])."</option><br>";
            }
            echo $ret;
         }
         if($_POST['action']=='metodo2'){
            $base = $_POST['dataset']['base'];
            $novo = $_POST['dataset']['novo'];
            $arr = (Metodo::tabela($base, $novo));
            $cont = 0;
            foreach($arr as $r){
                if($r['METODONOVO']!= null){
                echo "<option disabled value='".strval($r['METODONOVO'])."'>Metodo ".strval($r['METODONOVO'])."</option><br>";
                $cont++;
                }
            }
            for($i=1; $i<=($cont+1); $i++){
                echo "<option value='".strval($i+$cont)."'>Metodo ".strval($i+$cont)."</option><br>"; 
            }
            echo $ret;
         }
    }

?>