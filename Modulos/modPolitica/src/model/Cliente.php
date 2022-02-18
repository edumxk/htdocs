<?php
class Cliente{
    private $codcli;
    private $cliente;
    private $cidade;
    private $uf;
    private $rca;
    private $dias;
    private $total;
    private $status;
    private $politica;
    private $numregiao;
    private $ultcompra;

    function __construct($codcli, $cliente, $cidade, $uf,
    $rca, $dias, $total, $status, $politica, $numregiao, $ultcompra)
    {
        $this->codcli = $codcli;
        $this->cliente = $cliente;
        $this->cidade = $cidade;
        $this->uf = $uf;
        $this->rca = $rca;
        $this->dias = $dias;
        $this->total = $total;
        $this->status = $status;
        $this->politica = $politica;
        $this->numregiao = $numregiao;
        $this->ultcompra = $ultcompra;
    }
    function getCodcli(){
        return $this->codcli;
    }
    function getCliente(){
        return $this->cliente;
    }
    function getCidade(){
        return $this->cidade;
    }
    function getUf(){
        return $this->uf;
    }
    function getRca(){
        return $this->rca;
    }
    function getDias(){
        return $this->dias;
    }
    function getTotal(){
        return $this->total;
    }
    function getStatus(){
        return $this->status;
    }
    function getPolitica(){
        return $this->politica;
    }
    function getNumregiao(){
        return $this->numregiao;
    }
    function getUltcompra(){
        return $this->ultcompra;
    }
    function setPolitica($cod){
        if($cod<0 || $cod>3){
            return 'numero inválido, preencher entre 0 e 3';
        }
        $this->politica = $cod;
        return 'ok';
    }

}