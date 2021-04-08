<?php
//require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/controle/faltaControle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/falta.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/dao/daoFalta.php');

session_start();

//$falta = new Falta();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Nova Falta</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	</div>
</head>

<body style="background-color: teal;">
	<div class="header">
		<div class="row">
			<div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
				<img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
			</div>
			<div class="float-md-right">
				<div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
					Usuário: <?php echo $_SESSION['nome'] ?>
				</div>
				<div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
					Setor: <?php echo $_SESSION['setor'] ?>
				</div>
			</div>
		</div>
	</div>
	<div class="container" style="background-color: white;">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a href="home.php">Gerente Faltas</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Nova Falta</li>
			</ol>
		</nav>
		<div class="row" style="padding-bottom: 20px">
			<div class="col-md-12">
				<div class="page-header">
					<h1 style="padding-top: 20px">Gerência de Faltas
						<small> - Nova Falta</small>
					</h1>
				</div>
				<div class="row" style="text-align: center">
					<div class="col-md-12">
						<div id="secao" class="col-md-12" style="text-align: center">
							<div>Nova Falta</div>
						</div>
					</div>
					<div class="col-md-12">
					</div>
					<div class="col-md-12">
						<p>
						<p>
					</div>
					<div class="col-md-1">
						<div>
							<b>CódCli: </b>
						</div>
					</div>
					<div class="col-md-1">
						<div>
							<input id="codcli" type="number" class="form-control form-control-sm" tabindex="1">
						</div>
					</div>

					<div class="col-md-1">
						<div>
							<b>NFe Falta:</b>
						</div>
					</div>
					<div class="col-md-1">
						<div>
							<input style="text-align: left; width: 100px" id="numnota" type="number" class="form-control form-control-sm" tabindex="2">
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>NFe Custo:</b>
						</div>
					</div>
					<div class="col-md-1">
						<div>
							<input style="text-align: left; width: 100px" id="numnotacusto" type="number" class="form-control form-control-sm" tabindex="3">
						</div>
					</div>
					<div class="col-md-4">
						<div>
							Motorista:
							<select name="select1" id="select1" tabindex="4">>
								<optgroup id="motorista" label="Motorista Kokar">
									<option value="MARCO ANTONIO">Marco Antonio</option>
									<option value="LUIS">Luis</option>
									<option value="ANDRE">André</option>
									<option value="ROBSON">Robson</option>
									<option value="RENATO">Renato</option>
									<option value="ADILTON">Adilton</option>
									<option value="ISMAELDO">Ismaeldo</option>
									<option value="JHONATHAN">Jhonathan</option>
									<option value="EVANDRO">Evandro</option>
								</optgroup>
							</select>
						</div>
					</div>

					<div>
						<p>
					</div>
				</div>
				<div>
					<p>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Descrição do Problema</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px; text-align: center">
							<div>
								<input id="obs" type="text" class="form-control form-control-sm" tabindex="5">
							</div>
						</div>
					</div>
				</div>
				<div style="padding-left:20px">
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-5" style="padding-top: 10px; text-align: right">
							<button type="submit" class="btn btn-success"  tabindex="6" name="numrat" onclick="confirmar()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/jquery.redirect.js"></script>
	<script src="../../recursos/js/typeahead.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script>
		function confirmar(){
			var codcli =  $("#codcli").val();
			var numnota =  $("#numnota").val();
			var numnotacusto =  $("#numnotacusto").val();
			var motorista = $("#select1 option:selected").val();
			var obs =  $("#obs").val();
			var dados = {'codcli':codcli, 'numnota':numnota, 'numnotacusto':numnotacusto, 'motorista':motorista, 'obs':obs};
			//console.log(dados);
	
			$.ajax({
				type: 'POST',
				url: 'Controle/faltaControle.php',
				data: { 'action': 'incluirFaltaC', 'query': dados },
				success: function (response) {
					console.log(response);
					if(response=='Falta já existe! Verifique.')
						alert (response);
					else{
						alert ("Falta Incluida com Sucesso.");
				
			
			$.ajax({
				type: 'POST',
				url: 'editarfalta.php',
				data: { 'action': 'getnumfalta', 'query': dados['numnota']},
				success: function (response) {

					//console.log('editarfalta.php?numnota='+numnota);
					//$.redirect('editarfalta.php?numnota=256');
					//console.log('editarfalta.php?nurmat='+numrat);
					$(location).attr('href', 'editarfalta.php?numnota='+numnota);
				}
			});	
		}}
		});		
		}
</script>