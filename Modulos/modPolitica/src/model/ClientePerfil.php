<?php
//import formatador
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');

class ClientePerfil{

    public $codcli, $razao, $fantasia, $cidadeUf;

    function __construct($codcli, $razao, $fantasia, $cidadeUf){
        $this->codcli = $codcli;
        $this->razao = $razao;
        $this->fantasia = $fantasia;
        $this->cidadeUf = $cidadeUf;
    }

    public static function addClientes($dados){
        $arr = [];
       
        if(is_array($dados) && count($dados)>0){
            foreach($dados as $d){
                $arr[] = new ClientePerfil($d['CODCLI'], Formatador::br_decode($d['CLIENTE']), Formatador::br_decode($d['FANTASIA']), Formatador::br_decode($d['CIDADEUF']));
                if(count($arr)>50) 
                    return $arr;
            }
        }else return 'erro no addClientes';
        
        return $arr;
    }

}