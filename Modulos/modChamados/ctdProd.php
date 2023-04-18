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
$rat = Chamado::getRat($rt);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>CTD</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/bootstrap4/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	</div>
</head>

<body style="background-color: teal;" onload="listaDespesas(); listaOpcoesDespesas()">
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
						<small> - Cadastro CTD</small>
					</h1>
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
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Descrição do Problema</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px; text-align:center">
						<h4><div><?php echo $rat->problema ?></div></h4>
						</div>
					</div>

					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Motivo da Reclamação</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px; text-align:center">
							<h4><div>PATOLOGIA: <?php echo $rat->ALab->patologia ?></div></h4>
							<textarea class="form-group" readonly rows="3" cols="100"><?= $rat->ALab->parecer ?> </textarea>
						</div>
					</div>


					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Origem da Reclamação</div>
						</div>
						<div class="col-md-12 form-control" style="margin: 10px; text-align:center; font-size:20px">
							<div class="form-check-inline" style="margin: 10px">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" name="optradio1" value="0">Logistica
								</label>
							</div>
							<div class="form-check-inline">
								<label class="form-check-label" style="margin: 10px">
									<input type="radio" class="form-check-input" name="optradio1" value="1">Comercial
								</label>
							</div>
							<div class="form-check-inline">
								<label class="form-check-label" style="margin: 10px">
									<input type="radio" checked class="form-check-input" name="optradio1"  value="2">Produto
								</label>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Informações da Venda</div>
						</div>
						<div class="col-md-12" style="margin: 10px; text-align:center;">
							<h4><div>NFe </div></h4>
							<input type="number" id="numnota" class="form-group" placeholder="Nº da Nfe Venda">
						</div>
						<div id="dadosnfe" style="font-size:12px">
						</div>
					</div>

					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">NFe Devolução</div>
						</div>
						<div class="col-md-12" style="margin: 10px; text-align:center;">
							<div class="form-check-inline" style="margin: 10px; font-size:20px">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" name="optradio" value="0">Sim
								</label>
							</div>
							<div class="form-check-inline">
								<label class="form-check-label" style="margin: 10px; font-size:20px">
									<input type="radio" class="form-check-input" name="optradio" checked value="1">Não
								</label>
							</div>
							<div >
								<input type="number" class="form-group" placeholder="Nº da NF-e de Devolução" id="nfedev" disabled>
							</div>
							<div id="dadosnfedev" style="font-size:12px">

							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Ação Corretiva</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px; text-align:center">
							<textarea class="form-group" rows="3" cols="100" id="acaocorretiva" placeholder="Insira a Tratativa"></textarea>
						</div>
					</div>

					<!-- Tabela de Despesas -->
					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Despesas</div>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px; text-align:center">
							<select class="form-group" name="despesa" id="tipodespesa" style="height:26px">
								<option value="0">Escolher</option>
							</select>
							<input class="form-group" type="text" id="descricaoDespesa" placeholder="Descrição da Despesa">
							<input class="form-group" type="number" min="0.00" max="10000.00" step="0.01" id="valorDespesa" placeholder="Valor da Despesa"/>
							<button class="btn btn-success" style="height: 26px; padding: 0 5px" onclick="addDespesa()">Adicionar</button>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px; text-align:center">
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Tipo</th>
										<th scope="col">Descrição</th>
										<th scope="col">Valor</th>
										<th scope="col">Ação</th>
									</tr>
								</thead>
								<tbody id="tabela-despesas">
								</tbody>
								<tfoot id="tabela-despesas-total">
								</tfoot>
							</table>
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
	//Ordena os produtos por ordem de código por padrão
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
	});
	//Preenche os produtos da RAT
	$(document).ready(function() {
		setTabela();
	});

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

	var numrat = "<?php echo $rat->numRat ?>";
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
					tabela += '</tr>'
				}
				$("#lista_json").append(tabela);
			}
		});
	}

	/* Função para Adicionar Ação Corretiva */
	function addAcao() {
		
		let acaoCorretiva = $("#acaocorretiva").text();

		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'setAcao',
				'query': {'numrat': numrat,
				'acaoCorretiva': acaoCorretiva},
			},
			success: function(response) {
				console.log(response);
			}
		});
	}
	
	/* Função para Adicionar Despesa */
	function addDespesa() {
		
		let tipoDespesa = $("#tipodespesa").val();
		let descricaoDespesa = document.getElementById("tipodespesa").options[document.getElementById("tipodespesa").selectedIndex].text;
		let descricao = $("#descricaoDespesa").val();
		let valorDespesa = $("#valorDespesa").val();
		let despesa = {
			'numrat': numrat,
			'tipoDespesa': tipoDespesa,
			'descricao': descricao,
			'valorDespesa': valorDespesa
		}

		$("#tipodespesa").val('');
		if (descricao == "" || valorDespesa == "") {
			alert("Preencha todos os campos!");
			return;
		}
		if(tipoDespesa == 0){
			alert("Selecione um tipo de despesa!");
			return;
		}

		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'addDespesa',
				'query': despesa,
			},
			success: function(response) {
				console.log(response);
				if(response.includes('ok')){
					alert("Despesa adicionada com sucesso!");
					console.log("Atualizando tabela de despesas...");
					listaDespesas();
					/* Zerar valores após incluir despesas */
					$("#descricaoDespesa").val('');
					$("#valorDespesa").val('');
					$("#tipodespesa").val(0);

				}else if(response.includes('unique constraint')){
					alert("Despesa já adicionada! Verifique.");
				}else if(response.includes('erro com valor')){
					alert("Valor inválido! Valor deve ser maior que 0 e menor que 10.000");
				}
				else
					alert("Erro ao adicionar despesa!");
			}
		});
	}

	/* Função para listar as Despesas do banco de dados */
	function listaDespesas(){
		let total = 0;
		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'getDespesas',
				'query': numrat,
			},
			success: function(response) {
				
				var arr = JSON.parse(response);

				var tabela = '';
				$("#tabela-despesas").empty();
				if(arr.length == 0)
					$("#tabela-despesas").append(`<tr>
						<td colspan="4" style="text-align: center">Nenhuma Despesa Adicionada</td>
					</tr>`);
				for (i = 0; i < arr.length; i++) {
					total += parseFloat(arr[i].valorCalc);
					tabela +=
					`<tr>
						<th scope="row">${arr[i].tipo}</th>
						<td>${arr[i].descricao}</td>
						<td>${arr[i].valor}</td>
						<td><button class="btn btn-danger" style="height: 26px; padding: 0 5px" onclick="excluirDespesa(${numrat, arr[i].codTipo})">Excluir</button></td>
					</tr>
				`;
				}
				$("#tabela-despesas").append(tabela);
				$("#tabela-despesas-total").empty();
				$("#tabela-despesas-total").append(
					`
					<tr>
						<th scope="row" colspan="2" ><h3>Total das Despesas</h3></th>
						
						<td><h4>${toReal(total)}</h4></td>
						<td></td>
					`
				);
			}
		});
	}

	function toReal(valor) {
		return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
	}

	/* Função para listar as Opções das Despesas do banco de dados */
	function listaOpcoesDespesas(){
		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'getOpcoesDespesas',
			},
			success: function(response) {
				var arr = JSON.parse(response);

				var tabela = '';
				$("#tipodespesa").empty();
				for (i = 0; i < arr.length; i++) {

					tabela +=
					`<option value="${arr[i].codTipo}">${arr[i].descricao}</option>
				`;
				}
				$("#tipodespesa").append(tabela); 
			}
		});
	}

	/* Função para excluir Despesa */
	function excluirDespesa(codTipo){
		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'excluirDespesa',
				'query': {'numrat': numrat,
				'codTipo': codTipo},
			},
			success: function(response) {
				if(response.includes('ok')){
					alert("Despesa excluída com sucesso!");
					listaDespesas();
				}else
					alert("Erro ao excluir despesa!");
			}
		});
	}
	
	/* Função para buscar nfe no banco de dados */
	function buscaNfe(){
		let numnota = $("#numnota").val();
		let codcli = <?= $rat->codCli ?>;

		if (numnota == "") {
			alert("Preencha o campo! Digite o número da nota!");
			return;
		}

		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'getNfe',
				'query': {'numnota': numnota,
				'codcli': codcli},
			},
			success: function(response) {
				console.log(response);
				if((response.sizeof) == 0 || response.includes('erro')){
					alert("NFE não encontrada!");
					$("#dadosnfe").empty();
					$("#dadosnfe").append(`
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Data Emissão</th>
								<th scope="col">Destino</th>
								<th scope="col">NFE</th>
								<th scope="col">Veiculo</th>
								<th scope="col">Placa</th>
								<th scope="col">Motorista</th>
							</tr>
						</thead>
						<tbody>
					<tr>
						<td colspan="6" style="text-align: center">Nenhuma NFE Encontrada</td>
					</tr>
					</tbody>`);
					return;
				}
				
				let arr = JSON.parse(response);
				//preencher tabela com dados da NFE
				
				$("#dadosnfe").empty();

				$("#dadosnfe").append(
					`
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Data Emissão</th>
								<th scope="col">Destino</th>
								<th scope="col">NFE</th>
								<th scope="col">Veiculo</th>
								<th scope="col">Placa</th>
								<th scope="col">Motorista</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>${arr.DTSAIDA}</td>
								<td>${arr.DESTINO}</td>
								<td>${arr.NUMNOTA}</td>
								<td>${arr.VEICULO}</td>
								<td>${arr.PLACA}</td>
								<td>${arr.MOTORISTA}</td>
							</tr>
						</tbody>
					</table>
					`
				);
			}
		});
	}

	function buscaNfeDev(){
		let numnota = $("#nfedev").val();
		let codcli = <?= $rat->codCli ?>;
		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'getNfeDev',
				'query': {'numnota' : numnota, 'codcli' : codcli},
			},
			success: function(response) {
				console.log(response);
				if((response.sizeof) == 0 || response.includes('erro')){
					alert("NFE não encontrada! Verifique se a NFE já foi lançada no sistema! Verifique se o cliente está correto!");
					//voltar o foco para nfedev
					$("#nfedev").focus();
					//limpar dadosnfedev
					$("#dadosnfedev").empty();
					$("#dadosnfedev").append(`
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Cliente</th>
								<th scope="col">Data Emissão</th>
								<th scope="col">Data Entrada</th>
								<th scope="col">NFE</th>
								<th scope="col">Valor Total</th>
								<th scope="col">Valor ST</th>
								<th scope="col">Valor IPI</th>
								<th scope="col">Observação</th>
							</tr>
						</thead>
						<tbody>
					<tr>
						<td colspan="8" style="text-align: center">Nenhuma NFE de Devolução Encontrada</td>
					</tr>
					</tbody>`);

					return;
				}
				let arr = JSON.parse(response);
				console.log(arr);
				
				//inserir tabela com dados da nfe na div dadosnfedev como uma tabela

				/* dados base
				CODCLI :  "12" DTEMISSAO :  "07/02/2023" DTENT :  "08/02/2023" NUMNOTA :  "98243" 
				OBS :  "REF DEV PARCIAL NF 43122" VLIPI :  "24.87" VLST :  "0" VLTOTAL :  "1937.77" */
				$("#dadosnfedev").empty();
				$("#dadosnfedev").append(
					`
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Cliente</th>
								<th scope="col">Data Emissão</th>
								<th scope="col">Data Entrada</th>
								<th scope="col">NFE</th>
								<th scope="col">Observação</th>
								<th scope="col">Valor IPI</th>
								<th scope="col">Valor ST</th>
								<th scope="col">Valor Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>${arr.CODCLI}</td>
								<td>${arr.DTEMISSAODEV}</td>
								<td>${arr.DTENT}</td>
								<td>${arr.NUMNOTADEV}</td>
								<td>${arr.OBS}</td>
								<td>${arr.VLIPI}</td>
								<td>${arr.VLST}</td>
								<td>${arr.VLTOTAL}</td>
							</tr>
						</tbody>
					</table>
					`
				);

				
			}
		});
	}

	function convertData(data){
		let partes = data.split("/");
		let novaData = partes[2] + "-" + partes[1].padStart(2, '0') + "-" + partes[0].padStart(2, '0');
		return novaData;
	}

	function marDev(){
		//pegar valor pelo nome do elemento optradio
		let optradio = document.getElementsByName('optradio');
		let dev = 2;
		for (let i = 0; i < optradio.length; i++) {
			if (optradio[i].checked) {
				dev = optradio[i].value;
			}
		}
		if(dev == 0){
			$("#nfedev").prop('disabled', false);
			$("#nfedev").prop('required', true);
			return;
		}else if(dev == 1){
			$("#nfedev").prop('disabled', true);
			$("#nfedev").prop('required', false);
			return;
		}
		
	}

	//carregar função ao iniciar a página e sempre carregar ao alterar opcão de devolução
	document.getElementsByName('optradio').forEach((item) => {
		item.addEventListener('change', marDev);
		
	});

	document.getElementById('nfedev').addEventListener('change', buscaNfeDev);
	//add evento na nfevenda
	document.getElementById('numnota').addEventListener('change', buscaNfe);


	function confirmar(){
		let numnota = $("#numnota").val();
		let nfedev = $("#nfedev").val();
		let codcli = <?= $rat->codCli ?>;
		let numrat = <?= $rat->numRat ?>;
		let acaocorretiva = $("#acaocorretiva").val();

		let optdev = document.getElementsByName('optradio');
		//validar se optdev está marcado SIM
		optdev.forEach((item) => {
			if(item.checked && item.value == 0){
				if(nfedev == "" || nfedev == isNaN){
					alert("Informe a NFE de devolução!");
					return;
				}
			}
		});

		if(numnota == "" || numnota == isNaN){
			alert("Informe a NFE de venda!");
			return;
		}

		//validar se alguma nfe foi encontrada
		if($("#dadosnfedev").text().includes("Nenhuma NFE Encontrada")){
			alert("Nenhuma NFE encontrada!");
			return;
		}

		if($("#dadosnfe").text().includes("Nenhuma NFE de Devolução Encontrada")){
			alert("Nenhuma NFE de Devolução Encontrada!");
			return;
		}

		//validar se ação corretiva foi preenchida
		if(acaocorretiva == ""){
			alert("Informe a ação corretiva!");
			return;
		}

		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'confirmar',
				'query': {'numnota' : numnota, 'nfedev' : nfedev, 'codcli' : codcli, 'numrat' : numrat, 'acaocorretiva' : acaocorretiva},
			},
			success: function(response) {
				console.log(response);
				if((response.sizeof) == 0 || response.includes('erro')){
					alert("Erro ao confirmar!");
					return;
				}
				alert("Confirmado com sucesso!");
				//window.location.href = "rat.php";
			}
		});
	}

	//ao carregar o documento buscar uma funcao ajax
	
	$(document).ready(function(){
		$.ajax({
			type: 'POST',
			url: 'Controle/ctdControl.php',
			data: {
				'action': 'getDadosCTD',
				'query':  <?= $rat->numRat ?>,
			},
			success: function(response) {
				let dados = JSON.parse(response);
				// preencher acaocorretiva
				$("#acaocorretiva").val(dados.acaocorretiva);
				//preencher nfevenda
				$("#numnota").val(dados.nfe);
				//preencher nfedev
				$("#nfedev").val(dados.nfedev);

				
				//chama funcao para buscar dados da nfe de venda
				if(dados.nfe != null){
					buscaNfe();
					console.log("chamou buscaNfe");
				}
				
				//chama funcao para buscar dados da nfe de devolucao
				if(dados.nfedev != null){
					document.getElementsByName('optradio')[0].checked = true;
					marDev();
					buscaNfeDev();
					console.log("chamou buscaNfeDev");
				}

			}
		});
	});
	
	


</script>

</html>