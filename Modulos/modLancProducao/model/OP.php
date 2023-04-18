<?php 

class OP{

    public $codProducao;
    public $codProd;
    public $numOP;
    public $numLote;
    public $qtProduzir;
    public $qtPrevista;
    public $posicao;
    public $dtInicio;
    public $dtFecha;
    public $dtAbertura;
    public $metodo;
    public $codepto;
    public $codsec;
    public $codCategoria;
    public $categoria;
    public $descricao;
    public $status;
    public $custo;
    public $qtAjuste;
    public $custoAjuste;
    public $previsaoAjuste;
    public $rendimentoAjuste;
    public $rendimentoTotal;
    public $custoAjusteTotal;


    public function __construct(){}

    public function novaOPFromArray(array $a)
    {
        if(sizeOf($a)===0)
            return;

        $this->codProducao = $a['CODPRODUCAO'];
        $this->codProd = $a['CODPROD'];
        $this->numOP = $a['NUMOP'];
        $this->numLote = $a['NUMLOTE'];
        $this->qtProduzir = $a['QTPRODUZIR'];
        $this->qtPrevista = $a['QTPREVISTA'];
        $this->posicao = $a['POSICAO'];
        $this->dtInicio = $a['DTINICIO'];
        $this->dtFecha = $a['DTCHAR'];
        $this->dtAbertura = $a['DTABERTURA'];
        $this->metodo = $a['METODO'];
        $this->codepto = $a['CODEPTO'];
        $this->codsec = $a['CODSEC'];
        $this->codCategoria = $a['CODCATEGORIA'];
        $this->categoria = $a['CATEGORIA'];
        $this->descricao = $a['DESCRICAO'];
        $this->status = $a['STATUS'];
        $this->custo = $a['CUSTO'];
        $this->qtAjuste = $a['KGAJUSTE'];
        $this->custoAjuste = $a['CUSTOKGAJUSTE'];
        $this->previsaoAjuste = $a['PREVISAOCUSTO'];
    }

    static function novaOPLista($array):array
    {
        
        $ret = [];
        if(sizeOf($array)===0)
            return [];
        foreach($array as $a){
            $op = new OP();
            $op->codProducao = $a['CODPRODUCAO'];
            $op->rendimentoAjuste = $a['RENDAJUSTE'];
            $op->rendimentoTotal = $a['RENDIMENTO'];
            $op->codProd = $a['CODPRODMASTER'];
            $op->qtAjuste = $a['KGAJUSTE'];
            $op->numOP = $a['NUMOP'];
            $op->numLote = $a['NUMLOTE'];
            $op->qtProduzir = ($a['QTPRODUZIR']);
            if( $op->qtAjuste != 0){
                $op->qtPrevista = ($a['QTPRODUZIR']+$a['KGAJUSTE'])*(1 + $op->rendimentoAjuste);
                if ($op->qtPrevista == 0){
                    $op->qtPrevista = $op->qtProduzir;
                }
            }else{
                $op->qtPrevista = ($a['QTPRODUZIR']*(1 + $op->rendimentoAjuste));
            }
            $op->posicao = $a['POSICAO'];
            $op->dtInicio = $a['DTINICIO'];
            $op->dtFecha = $a['DTFECHA'];
            $op->dtAbertura = $a['DTABERTURA'];
            $op->metodo = $a['METODO'];
            $op->codepto = $a['CODEPTO'];
            $op->codsec = $a['CODSEC'];
            $op->codCategoria = $a['CODCATEGORIA'];
            $op->categoria = $a['CATEGORIA'];
            $op->descricao = $a['DESCRICAO'];
            $op->status = $a['STATUS'];
            $op->custo = $a['CUSTO'];
            $op->custoAjusteTotal = $a['VALORAJUSTE'];
            $op->custoAjuste = (($a['VALORAJUSTE'] + ($op->custo * $op->qtProduzir)) / $op->qtPrevista);
            if( $op->qtAjuste != 0)
                $op->previsaoAjuste = $op->custoAjuste / $op->custo -1;
            else
                $op->previsaoAjuste = 0;
            $ret[] = $op;
        }
        return $ret;
    }

}