<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/aTec.php');
    //require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/controle/ratControl.php');


    if(isset($_POST['action'])){

        if($_POST['action']=='setATec'){
            ATecControle::setATec($_POST['query']);
        }

    }

    class ATecControle{

        function setATec($key){
           
            
            $arr = Rat::newATec($key['numrat'], $key['data'], $key['parecer'], $key['testes'], $key['procedente'],
                        $key['codusur'], $key['final']);


            Rat::finalizarATec($key['numrat']);
            echo json_encode($arr);

        }
    }



?>