<?php

class Historico{

    public $codhist, $codgrupo, $descricao, $descant, $descnovo, $tabela;

    function __construct($codhist, $codgrupo, $descricao, $descant, $descnovo, $tabela)
    {
        $this->codhist = $codhist;
        $this->codgrupo = $codgrupo;
        $this->descricao = utf8_encode($descricao);
        $this->descant = $descant;
        $this->descnovo = $descnovo;
        $this->tabela = round($tabela,2);
    }

    
    
}