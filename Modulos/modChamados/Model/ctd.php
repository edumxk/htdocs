<?php

require_once 'clienteCtd.php';

class Ctd{

    public $numCtd;
    public $codUsur;
    public $codCli;
    public $dtCadastro;
    public $dtFechamento;
    public $status;
    public $motivo;
    public $motivoObs;
    public $numNota;
    public $numNotaDev;
    public $numRat;
    public $acaoCorretiva;
    public $dtExclusao;


    //dados para construção de relatórios de ctd
    public ClienteCtd $cliente;

    //construtor
    public function __construct($dados){

        $this->numCtd = $dados['NUMCTD'];
        $this->codCli = $dados['CODCLI'];
        $this->dtCadastro = $dados['DTCADASTRO'];
        $this->dtFechamento = $dados['DTFECHAMENTO'];
        $this->status = $dados['STATUS'];
        $this->motivo = $dados['MOTIVO'];
        $this->motivoObs = utf8_encode($dados['MOTIVO_OBS']);
        $this->numNota = $dados['NUMNOTA'];
        $this->numNotaDev = $dados['NUMNOTADEV'];
        $this->numRat = $dados['NUMRAT'];
        $this->acaoCorretiva = utf8_encode($dados['ACAO_CORRETIVA']);
        $this->dtExclusao = $dados['DTEXCLUSAO'];

        //dados para construção de relatórios de ctd
        $this->cliente = new ClienteCtd($dados);
    }
}