<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Processos de Vendas</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">


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
				<li class="breadcrumb-item active" aria-current="page">Mudança de Praça</li>
			</ol>
		</nav>
		<div class="row" style="padding-bottom: 5px">


			<div class="col-md-12">
				<h2 style="padding-bottom: 5px; padding-top: 5px">Mudança de Praça</h2>
			</div>
		</div>

		<div class="row">


			<div class="col-md-12" style="padding-top: 20px">
				<div class="col-md-12" style="padding-bottom: 10px">
					<div class="row" style="padding-bottom: 10px">
						<div class="col-sm-2" style="padding-bottom:10px; border-bottom: solid 1px">
							<input class="form-control" type="text" id="numped" placeholder="Nº Pedido">
						</div>
						<div class="col-sm-10" style="border-bottom: solid 1px">
							<button type="submit" class="btn btn-primary" onclick="buscaPed()">Buscar</button>
						</div>

						<div class="col-md-1">
							<label>Cod.:</label>
							<input class="form-control" type="text" id="codcli" readonly>
						</div>

						<div class="col-md-6">
							<label>Cliente:</label>
							<input class="form-control" type="text" id="cliente" readonly>
						</div>

						<div class="col-md-2">
							<label>Valor:</label>
							<input class="form-control" type="text" id="valor" readonly>
						</div>
					</div>

					<div class="row" style="padding-bottom: 10px">
						<div class="col-md-3">
							<label>Cidade:</label>
							<input class="form-control" type="text" id="cidade" readonly>
						</div>
						<div class="col-md-3">
							<label>Praça Atual:</label>
							<input class="form-control" type="text" id="pracaAtual" readonly>
						</div>
						<div class="col-md-3">
							<label>Região Atual:</label>
							<input class="form-control" type="text" id="regiaoAtual" readonly>
						</div>
						<div class="col-md-5">
							<label>Nova Praça:</label>
							<input type="text" class="form-control" id="novaPraca">
						</div>
						<div class="col-md-1">
							<label>Ação: </label>
							<button type="submit" class="btn btn-success" onclick="abrir()">Alterar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="header">

	</div>

	<!-- INICIO MODAL -->
	<div class="modal" tabindex="-1" role="dialog" id="myModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Alteração de Praça</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Confirma Alteração de Praça?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- FIM MODAL -->


	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/Chart.bundle.min.js"></script>
	<script src="../../recursos/js/typeahead.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>



</body>

<script>
	function abrir() {
		var numped = $('#numped').val();
		var codcli = $('#codcli').val();
		var praca = $('#novaPraca').val();

		if (numped == "" || codcli == "" || praca == "") {
			alert("Dados incompletos")
		} else {
			$('#myModal').modal('toggle');
		}


	}
</script>



<!-- TIPEAHEAD -->
<script>
	/*TypeAhead para busca de produtos*/
	var substringMatcher = function (strs) {
		return function findMatches(q, cb) {
			var matches, substringRegex;

			// an array that will be populated with substring matches
			matches = [];

			// regex used to determine if a string contains the substring `q`
			substrRegex = new RegExp(q, 'i');

			// iterate through the pool of strings and for any string that
			// contains the substring `q`, add it to the `matches` array
			$.each(strs, function (i, str) {
				if (substrRegex.test(str)) {
					matches.push(str);
				}
			});

			cb(matches);
		};
	};


	var states = [];


	$('#novaPraca').typeahead({
		hint: false,
		highlight: true,
		minLength: 1
	},
		{
			name: 'states',
			source: substringMatcher(states)
		}
	);
	$.ajax({
		type: 'POST',
		url: 'controle/ajaxControl.php',
		data: { 'action': 'praca', 'query': '' },
		success: function (response) {
			console.log(response);

			var teste = JSON.parse(response);
			var ret = [];
			console.log(teste[0])
			for (i = 0; i < teste.length; i++) {
				states.push(teste[i]);
			}


		}
	});



</script>

<script>
	function buscaPed() {
		var numped = $('#numped').val();

		if (numped == "") {
			$('#codcli').val("");
			$('#cliente').val("");
			$('#valor').val("");
			$('#cidade').val("");
			$('#pracaAtual').val("");
			$('#regiaoAtual').val("");
			$('#novaPraca').val("");
		} else {
			$.ajax({
				type: 'POST',
				url: 'controle/ajaxControl.php',
				data: { 'action': 'pedidoPraca', 'query': numped },
				success: function (response) {
					console.log(response);
					var teste = JSON.parse(response);
					$('#codcli').val(teste['CODCLI']);
					$('#cliente').val(teste['CLIENTE']);
					$('#valor').val(teste['VLTOTAL']);
					$('#cidade').val(teste['MUNICCOB']);
					$('#pracaAtual').val(teste['PRACA']);
					$('#regiaoAtual').val(teste['REGIAO']);
				}
			});
		}
	}
</script>

<script>
	$('#myModal').on('click', '.btn-primary', function () {
		var numped = $('#numped').val();
		var codcli = $('#codcli').val();
		var praca = $('#novaPraca').val();

		var dados = { 'numped': numped, 'codcli': codcli, 'praca': praca };

		$.ajax({
			type: 'POST',
			url: 'controle/ajaxControl.php',
			data: { 'action': 'alteraPraca', 'query': dados },
			success: function (response) {
				console.log(response)
				if (response == "ok") {
					alert("Praça alterada com sucesso!");
					buscaPed();
				}
				if (response == "erro praca") {
					alert("Praça Inválida!");
				}
			}
		});

	});
</script>



</html>