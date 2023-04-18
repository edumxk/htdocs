<?php

class ClienteCtd{

    public $razao;
    public $fantasia;
    public $cnpj;
    public $endereco;
    public $cidade;
    public $uf;
    public $cep;
    public $telefone;
    public $email;
    public $nomeRca;

    public function __construct($dados){
        $this->razao = utf8_encode($dados['RAZAO']);
        $this->fantasia = utf8_encode($dados['FANTASIA']);
        $this->cnpj = $dados['CNPJ'];
        $this->endereco = utf8_encode($dados['ENDERECO']);
        $this->cidade = utf8_encode($dados['CIDADE']);
        $this->uf = $dados['UF'];
        $this->cep = $dados['CEP'];
        $this->telefone = $dados['TELEFONE'];
        $this->email = utf8_encode($dados['EMAIL']);
        $this->nomeRca = utf8_encode($dados['NOMERCA']);
    }

}