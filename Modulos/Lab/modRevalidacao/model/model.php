<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Revalidacao
{
    public static function revalidar($lista)
    {
        $sql = new SqlOra();
        $sql1 = new SqlOra();
        $arr = [];
        if(strpos($lista['numlote'],'A')>1){
            $arr = str_split($lista['numlote'], strpos($lista['numlote'], 'A'));
            $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev)
            values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote2, (SELECT DATAFABRICACAO FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2), (SELECT DTVALIDADE FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2), :tempo, :usuario, :numlote||'B', (SELECT TO_DATE(SYSDATE) FROM DUAL))", array(
                ":numlote" => $arr[0],
                ":numlote2" => $lista['numlote'],
                ":tempo" => $lista['tempo'],
                ":usuario" => $lista['usuario']   
            ));
        }else if(strpos($lista['numlote'],'B')>1){
            $arr = str_split($lista['numlote'], strpos($lista['numlote'], 'B'));
            $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev)
            values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote2, (SELECT DATAFABRICACAO FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2), (SELECT DTVALIDADE FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2), :tempo, :usuario, :numlote||'C', (SELECT TO_DATE(SYSDATE) FROM DUAL))", array(
                ":numlote" => $arr[0],
                ":numlote2" => $lista['numlote'],
                ":tempo" => $lista['tempo'],
                ":usuario" => $lista['usuario']  
            ));

        }else {
            $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev)
            values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote, (SELECT DATAFABRICACAO FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote), (SELECT DTVALIDADE FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote), :tempo, :usuario, :numlote||'A', (SELECT TO_DATE(SYSDATE) FROM DUAL))", array(   
                ":numlote" => $lista['numlote'],
                ":tempo" => $lista['tempo'],
                ":usuario" => $lista['usuario'] 
            ));
           
        }
        return $sql1->select("SELECT kokar.revalidarlote(:numlote, :tempo, (SELECT novonumlote from paralelo.lotehistorico where numlote = :numlote)) from dual",
        [":numlote" => $lista['numlote'], ":tempo" => $lista['tempo']]); 
    }

    public static function buscaLote($numlote)
    {
        $sql = new SqlOra(); 
            return $sql->select("SELECT l.numlote, l.codprod, p.descricao, l.datafabricacao fab, l.dtvalidade val, h.novonumlote, h.tempo, H.DTFAB, h.dtval, h.usuario, H.DTREV
            from kokar.pclote l 
                left join paralelo.lotehistorico h on h.novonumlote = l.numlote
                left join kokar.pcprodut p on p.codprod = l.codprod
                where l.numlote LIKE :numlote ", [":numlote" => $numlote]);
    }
}