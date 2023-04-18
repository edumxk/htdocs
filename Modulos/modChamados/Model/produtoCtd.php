<?php
class ProdutoCtd{

    public $numctd;
    public $numnota;
    public $codprod;
    public $numlote;
    public $qt;
    public $punit;
    public $ipi;
    public $st;
    public $numregiao;
    private $frete;
    private $desconto;
    private $pesoBruto;
    

    //construtor
    public function __construct($dados){
        $this->numctd = $dados['NUMCTD'];
        $this->numnota = $dados['NUMNOTA'];
        $this->codprod = $dados['CODPROD'];
        $this->numlote = $dados['NUMLOTE'];
        $this->qt = $dados['QT'];
        $this->punit = $dados['PUNIT'];
        $this->ipi = $dados['IPI'];
        $this->st = $dados['ST'];
        $this->frete = $dados['FRETE'];
        $this->desconto = $dados['DESCONTO'];
        $this->numregiao = $dados['NUMREGIAO'];
        $this->pesoBruto = $dados['PESOBRUTO'];
    }

    public function getFrete(){
        return $this->pesoBruto * ( $this->frete * (100 - $this->desconto) / 100 );
    }

} 