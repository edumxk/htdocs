<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMapaProducao/Model/modelMapa.php');

class ModelLote{

    public static function consultaLote($dados){
        $ret = [];
        $produtos = [];
        $sql = new sqlOra();
        try{
            if($dados['lote'] != null){
            $produtos = $sql->select("SELECT distinct o.numlote lote, o.numop NUMOP, o.codprodmaster codprod, o.qtproduzir qt, l.qt qt_final,
                l.dtmxsalter dtapontamento, i.codproducao
                from kokar.pcopc o 
                left join kokar.pclote l 
                on l.numlote = o.numlote 
                and l.codprod = o.codprodmaster
                inner join kokar.pcprodut p on p.codprod = o.codprodmaster
                left join paralelo.mproducaoc c on c.lote = o.numlote and c.dtexclusao is null
                left join paralelo.mproducaoi i on i.codproducao = c.codproducao
                where o.numlote = :lote and posicao not in 'C' and p.codepto = 10000", [":lote" => $dados['lote']]);
            }else{
                $produtos =  $sql->select("SELECT codproducao, codprod, qt, op
                from paralelo.mproducaoi i where i.codproducao = :codproducao",[":codproducao"=>$dados['codproducao']]);
            }

            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
        if(sizeof($produtos)>0){
            foreach($produtos as $p){
                $m = new ModelLote();
                $m->codprod = $p['CODPROD'];
                if($p['QT_FINAL']!= '')
                    $m->qt = $p['QT'];
                else
                    $m->qt = $p['QT_FINAL'];
                $m->dtApontamento = $p['DTAPONTAMENTO'];
                $m->codProducao = $p['CODPRODUCAO'];
                $m->op = $p['NUMOP'];
                $m->lote = $p['LOTE'];
                
                $ret[] = $m;
            }
        }else{
            return null;
        }
        return $ret;
    }

    public static function consultaLoteOLD($dados){ //retorna a produção de existir, retorna null se não exitir
        $ret = [];
        $produtos = [];
        $sql = new sqlOra();
        try{
            if($dados['lote'] != null){
            $produtos = $sql->select("SELECT distinct o.numlote lote, o.numop NUMOP, o.codprodmaster codprod, o.qtproduzir qt, l.qt qt_final,
                l.dtmxsalter dtapontamento, i.codproducao
                from kokar.pcopc o 
                left join kokar.pclote l 
                on l.numlote = o.numlote 
                and l.codprod = o.codprodmaster
                inner join kokar.pcprodut p on p.codprod = o.codprodmaster
                left join paralelo.mproducaoc c on c.lote = o.numlote
                left join paralelo.mproducaoi i on i.codproducao = c.codproducao
                where o.numlote = :lote and posicao not in 'C' and p.codepto = 10000 and c.dtexclusao is null", [":lote" => $dados['lote']]);
            }else{
                $produtos =  $sql->select("SELECT codproducao, codprod, qt, op
                from paralelo.mproducaoi i where i.codproducao = :codproducao",[":codproducao"=>$dados['codproducao']]);
            }

            }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
        if(sizeof($produtos)>0){
            foreach($produtos as $p){
                $m = new ModelLote();
                $m->codprod = $p['CODPROD'];
                if($p['QT_FINAL']!= '')
                    $m->qt = $p['QT'];
                else
                    $m->qt = $p['QT_FINAL'];
                $m->dtApontamento = $p['DTAPONTAMENTO'];
                $m->codProducao = $p['CODPRODUCAO'];
                $m->op = $p['NUMOP'];
                $m->lote = $p['LOTE'];
                
                $ret[] = $m;
            }
        }else{
            return null;
        }
        return $ret;
        
        
        if($ret[0]->codProducao == null){
            Mapa::novaProducao($ret[0]->codProducao, $dados['tanque'], $dados['dtprevisao'],
                $dados['hrprevisao'], $dados['codfun'], $ret[0]->lote);
            
                foreach($ret as $r){
                    Mapa::inserirItem($r->codProducao, $r->codprod, $r->qt, $dados['codfun'], $r->lote,  $r->op);
                }
            return 'cadastrado: '. $ret[0]->codProducao;
        }
        
        return null;
    }
    
    public static function inserirItems($codproducao, $produtos, $array){
        $sql = new sqlOra();
        $prodadd = 0;

        foreach($produtos['produtos'] as $p){
        
            if($p['codprod']>0 && $p['qt']>0){
                $sql->insert("INSERT INTO  paralelo.mproducaoi (codproducao, codprod, qt, codfun, dtinclusao, horainclusao, status, op) 
                values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'),:status, :op)"
                ,[":codproducao"=>$codproducao, ":codprod"=>$p['codprod'], ":qt"=>$p['qt'], ":codfun"=>$array["codfun"],":status"=> $array["status"], ":op"=>$p['op']]);
                $prodadd ++;
            }
        }

        return $prodadd;
    }

    public static function inserirItems2($codproducao, $produtos, $array){
        $sql = new sqlOra();
        $prodadd = 0;

        foreach($produtos['produtos'] as $p){
        
            if($p->codprod>0 && $p->qt>0){
                $sql->insert("INSERT INTO  paralelo.mproducaoi (codproducao, codprod, qt, codfun, dtinclusao, horainclusao, status, op) 
                values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'),:status, :op)"
                ,[":codproducao"=>$codproducao, ":codprod"=>$p->codprod, ":qt"=>$p->qt, ":codfun"=>$array["codfun"],":status"=> $array["status"], ":op"=>$p->op]);
                $prodadd ++;
            }
        }

        return $prodadd;
    }

    public static function inserirHead($codproducao, $array, $prodadd){
        $sql = new sqlOra();
        $ret = '';
        if($prodadd>0){
            if($array["status"]=='F'){
                $ret = $sql->insert("INSERT INTO  paralelo.mproducaoc 
        (codproducao, codtanque, dtabertura, status, codfunc, dtproducao, horaabertura, horaproducao, dtfecha, horafecha, lote, dtcadastro) 
        values(:codproducao, :codtanque, to_char(sysdate,'dd/mm/yyyy'), :status, :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'), :lote, to_char(sysdate,'dd/mm/yyyy'))"
        ,[":codproducao"=>$codproducao, ":codtanque"=>$array["codtanque"], ":status"=> $array["status"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"], ":lote"=>$array["lote"] ]);
            }else{
                $ret = $sql->insert("INSERT INTO  paralelo.mproducaoc 
            (codproducao, codtanque, dtabertura, status, codfunc, dtproducao, horaabertura, horaproducao, dtfecha, horafecha, lote, dtcadastro) 
            values(:codproducao, :codtanque, :dataini, :status, :codfun, :dtproducao, to_char(sysdate, 'hh24:mi:ss'), :horaproducao, '', '', :lote, to_char(sysdate,'dd/mm/yyyy'))"
            ,[":codproducao"=>$codproducao, ":codtanque"=>$array["codtanque"], ":dataini"=> $array["dataini"] ,":status"=> $array["status"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"], ":lote"=>$array["lote"] ]);
            }
            return $ret;
        }
        else
            return "erro inserirHead, produtos add: ".$prodadd;
    }

    public static function cadastrarNovo($array){
        $produtos = $array["produtos"];
        $sql = new sqlOra(); 
        $codproducao = $sql->select("SELECT max(codproducao)+1 cod from paralelo.mproducaoc")[0]["COD"];
        if($codproducao<=0)
            $codproducao=1;
        
        try{  
            $prodadd = ModelLote::inserirItems($codproducao, $produtos, $array);
            
            $ret = ModelLote::inserirHead($codproducao, $array, $prodadd);
            
        }catch(Exception $e){
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            }
            return $ret;
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
    
    public static function editar2($array){
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
            $deleted = $sql->insert("UPDATE PARALELO.MPRODUCAOI SET DTEXCLUSAO = to_char(sysdate) WHERE CODPRODUCAO = :codproducao", [":codproducao"=>$codproducao]);
            
            if($deleted > 0){
                foreach($produtos as $p):
                    for($i=0; $i<3; $i++):
                        if($p[$i]['codprod']>0 && $p[$i]['qt']>0){
                            $sql->insert("INSERT INTO  paralelo.mproducaoi (codproducao, codprod, qt, codfun, dtinclusao, horainclusao, status) 
                            values(:codproducao, :codprod, :qt, :codfun, to_char(sysdate,'dd/mm/yyyy'), to_char(sysdate, 'hh24:mi:ss'), :status)"
                            ,[":codproducao"=>$codproducao, ":codprod"=>$p[$i]['codprod'], ":qt"=>$p[$i]['qt'], ":codfun"=>$array["codfun"], ":status"=> $array["status"] ]);
                            //echo ("produto $i COD:".$p[$i]['cod']."QT:".$p[$i]['qt']);
                            $prodadd ++;
                        }
                    endfor;
                endforeach;
            }

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
                    dtfecha = :dtfecha,
                    lote = :lote
                    WHERE codproducao = :codproducao
                    ",[":codproducao"=>$codproducao,":status"=>$array["status"], ":codtanque"=>$array["codtanque"],
                     ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]),
                      ":horaproducao"=>$array["hrprevisao"], ":dtfecha" => $dtfecha, ":horafecha" => $hrfecha, ":lote"=>$array["lote"]]);
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
                        dtfecha = to_char(sysdate,'dd/mm/yyyy'),
                        lote = :lote
                        WHERE codproducao = :codproducao
                        ",[":codproducao"=>$codproducao,":status"=>$array["status"], ":codtanque"=>$array["codtanque"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"] , ":lote"=>$array["lote"]]);
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
                    dtfecha = '',
                    lote = :lote
                    WHERE codproducao = :codproducao
                    ",[":codproducao"=>$codproducao,":status"=>$array["status"], ":codtanque"=>$array["codtanque"], ":codfun"=>$array["codfun"], ":dtproducao"=>Formatador::formatador2($array["dtprevisao"]), ":horaproducao"=>$array["hrprevisao"] , ":lote"=>$array["lote"]]);
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

    public static function getItemLote($codproducao, $lote){
        $sql = new sqlOra();
        if($lote!=null){
            try{
                $ret = $sql->select("SELECT o.codprodmaster codprod, o.qtproduzir qt, l.qt qt_final, l.dtmxsalter dtapontamento
                from kokar.pcopc o 
                left join kokar.pclote l 
                on l.numlote = o.numlote 
                and l.codprod = o.codprodmaster
                inner join kokar.pcprodut p on p.codprod = o.codprodmaster
            where o.numlote = '$lote' and posicao not in 'C' and p.codepto = 10000", []);
            
            $item = [];
            if(sizeof($ret)>0):
                foreach($ret as $r):
                $m = new Mapa();
                $m->codprod = $r['CODPROD'];
                if($r['QT_FINAL']>0)
                    $m->qt = $r['QT_FINAL'];
                else
                    $m->qt = $r['QT'];
                $m->dtApontamento = $r['DTAPONTAMENTO'];
                array_push($item, $m); 
            endforeach; 
        endif;           
        return $item;

        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
            return;
        }
        }else{
            try{
                $ret = $sql->select("SELECT codproducao, codprod, qt, op
                from paralelo.mproducaoi i where i.codproducao = $codproducao", []);
            
            $item = [];
            if(sizeof($ret)>0):
                foreach($ret as $r):
                    $m = new Mapa();
                    $m->codproducao = $r['CODPRODUCAO'];
                    $m->codprod = $r['CODPROD'];
                    $m->qt = $r['QT'];
                    array_push($item, $m); 
                endforeach; 
            endif;           
            return $item;

        }catch(Exception $e){
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        }
        return "FAIL";
    }

    public static function getItem($codproducao, $lote){
        
        $sql = new sqlOra();
        try{
            $ret = $sql->select("SELECT o.codprodmaster codprod, o.qtproduzir qt, l.qt qt_final, l.dtmxsalter dtapontamento
            from kokar.pcopc o 
            left join kokar.pclote l 
            on l.numlote = o.numlote 
            and l.codprod = o.codprodmaster
            inner join kokar.pcprodut p on p.codprod = o.codprodmaster
        where o.numlote =   '$lote' and posicao not in 'C' and p.codepto = 10000", []);
        
        $item = [];
        if(sizeof($ret)>0):
            foreach($ret as $r):
                $m = new Mapa();
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

}