<?php 


class Produtos{
    public $codProducao;
    public $codprod;
    public $op;
    public $qt;
    public $dtInclusao;
    public $hrInclusao;

    function  __construct($codProducao, $codprod, $op, $qt, $dtInclusao, $hrInclusao){
        $this->codProducao = $codProducao;
        $this->codprod = $codprod;
        $this->op = $op;
        $this->qt = $qt;
        $this->dtInclusao = $dtInclusao;
        $this->hrInclusao = $hrInclusao;
    }
}