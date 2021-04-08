<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'/modulos/modMultiplos/carreg.php');
session_start();

if(isset($_POST['action'])){
    if($_POST['action']=='desbloqueia'){
		$numcar = $_POST['query']['numcar'];
        echo Carregamento::desbloqueiaCar($numcar);
		
    }elseif($_POST['action']=='bloqueia'){
		$numcar = $_POST['query']['numcar'];
        echo Carregamento::bloqueiaCar($numcar);      
    }
}




	class Carregamento{

		function desbloqueiaCar($numcar){
			$sql = new SqlOra();
			$ret = "";
			$ret .= $sql->update("UPDATE kokar.pccarreg set numviasmapa = 0 where numcar = :numcar", array(":numcar"=>$numcar));
			return $ret;
		}

		function bloqueiaCar($numcar){
			$sql = new SqlOra();
			$ret = "";
			$ret .= $sql->update("UPDATE kokar.pccarreg set numviasmapa = 1 where numcar = :numcar", array(":numcar"=>$numcar)); 
			return $ret; 
		}

		public static function atualizaCar(){
			$sql = new SqlOra();
			$ret = "";
			$ret = $sql->select("SELECT nvl(c.numcar,0) as numcar
			from kokar.pccarreg c 
			where c.dtsaida > SYSDATE - 20
			and c.dtfat is NULL
			and (c.OBSFATUR not like '%faturado com sucesso.' or c.OBSFATUR is null OR c.OBSFATUR like '%faturado com sucesso.')
			and c.numnotas > 0");
			return $ret;
		}
	}

	?>