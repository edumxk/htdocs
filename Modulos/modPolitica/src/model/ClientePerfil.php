<?php
//import formatador
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');

class ClientePerfil{

    public $codcli, $razao, $fantasia, $cidadeUf, $dias, $status;

    function __construct($codcli, $razao, $fantasia, $cidadeUf, $dias = null, $status = null){
        $this->codcli = $codcli;
        $this->razao = $razao;
        $this->fantasia = $fantasia;
        $this->cidadeUf = $cidadeUf;
        $this->dias = $dias;
        $this->status = $status;
    }

    public static function addClientes($dados){
        $arr = [];
       
        if(is_array($dados) && count($dados)>0){
            foreach($dados as $d){
                if(!isset($d['DIAS'])){
                    $d['DIAS'] = null;
                    $d['STATUS'] = null;
                }
                $arr[] = new ClientePerfil($d['CODCLI'], Formatador::br_decode($d['CLIENTE']), Formatador::br_decode($d['FANTASIA']), Formatador::br_decode($d['CIDADEUF']), $d['DIAS'], $d['STATUS']);
                if(count($arr)>2000) 
                    return $arr;
            }
        }else return 'erro no addClientes';
        
        return $arr;
    }

}