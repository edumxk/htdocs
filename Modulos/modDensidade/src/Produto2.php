<?php

class Produto2
{
    public $codprodPa;
    public $descricaoPa;
    public $padrãoLaudo;
    public $codprodSa;
    public $descricaoSa;
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
                                $media, $min, $max, $contagem, $valores, 
                                $padrãoLaudo,  $codprodSa, $descricaoSa,
                                $pesoFormula, $litragem)
    {
        $this->codprodPa = $codprodPa;
        $this->descricaoPa = $descricaoPa;
        $this->media = $media;
        $this->min = $min;
        $this->max = $max;
        $this->padrãoLaudo = $padrãoLaudo;
        $this->codprodSa = $codprodSa;
        $this->descricaoSa = $descricaoSa;
        $this->valores = $valores;
        $this->pesoFormula = $pesoFormula;
        $this->litragem = $litragem;
        $this->contagem = $contagem;

    }

    /**
     * @return Produto lista de produtos
     */
    public static function dados($array)
    {
        $ret = [];
        foreach($array as $p){
                $ret[]= new Produto2($p['CODPROD'],$p['DESCRICAO'], null, null, null, null, null,
                utf8_encode($p['VALORPADRAO']), $p['CODPRODSA'], $p['DESCRICAOSA'], floatval($p['PESO_FORMULA']), floatval($p['LITRAGEM']));
            
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
    public static function planilhaMedias($array)
    {
        $ret[] =  [
         'CODPROD_PA'
        , 'DESCRICAO_PA'
        , 'MIN'
        , 'MAX'
        , 'MEDIA'
        , 'DENSIDADE_FORMULA'
        , 'QUANTIDADE'
        , 'PADRAO_LAUDO'
        , 'CODPROD_SA'
        , 'DESCRICAO_SA'
        , 'PESO_FORMULA'
        , 'LITRAGEM'];
        foreach($array as $p){
            $ret[]= [
                 $p->codprodPa
                , $p->descricaoPa
                , number_format($p->min,3,',','.')
                , number_format($p->max,3,',','.')
                , number_format($p->media,3,',','.')
                , number_format($p->pesoFormula/$p->litragem,3,',','.')
                , $p->contagem
                , $p->padrãoLaudo
                , number_format($p->pesoFormula,3,',','.')
                , number_format($p->litragem,1,',','.')
                , $p->codprodSa
                , $p->descricaoSa  
            ];
        }
        return $ret ;
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
    public static function dados2($array, $data1, $data2)
    {   
        $produtos = Model::getDados2($data1, $data2);
        $dados = [];
        $temp = [];

        foreach($produtos as $p){
            foreach($array as $p2){
                if($p['CODPROD'] == $p2['CODPROD']){
                        if(floatval(str_replace(',', '.',$p2['DENSIDADE']))>0)
                            array_push($temp, floatval(str_replace(',', '.',$p2['DENSIDADE'])));
                    }
                }
                if(count($temp)>0)
                
                $dados[] = [ new Produto2(intval($p['CODPROD']), null, array_sum($temp)/ count($temp), min($temp), max($temp), count($temp), $temp, null, null, null, null, null)];
                
                $temp = [];
            }
            
       
        return $dados ;
    }
    public static function mediaDensidade($dados1, $dados2, $data1, $data2){
        $produtos = Produto2::dados($dados1);
        $medias = Produto2::dados2($dados2, $data1, $data2);
        
        foreach($produtos as $p){
            foreach ($medias as $m){
                if($p->codprodPa == $m[0]->codprodPa){

                    $p->media = $m[0]->media ;
                    $p->contagem = $m[0]->contagem;
                    $p->min = $m[0]->min ;
                    $p->max = $m[0]->max ;
                    $p->valores = $m[0]->valores ;

                }
            }

        }
        return $produtos;
    }
}
