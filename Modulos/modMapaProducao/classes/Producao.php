<?php 

class Producao{
    public $codProducao;
    public $categoria;
    public $tanque;
    public $produtos;
    public $status;
    public $lote;
    public $dtCadastro;
    public $dtAbertura;
    public $hrAbertura;
    public $dtFecha;
    public $hrFecha;
    public $dtPrevisao;
    public $hrPrevisao;
    public $codFun;

    function  __construct($codProducao, $categoria, $tanque, $produtos, $status, $lote, $dtCadastro, $dtAbertura, $hrAbertura, $dtFecha, $hrFecha, $dtPrevisao, $hrPrevisao, $codFun){
        $this->codProducao = $codProducao;
        $this->categoria = $categoria;
        $this->tanque = $tanque;
        $this->produtos = $produtos;
        $this->status = $status;
        $this->lote = $lote;
        $this->dtCadastro = $dtCadastro;
        $this->dtAbertura = $dtAbertura;
        $this->hrAbertura = $hrAbertura;
        $this->dtFecha = $dtFecha;
        $this->hrFecha = $hrFecha;
        $this->dtPrevisao = $dtPrevisao;
        $this->hrPrevisao = $hrPrevisao;
        $this->codFun = $codFun;
    }

}