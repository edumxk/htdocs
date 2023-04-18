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
                
                $ret[] = $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev)
                values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote2, 
                (SELECT DATAFABRICACAO FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2), 
                (SELECT DTVALIDADE FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote2)
                , :tempo, :usuario, :numlote,
                to_date(sysdate))", array(
                    ":numlote" => $num.$letra,
                    ":numlote2" => $lista['numlote'],
                    ":tempo" => $lista['tempo'],
                    ":usuario" => $lista['usuario']));
            }
            //echo json_encode(["numlote" => $num.$letra, "numlote2" => $lista['numlote'], "tempo" => $lista['tempo'], "usuario" => $lista['usuario']]);
        }else{
            
            $ret[] = $sql->insert("INSERT into paralelo.lotehistorico (codrev, numlote, dtfab, dtval, tempo, usuario, novonumlote, dtrev)
            values ((SELECT MAX(CODREV)+1 FROM PARALELO.LOTEHISTORICO), :numlote, (SELECT DATAFABRICACAO FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote), 
            (SELECT DTVALIDADE FROM KOKAR.PCLOTE WHERE NUMLOTE = :numlote), :tempo, :usuario, :numlote||'A', (SELECT TO_DATE(SYSDATE) FROM DUAL))", array(   
                ":numlote" => $lista['numlote'],
                ":tempo" => $lista['tempo'],
                ":usuario" => $lista['usuario']));
        }
        if($ret[0] == 'ok'){
            return $sql->select("SELECT kokar.revalidarlote(:numlote, :tempo, (SELECT novonumlote from paralelo.lotehistorico where numlote = :numlote)) from dual",
            [":numlote" =>  $lista['numlote'], ":tempo" => $lista['tempo']]);
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
                left join paralelo.lotehistorico h on h.novonumlote = l.numlote
                left join kokar.pcprodut p on p.codprod = l.codprod
                where l.numlote LIKE :numlote 
                order by H.novonumlote, l.numlote", [":numlote" => $numlote]);
    }
}