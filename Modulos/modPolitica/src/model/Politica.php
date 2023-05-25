<?php

//require formatador
require_once ($_SERVER["DOCUMENT_ROOT"] . './Controle/Formatador.php');

class Politica{
public $codPerfil;
public $desconto;
public $codGrupo;
public $descricao;

    function __construct($codPerfil, $codGrupo, $desconto, $descricao){
        $this->codPerfil = $codPerfil;
        $this->codGrupo = $codGrupo;
        $this->desconto = $desconto;
        $this->descricao = $descricao;

    }

    public static function addPoliticas($dados){
        $arr = [];
        if(count($dados)==0){
            return $arr;
        }
        if(is_array($dados) && count($dados)>0)
        foreach($dados as $d){
            if(!isset($d['DESCONTO']) && isset($d['PERCDESC']))
                $d['DESCONTO'] = $d['PERCDESC'];
            $arr[] = new Politica($d['CODPERFIL'], $d['CODGRUPO'], $d['DESCONTO'], Formatador::br_decode($d['DESCRICAO']));
        }
        else return 'erro no addPoliticas';
        return $arr;
    }
    
}