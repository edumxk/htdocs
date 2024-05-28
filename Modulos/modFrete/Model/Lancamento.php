<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/model/SqlOracle.php';

class Lancamento
{
    public static function save(Array $dados)
    {
        $sql = new SqlOra();

        try{
            $inserts = $sql->insert("INSERT INTO PARALELO.FRETELANCAMENTOS (competencia, codmotorista, codtipo, valor, coduser)
            VALUES (:data, :motorista, :tipo, :valor, :coduser)", $dados);

        }catch(Exception $e){
            echo "Erro ao inserir Lançamento: ".$e->getMessage();
        }
        
        return $inserts;
    }

    public static function saveKm(Array $dados)
    {
        $sql = new SqlOra();

        try{
            $inserts = $sql->insert("INSERT INTO PARALELO.lancamentoskm (competencia, codveiculo, valor, coduser)
            VALUES (:data, :veiculos, :valor, :coduser)", $dados);

        }catch(Exception $e){
            echo "Erro ao inserir Lançamento: ".$e->getMessage();
        }
        
        return $inserts;
    }

    public static function getMotoristas(){
        $sql = new SqlOra();

        try{
            $motoristas = $sql->select("SELECT MATRICULA, NOME, ADMISSAO FROM KOKAR.PCEMPR E WHERE E.TIPOMOTORISTA = 'F' AND TIPO = 'M'
            AND E.RESCISAO IS NULL AND E.SITUACAO = 'A'
            ORDER BY NOME");
        }catch(Exception $e){
            echo "Erro ao buscar Motoristas: ".$e->getMessage();
        }

        return $motoristas;
    }

    public static function getTipoLancamentos(){
        $sql = new SqlOra();

        try{
            $tipos = $sql->select("SELECT * FROM paralelo.fretetipolanc WHERE IS_ATIVO = 1");
        }catch(Exception $e){
            echo "Erro ao buscar Tipos de Lançamentos: ".$e->getMessage();
        }

        return $tipos;
    }

    public static function getLancamentos($competencia){
        $sql = new SqlOra();

        try{
            $lancamentos = $sql->select("SELECT f.id, codmotorista, nome, v.codveiculo, v.placa, codtipo, 
            competencia, t.descricao, f.valor from PARALELO.FRETELANCAMENTOS f
            inner join kokar.pcempr e on e.matricula = f.codmotorista
            inner join paralelo.fretetipolanc t on t.id = f.codtipo
            left join kokar.pcveicul v on v.codveiculo = e.codveiculo
            WHERE extract (month from competencia) = extract (month from to_date(:competencia))
            and extract (year from competencia) = extract (year from to_date(:competencia))
            order by nome, codtipo", [":competencia"=>$competencia]);
        }catch(Exception $e){
            echo "Erro ao buscar Lançamentos: ".$e->getMessage();
        }
        return $lancamentos;
    }

    public static function delete($id){
        $sql = new SqlOra();

        try{
            $delete = $sql->delete("DELETE FROM PARALELO.FRETELANCAMENTOS WHERE ID = :id", [":id"=>$id]);
        }catch(Exception $e){
            echo "Erro ao deletar Lançamento: ".$e->getMessage();
        }
        return $delete;
    }

    public static function getLancamentosKm($competencia){
        $sql = new SqlOra();

        try{
            $lancamentos = $sql->select("SELECT f.id, f.codveiculo, v.descricao, v.marca, placa, competencia, f.valor from PARALELO.lancamentoskm f
            inner join kokar.pcveicul v on v.codveiculo = f.codveiculo
            WHERE extract (month from competencia) = extract (month from to_date(&competencia))
            and extract (year from competencia) = extract (year from to_date(&competencia))
            order by placa, competencia ", [":competencia"=>$competencia]);
        }catch(Exception $e){
            echo "Erro ao buscar Lançamentos: ".$e->getMessage();
        }
        return $lancamentos;
    }

    public static function deleteLancamentoKm($id){
        $sql = new SqlOra();

        try{
            $delete = $sql->delete("DELETE FROM PARALELO.lancamentoskm WHERE ID = :id", [":id"=>$id]);
        }catch(Exception $e){
            echo "Erro ao deletar Lançamento: ".$e->getMessage();
        }
        return $delete;
    }
}