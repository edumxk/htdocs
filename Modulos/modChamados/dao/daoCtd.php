<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modChamados/model/despesa.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Modulos/modChamados/model/ctdDados.php');


class Ctd{

    public static function addDespesa($dados){
        $numrat = $dados['numrat'];
        $tipoDespesa = $dados['tipoDespesa'];
        $descricao = $dados['descricao'];
        $valor = $dados['valorDespesa'];

        $sql = new sqlOra();
        return $sql->insert('INSERT INTO RATCTDDESPESA (NUMRAT, CODTIPO, DESCRICAO, VALOR) 
                        VALUES (:numrat, :tipoDespesa, :descricao, :valor)', array(
            ':numrat' => $numrat,
            ':tipoDespesa' => utf8_decode($tipoDespesa),
            ':descricao' => utf8_decode($descricao),
            ':valor' => $valor
        ));
    }

    public static function getDespesas($numrat){
        $sql = new sqlOra();
        $ret = [];
        $dados = $sql->select('SELECT d.codtipo, t.descricao tipo, d.descricao, valor FROM paralelo.RATCTDDESPESA d
                            inner join paralelo.RATCTDTIPOdespesa t on d.codtipo = t.codtipo
                            WHERE NUMRAT = :numrat', array(
                            ':numrat' => $numrat
        ));
        foreach($dados as $d){
            array_push($ret, new Despesa($d));
        }
        return $ret;
    }

    public static function getTiposDespesa(){
        $sql = new sqlOra();
        $ret = [];
        $dados = $sql->select('SELECT * FROM RATCTDTIPODESPESA');
        foreach($dados as $d){
            array_push($ret, new Despesa($d));
        }
        return $ret;
    }

    public static function excluirDespesa($dados){
        $numrat = $dados['numrat'];
        $codtipo = $dados['codTipo'];
        $sql = new sqlOra();
        return $sql->delete('DELETE FROM RATCTDDESPESA WHERE NUMRAT = :numrat AND CODTIPO = :codtipo', array(
            ':numrat' => $numrat,
            ':codtipo' => $codtipo
        ));
    }

    public static function getNfe($dados){
        
        $numnota = $dados['numnota'];
        $codcli = $dados['codcli'];
        $sql = new sqlOra();

        $dados = $sql->select('SELECT n.numnota, n.dtsaida, g.destino, n.numcar, v.descricao veiculo, v.placa, e.nome_guerra motorista 
        from kokar.pcnfsaid n
        left join kokar.pccarreg g on g.numcar = n.numcar
        left join kokar.pcempr e on e.matricula = g.codmotorista
        left join kokar.pcveicul v on v.codveiculo = g.codveiculo
         where numnota = :numnota
         AND CODCLI = :codcli', array(
            ':numnota' => $numnota,
            ':codcli' => $codcli
        ));
        return $dados;
    }

    
    public static function getNfeDev($dados){
        $sql = new sqlOra();
        $numnota = $dados['numnota'];
        $codcli = $dados['codcli'];
        $dados = $sql->select('SELECT numnota numnotadev, codfornec codcli, obs, e.dtent, e.dtemissao dtemissaodev, e.vltotal, e.vlst, e.vlipi  
        from kokar.pcnfent e where e.numnota = :numnota and codfornec = :codcli', array(
            ':numnota' => $numnota,
            ':codcli' => $codcli
        ));
        return $dados;
    }

    public static function confirmar($dados){
        $numnota = $dados['numnota'];
        $nfedev = $dados['nfedev'];

        $codcli = $dados['codcli'];
        $numrat = $dados['numrat'];
        $acaocorretiva = $dados['acaocorretiva'];
        $sql = new sqlOra();
        
        return $sql->insert('UPDATE PARALELO.RATC SET NFEVENDA = :numnota, NFEDEV = :nfedev, ACAOCORRETIVA = :acaocorretiva
                        WHERE NUMRAT = :numrat AND CODCLI = :codcli
                        ', array(
            ':numrat' => $numrat,
            ':numnota' => $numnota,
            ':nfedev' => $nfedev,
            ':codcli' => $codcli,
            ':acaocorretiva' => utf8_decode($acaocorretiva)
        ));
    }

    public static function getDadosCTD($numrat){
        $ret = [];
        $numnota = 0;
        $numnotadev = 0;
        $acaocorretiva = '';
        $codcli = 0;
        $sql = new sqlOra();
        $dados = $sql->select('SELECT codcli, nfevenda, nfedev, acaocorretiva FROM PARALELO.RATC WHERE NUMRAT = :numrat', array(
            ':numrat' => $numrat
        ));

        if(count($dados) > 0){
            $numnota = $dados[0]['NFEVENDA'];
            $numnotadev = $dados[0]['NFEDEV'];
            $acaocorretiva = utf8_encode($dados[0]['ACAOCORRETIVA']);
            $codcli = $dados[0]['CODCLI'];
        }
        if($numnota != 0)
            try{
                $ret = $ret + Ctd::getNfe(['numnota' => $numnota, 'codcli' => $codcli])[0];
            }catch(Exception $e){
               // echo $e->getMessage();
                $ret = $ret + ["NUMNOTA" => null, "DTSAIDA" => null, "DESTINO" => null, "NUMCAR" => null, "VEICULO" => null, "PLACA" => null, "MOTORISTA" => null];
            }
          
        if($numnotadev != 0)
            try{
                $ret = $ret + Ctd::getNfeDev(['numnota' => $numnotadev, 'codcli' => $codcli])[0];
            }catch(Exception $e){
                //echo $e->getMessage();
                $ret = $ret + ["NUMNOTADEV" => null, "CODCLI" => null, "OBS" => null, "DTENT" => null, "DTEMISSAODEV" => null, "VLTOTAL" => null, "VLST" => null, "VLIPI" => null];
            }
       
        if($acaocorretiva != '')
            $ret = $ret + ["ACAOCORRETIVA" => $acaocorretiva];
        $ctd = new CtdDados($ret);
        return $ctd;
    }

    public static function novaCtd($dados){
        $sql = new sqlOra();
        $numctd = $dados['numctd'];
        $codcli = $dados['codcli'];
        $solicitante = iconv("UTF-8", "WINDOWS-1252",strtoupper($dados['solicitante']));
        $telSolicitante = $dados['telSolicitante'];
        $emailSolicitante = iconv("UTF-8", "WINDOWS-1252",strtoupper($dados['emailSolicitante']));
        $problema = iconv("UTF-8", "WINDOWS-1252",strtoupper($dados['problema']));
        $usuario = $dados['user'];

        try{
            $ret = $sql->insert("INSERT INTO PARALELO.ctdc (NUMCTD, CODCLI, CODUSUR, SOLICITANTE, TELSOLICITANTE, EMAILSOLICITANTE, MOTIVODESC, DTCADASTRO, status) 
            VALUES (:numctd, :codcli, :usuario, :solicitante, :telsolicitante, :email, :problema, to_char(sysdate,'dd/mm/yyyy HH24:mi:ss'), 0)", 
            array(
                ':numctd' => $numctd,
                ':codcli' => $codcli,
                ':usuario' => $usuario,
                ':solicitante' => $solicitante,
                ':telsolicitante' => $telSolicitante,
                ':email' => $emailSolicitante,
                ':problema' => $problema
            ));
        }catch(Exception $e){
            echo $e->getMessage();
            return 'deu pau';
        }

        return json_encode($ret);
    }

    public static function getNumctd(){
        $sql = new sqlOra();
        $dados = $sql->select('SELECT MAX(NUMCTD) NUMCTD FROM PARALELO.CTDC');
        if(isset($dados[0]['NUMCTD']))
            $dados = $dados[0]['NUMCTD'];
        else
            $dados = 0;
        return $dados +1;
    }


}