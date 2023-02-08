<?php 

class Ajuste{

    public $numOP;
    public $codProd;
    public $descricao;
    public $precoUnitario;
    public $qt;
    public $custo;
    public $operacao;
    

    public function __construct(){}

    public function novoAjusteFromArray(array $a)
    {
        if(sizeOf($a)===0)
            return;

        $this->codProd = $a['CODPROD'];
        $this->numOP = $a['NUMOP'];
        $this->descricao = $a['DESCRICAO'];
        $this->custo = $a['VALOR'];
        $this->qt = $a['QT'];
        $this->operacao = $a['CODOPER'];
        $this->precoUnitario = $a['PUNIT'];
    }

    static function novoAjusteLista($array):array
    {
        
        $ret = [];
        if(sizeOf($array)===0)
            return [];
        foreach($array as $a){
            $aj = new Ajuste();
            $aj->codProd = $a['CODPROD'];
            $aj->numOP = $a['NUMOP'];
            $aj->descricao = $a['DESCRICAO'];
            $aj->custo = $a['VALOR'];
            $aj->qt = $a['QT'];
            $aj->operacao = $a['CODOPER'];
            $aj->precoUnitario = $a['PUNIT'];
            $ret[] = $aj;
        }
        return $ret;
    }

}