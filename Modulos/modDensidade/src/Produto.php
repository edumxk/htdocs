<?php

class Produto
{
    public $codprodPa;
    public $descricaoPa;
    public $densidadeLaudo;
    public $idLaudo;
    public $lote;
    public $data;
    public $metodoLaudo;
    public $padrãoLaudo;
    public $codprodSa;
    public $descricaoSa;
    public $qtProduzida;
    public $pesoFormula;
    public $litragem;
//dados Calculados
    public $media;
    public $contagem;
    public $min;
    public $max;
    public $valores;

    /**
     * @param $codprodPa
     * @param $descricaoPa
     * @param $densidadeLaudo
     * @param $lote
     * @param $metodoLaudo
     * @param $padrãoLaudo
     * @param $codprodSa
     * @param $descricaoSa
     * @param $qtProduzida
     * @param $pesoFormula
     * @param $litragem
     */
    public function __construct($codprodPa, $descricaoPa,
                                $densidadeLaudo, $lote, $metodoLaudo,
                                $padrãoLaudo, $codprodSa, $descricaoSa,
                                $qtProduzida, $pesoFormula, $litragem, $idLaudo, $data)
    {
        $this->codprodPa = $codprodPa;
        $this->descricaoPa = $descricaoPa;
        $this->densidadeLaudo = $densidadeLaudo;
        $this->lote = $lote;
        $this->metodoLaudo = $metodoLaudo;
        $this->padrãoLaudo = $padrãoLaudo;
        $this->codprodSa = $codprodSa;
        $this->descricaoSa = $descricaoSa;
        $this->qtProduzida = $qtProduzida;
        $this->pesoFormula = $pesoFormula;
        $this->litragem = $litragem;
        $this->data = $data;
        $this->idLaudo = $idLaudo;
    }

    /**
     * @return Produto lista de produtos
     */
    public static function dados($array)
    {
        $ret = [];
        foreach($array as $p){
                if(floatval(str_replace(',', '.',$p['DENSIDADE']))>0)
                $ret[]= new Produto($p['CODPROD'],$p['DESCRICAO'], floatval(str_replace(',', '.',$p['DENSIDADE'])), $p['LOTE'], $p['DESCRICAOPADRAO'],
                utf8_encode($p['VALORPADRAO']), $p['CODPRODSA'], $p['DESCRICAOSA'], $p['QT_PRODUZIDA'], floatval($p['PESO_FORMULA']), floatval($p['LITRAGEM']),
            $p['IDLAUDO'], $p['DATA']);
            
        }
        return $ret ;
    }

    public static function planilha($array)
    {
        $ret[] =  [
        'ID_LAUDO' 
        , 'DATA'
        , 'LOTE'
        , 'CODPROD_PA'
        , 'DESCRICAO_PA'
        , 'DENSIDADE'
        , 'METODO_LAUDO'
        , 'PADRAO_LAUDO'
        , 'CODPROD_SA'
        , 'DESCRICAO_SA'
        , 'QT_PRODUZIDA'
        , 'PESO_FORMULA'
        , 'LITRAGEM'];
        foreach($array as $p){
            if(floatval(str_replace(',', '.',$p['DENSIDADE']))>0)
            $ret[]= [
                'ID_LAUDO' => $p['IDLAUDO']
                , 'DATA' => $p['DATA']
                , 'LOTE' => $p['LOTE']
                , 'CODPROD_PA' => $p['CODPROD']
                , 'DESCRICAO_PA' => $p['DESCRICAO']
                , 'DENSIDADE' => number_format(floatval(str_replace(',', '.',$p['DENSIDADE'])),3,',','.')
                , 'METODO_LAUDO' => $p['DESCRICAOPADRAO']
                , 'PADRAO_LAUDO' => utf8_encode($p['VALORPADRAO'])
                , 'CODPROD_SA' => $p['CODPRODSA']
                , 'DESCRICAO_SA' => $p['DESCRICAOSA']
                , 'QT_PRODUZIDA' => $p['QT_PRODUZIDA']
                , 'PESO_FORMULA' =>  number_format(floatval($p['PESO_FORMULA']),3,',','.')
                , 'LITRAGEM' => number_format(floatval($p['LITRAGEM']),3,',','.')
            ];
        }
        return $ret ;
    }
    function media($array)
    {
        foreach($array as $a){
            echo $a;
        }
    }
    public static function medias($array)
    {
        $ret[] =  [
        'ID_LAUDO' 
        , 'DATA'
        , 'LOTE'
        , 'CODPROD_PA'
        , 'DESCRICAO_PA'
        , 'DENSIDADE'
        , 'METODO_LAUDO'
        , 'PADRAO_LAUDO'
        , 'CODPROD_SA'
        , 'DESCRICAO_SA'
        , 'QT_PRODUZIDA'
        , 'PESO_FORMULA'
        , 'LITRAGEM'];
        foreach($array as $p){
            if(floatval(str_replace(',', '.',$p['DENSIDADE']))>0)
            $ret[]= [
                'ID_LAUDO' => $p['IDLAUDO']
                , 'DATA' => $p['DATA']
                , 'LOTE' => $p['LOTE']
                , 'CODPROD_PA' => $p['CODPROD']
                , 'DESCRICAO_PA' => $p['DESCRICAO']
                , 'DENSIDADE' => number_format(floatval(str_replace(',', '.',$p['DENSIDADE'])),3,',','.')
                , 'METODO_LAUDO' => $p['DESCRICAOPADRAO']
                , 'PADRAO_LAUDO' => utf8_encode($p['VALORPADRAO'])
                , 'CODPROD_SA' => $p['CODPRODSA']
                , 'DESCRICAO_SA' => $p['DESCRICAOSA']
                , 'QT_PRODUZIDA' => $p['QT_PRODUZIDA']
                , 'PESO_FORMULA' =>  number_format(floatval($p['PESO_FORMULA']),3,',','.')
                , 'LITRAGEM' => number_format(floatval($p['LITRAGEM']),3,',','.')
            ];
        }

        $medias = array_map('media', $ret);

        return $medias ;

    }
    public static function dados2($array)
    {   
        $produtos = Model::getDados2();
        $dados = [];
        $temp = [];
        $media = [];

        foreach($produtos as $p){
            foreach($array as $p2){
                if($p['CODPROD'] == $p2['CODPROD']){
                        if(floatval(str_replace(',', '.',$p2['DENSIDADE']))>0)
                            array_push($temp, floatval(str_replace(',', '.',$p2['DENSIDADE'])));
                    }
                }
                if(count($temp)>0)
                $dados[] = [$p['CODPROD'] => ['MEDIA' => array_sum($temp)/ count($temp), 'CONTAGEM' => count($temp), 'MIN' => min($temp), 'MAX'=> max($temp), 'VALORES' => $temp]];
                
                $temp = [];
            }
            
            /*foreach($dados as $d){
                foreach($d as $b)
                    if(count($b)>0)
                        $media[] = [key($d) => [array_sum($b) / count($b), count($b)]];
                    else
                        $media[] = [key($d) => [$b, count($b)]];
            }*/
       
        return $dados ;
    }
}
