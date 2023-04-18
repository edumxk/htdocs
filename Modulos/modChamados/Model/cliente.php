<?php
class Cliente{
    public $codCli;
    public $cliente;

    public function __construct($codcli, $cliente){
        $this->codCli = $codcli;
        $this->cliente = $cliente;
    }
}