<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');


class ProdDiaria{
    public $cod;
    public $linha;
    public $numOp;
    public $qtOp;
    public $codProd;
    public $descricao;
    public $qtProduzida;



    public static function getProdAnalitico($data){
        $data = ProdDiaria::formatador($data);

        $sql = new sqlOra();
        $ret = $sql->select("SELECT a.tipo, c.numop, TO_CHAR(qtproduzida,'9999999.99') qtproduzida, p.codprod, p.descricao,
                case when a.tipo = 'TINTAS' then
                case when c.qtproduzida <= 2700 then 2 else 1 end
                when a.tipo = 'TEXTURAS' then 3 
                when a.tipo = 'MASSAS' then 4
                when a.tipo = 'SOLVENTES' then 5 end cod
            from kokar.pcopc c inner join kkagrupamentosa a on a.codprod = c.codprodmaster
                inner join kokar.pcprodut p on c.codprodmaster = p.codprod
            where c.dtfecha = :dtFecha
            and c.dtcancel is null
            order by cod, numop",
            array(":dtFecha"=>$data)
        );

        $arrRet = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new ProdDiaria();
                $p->cod = $r['COD'];
                $p->linha = $r['TIPO'];
                $p->numOp = $r['NUMOP'];
                $p->codProd = $r['CODPROD'];
                $p->descricao = utf8_encode($r['DESCRICAO']);
                $p->qtProduzida = $r['QTPRODUZIDA'];
                array_push($arrRet, $p);
                // echo json_encode($arrRet);
            }
        }

        return $arrRet;
    }


    public static function getProdResumo($data){
        $data = ProdDiaria::formatador($data);
        $sql = new sqlOra();
        $ret = $sql->select("SELECT tipo, cod, count(numop)qt, TO_CHAR(sum(qtproduzida),'9999999.99') total
                from (
                select a.tipo, c.numop, c.qtproduzida, 
                    case when a.tipo = 'TINTAS' then
                        case when c.qtproduzida <= 2700 then 2 else 1 end
                    when a.tipo = 'TEXTURAS' then 3 
                    when a.tipo = 'MASSAS' then 4
                    when a.tipo = 'SOLVENTES' then 5 end cod
                from kokar.pcopc c inner join kkagrupamentosa a on a.codprod = c.codprodmaster
                where c.dtfecha = :dtFecha
                and c.dtcancel is null
                ) group by tipo, cod
                order by cod",
            array(":dtFecha"=>$data)
        );

        $arrRet = [];
        if(sizeof($ret)>0){
            foreach($ret as $r){
                $p = new ProdDiaria();
                $p->cod = $r['COD'];
                $p->linha = $r['TIPO'];
                $p->qtOp = $r['QT'];
                $p->qtProduzida = $r['TOTAL'];
                array_push($arrRet, $p);
            }
        }

        return $arrRet;
    }


    function formatador($data){
        $d = explode('-', $data);
        $saida = $d[2].'/'.$d[1].'/'.$d[0];
        return $saida;
    }
    
}








?>
