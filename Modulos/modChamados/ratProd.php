<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/controle/ratProdControle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/rat.php');

session_start();

if (!isset($_SESSION)) {
	if ($_SESSION == array())
		header("location: index.php");
}

if (isset($_POST['numrat'])) {
	$rt = $_POST['numrat'];
}
if (isset($_GET['numrat'])) {
	$rt = $_GET['numrat'];
}

$rat = new Chamado();
//$rat = $rat->getRat($rt);
$rat = Chamado::getRat($rt);
//echo json_encode($rat);

$clientes = $rat->getClientes();





?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>RAT | Produtos</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/bootstrap4/css/bootstrap.min.css" rel="stylesheet">
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
					<a href="listaRat.php">Lista de RAT's</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Produtos do Chamado</li>
			</ol>
		</nav>
		<div class="row" style="padding-bottom: 20px">
			<div class="col-md-12">
				<div class="page-header">
					<h1 style="padding-top: 20px">Gerência de Chamados
						<small> - Novo Chamado</small>
					</h1>
					<div class="col-md-12" style="text-align: center; margin:10px; display:flex; justify-content: space-around">
						<button class="btn btn-danger" onclick="cancelarRat()">CANCELAR RAT</button>
						<button class="btn btn-warning" onclick="$('#modal-cliente').modal('show')">ALTERAR CLIENTE</button>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div id="secao" class="col-md-12" style="text-align: center">
							<div>RAT nº <?php echo $rat->numRat ?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Data de Abertura:</b>
						</div>
					</div>
					<div class="col-md-5">
						<!-- <div id="date"></div> -->
						<div><?php echo $rat->dtAbertura ?></div>
					</div>
					<div class="col-md-2" style="padding-bottom: 20px">
						<div>
							<b>Data de Encerramento:</b>
						</div>
					</div>
					<div class="col-md-3">
						<div><?php echo $rat->dtEncerramento ?></div>
					</div>
					<div class="col-md-12">
						<div style="border-bottom: 1px solid gray;"></div>
					</div>

					<div class="col-md-1">
						<div>
							<b>COD: </b>
						</div>
					</div>
					<div class="col-md-1">
						<div><?php echo $rat->codCli ?></div>
					</div>

					<div class="col-md-1">
						<div>
							<b>Razão:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->cliente ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Fantasia:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->fantasia ?></div>
					</div>

					<div class="col-md-1">
						<div>
							<b>CNPJ:</b>
						</div>
					</div>
					<div class="col-md-2">
						<div><?php echo $rat->cnpj ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Cidade:</b>
						</div>
					</div>
					<div class="col-md-3">
						<div><?php echo $rat->cidade ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Estado:</b>
						</div>
					</div>
					<div class="col-md-1">
						<div><?php echo $rat->uf ?></div>
					</div>


					<div class="col-md-1">
						<div>
							<b>Telefone:</b>
						</div>
					</div>
					<div class="col-md-2" style="padding-bottom: 20px">
						<div><?php echo $rat->telCliente ?></div>
					</div>
					<div class="col-md-12">
						<div style="border-bottom: 1px solid gray;"></div>
					</div>

					<div class="col-md-2">
						<div>
							<b>Representante:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->rca ?></div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Tel. Representante:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->telRca ?></div>
					</div>

					<div>
						<p>
					</div>

					<div class="col-md-2">
						<div style="padding-top: 5px">
							<b>Solicitante:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 10px">
							<div><?php echo $rat->solicitante ?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div style="padding-top: 5px">
							<b>Tel. Solicitante:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 5px; padding-top: 5px">
							<div><?php echo $rat->telSolicitante ?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Cliente:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 10px">
							<div><?php echo $rat->pintor ?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Tel. Cliente:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 5px; padding-top: 5px">
							<div><?php echo $rat->telPintor ?></div>
						</div>
					</div>

				</div>

				<datalist id="clientes">
				<?php foreach($clientes as $c):?>
					<option value="<?php echo $c->codCli ?>"><?php echo $c->cliente ?></option>
				<?php endforeach;?>
				</datalist>
				




				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Descrição do Problema</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px">
							<div><?php echo $rat->problema ?></div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-12 secao" id="formulario">
							<div style="text-align: center;">Formulário de Cadastro</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px">
							<div style="text-align: center;"><button onclick="abrirForm()" class="btn btn-info">Ver Formulário</button> </div>
						</div>
					</div>
				</div>
				<!-------------------------------->
				<div>
					<p>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Produtos</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div class="row" style="padding-top: 10px; padding-bottom: 10px; font-weight: 700">


									<div class="col-md-12" style="text-align: center">
										<h5>Incluir Produto:</h5>
									</div>
									<div class="col-md-12">
										<div class="col-md-12" style=" padding-top:20px; padding-bottom:10px; text-align: center">
											<button type="submit" class="btn btn-warning" id="btnPorLote" onclick="btnPorLote()">Por Lote</button>
											<button type="submit" class="btn btn-primary" id="btnPorCodigo" onclick="btnPorCodigo()">Por Codigo</button>
											<button type="submit" class="btn btn-primary" id="btnPorNome" onclick="btnPorNome()">Por Nome</button>
										</div>
									</div>
									<div class="col-md-1">Lote:</div>
									<div class="col-md-1"></div>
									<div class="col-md-1">Cod.:</div>
									<div class="col-md-6">Produto:</div>
									<div class="col-md-1">Qt.:</div>
									<div class="col-md-2"></div>
								</div>

								<div class="row">
									<div class="col-md-1">
										<input id="prodlote" type="text" class="form-control form-control-sm" tabindex="1">
									</div>
									<div class="col-md-1">
										<button type="submit" class="btn btn-warning btn-sm" onclick="buscaprod()" tabindex="2">Buscar</button>
									</div>
									<div class="col-md-1">
										<input id="codprod" type="text" class="form-control form-control-sm " onfocusout="buscaprod()" tabindex="4" disabled>
									</div>

									<div class="col-md-6" id="the-basics">
										<input id="produtoJson" style="width: 300%;" type="text" class="form-control form-control-sm" tabindex="3" disabled>
									</div>
									<div class="col-md-1">
										<input id="qt" type="text" class="form-control form-control-sm" tabindex="5">
									</div>

									<div class="col-md-1">
										<button type="submit" class="btn btn-primary btn-sm" onclick="incluirProd()" tabindex="5">Incluir</button>
									</div>
								</div>

							</div>
							<div class="row" style="padding-top: 20px; padding-bottom: 20px">
								<div class="col-md-12">
									<!-- <table id="example" class="display" width="100%"></table> -->
									<table id="table_id" class="display compact" style="width:100%">
										<thead>
											<tr>
												<th scope="col" style="width: 5%">COD</th>
												<th scope="col">Produto</th>
												<th scope="col" style="width: 5%">QT</th>
												<th scope="col" style="width: 5%">Lote</th>
												<th scope="col" style="width: 10%">Fabricação</th>
												<th scope="col" style="width: 10%">Vencimento</th>
												<th scope="col" style="width: 10%">Vl.Total</th>
												<th scope="col" style="width: 2%">Ação</th>
											</tr>
										</thead>
										<tbody id="lista_json">

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>


				</div>
				<div style="padding-left:20px">
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-6" style="padding-top: 10px; text-align: right">
							<button type="submit" class="btn btn-success" name="numrat" onclick="confirmar()">Confirmar</button>
						</div>
					</div>
				</div>

				<!-------------------------------->

				<div style="height: 50px">
					<p>
				</div>


			</div>
		</div>
		<!-- Modal para alteração de cliente -->
		<div class="modal" id="modal-cliente" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Selecione Novo Cliente</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="modal-body-cli">
						<input id="clienteJson" list="clientes" type="text" class="form-control form-control-sm" onfocusout="buscaCliente()">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="alterarCliente()">Confirmar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal para seleção de produto do Lote -->
		<div class="modal" id="modal-prod" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Seleção de Produto</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="modal-body-prod">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="buscarEspecial(), buscaprod(),closeModal()">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal para formulario de cadastro -->
		<div class="modal" tabindex="-1" role="dialog" id="modal-form">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content" style="padding: 20px 50px">
					<div class="modal-header">
						<h2 class="modal-title">FORMULÁRIO DE ATENDIMENTO</h2>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span class="btn btn-danger" aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="font-size: 1.4rem;">
						<form id="modal-formulario-dados">
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">NOME</label>
								<input type="text" class="form-control" id="recipient-name" name="nome" placeholder="Nome Completo">
							</div>
							<div class="form-group">
								<label for="recipient-phone" class="col-form-label">TELEFONE</label>
								<input type="phone" class="form-control" id="recipient-phone" name="phone" placeholder="(63)99200-9999">
							</div>
							<div class="form-group">
								<label for="recipient-email" class="col-form-label">EMAIL</label>
								<input type="email" class="form-control" id="recipient-email" name="email" placeholder="seuemail@mail.com">
							</div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="salvarForm()">SALVAR FORMULÁRIO</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="header">

	</div>




	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/bootstrap4/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/jquery.redirect.js"></script>
	<script src="../../recursos/js/typeahead.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


</body>


<script>
	$(document).ready(function() {
		$('#table_id').DataTable({
			"bLengthChange": false,
			"bFilter": false,
			"bPaginate": false,
			"bInfo": false,

			"language": {
				"sEmptyTable": "Nenhum registro encontrado",
				"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de _MAX_ registros)",
				"sInfoPostFix": "",
				"sInfoThousands": ".",
				"sLengthMenu": "_MENU_ resultados por página",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhum registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Próximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "Último"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				}
			}
		});

		//abrir formulario
		abrirForm();
	});
</script>

<script>
	$(document).ready(function() {
		setTabela();
	});
</script>


<script>
	// Função para alterar Cliente
	function alterarCliente(){
		var numrat = "<?php echo $rat->numRat ?>";
		var codcli = $("#clienteJson").val();
		console.log(isNaN(codcli));
		if(codcli == "" || codcli == null || codcli == undefined || codcli == "undefined" || codcli == "null" || isNaN(codcli)){
				alert("Selecione um cliente!");
			return;
		}
		$.ajax({
			type: 'POST',
			url: 'Controle/ratControl.php',
			data: {
				'action': 'alterarCliente',
				'query': {'numrat' : numrat,
				'codcli': codcli }
			},
			success: function(response) {
				
				if (response == 1) {
					if(confirm("Cliente alterado com sucesso!")== true){
						location.reload();
					}else{
						location.reload();
					}
				} else {
					alert("Erro ao alterar cliente! \nErro: " + response);
				}
			}
		});
	
	}

	/*Função para preencher a tabela de produtos da RAT*/
	function setTabela() {
		var numrat = "<?php echo $rat->numRat ?>";
		$.ajax({
			type: 'POST',
			url: 'Controle/ratProdControle.php',
			data: {
				'action': 'getProdRat',
				'query': numrat
			},
			success: function(response) {
				//console.log(response);
				var arr = JSON.parse(response);

				var tabela = '';
				$("#lista_json").empty();
				for (i = 0; i < arr.length; i++) {

					tabela += '<tr>'
					tabela += '<th scope="row" style="widht:50px" id="cellCod">' + arr[i].codprod + '</th>'
					tabela += '<td id="tbProduto">' + arr[i].produto + '</td>'
					tabela += '<td>' + arr[i].qt + '</td>'
					tabela += '<td id="cellLote">' + arr[i].numlote + '</td>'
					tabela += '<td>' + arr[i].dtFabricacao + '</td>'
					tabela += '<td>' + arr[i].dtValidade + '</td>'
					tabela += '<td>' + arr[i].total.toLocaleString('pt-BR') + '</td>'
					tabela += '<td style="text-align: center;"><button type="button" class="btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="delProd(this)">x</button></td>'
					tabela += '</tr>'
				}
				$("#lista_json").append(tabela);
			}
		});
	}
</script>



<script>
	codprod = 0;

	function buscaProdutosLote() {
		var lote = $("#prodlote").val();
		var codcli = "<?php echo $rat->codCli ?>";
		var modal = $("#modal-body-prod");
		dataset = {
			'lote': lote,
			'codcli': codcli
		}
		$.ajax({
			type: 'POST',
			url: 'Controle/ratProdControle.php',
			data: {
				'action': 'produto2',
				'query': dataset
			},
			success: function(response) {
				let arr = JSON.parse(response);
				let body = '';
				console.log(arr)

				console.log('lote 2')
				$(modal).empty();

				arr.forEach(function(t) {
					body += `<option value='${t['codprod']}'> ${t['codprod'] +' - '+t['produto']} </option>`;
				})
				$(modal).append(`<select id='modal_produto'>${body}</select>`);
				$("#modal-prod").modal('toggle');
			}
		});

	}

	function buscarEspecial() {
		codprod = $("#modal_produto option:selected").val();
		console.log(codprod);
	}

	function closeModal() {
		$('#modal-prod').modal('toggle');
	}
	/*Função de Busca de Produto por Lote*/
	function buscaprod() {
		var lote = $("#prodlote").val();
		var produto = $("#produtoJson").val();
		var codcli = "<?php echo $rat->codCli ?>";
		if (codprod == 0)
			codprod = $("#codprod").val();

		//console.log(codcli);


		var dataset = {
			'lote': lote,
			'codcli': codcli,
			'codprod': codprod,
			'produto': produto
		};

		$.ajax({
			type: 'POST',
			url: 'Controle/ratProdControle.php',
			data: {
				'action': 'produto',
				'query': dataset
			},
			success: function(response) {
				//console.log(response);
				if (response == false) {
					alert("Selecione o produto!");
					$("#produtoJson").val("");
					$("#codprod").val("");

				} else {
					//console.log('ok')
					console.log(response);
					var arr = JSON.parse(response)

					$("#produtoJson").val(arr['produto']);
					$("#codprod").val(arr['codprod']);

				}
			}
		})
		if (lote != '' && codprod == '')
			buscaProdutosLote()
		codprod = 0;
	}
</script>

<script>
	/*Função de Inclusão*/
	function incluirProd() {

		var numrat = "<?php echo $rat->numRat ?>";
		var codcli = "<?php echo $rat->codCli ?>";
		var codprod = $("#codprod").val();
		var produto = $("#produtoJson").val();
		var numlote = $("#prodlote").val();
		var qt = $('#qt').val();

		if (qt > 0) {
			var dados = {
				'numrat': numrat,
				'codcli': codcli,
				'codprod': codprod,
				'produto': produto,
				'numlote': numlote,
				'qt': qt
			};
			//console.log(dados);
			$.ajax({
				type: 'POST',
				url: 'controle/ratProdControle.php',
				data: {
					'action': 'setProdRat',
					'query': dados
				},
				success: function(response) {
					//console.log(response);
					if (response.includes("existe")) {
						alert("Produto já existe na RAT");
					}
					if (response.includes("erro produto")) {
						alert("Produto inválido");
					} else {
						console.log(response);
						setTabela();
						limpa();
					}
				}
			});
		} else {
			alert('Quantidade não informada.');
		}


	}
</script>

<script>
	/*Função de Deleção*/
	function delProd(elm) {
		var result = confirm("Confirma exclusão do produto?");
		if (result) {
			var rat = "<?php echo $rat->numRat ?>";
			var cod = $(elm).parent().parent().children('#cellCod').text();
			var lote = $(elm).parent().parent().children('#cellLote').text();

			var dados = {
				'numrat': rat,
				'codprod': cod,
				'numlote': lote
			};

			$.ajax({
				type: 'POST',
				url: 'controle/ratProdControle.php',
				data: {
					'action': 'delProdRat',
					'query': dados
				},
				success: function(response) {
					console.log(response);
					if (response.includes("erro")) {
						console.log("erro");
					} else {
						//console.log(response);
						setTabela();
					}

				}
			});
		}
	}
</script>




<script>
	/*TypeAhead para busca de produtos*/
	var substringMatcher = function(strs) {
		return function findMatches(q, cb) {
			var matches, substringRegex;

			// an array that will be populated with substring matches
			matches = [];

			// regex used to determine if a string contains the substring `q`
			substrRegex = new RegExp(q, 'i');

			// iterate through the pool of strings and for any string that
			// contains the substring `q`, add it to the `matches` array
			$.each(strs, function(i, str) {
				if (substrRegex.test(str)) {
					matches.push(str);
				}
			});

			cb(matches);
		};
	};


	var states = [];


	$('#produtoJson').typeahead({
		hint: false,
		highlight: true,
		minLength: 1
	}, {
		name: 'states',
		source: substringMatcher(states)
	});
	$.ajax({
		type: 'POST',
		url: 'controle/ratProdControle.php',
		data: {
			'action': 'produtoJson',
			'query': ''
		},
		success: function(response) {
			var teste = [];//JSON.parse(response);
			var ret = [];
			for (i = 0; i < teste.length; i++) {
				states.push(teste[i]['DESCRICAO']);
			}


		}
	});
</script>

<script>
	function btnPorLote() {
		$("#btnPorLote").attr('class', 'btn btn-warning');
		$("#btnPorCodigo").attr('class', 'btn btn-primary');
		$("#btnPorNome").attr('class', 'btn btn-primary');

		$("#prodlote").attr('disabled', false);
		$("#codprod").attr('disabled', true);
		//$("#codprod").attr('onfocusout',null).off('focusout');
		$("#produtoJson").attr('disabled', true);


		limpa()
	}

	function btnPorCodigo() {
		$("#btnPorLote").attr('class', 'btn btn-primary');
		$("#btnPorCodigo").attr('class', 'btn btn-warning');
		$("#btnPorNome").attr('class', 'btn btn-primary');

		$("#prodlote").attr('disabled', true);
		$("#codprod").attr('disabled', false);
		$("#produtoJson").attr('disabled', true);

		limpa()
	}

	function btnPorNome() {
		$("#btnPorLote").attr('class', 'btn btn-primary');
		$("#btnPorCodigo").attr('class', 'btn btn-primary');
		$("#btnPorNome").attr('class', 'btn btn-warning');

		$("#prodlote").attr('disabled', true);
		$("#codprod").attr('disabled', true);
		$("#produtoJson").attr('disabled', false);

		limpa()
	}

	function limpa() {
		$("#prodlote").val('');
		$("#codprod").val('');
		$("#produtoJson").val('');
		$("#qt").val('');
	}
</script>

<script>
	function confirmar() {
		let numrat = '<?php echo $rat->numRat ?>';
		let patologia = $("#idp1").val();
		console.log(patologia);
		let c

		if(patologia == 1 || patologia == 12 || patologia == 36){
			if(confirm("Deseja finalizar a RAT por Troca de Etiqueta/Embalagem?")){
				c = true;
			}
		}
		if(c==true){
			let dataset = {
				'numrat': numrat,
				'patologia': patologia,
				'coduser': '<?php echo $_SESSION['coduser'] ?>'
			}
			$.ajax({
				type: 'POST',
				url: 'Controle/ratProdControle.php',
				data: {
					'action': 'finalizarProdEspecial',
					'query': dataset,
				},
				success: function(response) {
					alert('RAT finalizada com sucesso!')
					$.redirect('listaRat.php');
				}
			});
		}else{
			$.ajax({
				type: 'POST',
				url: 'Controle/ratProdControle.php',
				data: {
					'action': 'finalizarProd',
					'query': numrat,
				},
				success: function(response) {
					alert('Produto finalizado!')
					$.redirect('listaRat.php');
				}
			});
		}
	}

	function abrirForm() {
		let formulario = $("#modal-form")
		let numrat = '<?php echo $rat->numRat ?>';
		$.ajax({
			type: 'POST',
			url: 'Controle/ratProdControle.php',
			data: {
				'action': 'getForm',
				'query': numrat
			},
			success: function(response) {
				
				let form = JSON.parse(response);
				console.log(form);
				gerarFormulario(form)
				if(form.length == 3){
					$('#recipient-name').val(form[2]['NOME']);
					$('#recipient-phone').val(form[2]['TELEFONE']);
					$('#recipient-email').val(form[2]['EMAIL']);
				}
				$("#recipient-phone").keyup( function() {
					let numero = $(this).val();
					formatarTelefoneEmTempoReal(this);
				});
				formulario.modal('show');
			}
		});
	}
	var flag = 0; //flag para não repetir o formulario na funcao gerarFormulario.
	function gerarFormulario(dados){
		let formulario = $("#modal-formulario-dados");
		let html = formulario.html();
		if(flag == 1)
			return;

		for (let d in dados[0]) {
			html += 
			`<div class="form-group">
				<label for="idp${dados[0][d]['idPrincipal']}">${dados[0][d]['textoPrincipal']}</label>
				<select class="form-control" id="idp${dados[0][d]['idPrincipal']}">`;
			for (let i in  dados[1]) {
				if(dados[1][i]['idPrincipal'] == dados[0][d]['idPrincipal'])
					if(dados[1][i]['selected'] > 0)
						html += `<option selected value="${dados[1][i]['idOpcao']}">${dados[1][i]['textoOpcao']}</option>`;
					else
						html += `<option value="${dados[1][i]['idOpcao']}">${dados[1][i]['textoOpcao']}</option>`;
			}
			html +=	`</select>
			</div>
			`;
		}
		//console.log(dados[2]);
		
		flag = 1;
		formulario.html(html);
	}

	function formatarTelefoneEmTempoReal(input) {
		// Define um timeout para que a função só seja executada após o usuário digitar completamente o número
		let timeout = null;
		
		// Adiciona um event listener para o evento "input" da input
		input.addEventListener('input', () => {
			// Cancela o timeout anterior, se houver
			clearTimeout(timeout);

			// Define um novo timeout para executar a função de formatação após 500ms
			timeout = setTimeout(() => {
			// Remove todos os caracteres não numéricos do valor atual da input
			const valor = input.value.replace(/\D/g, '');

			// Formata o valor com parênteses e hífens
			const valorFormatado = valor.replace(/^(\d{2})(\d{4,5})(\d{4})$/, '($1) $2-$3');

			// Define o novo valor da input
			input.value = valorFormatado;
			}, 200);
		});
	}
	


	function salvarForm() {
		let formulario = $("#modal-formulario-dados");
		let html = formulario.html();
		let numrat = '<?php echo $rat->numRat ?>';
		let selecoes = [];
		let i = 0;
		let nome = $('#recipient-name').val();
		let telefone = $('#recipient-phone').val();
		let email = $('#recipient-email').val();

		$("#modal-formulario-dados select").each(function() {
			selecoes[i] = $(this).val();
			i++;
		});
		if(nome == '' || telefone == ''){
			alert('Preencha pelo o menos nome e telefone');
			return false;
		}
		//validar telefone celular válido


		if(telefone.length <= 11){
			alert('Telefone inválido, digite o DDD e o número');
			return false;
		}
		//validação de email valido
		if(email != ''){
			if(email.indexOf('@') == -1 || email.indexOf('.') == -1){
				alert('Email inválido');
				return false;
			}
		}
		
		if(nome.length > 60){
			alert('Nome inválido, máximo 60 caracteres');
			return false;
		}
		

		$.ajax({
			type: 'POST',
			url: 'Controle/ratProdControle.php',
			data: {
				'action': 'salvarForm',
				'query': {'numrat': numrat, 'nome': nome, 'telefone': telefone, 'email': email, 'selecoes': selecoes },
			},
			success: function(response) {
				console.log(response);
				alert("Formulario salvo com sucesso!");
				$('#modal-form').modal('hide');
			}
		});
	}

	function cancelarRat(){
		let numrat = '<?php echo $rat->numRat ?>';
		if(confirm("Deseja cancelar a RAT?")){
			//cancela a rat
			$.ajax({
				type: 'POST',
				url: 'Controle/ratControl.php',
				data: {
					'action': 'cancelarRat',
					'query': numrat
				},
				success: function(response) {
					//console.log(response);
					if(response == 1)
					if(confirm("RAT cancelada com sucesso!"))
						$.redirect('listaRat.php');
				}
			});
		}
	}

</script>


</html>