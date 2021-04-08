<?php

class Produto{
    //Tabela ratI
    public $numrat;
    public $codprod;
    public $numlote;
    public $qt;
    public $pVenda;

    //Inner join de outras tabelas
    public $produto;
    public $dtFabricacao;
    public $dtValidade;
    public $total;


    public function Total(){
        $this->total = $this->pVenda * $this->qt;
    }

    public function getTotal(){
        return  $this->pVenda * $this->qt;
    }


    
}


?>