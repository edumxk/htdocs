<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');

class Revalidacao
{
    public static function revalidar($lista)
    {
        $sql = new SqlOra();
        $ret = [];
        $num = 0;
        $string = $lista['numlote']; // string a ser analisada
        //chechar se existe letra no lote
        if(preg_match("/[a-zA-Z]/", $string, $match, PREG_OFFSET_CAPTURE)) {
            $pos = $match[0][1]; // posição da letra na string
            $letra = substr($string, $pos, 1); // extrai a letra da string
            $num = substr($string, 0, $pos); // extrai o número da string

            if(strpos($string, $letra)>1){

                $letra = chr(ord($letra) + 1);
                
                $ret[] = $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev, codprod)
                values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote2, 
                (SELECT max(DATAFABRICACAO) FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2 and codprod = :codprod), 
                (SELECT  max(DTVALIDADE) FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2 and codprod = :codprod)
                , :tempo, :usuario, :numlote,
                to_date(sysdate), :codprod)", array(
                    ":numlote" => $num.$letra,
                    ":numlote2" => $lista['numlote'],
                    ":tempo" => $lista['tempo'],
                    ":usuario" => $lista['usuario'],
                    ":codprod" => $lista['codprod']));
            }
            //echo json_encode(["numlote" => $num.$letra, "numlote2" => $lista['numlote'], "tempo" => $lista['tempo'], "usuario" => $lista['usuario']]);
        }else{
            
            $ret[] = $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev, codprod)
            values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote, (SELECT  max(DATAFABRICACAO) FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote and codprod = :codprod), 
            (SELECT max(DTVALIDADE) FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote and codprod = :codprod), :tempo, :usuario, :numlote||'A', (SELECT TO_DATE(SYSDATE) FROM DUAL), :codprod)", array(   
                ":numlote" => $lista['numlote'],
                ":tempo" => $lista['tempo'],
                ":usuario" => $lista['usuario'],
                ":codprod" => $lista['codprod']));
        }
        if($ret[0] == 'ok'){
            return $sql->select("SELECT kokar.revalidarlote(:numlote, :tempo, (SELECT max(novonumlote) from paralelo.lotehistorico where numlote = :numlote and codprod = :codprod)) from dual",
            [":numlote" =>  $lista['numlote'], ":tempo" => $lista['tempo'], ":codprod" => $lista['codprod']]);
        }else{
            return 'Algo deu errado';
        }
        
    }

    public static function buscaLote($numlote)
    {
        $sql = new SqlOra(); 
            return $sql->select("SELECT l.numlote, l.codprod, p.descricao, l.datafabricacao fab, l.dtvalidade val, h.novonumlote,
              tempo
             , H.DTFAB, h.dtval, h.usuario, H.DTREV
            from kokar.pclote l 
                left join paralelo.lotehistorico h on h.novonumlote = l.numlote --and h.codprod = l.codprod
                left join kokar.pcprodut p on p.codprod = l.codprod
                where l.numlote LIKE :numlote 
                order by l.codprod, H.novonumlote, l.numlote", [":numlote" => $numlote]);
    }
}