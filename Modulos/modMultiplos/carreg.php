<?php

	require_once ($_SERVER["DOCUMENT_ROOT"].'/model/sqlOracle.php');
	require_once ($_SERVER["DOCUMENT_ROOT"].'/modulos/modMultiplos/model/carregamento.php');

	header('Content-Type: text/html; charset=UTF-8');
	$numcar = array();
	$numcar = Carregamento::atualizaCar();
	
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Mapa</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</head>

<body style="background-color: teal;">
	<div class="header">
		<div class="row">
			<div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
				<img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
			</div>
				<div class="col-md-2" style="top: 25px; font-weight: 700; color: white"></div>
		</div>
	</div>
	<div class="container" style="background-color: white; border-style: solid; border-width: 1px">

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Ajuste Carregamento</li>
			</ol>
		</nav>
		<div class="row" style="padding-bottom: 5px">

			<div class="col-md-3" >
				<h2 style="padding-bottom: 5px; padding-top: 5px">Mapa Carregamento</h2>
			</div>
		</div>
		<div class="row" style="padding-bottom:20px">
			<div id="loading" class="col-12">
				<div class="d-flex justify-content-center">
					<div class="spinner-border text-danger " role="status"  style="width: 5rem; height: 5rem">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
			</div>
			<div class="col" >
				<div class="d-flex justify-content-center">
					<div class="col-md-1" ><h4>Selecione</div>
					<div class="col-md-2" >
						<select id="selectCarreg" style="width: 100%">
							<option value="" selected>Carregamento</option>
							<?php foreach($numcar as $c):?>
								<option value="<?php echo($c['NUMCAR'])?>"><?php echo($c['NUMCAR'])?></option>
							<?php endforeach?>
						</select>
					</div>
					<div class="col-md-3">
						<button type="button" 
							onclick="desbloqueia()"
							class="btn btn-warning" 
							style="width:200px; border-radius: 5px; color: black; font-weight: 700;">Desbloquear Carregamento</button>
					</div>
					<div class="col-md-3">
						<button type="button" 
							onclick="bloqueia()"
							class="btn btn-success" 
							style="width:200px; border-radius: 5px; color: black; font-weight: 700;">Bloquear Carregamento</button>	
					</div>
				</div>
			</div>
		</div>

	</div>
	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script>
	
		$(document).ready(function () {
			$('#loading').hide();
		});

		//Ajustar Carregamento
		function desbloqueia(){
			$('#loading').toggle();
			$('.btn').toggle();
		   	numcar = $("#selectCarreg option:selected").val();
			dataset = {"numcar" : numcar};
			console.log(dataset);
		
			$.ajax({
				type: 'POST',
				url: 'model/Carregamento.php',
				data: { 'action': 'desbloqueia', 'query': dataset},
				success: function (response) {
					console.log(response);
					$('#loading').toggle();
					$('.btn').toggle();
					alert("Carregamento liberado para edição! Numcar: "+ numcar)
				}
			});
		}

		function bloqueia(){
			$('#loading').toggle();
			$('.btn').toggle();
			numcar = $("#selectCarreg option:selected").val();
			dataset = {"numcar" : numcar};
			console.log(dataset);
			$.ajax({
				type: 'POST',
				url: 'model/Carregamento.php',
				data: { 'action': 'bloqueia', 'query': dataset },
				success: function (response) {
					console.log(response);
					$('#loading').toggle();
					$('.btn').toggle();
					alert("Carregamento bloqueado para edição! Numcar: " + numcar)
				}
			});
		}
	

	</script>

</body>
</html>

