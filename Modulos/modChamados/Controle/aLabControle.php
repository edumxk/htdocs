<?php 
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/aLab.php');


     if(isset($_POST['action'])){

        if($_POST['action']=='getPatologias'){
            ALabControle::getPatologias();
        }
        if($_POST['action']=='newPatologia'){
            ALabControle::newPatologia($_POST['query']);
        }
        if($_POST['action']=='setALab'){
            ALabControle::setALab($_POST['query']);
        }


    }

    class ALabControle{


        function getPatologias(){
            $arr = Rat::getPatologias();
            //var_dump($arr);
           for($i = 0; $i<sizeof($arr); $i++){
                $arr[$i]['PATOLOGIA'] = utf8_encode($arr[$i]['PATOLOGIA']);
            }
            //var_dump($arr);
            echo json_encode($arr);
        }

        function newPatologia($key){
            $newPat = $key;
            $arr = Rat::getPatologias();
            foreach($arr as $a){
                if($a['PATOLOGIA']===strtoupper(utf8_decode($newPat))){
                    echo "existe";
                    exit();
                }            }
            echo Rat::newPatologia(strtoupper(utf8_decode($newPat)));
        }

        function setALab($key){
            
            $arr = Rat::newALab($key['numrat'], $key['data'], utf8_decode($key['parecer']), $key['patologia'], $key['procedente'],
                        $key['codusur'], $key['final']);


            Rat::finalizarALab($key['numrat']);

            
            echo $arr;

        }
        


    }



?>