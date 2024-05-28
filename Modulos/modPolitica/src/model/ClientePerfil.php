<?php
//import formatador
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');

class ClientePerfil{

    public $codcli, $razao, $fantasia, $cidadeUf, $dias, $status, $praca, $atividade;

    function __construct($codcli, $razao, $fantasia, $cidadeUf, $dias = null, $status = null, $praca = null, $atividade = null){
        $this->codcli = $codcli;
        $this->razao = $razao;
        $this->fantasia = $fantasia;
        $this->cidadeUf = $cidadeUf;
        $this->praca = $praca;
        $this->dias = $dias;
        $this->status = $status;
        $this->atividade = $atividade;
    }

    public static function addClientes($dados){
        $arr = [];
       
        if(is_array($dados) && count($dados)>0){
            foreach($dados as $d){
                if( !isset($d['DIAS']) )
                    $d['DIAS'] = null;
                if( !isset($d['STATUS']) )
                    $d['STATUS'] = null;
                if( !isset($d['PRACA']) )
                    $d['PRACA'] = null;
                if( !isset($d['ATIVIDADE']) )
                    $d['ATIVIDADE'] = 6;
                $arr[] = new ClientePerfil($d['CODCLI'], Formatador::br_decode($d['CLIENTE']), Formatador::br_decode($d['FANTASIA']),
                                             Formatador::br_decode($d['CIDADEUF']), $d['DIAS'], Formatador::br_decode($d['STATUS']), 
                                             Formatador::br_decode($d['PRACA']), $d['ATIVIDADE']);
                if(count($arr)>2000) 
                    return $arr;
            }
        }else return 'erro no addClientes';
        
        return $arr;
    }

}