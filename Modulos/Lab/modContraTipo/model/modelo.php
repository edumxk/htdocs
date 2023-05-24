<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

class ContraTipoModel{

    public static function getFiltros()
    {
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT DISTINCT  P.CODCATEGORIA , CATEGORIA, p.codsubcategoria, sg.subcategoria
            FROM kokar.PCPRODUT P 
            INNER JOIN kokar.PCCATEGORIA G 
            ON G.CODSEC = P.CODSEC 
            AND P.CODCATEGORIA = G.CODCATEGORIA
            inner join kokar.pcsubcategoria sg on sg.codsubcategoria= p.codsubcategoria
            and sg.codcategoria = p.codcategoria
            INNER JOIN kokar.PCCOMPOSICAO C ON C.CODPRODMASTER = P.CODPROD
            AND METODO = 1
            WHERE P.CODSEC IN (10013, 10014) AND CODEPTO IN (10001, 10002)
            ORDER BY CATEGORIA, subcategoria");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getProdutos($codprod, $metodo)
    {
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT DISTINCT C.CODPRODMASTER, C.CODPROD, C.METODO, P.DESCRICAO , CA.CATEGORIA, CA.CODCATEGORIA
            FROM kokar.PCCOMPOSICAO C 
            INNER JOIN kokar.PCPRODUT P ON P.CODPROD = C.CODPRODMASTER
            INNER JOIN kokar.PCCATEGORIA CA ON CA.CODSEC = P.CODSEC AND CA.CODCATEGORIA = P.CODCATEGORIA
            WHERE C.METODO = $metodo AND C.CODPROD = $codprod
            ORDER BY P.DESCRICAO");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function getProduto()
    {
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT CODPROD, DESCRICAO
            FROM kokar.PCPRODUT P
            WHERE CODEPTO IN (10001, 10002) --AND CODSEC NOT IN (10013)
            ORDER BY DESCRICAO");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function getProduto2($id)
    {
        $sql = new SqlOra();
        try{
            return $sql->select("SELECT I.ID, I.CODPROD, P.DESCRICAO, I.FRACAOUMIDA
            FROM CONTRATIPOI I
            INNER JOIN kokar.PCPRODUT P ON I.CODPROD = P.CODPROD
            WHERE I.ID = $id
            ORDER BY DESCRICAO");
             
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
    public static function setListaAlterados(array $arrayc, array $arrayi)
    {   
        $produtoAntigo = $arrayc['codprod1'];
        $produtoNovo = $arrayc['codprod2'];
        $metodo = $arrayc['metodo'];
        $sql = new SqlOra();
        $updateSQL = [];
        $tempArr = [];
        
        try{
            foreach($arrayi as $i):
                
                array_push($tempArr, $sql->select("SELECT rowid, codprodmaster, codprod, fracaoumida from kokar.pccomposicao WHERE codprodmaster = $i AND codprod = $produtoAntigo AND metodo = $metodo"));
                $updateLinha = $sql->update("UPDATE kokar.pccomposicao c SET codprod = $produtoNovo, dtultalter = sysdate WHERE codprodmaster = $i AND codprod = $produtoAntigo AND metodo = $metodo",[]);
                if($updateLinha!='ok')
                    return $updateLinha  = 'erro no produto Master: '.$i.' | Novo: '.$produtoNovo.'| Antigo: '.$produtoAntigo;
                array_push($updateSQL, $i." - ".$updateLinha);
                 
            endforeach;
            //echo json_encode($updateSQL);
        }catch(Exception $e){
            echo 'Cabeçalho - Exceção capturada: ',  $e->getMessage(), "\n";
        }
        
        try{
            $sql->insert("INSERT INTO CONTRATIPOC (CODPROD1, CODPROD2, CODFUNALTER, DATA, METODO)
             VALUES(:codprod1, :codprod2, :codfun, :data, :metodo)", [':codprod1' => $arrayc['codprod1'],
              ':codprod2' => $arrayc['codprod2'], ':codfun' => $arrayc['codfun'], ':data' => $arrayc['data'],
               ':metodo' => $arrayc['metodo']]);
            $id =  $sql->select("SELECT MAX(ID) ID FROM CONTRATIPOC","")[0]["ID"];
            try{
                foreach($arrayi as $p):
                    foreach($tempArr as $t):
                        foreach($t as $t1):
                        echo $t1['CODPRODMASTER'].' - ';
                        echo $p.' \ ';
                        if($t1['CODPRODMASTER']==$p)
                        $sql->insert("INSERT INTO CONTRATIPOI (ID, CODPROD, LINHAID, FRACAOUMIDA) 
                        VALUES($id, :codprod, :linhaid, :fracaoumida)", [':codprod' => $p, ':linhaid' => $t1['ROWID'], ':fracaoumida' => $t1['FRACAOUMIDA'] ]);
                        endforeach;
                    endforeach;
                endforeach;
            
            }catch(Exception $e){
                echo 'Produtos Itens - Exceção capturada: ',  $e->getMessage(), "\n";
            }
        }catch(Exception $e){
            echo 'Cabeçalho - Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
    public static function getAlteracoes(){
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT C.ID, C.CODPROD1, C.CODPROD2, C.DATA, R.NOME, C.METODO FROM PARALELO.CONTRATIPOC C
            INNER JOIN PARALELO.RATUSER R
            ON R.CODUSER = C.CODFUNALTER
            where dtexclusao is null
            order by id desc");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function getFormula($codprod, $metodo){
        $sql = new SqlOra();
        try{
            $ret = $sql->select("SELECT c.codprodmaster, p2.descricao descricaomaster, c.codprod, p.descricao, c.qt, numseq, fracaoumida, percformulacaototal percentual
            from kokar.pccomposicao c
            inner join kokar.pcprodut p on p.codprod = c.codprod
            inner join kokar.pcprodut p2 on p2.codprod = c.codprodmaster
            where c.codprodmaster = $codprod and metodo = $metodo
           order by numseq");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }
    public static function deletar($id, $senha){
        $sql = new SqlOra();
        if($senha!='labkk@22'):
            echo 'Senha Incorreta! Procure o T.I.';
            return;
        endif;
            try{
                $ret = $sql->select("SELECT codprod1, codprod2, metodo from paralelo.contratipoc c where c.id = $id")[0];
                $produtoAntigo = $ret['CODPROD1'];
                $produtoNovo = $ret['CODPROD2'];
                $metodo = $ret['METODO'];
                $itens = $sql->select("SELECT codprod, fracaoumida from paralelo.contratipoi c where c.id = $id");
                foreach($itens as $i):
                    echo $sql->update2("UPDATE kokar.pccomposicao c SET codprod = $produtoAntigo, dtultalter = sysdate 
                    WHERE fracaoumida = :fracaoumida and codprodmaster = :codprod and codprod = $produtoNovo"
                    ,[':fracaoumida' => $i['FRACAOUMIDA'], ':codprod' => $i['CODPROD']]);
                    $sql->update2("UPDATE paralelo.contratipoi SET dtexclusao = to_date(sysdate) WHERE codprod = :linhaid and id = $id",[':linhaid' => $i['CODPROD']]);
                endforeach;
                    $sql->update2("UPDATE paralelo.contratipoc c SET dtexclusao = to_date(sysdate) WHERE id = $id",[]);
            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
    }
}                                         