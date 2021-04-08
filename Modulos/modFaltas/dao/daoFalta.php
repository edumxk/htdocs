<?php
//require_once("../../model/Sql.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');

class daoFalta{

    public static function getnumfalta()
    {
        $sql = new SqlOra();

        echo $sql->select("SELECT max(numfalta) AS numfalta from paralelo.faltac");
    }
    public static function getFalta($numnota)
    {
        $sql = new SqlOra();
        return $sql->select("SELECT c.numfalta, c.codcli, cl.cliente,cl.fantasia, cl.municcob ||' - '|| cl.estcob cidade,cl.cgcent cnpj,
        c.numnota,c.numnotacusto, pc.numped, u.codusur rca, u.nome nomerca, u.telefone1 telrca, cl.telcob telcli,
         i.codprod,p.descricao, i.qt, i.posicao, i.motivo, i.dtenvio, i.obs, i.tipocusto,
        c.motorista, c.obs, pc.data, pc.dtfat, pc.numcar, pc.obsentrega1, i.rowid
            from paralelo.faltac c 
                left join paralelo.faltai i on c.numnota = i.numnota
                inner join kokar.pcpedc pc on pc.numnota = c.numnota
                inner join kokar.pcclient cl on cl.codcli = c.codcli
                left join kokar.pcprodut p on p.codprod = i.codprod
                inner join kokar.pcusuari u on u.codusur = pc.codusur
       where c.numnota = :numnota", [':numnota' => $numnota]);
    }
    public static function incluirProd($numfalta, $codprod, $qt, $posicao, $motivo, $codcli ,$numnota, $tipocusto,  $dataf, $obs){
        $sql = new SqlOra();
        return $sql->select("SELECT  paralelo.incluir_faltai(:numfalta,:codprod,:qt,:posicao,:motivo,:codcli,:numnota,:tipocusto,:dataf,:obs) from dual",
            [':codcli' => $codcli,':numnota' => $numnota, ':tipocusto' => $tipocusto,':motivo' => $motivo,':obs' => $obs,
            ':numfalta' => $numfalta,':codprod' => $codprod,':qt' => $qt,':posicao' => $posicao, ':dataf' => $dataf
            ]); 
    }
    public static function delProd($id){
        $sql = new SqlOra();
        return $sql->select("SELECT paralelo.deletar_faltai(:id) as id from dual",
            [':id' => $id]);
    }
    public static function enviarProd($id){
        $sql = new SqlOra();
        return $sql->select("SELECT paralelo.enviar_faltai(:id) as id from dual",
            [':id' => $id]);
    }
    public static function incluirFaltaC($codcli, $numnota, $numnotacusto, $motorista, $obs ){
        $sql1 = new SqlOra();
        $sql = new SqlOra();
        $valid = $sql1->select("SELECT numfalta from paralelo.faltac where numnota = :numnota", [':numnota' => $numnota]);
        if($valid[0]['NUMFALTA']>0){
            return 'Falta já existe! Verifique.';
        }else
            return $sql->select("SELECT paralelo.nova_faltac(:codcli,:numnota,:numnotacusto,:motorista,:obs) from dual",
            [':codcli' => $codcli,':numnota' => $numnota, ':numnotacusto' => $numnotacusto,':motorista' => $motorista,':obs' => $obs]);  
        }
}                                         