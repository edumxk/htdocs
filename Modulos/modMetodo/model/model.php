<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Metodo{
    public $codprodbase;
    public $codprodnovo;
    public $metodo;



    public static function tabela($codprodbase, $codprodnovo){
        $sql = new sqlOra();
        try{
            $teste = $sql->select("SELECT t1.metodo metodobase, t2.metodo metodonovo FROM 
                                    (select distinct c.codprodmaster, p.descricao, p.codepto, p.codsec, p.codcategoria, c.metodo
                                    from kokar.pcprodut p
                                    inner join kokar.pccomposicao c on c.codprodmaster = p.codprod
                                    where c.codprodmaster = $codprodbase)t1
                                    left join
                                    (select distinct c.codprodmaster, p.descricao, p.codepto, p.codsec, p.codcategoria, c.metodo
                                    from kokar.pcprodut p
                                    inner join kokar.pccomposicao c on c.codprodmaster = p.codprod
                                    where c.codprodmaster = $codprodnovo)t2 on t1.metodo = t2.metodo
                                    order by t1.metodo");
            return $teste;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
            return "FAIL";
  }
    /*DUPLICAR METODO PARA OUTRO PRODUTO - tratar possiveis erros no codigo fonte!!! IMPORTANTE */
    public static function duplicar($codprodbase, $codprodnovo, $metodobase, $metodonovo){
    $sql = new sqlOra();
    try{
        $ret = $sql->insert("BEGIN
                                FOR T IN (
                                SELECT :CODPROD2 CODPRODMASTER, CODFILIAL, CODPROD, METODO, QT, NUMSEQ, PERCPERDA, DTCADASTRO, CODFUNCCADASTRO, DTULTALTER, CODFUNCALTER, 
                                LOTEPRODUCAOMASTER, CODFUNCULTALTER, FRACAOUMIDA, FRACAOSEPARACAO, VALIDADE, UNESTRUTURA, ACEITAREQACIMAPREV, NUMETAPA, 
                                NUMDECIMAIS, NUMDECIMAL, PERCFORMULACAOTOTAL, REPROCESSO, OBSMETODO, MODOPREPARO, METODOPADRAO 
                                FROM KOKAR.PCCOMPOSICAO WHERE CODPRODMASTER = :CODPROD1 AND METODO = :METODO1
                                ) LOOP  
                            
                                INSERT INTO KOKAR.PCCOMPOSICAO 
                                (CODPRODMASTER, CODFILIAL, CODPROD, METODO, QT, NUMSEQ, PERCPERDA, DTCADASTRO, CODFUNCCADASTRO, DTULTALTER, CODFUNCALTER, 
                                LOTEPRODUCAOMASTER, CODFUNCULTALTER, FRACAOUMIDA, FRACAOSEPARACAO, VALIDADE, UNESTRUTURA, ACEITAREQACIMAPREV, NUMETAPA, 
                                NUMDECIMAIS, NUMDECIMAL, PERCFORMULACAOTOTAL, REPROCESSO, OBSMETODO, MODOPREPARO, METODOPADRAO)
                                    VALUES
                                (T.CODPRODMASTER, T.CODFILIAL, T.CODPROD, :METODO2, T.QT, T.NUMSEQ, T.PERCPERDA, T.DTCADASTRO, T.CODFUNCCADASTRO, T.DTULTALTER, T.CODFUNCALTER,
                                T.LOTEPRODUCAOMASTER, T.CODFUNCULTALTER, T.FRACAOUMIDA, T.FRACAOSEPARACAO, T.VALIDADE, T.UNESTRUTURA, T.ACEITAREQACIMAPREV, T.NUMETAPA,
                                T.NUMDECIMAIS, T.NUMDECIMAL, T.PERCFORMULACAOTOTAL, T.REPROCESSO, T.OBSMETODO, T.MODOPREPARO, T.METODOPADRAO);
                            
                            END LOOP;
                            END;", [":CODPROD1"=>$codprodbase, ":CODPROD2"=>$codprodnovo, ":METODO1"=>$metodobase, ":METODO2"=>$metodonovo]);
        return $ret;
    }catch(Exception $e){
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
        return "FAIL";
  }

  public static function getProdutos(){
    $sql = new sqlOra();
    try{
        $ret = $sql->select("SELECT CODPROD, DESCRICAO FROM KOKAR.PCPRODUT WHERE CODEPTO IN (10000, 10001)"); 
    }catch(Exception $e){
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
    return ($ret);
  }
}
