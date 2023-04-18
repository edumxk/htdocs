<?php

class CtdDados{
    public $nfe;
    public $dataemissao;
    public $destino;
    public $veiculo;
    public $motorista;
    public $placa;
    public $numcar;

    public $nfedev;
    public $dataemissaodev;
    public $dataentradadev;
    public $vlst;
    public $vlipi;
    public $vltotal;
    public $obs;

    public $acaocorretiva;


    //constructor
    public function __construct($dados){
        if(!isset($dados['NUMNOTA'])){
            $dados['NUMNOTA'] = null;
        }
        if(!isset($dados['DTSAIDA'])){
            $dados['DTSAIDA'] = '';
        }
        if(!isset($dados['DESTINO'])){
            $dados['DESTINO'] = '';
        }
        if(!isset($dados['VEICULO'])){
            $dados['VEICULO'] = '';
        }
        if(!isset($dados['MOTORISTA'])){
            $dados['MOTORISTA'] = '';
        }
        if(!isset($dados['PLACA'])){
            $dados['PLACA'] = '';
        }
        if(!isset($dados['NUMCAR'])){
            $dados['NUMCAR'] = '';
        }
        if(!isset($dados['NUMNOTADEV'])){
            $dados['NUMNOTADEV'] = null;
        }
        if(!isset($dados['DTEMISSAODEV'])){
            $dados['DTEMISSAODEV'] = '';
        }
        if(!isset($dados['DTENT'])){
            $dados['DTENT'] = '';
        }
        if(!isset($dados['VLST'])){
            $dados['VLST'] = null;
        }
        if(!isset($dados['VLIPI'])){
            $dados['VLIPI'] = null;
        }
        if(!isset($dados['VLTOTAL'])){
            $dados['VLTOTAL'] = null;
        }
        if(!isset($dados['OBS'])){
            $dados['OBS'] = '';
        }
        if(!isset($dados['ACAOCORRETIVA'])){
            $dados['ACAOCORRETIVA'] = '';
        }
        $this->nfe = $dados['NUMNOTA'];
        $this->dataemissao = $dados['DTSAIDA'];
        $this->destino = utf8_encode($dados['DESTINO']);
        $this->veiculo = utf8_encode($dados['VEICULO']);
        $this->motorista = utf8_encode($dados['MOTORISTA']);
        $this->placa = utf8_encode($dados['PLACA']);
        $this->numcar = utf8_encode($dados['NUMCAR']);

        $this->nfedev = $dados['NUMNOTADEV'];
        $this->dataemissaodev = $dados['DTEMISSAODEV'];
        $this->dataentradadev = $dados['DTENT'];
        $this->vlst = $dados['VLST'];
        $this->vlipi = $dados['VLIPI'];
        $this->vltotal = $dados['VLTOTAL'];
        $this->obs = utf8_encode($dados['OBS']);

        $this->acaocorretiva = mb_strtoupper($dados['ACAOCORRETIVA']);


        //numnota, codfornec codcli, obs, e.dtent, e.dtemissao, e.vltotal, e.vlst, e.vlipi
    }



}