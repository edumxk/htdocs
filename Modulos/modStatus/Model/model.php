<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Model/SqlOracle.php');

class Model{
/*
    public $codProducao;
    public $codTanque;
    public $codLinha;
    public $status;
    public $codProd;
    public $descricao;
    public $capacidade;
    public $pliquido;
    public $lote;
    public $op;
    public $posicao;*/


    public static function getNotificacoes(){
        $sql = new SqlOra;
        $ret = $sql->select("SELECT c.codproducao, o.codprodmaster codprod, descricao, p.embalagem, p.codepto, c.codtanque,
        t.codlinha, t.capacidade,
        case 
          when qtproduzida is not null
            then qtproduzida
          when i.qt is null and qtproduzida is null
          then o.qtproduzir else i.qt end as qt,
        o.numlote, o.numop, c.status, o.posicao, c.dtabertura, c.horaabertura, c.dtproducao 
        from paralelo.mproducaoc c
        left join kokar.pcopc o on o.numlote = c.lote
        left join paralelo.mproducaoi i on i.codproducao = c.codproducao and i.codprod = o.codprodmaster  
        left join kokar.pcprodut p on p.codprod = o.codprodmaster
        inner join paralelo.mtanques t on t.codtanque = c.codtanque
        where c.dtexclusao is null
        and c.dtfecha is null
        and posicao not in 'C'
        and i.dtexclusao is null
        and p.codepto = 10001
        order by codlinha, numlote, codprod");
        $notificacoes = [];
        foreach($ret as $r){
            $p = new Model();
            $p->codProducao = $r['CODPRODUCAO']; 
            $p->codTanque = $r['CODTANQUE']; 
            $p->linha = $r['CODLINHA'];
            $p->abertura = $r['DTABERTURA'] . ' ' . $r['HORAABERTURA'];
            $p->codProd = $r['CODPROD']; 
            $p->descricao = str_replace('- SA','', (utf8_encode($r['DESCRICAO']))); 
            $p->pliquido = $r['QT']; 
            $p->lote = $r['NUMLOTE']; 
            $p->op = $r['NUMOP']; 
            $p->posicao = $r['POSICAO']; 

            switch ($r['STATUS']):
                case ('A'):
                    $p->status = "ABERTURA/OP";
                    break;
                case ('P'):
                    $p->status = "PESAGEM";
                    break;
                case ('D'):
                    $p->status = "DISPERSÃO / BASE";
                    break;
                case ('L'):
                    $p->status = "LABORATÓRIO";
                    break;
                case ('C'):
                    $p->status = "COR";
                    break;
                case ('E'):
                    $p->status = "ENVASE";
                    break;
                case ('B'):
                    $p->status = "CORREÇÃO";
                    break;
                case ('F'):
                    $p->status = "FINALIZADO";
                    break;
                case ('S'):
                    $p->status = "AGUARDANDO";
                    break;
            endswitch;
            $notificacoes[] = $p;
        }
        
        return $notificacoes;
    }

    public static function atualizaLog($codProducao){
        $sql = new SqlOra;
        $ret = $sql->select("SELECT * from paralelo.mproducaoc where status not in ('F') and dtexclusao is null");
        return $ret;
    }
    

    public static function getDadosByLote(Model $dados){
        $sql = new SqlOra;
        $ret = $sql->select("SELECT * from paralelo.mproducaoc where lote = :lote and dtexclusao is null", [ ':lote' => $dados->lote ]);
        if(sizeof($ret)>0):
            foreach($ret as $r){
                $p = new Model();
                $p->codProducao = $r['CODPRODUCAO']; 
                $p->codTanque = $r['CODTANQUE']; 
                $p->abertura = $r['DTABERTURA'] . ' ' . $r['HORAABERTURA'];
                $p->status = $r['STATUS']; 

                return $p;
            }
        else:
            return null;
        endif;
    }

    public static function getStatus($codProducao){
        $sql = new SqlOra;
        $ret = $sql->select("SELECT * FROM STATUS WHERE NUMSEQ = (SELECT MAX(NUMSEQ)NUMSEQ FROM STATUS where codproducao = :codproducao) AND codproducao = :codproducao", [ ':codproducao' => $codProducao ]);
        $p = new Model();
        if(sizeof($ret)>0){
            foreach($ret as $r){
               
                $p->codProducao = $r['CODPRODUCAO']; 
                $p->numSeq = $r['NUMSEQ']; 
                $p->inicio = $r['DTHRINI'];
                $p->fim = $r['DTHRFIM'];
                $p->status = $r['STATUS']; 
                
                if ($p->inicio == '' || $p->inicio == null )
                    $p->andamento = '0';
                else if( $p->fim == '' || $p->fim == null )
                    $p->andamento = '1';
                else
                    $p->andamento = '2';
                
                return $p;
            }
        }else{
            $p->codProducao = null;
            $p->andamento = '0';
            return $p;
        }
    }

    public static function getNewStatus($status){
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
        return $status;
    }

    public static function setFimStatus($dados){
        $sql = new SqlOra;

        $sql->update("UPDATE STATUS SET DTHRFIM =  to_char(sysdate, 'dd/mm/yyyy hh24:mi:ss') WHERE codproducao = :codproducao and numseq = :numseq"
        , [':codproducao' => $dados->codProducao, ':numseq' => $dados->numSeq]);

    }
    
    public static function insertNewStatus($banco, $status){
        $sql = new SqlOra;
        
        if($status->codProducao == null)
            return $sql->insert("INSERT INTO status( codProducao, status, dthrini, codfun, numseq, operador ) values( :codProducao, :status, to_char(sysdate, 'dd/mm/yyyy hh24:mi:ss'), :codfun, :numseq, :operador) ",
            [':codProducao' => $banco->codProducao, ':status' => $banco->status, ':codfun' => $banco->codfunc, ':numseq' => 0, ':operador' => $banco->operador] ).'inserir sem status cadastrado - nulo';
        else
        if($banco->andamento == 0){
            $status->numSeq =  $status->numSeq +1;
            return $sql->insert("INSERT INTO status( codProducao, status, dthrini, dthrfim, codfun, numseq, operador ) values( :codProducao, :status, :dthrini, :dthrfim, :codfun, :numseq, :operador) ",
            [':codProducao' => $banco->codProducao, ':status' => model::getNewStatus($banco->status), ':dthrini' => '', ':dthrfim' => '', ':codfun' => $banco->codfunc, ':numseq' => $status->numSeq, ':operador' => $banco->operador] ).'inserir novo andamento 0';
        }
        else if($status->andamento == 1){
            return $sql->update("UPDATE STATUS SET DTHRINI =  to_char(sysdate, 'dd/mm/yyyy hh24:mi:ss') WHERE codproducao = :codproducao and numseq = :numseq"
        , [':codproducao' => $banco->codProducao, ':numseq' => $status->numSeq]).'inserir andamento 1';
        }
        else return 'nao fez nada';

    }
   
    public static function cadastrarOperador($dados){ // Cadastro Funcional e Validado
        $sql = new SqlOra;
        $operador = $sql->select("SELECT id, nome FROM STATUSOPERADOR WHERE NOME LIKE UPPER(:NOME)", [':NOME'=> $dados->nome]);
        
        if( sizeof( $operador ) > 0 )
            return 'Operador já cadastrado, verifique!';
        
        $id = $sql->select("SELECT max(id) id from STATUSOPERADOR", []);
        
        if( ( $id[0]['ID'] ) == '' )
            $id = 0;
        else
            $id = $id[0]['ID'] + 1;

        return $sql->insert("INSERT INTO STATUSOPERADOR(ID, NOME, SETOR, DTCADASTRO, CODFUNALTER)
                            VALUES(:ID, upper(:NOME), :SETOR, to_char(sysdate, 'dd/mm/yyyy hh24:mi:ss'), :CODFUN)", 
                            [ ':ID' => $id, ':NOME' => $dados->nome, ':SETOR' => $dados->setor, ':CODFUN' => $dados->codFun ]);
        return 'Não entrou!';
    }

    public static function getOperadores(){
        $sql = new SqlOra;
        $dados = $sql->select("SELECT D.*, L.NOME NOMESETOR FROM STATUSOPERADOR d
                                inner join MPRODUCAOLINHAS L
                                ON L.ID = D.SETOR order by D.ID");
        $ret = [];
        foreach($dados as $d){
            $o = new Model();
            $o->id = $d['ID'];
            $o->nome = $d['NOME'];
            $o->codSetor = $d['SETOR'];
            $o->setor = $d['NOMESETOR'];
            $o->cadastro = $d['DTCADASTRO'];
            $ret[] = $o;
        }
        return $ret;
    }
}

class Status{

    public static function getStatusHistory($codProducao){
        $ret = model::getStatus($codProducao);
        return $ret;
    }

}

