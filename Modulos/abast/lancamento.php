<!DOCTYPE html>
<html lang="pt-br">
<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once 'controleAbast.php';
$lista = [];

?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<title>Abastecimento</title>

	<meta name="description" content="Lançamento de requisições de abastecimento">
	<meta name="author" content="Eduardo Patrick">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">

</head>

<body style="background-color: teal;width:100%">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">
				<a href="../../home.php">Home</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
                <a href="../../homea.php">Controle Almoxarifado</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Ajuste de Abastecimento</li>
		</ol>
	</nav>
<div id="abastecimento" class="col-md-12" style="padding-bottom: 20px; background-color: white; border:2px solid black">
    <div class="row" style='padding-top: 20px'>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h2>Número Requerimento:</h2>
        </div>
        <div class="col-md-1">
            <input type="number" id="numreq" autocomplete="off"><br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h2>Estoque Físico:</h2>
        </div>
        <div class="col-md-1">
            <input type="number" id="estfisico" autocomplete="off"><br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h2>Qtd Abastecida:</h2>
        </div>
        <div class="col-md-1">
            <input type="number" id="qtdabast" autocomplete="off"><br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h2>Estoque Final:</h2>
        </div>
        <div class="col-md-1">
            <input type="number" id="estfinal" autocomplete="off"><br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h2>Responsável :</h2>
        </div>
        <div class="col-md-1">
        <select name="Solicitante" id="solicitante">
                    <optgroup value="abastecimento" id="abastecimento" label="Responsável pelo Abastecimento">
                        <option value="EVERALDO">Everaldo</option>
                        <option value="VINICIUS">Vinicius</option>
                        <option value="WALLYSON">Wallyson</option>
                        <option value="GERFESON">Gerfeson</option>
                        <option value="DANILLO">Danillo</option>
                    </optgroup>
        </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h2>Data/hora :</h2>
        </div>
        <div class="col-md-1">
            <input type="date" id="dataabast" >
        </div>
        <div class="col-md-1">
            <input type="time" id="horaabast" ><br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-1">
            <button style="width:178px" type="submit" class="btn btn-sm btn-success" onclick="atualizarAbast()">
                        <b>Confirmar Lançamento</b>
            </button>
        </div> 
    </div>
</div>
</body>
        <script src="../../recursos/js/jquery.min.js"></script>
		<script src="../../recursos/js/bootstrap.min.js"></script>
		<script src="../../recursos/js/scripts.js"></script>
		<script src="../../recursos/js/Chart.bundle.min.js"></script>
		<script src="../../recursos/js/jquery.tablesorter.js"></script>
        <script src="../../recursos/js/jquery-ui-1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>
   
    function atualizarAbast()
    {
        numreq = document.getElementById('numreq').value;
        estfisico = document.getElementById('estfisico').value;
        qtdabast = document.getElementById('qtdabast').value;
        estfinal = document.getElementById('estfinal').value;
        dataabast = document.getElementById('dataabast').value;
        respabast = $("#solicitante option:selected").val();
        horaabast = document.getElementById('horaabast').value;
        dataset = { "numreq": numreq, "estfisico": estfisico, "qtdabast": qtdabast,
         "estfinal": estfinal, "dataabast": dataabast, "respabast": respabast,
         "horaabast": horaabast
          };
		console.log(dataset);
		$.ajax({
			type: 'POST',
			url: 'controleAbast.php',
			data: { 'action': 'atualizaAbast', 'query': dataset },
			success: function (response) {
				console.log(response);
				if (response.match("ok")) {
                alert("Requisição n° " + numreq +" Atualizada!  =>  Imprimir na Rotina 8118!!!");
                location.reload();

				}
			}
		});
	}

</script>

</html>
