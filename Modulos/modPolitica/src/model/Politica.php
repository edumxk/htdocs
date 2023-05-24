<?php
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
            $arr[] = new Politica($d['CODPERFIL'], $d['CODGRUPO'], $d['DESCONTO'], utf8_encode($d['DESCRICAO']));
        }
        else return 'erro no addPoliticas';
        return $arr;
    }
    
}