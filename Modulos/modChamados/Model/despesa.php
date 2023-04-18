<?php

class Despesa{
    public $codTipo;
    public $tipo;
    public $descricao;
    public $valor;
    public $valorCalc;

    public function __construct($dados){
        if(!isset($dados['CODTIPO'])){
            $dados['CODTIPO'] = null;
        }
        if(!isset($dados['TIPO'])){
            $dados['TIPO'] = '';
        }
        if(!isset($dados['DESCRICAO'])){
            $dados['DESCRICAO'] = '';
        }
        if(!isset($dados['VALOR'])){
            $dados['VALOR'] = null;
        }

        $this->codTipo = $dados['CODTIPO'];
        $this->tipo = utf8_encode($dados['TIPO']);
        $this->descricao = utf8_encode($dados['DESCRICAO']);
        $this->valor = 'R$ '.number_format($dados['VALOR'], 2, ',', '.') ;
        $this->valorCalc = $dados['VALOR'] ;
    }
}