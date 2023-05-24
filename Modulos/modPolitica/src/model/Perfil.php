<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modPolitica/src/model/Politica.php');

class Perfil{

    public $codPerfil, $descricao, $rca, $regiao, $politicas;

    function __construct($codPerfil, $descricao, $rca, $regiao, array $politicas)
    {
        $this->codPerfil = $codPerfil;
        $this->descricao = $descricao;
        $this->rca = $rca;
        $this->regiao = $regiao;
        $this->politicas = $politicas;
    }

}