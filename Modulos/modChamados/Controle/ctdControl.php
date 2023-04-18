<?php
//definir timezone pt-br

require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modChamados/dao/daoCtd.php');

if(isset($_POST['action'])){
    if($_POST['action']=='addDespesa'){
        if($_POST['query']['valorDespesa'] <= 0 || $_POST['query']['valorDespesa'] >= 10000){
           return 'erro com valor';
        }
        echo json_encode(Ctd::addDespesa($_POST['query']));
    }
    if($_POST['action']=='getDespesas'){
        echo json_encode(Ctd::getDespesas($_POST['query']));
    }
    if($_POST['action']=='getOpcoesDespesas'){
        echo json_encode(Ctd::getTiposDespesa());
    }
    if($_POST['action']=='excluirDespesa'){
        echo json_encode(Ctd::excluirDespesa($_POST['query']));
    }
    if($_POST['action']=='getNfe'){
        $nfe = Ctd::getNfe($_POST['query']);
        if(count($nfe) > 0){
            $nfe[0]['DESTINO'] = utf8_encode($nfe[0]['DESTINO']);
            $nfe[0]['VEICULO'] = utf8_encode($nfe[0]['VEICULO']);
            $nfe[0]['MOTORISTA'] = utf8_encode($nfe[0]['MOTORISTA']);
            $nfe[0]['PLACA'] = utf8_encode($nfe[0]['PLACA']);
            echo json_encode($nfe[0]);
        }
        else    
            echo json_encode('erro');
    }
    if($_POST['action']=='getNfeDev'){
        $nfe = Ctd::getNfeDev($_POST['query']);
        if(count($nfe) > 0){
            $nfe[0]['OBS'] = utf8_encode($nfe[0]['OBS']);
            echo json_encode($nfe[0]);
        }
        else    
            echo json_encode('erro');
    }
    //implementar confirmar
    if($_POST['action']=='confirmar'){
        echo json_encode(Ctd::confirmar($_POST['query']));
    }
    
    if($_POST['action']=='getDadosCTD'){
        echo json_encode(Ctd::getDadosCTD($_POST['query']));
    }

    //Nova CTD a partir do zero.
    if($_POST['action']=='novaCtd'){
        echo json_encode(Ctd::novaCtd($_POST['query']));
    }
}

class ctdControl{
    public static function getNumctd(){
        return Ctd::getNumctd();
    }
}