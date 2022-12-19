<?php

use Model as GlobalModel;

    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . "/Modulos/modMapaProducao/controle/controle.php");

class Model{

    public static function getProducaoFeed($data){

        $sql = new sqlOra(); 
        try{
            if($data >= date('d/m/y')):   
                $ret = $sql->select("SELECT c.codproducao, t.codtanque, c.dtabertura, c.dtproducao,
                m.linha , t.capacidade, m.operador, t.status statust, M.COD, c.lote,
                c.dtfecha ||' '||c.horafecha fechamento,
                c.dtproducao||' '|| c.horaproducao producao, c.status, c.dtabertura||' '||c.horaabertura abertura ,
                c.dtcadastro||' '||c.horaabertura dtcadastro 
                from paralelo.mproducaoc c
            right join paralelo.mtanques t on t.codtanque = c.codtanque
            inner join paralelo.metasprodc m on m.cod = t.codlinha
            WHERE 
                ((c.dtfecha = :data and c.status = 'F') 
                or (c.status != 'F' and c.dtabertura >= :data))
                and c.dtexclusao is null     
                order by c.dtfecha, c.horafecha, c.dtproducao, c.horaproducao
                ",[":data"=>$data]);
            else:
                $ret = $sql->select("SELECT c.codproducao, t.codtanque, c.dtabertura, c.dtproducao,
                m.linha , t.capacidade, m.operador, t.status statust, M.COD, c.lote,
                c.dtfecha ||' '||c.horafecha fechamento,
                c.dtproducao||' '|| c.horaproducao producao, c.status,  c.dtabertura||' 08:00:00' abertura,
                c.dtcadastro||' '||c.horaabertura dtcadastro 
                from paralelo.mproducaoc c
                right join paralelo.mtanques t on t.codtanque = c.codtanque
                inner join paralelo.metasprodc m on m.cod = t.codlinha
                WHERE (((c.dtfecha = :data and c.status = 'F') or c.status !='F' and c.dtabertura <= :data))and c.dtexclusao is null
                order by c.dtfecha, c.horafecha, c.dtproducao, c.horaproducao
                ",[":data"=>$data]);
            endif;
            $mapa = [];
            if(sizeof($ret)>0):
                foreach($ret as $r):
                $m = new Model();
                $m->codproducao = $r['CODPRODUCAO'];
                $m->cod = $r['COD'];
                $m->linha = $r['LINHA'];
                $m->codtanque = $r['CODTANQUE'];
                $m->capacidade = $r['CAPACIDADE'];
                $m->producao = $r['PRODUCAO'];
                $m->abertura = $r['ABERTURA'];
                $m->cadastro = $r['DTCADASTRO'];
                $m->fechamento = $r['FECHAMENTO'];
                $m->dtproducao = $r['DTPRODUCAO'];
                $m->dtabertura = $r['DTABERTURA'];
                $m->lote = $r['LOTE'];
                

                switch ($r['STATUS']):
                    case ('A'):
                        $m->status = "ABERT/OP";
                        break;
                    case ('P'):
                        $m->status = "PESAGEM";
                        break;
                    case ('D'):
                        $m->status = "DISP/BASE";
                        break;
                    case ('L'):
                        $m->status = "LAB";
                        break;
                    case ('C'):
                        $m->status = "COR";
                        break;
                    case ('E'):
                        $m->status = "ENVASE";
                        break;
                    case ('B'):
                        $m->status = "CORREÇÃO";
                        break;
                    case ('F'):
                        $m->status = "FINALIZADO";
                        break;
                    case ('S'):
                        $m->status = "AGUARDANDO";
                        break;
                endswitch;
                array_push($mapa, $m);      
            endforeach; 
        endif;           
                    
            return $mapa;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "funcao getProducaoFeed: erro não identificado";
    }

    public static function cadastrar($dados){
        $sql = new sqlOra(); 
        $dados->codProducao = $sql->select("SELECT max(codproducao)+1 cod from paralelo.mproducaoc")[0]["COD"];

        if( $dados->codProducao <=0)
            $dados->codProducao = 1 ;

        $prodadd = 0;

        echo json_encode($dados->produtos);
        try{  
            $prodadd = Model::inserirItems($dados);

            if($prodadd>0)
                return Model::inserirHead($dados);
            else 
                return "Cadastrar: Erro, nenhum produto incluido.";

        }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
    }

    public static function alterarStatus($codproducao){
        $sql = new sqlOra(); 
        $status = $sql->select("SELECT status from paralelo.mproducaoc where codproducao = :codproducao",[":codproducao"=>$codproducao])[0]["STATUS"];
        switch ($status):
            case ('A'):
                $status = "P";
                break;
            case ('P'):
                $status = "D";
                break;
            case ('D'):
                $status = "L";
                break;
            case ('L'):
                $status = "C";
                break;
            case ('C'):
                $status = "E";
                break;
            case ('E'):
                $status = "F";
                break;
            case ('B'):
                $status = "E";
                break;
            case ('F'):
                $status = "F";
                break;
            case ('S'):
                $status = "A";
                break;
        endswitch;
    
        try{
            if($status =='F'){
                $sql->insert("UPDATE paralelo.mproducaoc SET
                status = :status,
                dtfecha = to_char(sysdate,'dd/mm/yyyy'),
                horafecha = to_char(sysdate, 'hh24:mi:ss')
                WHERE codproducao = :codproducao
                ",[":codproducao"=>$codproducao, ":status"=>$status]);
            }else{
                $sql->insert("UPDATE paralelo.mproducaoc SET
                status = :status 
                WHERE codproducao = :codproducao
                ",[":codproducao"=>$codproducao, ":status"=>$status]);
            }
                $sql->insert("UPDATE paralelo.mproducaoi SET
                status = :status 
                WHERE codproducao = :codproducao
                ",[":codproducao"=>$codproducao, ":status"=>$status]);
            return "OK";
         }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getItemH(){

        $sql = new sqlOra(); 
        try{   
            $ret = $sql->select("SELECT codproducao, categoria, cor, sum(peso) peso from (
                select codproducao, categoria, cor, case when pesoformula > 0 then pesoformula
                else peso end as peso from(
                SELECT DISTINCT i.codproducao, c.categoria, r.descricao cor, round(sum(i.qt*p.pesoliq),0)peso, round(sum(i.qt*cp.qt),0)pesoformula
                            from paralelo.mproducaoi i
                            inner join kokar.pcprodut p on i.codprod = p.codprod
                            left join kokar.pccor r on r.codcor = p.codcor
                            inner join kokar.pccategoria c on c.codsec = p.codsec and c.codcategoria = p.codcategoria
                            left join kokar.pcopc o on o.numop = i.op
                            left join (select c1.codprodmaster, c1.qt, c1.metodo from kokar.pccomposicao c1 
                                inner join kokar.pcprodut p1 on c1.codprod = p1.codprod and p1.codepto = 10001 and p1.codsec IN (10012, 10013))cp
                                on cp.codprodmaster = o.codprodmaster and cp.metodo = o.metodo
                            group by i.codproducao, c.categoria, r.descricao, cp.qt
                            order by codproducao))
                            group by codproducao, categoria, cor
                            order by codproducao desc");
            
            $item = [];
            if(sizeof($ret)>0):
                foreach($ret as $r):
                $m = new Model();
                switch (utf8_encode($r['CATEGORIA'])):
                    case ('STANDARD FOSCO'):
                        $m->categoria = "STD FS";
                    break;
                    case ('STANDARD SEMIBRILHO'):
                        $m->categoria = "STD SB";
                    break;
                    case ('ECONOMICO FOSCO'):
                        $m->categoria = "ECO FS";
                    break;
                    case ('FIT ECONOMICO FOSCO'):
                        $m->categoria = "FIT ACR";
                    break;
                    case ('FIT FUNDO PREPARADOR'):
                        $m->categoria = "FIT FUNDO PP";
                    break;
                    case ('PREMIUM ACETINADO'):
                        $m->categoria = "PREMIUM AC";
                    break;
                    case ('PREMIUM FOSCO'):
                        $m->categoria = "PREMIUM FS";
                    break;
                    case ('PISO PREMIUM'):
                        $m->categoria = "PISO";
                    break;
                    case ('PREMIUM SEMIBRILHO'):
                        $m->categoria = "PREMIUM SB";
                    break;
                    case ('RESINA ALTO BRILHO'):
                        $m->categoria = "RESINA";
                    break;
                    case ('TEXTURA TRADICIONAL'):
                        $m->categoria = "TRADICIONAL";
                    break;
                    case ('TEXTURA RISCADO'):
                        $m->categoria = "RISCADO";
                    break;
                    case ('GRANULADO LUME'):
                        $m->categoria = "LUME";
                    break;
                    case ('GRANULADO SUBLIME'):
                        $m->categoria = "SUBLIME";
                    break;
                    case ('GRANULADO MINERAIS'):
                        $m->categoria = "MINERAIS";
                    break;
                    case ('FUNDO PREPARADOR'):
                        $m->categoria = "FUNDO PP";
                    break;
                    case ('ESMALTE SINT BRILHANTE'):
                        $m->categoria = "ESM SINT BR";
                    break;
                    case ('ESMALTE SINT FOSCO'):
                        $m->categoria = "ESM SINT FS";
                    break;
                    case ('FUNDO E ACABAMENTO'):
                        $m->categoria = "FUND E ACAB";
                    break;
                    case ('FUNDO SINTÉTICO'):
                        $m->categoria = "FUND SINT";
                    break;
                    
                    case($r['CATEGORIA']):
                    $m->categoria = utf8_encode($r['CATEGORIA']);
                endswitch;
                $m->cor = utf8_encode($r['COR']);
                $m->peso = $r['PESO'];
                $m->codproducao = $r['CODPRODUCAO'];
                array_push($item, $m); 
            endforeach; 
            
        endif;           
        return $item;
    
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getItem(){

        $sql = new sqlOra(); 
        try{   
            $ret = $sql->select("SELECT i.codproducao, i.codprod, p.descricao produto,c.categoria, r.descricao cor, i.codprod ||' | '||p.embalagem embalagem, 
            I.QT, p.pesoliq PESO, p.litragem
             from paralelo.mproducaoi i
             inner join kokar.pcprodut p on i.codprod = p.codprod
             left join kokar.pccor r on r.codcor = p.codcor
             inner join kokar.pccategoria c on c.codsec = p.codsec and c.codcategoria = p.codcategoria
             inner join paralelo.mproducaoc m on m.codproducao = i.codproducao
             where m.dtexclusao is null
             order by codproducao, embalagem");
            
            $item = [];
            if(sizeof($ret)>0):
                foreach($ret as $r):
                $m = new Model();
                $m->codproducao = $r['CODPRODUCAO'];
                $m->codprod = $r['CODPROD'];
                $m->produto = $r['EMBALAGEM'];
                $m->qt = $r['QT'];
                $m->peso = $r['PESO'];
                $m->litragem = $r['LITRAGEM'];
                array_push($item, $m); 
            endforeach; 
        endif;           
        return $item;
    
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getItemLote($lote){
        
        $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT distinct o.codprodmaster codprod, o.qtproduzir qt, l.qt qt_final, l.dtmxsalter dtapontamento
            from kokar.pcopc o 
            left join kokar.pclote l 
            on l.numlote = o.numlote 
            and l.codprod = o.codprodmaster
            inner join kokar.pcprodut p on p.codprod = o.codprodmaster
        where o.numlote =   '$lote' and posicao not in 'C' and p.codepto = 10000", []);
        
        $item = [];
        if(sizeof($ret)>0):
            foreach($ret as $r):
                $m = new Model();
                $m->codprod = $r['CODPROD'];
                $m->qt = $r['QT'];
                $m->qtFinal = $r['QT_FINAL'];
                $m->dtApontamento = $r['DTAPONTAMENTO'];
                array_push($item, $m); 
            endforeach; 
        endif;           
        return $item;

        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function removeLote($dados){
        
        $sql = new sqlOra();
        try{
            $ret = $sql->insert("UPDATE PARALELO.MPRODUCAOC SET LOTE = ''
                where CODPRODUCAO = '$dados->codProducao'", []) . $sql->insert("UPDATE PARALELO.MPRODUCAOI SET OP = ''
                where CODPRODUCAO = '$dados->codProducao'", []) ;
    
        return $ret;

        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function consultaLote($dados){
        $ret = [];
        $produtos = [];
        $sql = new sqlOra();
        try{
            if($dados->lote != '' || $dados->lote != null){
                $produtos = $sql->select( "SELECT distinct o.numlote lote, o.numop NUMOP, o.codprodmaster codprod, o.qtproduzir qt, l.qt qt_final, o.posicao,
                    l.dtmxsalter dtapontamento, i.codproducao
                    from kokar.pcopc o 
                    left join kokar.pclote l 
                    on l.numlote = o.numlote 
                    and l.codprod = o.codprodmaster
                    inner join kokar.pcprodut p on p.codprod = o.codprodmaster
                    left join paralelo.mproducaoc c on c.lote = o.numlote and c.dtexclusao is null
                    left join paralelo.mproducaoi i on i.codproducao = c.codproducao
                    where o.numlote = :lote and o.posicao not in 'C' and p.codepto = 10000", [":lote" => $dados->lote] );
                
            }else{
                $produtos =  $sql->select("SELECT codproducao, codprod, qt, op NUMOP,'' QT_FINAL ,'' DTAPONTAMENTO,'' LOTE, '' posicao
                from paralelo.mproducaoi i where i.codproducao = :codproducao",[":codproducao"=>$dados->codProducao]); 
            }
           

            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
            
            if( sizeof($produtos) == 0 ){
                $p = new Model();
                $p->codproducao = null;
                $p->codprod = null;
                $p->qt = null;
                $p->op = null;
                $p->lote = null;

                return [$p];
            }
            if($produtos[0]['CODPROD'] != ''){
                foreach($produtos as $p){
                    $m = new Model();
                    $m->codproducao = $p['CODPRODUCAO'];
                    $m->codprod = $p['CODPROD'];
                    if($p['POSICAO']!= 'F')
                        $m->qt = $p['QT'];
                    else
                        $m->qt = $p['QT_FINAL'];
                    $m->dtApontamento = $p['DTAPONTAMENTO'];
                    $m->op = $p['NUMOP'];
                    $m->lote = $p['LOTE'];
                    
                    $ret[] = $m;
                }
            }else{
                return null;
            }
        return $ret;
    }

    public static function inserirItems($dados){
        $sql = new sqlOra();
        $prodadd = 0;

        foreach($dados->produtos as $p){
            
            if($p->codprod>0 && $p->qt>0){
                echo $sql->insert("INSERT INTO  paralelo.mproducaoi (codproducao, codprod, qt, codfun, dtinclusao, horainclusao, status, op) 
                values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'),:status, :op)"
                ,[":codproducao"=>$dados->codProducao, ":codprod"=>$p->codprod, ":qt"=>$p->qt, ":codfun"=>$dados->codFun ,":status"=> $dados->status, ":op"=>$p->op]);
                $prodadd ++;
            }
            echo 'OP: '.$p->op;
        }

        return $prodadd;
    }

    public static function inserirHead($dados){
        $sql = new sqlOra();
        $ret = '';
        
        if( $dados->status == 'F' ){
            $ret = $sql->insert("INSERT INTO  paralelo.mproducaoc 
                (codproducao, codtanque, dtabertura, status, codfunc, dtproducao, horaabertura, horaproducao, dtfecha, horafecha, lote, dtcadastro)  values
                (:codproducao, :codtanque, to_char(sysdate,'dd/mm/yyyy'), :status, :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'), :lote, to_char(sysdate,'dd/mm/yyyy'))"
                ,[":codproducao"=>$dados->codProducao, ":codtanque"=>$dados->tanque, ":status" => $dados->status, ":codfun" => $dados->codFun, ":dtproducao"=>$dados->dtPrevisao,
                ":horaproducao"=>$dados->hrPrevisao, ":lote"=>$dados->lote ]);
        }else{
            $ret = $sql->insert("INSERT INTO  paralelo.mproducaoc 
                (codproducao, codtanque, dtabertura, status, codfunc, dtproducao, horaabertura, horaproducao, lote, dtcadastro)  values
                (:codproducao, :codtanque, :dtAbertura, :status, :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao, :lote, to_char(sysdate,'dd/mm/yyyy'))"
                ,[":codproducao"=>$dados->codProducao, ":codtanque"=>$dados->tanque, ':dtAbertura' => $dados->dtAbertura ,":status" => $dados->status, ":codfun" => $dados->codFun, ":dtproducao"=>$dados->dtPrevisao,
                ":horaproducao"=>$dados->hrPrevisao, ":lote"=>$dados->lote ]);
        }
            return $ret;
    }

    public static function cadastrarProdutos($dados){
        $sql = new sqlOra(); 
        
        $prodadd = 0;


        try{
            $sql->insert( "DELETE FROM paralelo.mproducaoi WHERE codproducao = $dados->codProducao", []);
            
            $prodadd = Model::inserirItems($dados);

            if($prodadd>0)
                echo "\nProducao Atualizada: ".
                $sql->insert( "UPDATE paralelo.mproducaoc SET LOTE = '$dados->lote' WHERE codproducao = $dados->codProducao", []) 
                . " Produtos adicionados: ".$prodadd." \n\n ";
            else 
                echo "Cadastrar: Erro, nenhum produto incluido. procure o TI com o código: $dados->codProducao\n\n";

        }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
    }

    public static function editar($array){
        $produtos = $array["produtos"];
        $sql = new sqlOra(); 
        $codproducao = $array["codproducao"];
        $lote = $array['lote'];

        try{ 

            if($lote != ''){
                $sql->insert("UPDATE PARALELO.MPRODUCAOI SET DTEXCLUSAO = to_char(sysdate) WHERE CODPRODUCAO = :codproducao", [":codproducao"=>$codproducao]);

                $produtos = ["produtos" => ModelLote::consultaLote($array)];
              
                ModelLote::inserirItems2($codproducao, $produtos, $array);
            
                $ret = $sql->update("UPDATE PARALELO.MPRODUCAOC SET LOTE = $lote WHERE CODPRODUCAO = :codproducao", [":codproducao"=>$codproducao]);

                return $ret;
            }
        
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getProducaoEditar($codproducao){

        $sql = new sqlOra(); 
        try{   
            $r = $sql->select("SELECT c.codproducao, c.codtanque, c.dtabertura, c.horaabertura hrabertura,
                                    c.dtfecha, c.horafecha hrfecha, c.dtproducao dtprevisao, c.horaproducao hrprevisao,
                                    c.status, c.lote, c.codfunc codfun, c.dtcadastro
                                from paralelo.mproducaoc c
                                WHERE c.codproducao = :codproducao and c.dtexclusao is null",[":codproducao"=>$codproducao])[0];
            
            if(sizeof($r)>0):
                $m = new Model();
                $m->codProducao = $r['CODPRODUCAO'];
                $m->tanque = $r['CODTANQUE'];
                $m->status = $r['STATUS'];
                $m->dtPrevisao = $r['DTPREVISAO'];
                $m->hrPrevisao = $r['HRPREVISAO'];
                $m->dtFecha = $r['DTFECHA'];
                $m->hrFecha = $r['HRFECHA'];
                $m->dtAbertura = $r['DTABERTURA'];
                $m->produtos = null;
                $m->hrAbertura = $r['HRABERTURA'];
                $m->lote = $r['LOTE'];
                $m->codFun = $r['CODFUN'];
                $m->status = $r['STATUS'];      
            endif;           
                        
            return $m;
        
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
            return "Erro na coleta de dados do banco na edição";
    }

    public static function excluir($codproducao, $codfun){
        $sql = new sqlOra();
        try{
            $sql->insert("UPDATE paralelo.mproducaoc SET
            dtexclusao = to_char(sysdate,'dd/mm/yyyy'),
            horaexclusao = to_char(sysdate, 'hh24:mi:ss'),
            COFUNEXCLUSAO = :codfun,
            STATUS = 'S'
            WHERE codproducao = :codproducao
            ",[":codproducao"=>$codproducao, ":codfun"=>$codfun] );
            
            $sql->insert("UPDATE paralelo.mproducaoi SET
                dtexclusao = to_char(sysdate,'dd/mm/yyyy')
                WHERE codproducao = :codproducao
                ",[":codproducao"=>$codproducao] );
    
            return "ok";
         }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function linhas($valor){
        $sql = new sqlOra();
        try{
            return $sql->select("SELECT CODTANQUE, C.NOME , CAPACIDADE from paralelo.mtanques T
            INNER JOIN PARALELO.METASPROD C ON C.COD = T.CODLINHA
                where codlinha = (select codlinha from paralelo.mtanques where codtanque = $valor) ORDER BY CODTANQUE");
            }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }

    public static function getLinhas(){
        $sql = new sqlOra(); 
        try{   
            $ret = $sql->select("SELECT case when linha like 'TINTAS-2000'
            then 0
              when linha like 'TEXTURAS'
               then 1
                 when linha like 'MASSAS'
               then 2
                 when linha like 'TINTAS-5000'
               then 3
                 when linha like 'SOLVENTES'
               then 4 end as ordernacao, linha 
               from paralelo.metasprodc c order by ordernacao");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }  

    public static function getProdutosLista(){

        $sql = new sqlOra(); 
        try{   
            $ret = $sql->select("SELECT codprod, descricao, pesoliq peso from KOKAR.pcprodut 
            where codepto = 10000 and dtexclusao is null
            and codprod != 12
            order by descricao, codprod
            
            ");
            return $ret;
        }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
            return "FAIL";
    }

    public static function updateStatus($dados){
        $sql = new sqlOra();
        try{
            if($dados->status=='F'){
                Model::cadastrarProdutos($dados);
                $sql->insert("UPDATE paralelo.mproducaoi SET STATUS = '$dados->status' where codproducao = $dados->codProducao",[]);
                $sql->insert("UPDATE paralelo.mproducaoc SET STATUS = '$dados->status', dtfecha = to_char(sysdate,'dd/mm/yyyy'), horafecha = to_char(sysdate, 'hh24:mi:ss') where codproducao = $dados->codProducao",[]);
                echo "Status Atualizado: codProducao: $dados->codProducao | status: $dados->status\n";
            }else{
                $sql->insert("UPDATE paralelo.mproducaoi SET STATUS = '$dados->status' where codproducao = $dados->codProducao",[]);
                $sql->insert("UPDATE paralelo.mproducaoc SET STATUS = '$dados->status', dtfecha = '', horafecha = '' where codproducao = $dados->codProducao",[]);
                echo "Status Atualizado: codProducao: $dados->codProducao | status: $dados->status\n";

                }
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    public static function updateTanque($dados){
        $sql = new sqlOra();
        try{
            $sql->insert("UPDATE paralelo.mproducaoc SET codtanque = $dados->tanque where codproducao = $dados->codProducao",[]);
           
            echo "Tanque atualizado: $dados->tanque\n\n";
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    public static function updateDatas($dados, $tipo){
        $sql = new sqlOra();
        
        try{
            if($tipo == 'previsao')
                echo $sql->insert("UPDATE paralelo.mproducaoc SET dtproducao = to_date('$dados->dtPrevisao'), horaproducao = '$dados->hrPrevisao' where codproducao = $dados->codProducao ",[]);
            elseif($tipo == 'fechamento')
                echo $sql->insert("UPDATE paralelo.mproducaoc SET dtfecha = to_date('$dados->dtFecha'), horafecha = '$dados->hrFecha' where codproducao = $dados->codProducao ", []);
          
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }

    }

    public static function atualizaProducao(){
        $sql = new SqlOra();

        $dados = $sql->select("SELECT distinct c.codproducao, c.op, c.status, o.posicao
        from paralelo.mproducaoi c
        inner join kokar.pcopc o on o.numop = c.op
        WHERE 
        c.dtexclusao is null
        and c.status !='F' and c.op > 1     
        and o.posicao = 'F'
        order by codproducao",[]);

        $ret='';
        $m = [];
        if(sizeof($dados)>0){
            foreach($dados as $d){ 
                echo 'entrou: '.$d['CODPRODUCAO'];
                if($d['CODPRODUCAO'] != null || $d['CODPRODUCAO'] != '') 
                    $m = Model::getProducaoEditar($d['CODPRODUCAO']);
                    $m->produtos = Model::consultaLote($m);
                    $m->status = 'F';
                    echo json_encode($m);
                    Model::updateStatus($m);
                    $ret = 'ok';
            }
        }

        return $ret;
    }

    public function setNewStatus($dados){
        
    }
}
