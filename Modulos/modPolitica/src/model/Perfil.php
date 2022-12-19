<?php
class Perfil{

    public $codigo, $descricao, $rca, $regiao, $politica;

    function __construct($codigo, $descricao, $rca, $regiao, $politica)
    {
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->rca = $rca;
        $this->regiao = $regiao;
        $this->politica = $politica;
    }

    public function aplicaPolitica(Perfil $perfil, $clientes){
        /*
        1° Listar clientes e perfils para aplicar o novo desconto;
        2° Criar politicas em todos os clientes que não possuem politica;
        3° Aplicar desconto do perfil na lista de clientes;
        4° Deve ser salvo no histórico.
        5° Ao vincular um perfil, todas as politicas devem seguir esse perfil? não!
        */
        
    }

}