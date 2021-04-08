<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Múltiplos de Produtos</title>

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
			<div class="col-md-2" style="top: 25px; font-weight: 700; color: white">
				<!-- Usuário: Teste -->
			</div>
		</div>
	</div>


	<div class="container" style="background-color: white; border-style: solid; border-width: 1px">

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Múltiplos</li>
			</ol>
		</nav>

		<div class="row" style="padding-bottom: 5px">

			<div class="col-md-12">
				<h2 style="padding-bottom: 5px; padding-top: 5px">Múltiplos</h2>
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
			<div class="col">
				<div class="d-flex justify-content-center">
					<button type="button" 
						onclick="ajustaMultiplos()"
						class="btn btn-success" 
						style="width:200px; border-radius: 5px; color: black; font-weight: 700;">AJUSTAR MULTIPLOS</button>
				</div>
			</div>
			<div class="col">
				<div class="d-flex justify-content-center">
					<button type="button" 
						onclick="fragmentaMultiplos()"
						class="btn btn-warning" 
						style="width:200px; border-radius: 5px; color: black; font-weight: 700;">FRAGMENTAR MULTIPLOS</button>
				</div>
			</div>
			
		</div>

	</div>


	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>

	
	<script>
		$(document).ready(function () {
			$('#loading').hide();
		});


		//Ajustar Multiplos
		function ajustaMultiplos(){
			$('#loading').toggle();
			$('.btn').toggle();

			$.ajax({
				type: 'POST',
				url: 'model/multiplos.php',
				data: { 'action': 'ajusta'},
				success: function (response) {
					console.log(response);
					$('#loading').toggle();
					$('.btn').toggle();
					alert("Muliplos ajustados!")
				}
			});
		}


		function fragmentaMultiplos(){
			$('#loading').toggle();
			$('.btn').toggle();

			$.ajax({
				type: 'POST',
				url: 'model/multiplos.php',
				data: { 'action': 'fragmenta'},
				success: function (response) {
					console.log(response);
					$('#loading').toggle();
					$('.btn').toggle();
					alert("Muliplos fragmentados!")
				}
			});
		}
	</script>
	

</body>


</html>