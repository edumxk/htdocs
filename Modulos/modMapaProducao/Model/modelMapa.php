<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Mapa{

    public $codproducao;
   
    

    public static function novaProducao($codproducao, $codtanque, $dtproducao, $horaproducao, $codfun, $op){
        $dtproducao = Formatador::formatarData($dtproducao);

        $sql = new sqlOra();
        try{
            $sql->insert("INSERT INTO  paralelo.mproducaoc (codproducao, codtanque, dtabertura, op, status, codfunc, dtproducao, horaabertura, horaproducao) 
            values(:codproducao, :codtanque, to_char(sysdate,'dd/mm/yyyy'), :op, UPPER('A'), :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao)",
                [":codproducao"=>$codproducao, ":codtanque"=>$codtanque, ":op"=>$op, ":codfun"=>$codfun, ":dtproducao"=>$dtproducao, ":horaproducao"=>$horaproducao]);
                return "OK";
            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
        return "FAIL";
        //return 'teste';
    }

    public static function inserirItem($codproducao, $codprod, $qt, $codfun, $op){

        $sql = new sqlOra();
        try{
            $sql->insert("INSERT INTO  paralelo.mproducaoI (codproducao,codprod,qt,codfun,dtinclusao,op, horainclusao) 
            values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), :op, to_char(sysdate, 'hh24:mi:ss'))",
                [":codproducao"=>$codproducao, ":codprod"=>$codprod, ":qt"=>$qt, ":codfun"=>$codfun, ":op"=>$op]);
                return "OK";
            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
        return "FAIL";
        //return 'teste';
    }
    public static function getTanque($codlinha){
        $sql = new sqlOra(); 
        try{
            if($codlinha > 0 && $codlinha <= 5):   
                $ret = $sql->select("SELECT LINHA || ' | '|| CAPACIDADE || 'KG' DESCRICAO, CODTANQUE, CODLINHA FROM(
                    SELECT CASE WHEN CODLINHA = 1
                           THEN 'TINTAS 5000' 
                            WHEN CODLINHA = 2
                           THEN 'TINTAS 2000'
                             WHEN CODLINHA = 3
                           THEN 'TEXTURAS'
                             WHEN CODLINHA = 4
                           THEN 'MASSAS'
                             WHEN CODLINHA = 5
                           THEN 'SOLVENTE'
                              END AS LINHA,
                              T.CODTANQUE, T.CODLINHA, T.CAPACIDADE FROM PARALELO.MTANQUES T
                              WHERE CODLINHA = $codlinha
                              ORDER BY CODLINHA, CAPACIDADE DESC, CODTANQUE)");
            else:
                $ret = $sql->select("SELECT LINHA || ' | '|| CAPACIDADE || 'KG' DESCRICAO, CODTANQUE, CODLINHA FROM(
                    SELECT CASE WHEN CODLINHA = 1
                           THEN 'TINTAS 5000' 
                            WHEN CODLINHA = 2
                           THEN 'TINTAS 2000'
                             WHEN CODLINHA = 3
                           THEN 'TEXTURAS'
                             WHEN CODLINHA = 4
                           THEN 'MASSAS'
                             WHEN CODLINHA = 5
                           THEN 'SOLVENTE'
                              END AS LINHA,
                              T.CODTANQUE, T.CODLINHA, T.CAPACIDADE FROM PARALELO.MTANQUES T
                              ORDER BY CODLINHA, CAPACIDADE DESC, CODTANQUE)");
            endif;

            return $ret;

            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
            return "FAIL";
    }

    public static function getProducao($data){

        $sql = new sqlOra(); 
        try{
            if($data >= date('d/m/y')):   
                $ret = $sql->select("SELECT c.codproducao, t.codtanque, c.dtabertura, c.dtproducao,
                m.linha , t.capacidade, m.operador, t.status statust, M.COD,
                c.dtfecha ||' '||c.horafecha fechamento,
                c.dtproducao||' '|| c.horaproducao producao, c.status, c.op, c.dtabertura||' '||c.horaabertura abertura 
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
                m.linha , t.capacidade, m.operador, t.status statust, M.COD,
                c.dtfecha ||' '||c.horafecha fechamento,
                c.dtproducao||' '|| c.horaproducao producao, c.status, c.op, c.dtabertura||' '||c.horaabertura abertura 
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
                $m = new Mapa();
                $m->codproducao = $r['CODPRODUCAO'];
                $m->cod = $r['COD'];
                $m->codtanque = $r['CODTANQUE'];
                $m->capacidade = $r['CAPACIDADE'];
                $m->producao = $r['PRODUCAO'];
                $m->abertura = $r['ABERTURA'];
                $m->fechamento = $r['FECHAMENTO'];
                $m->dtproducao = $r['DTPRODUCAO'];
                $m->dtabertura = $r['DTABERTURA'];
                

                switch ($r['STATUS']):
                    case ('A'):
                        $m->status = "ABERTURA/OP";
                        break;
                    case ('P'):
                        $m->status = "PESAGEM";
                        break;
                    case ('D'):
                        $m->status = "DISPERSÃO/BASE";
                        break;
                    case ('L'):
                        $m->status = "LABORATÓRIO";
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
        return "FAIL";
    }
    public static function getProducaoEditar($codproducao){

        $sql = new sqlOra(); 
        try{   
            $ret = $sql->select("SELECT c.codproducao, t.codtanque, t.status statust,
            c.dtfecha, c.horafecha, c.dtproducao, c.horaproducao hrproducao, c.status,
            c.dtfecha, c.horafecha
            from paralelo.mproducaoc c
            right join paralelo.mtanques t on t.codtanque = c.codtanque
            inner join paralelo.metasprodc m on m.cod = t.codlinha
            WHERE c.codproducao = :codproducao
            ",[":codproducao"=>$codproducao]);
            
        $mapa = [];
        if(sizeof($ret)>0):
            foreach($ret as $r):
                $m = new Mapa();
                $m->codproducao = $r['CODPRODUCAO'];
                $m->codtanque = $r['CODTANQUE'];
                $m->statust = $r['STATUST'];
                $m->statusp = $r['STATUS'];
                $m->dtproducao = $r['DTPRODUCAO'];
                $m->hrproducao = $r['HRPRODUCAO'];
                $m->dtfecha = $r['DTFECHA'];
                $m->hrfecha = $r['HORAFECHA'];
                switch ($r['STATUS']):
                    case ('A'):
                        $m->status = "ABERTURA/OP";
                        break;
                    case ('P'):
                        $m->status = "PESAGEM";
                        break;
                    case ('D'):
                        $m->status = "DISPERSÃO/BASE";
                        break;
                    case ('L'):
                        $m->status = "LABORATÓRIO";
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
        return "FAIL";
    }

    public static function getLinhas(){
        $sql = new sqlOra(); 
        try{   
            $ret = $sql->select("SELECT linha from paralelo.metasprodc");
            return $ret;
        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
    }   

public static function getItem(){

    $sql = new sqlOra(); 
    try{   
        $ret = $sql->select("SELECT i.codproducao, i.codprod, p.descricao produto,c.categoria, r.descricao cor, 'COD: '||i.codprod ||' | '||p.embalagem embalagem, 
        I.QT, p.pesoliq PESO, p.litragem
         from paralelo.mproducaoi i
         inner join kokar.pcprodut p on i.codprod = p.codprod
         left join kokar.pccor r on r.codcor = p.codcor
         inner join kokar.pccategoria c on c.codsec = p.codsec and c.codcategoria = p.codcategoria
         order by codproducao, embalagem");
        
        $item = [];
        if(sizeof($ret)>0):
            foreach($ret as $r):
            $m = new Mapa();
            $m->codprod = $r['CODPROD'];
            $m->produto = $r['EMBALAGEM'];
            $m->codproducao = $r['CODPRODUCAO'];
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
public static function getItemH(){

    $sql = new sqlOra(); 
    try{   
        $ret = $sql->select("SELECT DISTINCT i.codproducao, c.categoria, r.descricao cor, round(sum(i.qt*p.pesoliq),0)peso
        from paralelo.mproducaoi i
        inner join kokar.pcprodut p on i.codprod = p.codprod
        left join kokar.pccor r on r.codcor = p.codcor
        inner join kokar.pccategoria c on c.codsec = p.codsec and c.codcategoria = p.codcategoria
        group by i.codproducao, c.categoria, r.descricao
        order by codproducao");
        
        $item = [];
        if(sizeof($ret)>0):
            foreach($ret as $r):
            $m = new Mapa();
            switch ($r['CATEGORIA']):
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
                    $m->categoria = "ESM SINT";
                break;
                case ('ESMALTE SINT FOSCO'):
                    $m->categoria = "ESM SINT FS";
                break;
                
                case($r['CATEGORIA']):
                $m->categoria = $r['CATEGORIA'];
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

public static function getProduto($codigo){

    $sql = new sqlOra(); 
    try{   
        $ret = $sql->select("SELECT codprod, descricao, pesoliq peso from KOKAR.pcprodut 
        where codprod = :codigo AND codepto = 10000 and dtexclusao is null
        and codprod != 12 
        
        ",[":codigo"=>$codigo]);
        return $ret;
    }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
}
public static function getProduto2(){

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
public static function cadastrar($array){
    $produtos = $array["produtos"];
    $sql = new sqlOra(); 
    $codproducao = $sql->select("SELECT max(codproducao)+1 cod from paralelo.mproducaoc")[0]["COD"];
    if($codproducao<=0)
    $codproducao=1;
    $prodadd = 0;
    $dataini = $array["dataini"];
    try{  
        foreach($produtos as $p):
            for($i=0; $i<3; $i++):
                if($p[$i]['cod']>0 && $p[$i]['qt']>0){
                    $sql->insert("INSERT INTO  paralelo.mproducaoi (codproducao, codprod, qt, codfun, dtinclusao, horainclusao, status) 
                    values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'),:status)"
                    ,[":codproducao"=>$codproducao, ":codprod"=>$p[$i]['cod'], ":qt"=>$p[$i]['qt'], ":codfun"=>$array["codfun"],":status"=> $array["status"] ]);
                    $prodadd ++;
                }
                
            endfor;
        endforeach;
        if($prodadd>0){
            if($array["status"]=='F'){
        $sql->insert("INSERT INTO  paralelo.mproducaoc 
        (codproducao, codtanque, dtabertura, status, codfunc, dtproducao, horaabertura, horaproducao, dtfecha, horafecha) 
        values(:codproducao, :codtanque, to_char(sysdate,'dd/mm/yyyy'), :status, :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'))"
        ,[":codproducao"=>$codproducao, ":codtanque"=>$array["codtanque"], ":status"=> $array["status"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"] ]);
        }else{
            $sql->insert("INSERT INTO  paralelo.mproducaoc 
        (codproducao, codtanque, dtabertura, status, codfunc, dtproducao, horaabertura, horaproducao, dtfecha, horafecha) 
        values(:codproducao, :codtanque, :dataini, :status, :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao, '', '')"
        ,[":codproducao"=>$codproducao, ":codtanque"=>$array["codtanque"], ":dataini"=> $dataini,":status"=> $array["status"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"] ]);
        }
        return "OK";
        }else {
            return "FAIL";
        }
        
    }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
}
public static function editar($array){
    $produtos = $array["produtos"];
    $sql = new sqlOra(); 
    $codproducao = $array["codproducao"];
    $prodadd = 0;
    if($array["dtfecha"]>'')
        $dtfecha = Formatador::formatador2($array["dtfecha"]);
    else 
        $dtfecha = '';
    $hrfecha = $array["hrfecha"];
    try{ 
        $sql->insert("DELETE FROM PARALELO.MPRODUCAOI WHERE CODPRODUCAO = :codproducao", [":codproducao"=>$codproducao]);
        foreach($produtos as $p):
            for($i=0; $i<3; $i++):
                if($p[$i]['cod']>0 && $p[$i]['qt']>0){
                    $sql->insert("INSERT INTO  paralelo.mproducaoi (codproducao, codprod, qt, codfun, dtinclusao, horainclusao, status) 
                    values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'), :status)"
                    ,[":codproducao"=>$codproducao, ":codprod"=>$p[$i]['cod'], ":qt"=>$p[$i]['qt'], ":codfun"=>$array["codfun"], ":status"=> $array["status"] ]);
                    //echo ("produto $i COD:".$p[$i]['cod']."QT:".$p[$i]['qt']);
                    $prodadd ++;
                }
            endfor;
        endforeach;
        if($prodadd>0){
            if($array["status"]=='F'){
                $stemp = $sql->select("SELECT STATUS FROM PARALELO.MPRODUCAOC WHERE CODPRODUCAO = :codproducao",[":codproducao"=>$codproducao]);
                if($stemp[0]['STATUS'] == 'F'){
                $sql->insert("UPDATE paralelo.mproducaoc
                SET
                codproducao = :codproducao,
                codtanque = :codtanque, 
                status = :status, 
                codfunc = :codfun, 
                dtproducao = :dtproducao,  
                horaproducao = :horaproducao,
                horafecha = :horafecha,  
                dtfecha = :dtfecha
                WHERE codproducao = :codproducao
                ",[":codproducao"=>$codproducao,":status"=>$array["status"], ":codtanque"=>$array["codtanque"],
                 ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]),
                  ":horaproducao"=>$array["hrprevisao"], ":dtfecha" => $dtfecha, ":horafecha" => $hrfecha]);
                }else{
                    $sql->insert("UPDATE paralelo.mproducaoc
                    SET
                    codproducao = :codproducao,
                    codtanque = :codtanque, 
                    status = :status, 
                    codfunc = :codfun, 
                    dtproducao = :dtproducao,  
                    horaproducao = :horaproducao,
                    horafecha = to_char(sysdate, 'hh24:mi:ss'),
                    dtfecha = to_char(sysdate,'dd/mm/yyyy')
                    WHERE codproducao = :codproducao
                    ",[":codproducao"=>$codproducao,":status"=>$array["status"], ":codtanque"=>$array["codtanque"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"] ]);
                }
        }else{
                $sql->insert("UPDATE paralelo.mproducaoc
                SET
                codproducao = :codproducao,
                codtanque = :codtanque, 
                status = :status, 
                codfunc = :codfun, 
                dtproducao = :dtproducao,  
                horaproducao = :horaproducao,
                horafecha = '',
                dtfecha = ''
                WHERE codproducao = :codproducao
                ",[":codproducao"=>$codproducao,":status"=>$array["status"], ":codtanque"=>$array["codtanque"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"] ]);
            }
        return "OK";
        }else {
            return "FAIL";
        }
        
    }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return "FAIL";
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
             dtexclusao = to_char(sysdate,'dd/mm/yyyy'),
             WHERE codproducao = :codproducao
             ",[":codproducao"=>$codproducao] );

        return "OK";
     }catch(Exception $e){
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
    return "FAIL";
    }
public static function linhas($valor){
    $sql = new sqlOra();
    try{
        return $sql->select("select CODTANQUE, C.NOME , CAPACIDADE from paralelo.mtanques T
        INNER JOIN PARALELO.METASPROD C ON C.COD = T.CODLINHA
         where codlinha = (select codlinha from paralelo.mtanques where codtanque = $valor) ORDER BY CODTANQUE");
     }catch(Exception $e){
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
    return "FAIL";
    }
public static function linhas2($valor){
    $sql = new sqlOra();
    try{
        return $sql->select("SELECT distinct t.CODTANQUE, C.NOME , CAPACIDADE from paralelo.mtanques T
        INNER JOIN PARALELO.METASPROD C ON C.COD = T.CODLINHA
         where codlinha = (select mm.codlinha from paralelo.mproducaoc cc inner join paralelo.mtanques mm on mm.codtanque = cc.codtanque where cc.codproducao = $valor) ORDER BY CODTANQUE");
     }catch(Exception $e){
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
    return "FAIL";
    }
}
