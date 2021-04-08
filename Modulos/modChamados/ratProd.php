<?php
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/controle/ratProdControle.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/model/rat.php');

	session_start();

	
	if(isset($_POST['numrat'])){
		$rt = $_POST['numrat'];
	}
	if(isset($_GET['numrat'])){
		$rt = $_GET['numrat'];
	}

	$rat = new Chamado();
	//$rat = $rat->getRat($rt);
	$rat = Chamado::getRat($rt);
	//echo json_encode($rat);




  
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Nova RAT</title>

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
                    Setor: <?php echo $_SESSION['setor']?>
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
				</div>

				<div class="row">
					<div class="col-md-12">
						<div id="secao" class="col-md-12" style="text-align: center">
							<div>RAT nº <?php echo $rat->numRat?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Data de Abertura:</b>
						</div>
					</div>
					<div class="col-md-5">
						<!-- <div id="date"></div> -->
						<div><?php echo $rat->dtAbertura?></div>
					</div>
					<div class="col-md-2" style="padding-bottom: 20px">
						<div>
							<b>Data de Encerramento:</b>
						</div>
					</div>
					<div class="col-md-3">
						<div><?php echo $rat->dtEncerramento?></div>
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
						<div><?php echo $rat->codCli?></div>
					</div>

					<div class="col-md-1">
						<div>
							<b>Razão:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->cliente?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Fantasia:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->fantasia?></div>
					</div>

					<div class="col-md-1">
						<div>
							<b>CNPJ:</b>
						</div>
					</div>
					<div class="col-md-2">
						<div><?php echo $rat->cnpj?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Cidade:</b>
						</div>
					</div>
					<div class="col-md-3">
						<div><?php echo $rat->cidade?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Estado:</b>
						</div>
					</div>
					<div class="col-md-1">
						<div><?php echo $rat->uf?></div>
					</div>
					

					<div class="col-md-1">
						<div>
							<b>Telefone:</b>
						</div>
					</div>
					<div class="col-md-2" style="padding-bottom: 20px">
						<div><?php echo $rat->telCliente?></div>
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
						<div><?php echo $rat->rca?></div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Tel. Representante:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div><?php echo $rat->telRca?></div>
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
							<div><?php echo $rat->solicitante?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div style="padding-top: 5px">
							<b>Tel. Solicitante:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 5px; padding-top: 5px">
						<div><?php echo $rat->telSolicitante?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Pintor:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 10px">
						<div><?php echo $rat->pintor?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Tel. Pintor:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 5px; padding-top: 5px">
						<div><?php echo $rat->telPintor?></div>
						</div>
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
						<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px">
							<div><?php echo $rat->problema?></div>
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
										<input id="prodlote" type="text" class="form-control form-control-sm"  tabindex="1">
									</div>
									<div class="col-md-1">
										<button type="submit" class="btn btn-warning btn-sm" onclick="buscaprod()"  tabindex="2">Buscar</button>
									</div>
									<div class="col-md-1">
										<input id="codprod" type="text" class="form-control form-control-sm "onfocusout="buscaprod()"  tabindex="4" disabled>
									</div>

									<div class="col-md-6" id="the-basics">
										<input id="produtoJson" style="width: 300%;"type="text" class="form-control form-control-sm"  tabindex="3" disabled>
									</div>
									<div class="col-md-1">
										<input id="qt"type="text" class="form-control form-control-sm"  tabindex="5">
									</div>

									<div class="col-md-1">
										<button type="submit" class="btn btn-primary btn-sm" onclick="incluirProd()"  tabindex="5">Incluir</button>
									</div>
								</div>

							</div>
							<div class="row" style="padding-top: 20px; padding-bottom: 20px">
								<div class="col-md-12">
									<!-- <table id="example" class="display" width="100%"></table> -->
									<table id="table_id" class="display compact" style="widht:100%" >
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

	</div>

	<div class="header">

	</div>




	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/jquery.redirect.js"></script>
	<script src="../../recursos/js/typeahead.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


</body>


	<script>
		$(document).ready(function () {
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
	</script>

<script>
$(document).ready(function () {
	setTabela();
});
</script>


<script>
/*Função para preencher a tabela de produtos da RAT*/
function setTabela(){
	var numrat = "<?php echo $rat->numRat?>";
	$.ajax({
		type: 'POST',
		url: 'Controle/ratProdControle.php',
		data: {'action': 'getProdRat', 'query':numrat},
		success: function(response){
				console.log(response);
				var arr = JSON.parse(response);
				
				var tabela = '';
				$("#lista_json").empty();
				for (i = 0; i<arr.length; i++){

					tabela+= '<tr>'
					tabela+= '<th scope="row" style="widht:50px" id="cellCod">'+arr[i].codprod+'</th>'
					tabela+= '<td id="tbProduto">'+arr[i].produto+'</td>'
					tabela+= '<td>'+arr[i].qt+'</td>'
					tabela+= '<td id="cellLote">'+arr[i].numlote+'</td>'
					tabela+= '<td>'+arr[i].dtFabricacao+'</td>'
					tabela+= '<td>'+arr[i].dtValidade+'</td>'
					tabela+= '<td>'+arr[i].total.toLocaleString('pt-BR')+'</td>'
					tabela+= '<td style="text-align: center;"><button type="button" class="btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="delProd(this)">x</button></td>'
					tabela+= '</tr>'
				}
				$("#lista_json").append(tabela);
		}
	});
}
</script>



<script>
/*Função de Busca de Produto por Lote*/
function buscaprod(){
	var lote = $("#prodlote").val(); 
	var codprod = $("#codprod").val();
	var produto =  $("#produtoJson").val();
	var codcli = "<?php echo $rat->codCli?>";
	//console.log(codcli);

	var dataset = {'lote':lote, 'codcli':codcli, 'codprod':codprod, 'produto':produto };

	$.ajax({
		type: 'POST',
		url: 'Controle/ratProdControle.php',
		data: {'action': 'produto', 'query':dataset},
		success: function(response){
			console.log(response);
			if(response==false){
				alert("Produto Não Encontrado!");
				$("#produtoJson").val("");
				$("#codprod").val("");

			}else{
				//console.log('ok')
				console.log(response);
				var arr = JSON.parse(response)
				
				$("#produtoJson").val(arr['produto']);
				$("#codprod").val(arr['codprod']);

			}
		}
	});
}
</script>

<script>
/*Função de Inclusão*/
function incluirProd(){

	var numrat = "<?php echo $rat->numRat?>";
	var codcli = "<?php echo $rat->codCli?>";
	var codprod = $("#codprod").val(); 
	var produto = $("#produtoJson").val(); 
	var numlote = $("#prodlote").val(); 
	var qt = $('#qt').val();

	if(qt > 0){
		var dados = {'numrat':numrat, 'codcli':codcli, 'codprod':codprod, 'produto':produto, 'numlote':numlote, 'qt':qt};
		//console.log(dados);
		$.ajax({
			type: 'POST',
			url: 'controle/ratProdControle.php',
			data: {'action': 'setProdRat', 'query':dados},
			success: function(response){
				//console.log(response);
				if(response.includes("existe")){
					alert("Produto já existe na RAT");
				}if(response.includes("erro produto")){
					alert("Produto inválido");
				}else{
					console.log(response);
					setTabela();
					limpa();
				}
			}
		});
	}else{
		alert('Quantidade não informada.');
	}


}
</script>

<script>
/*Função de Deleção*/
function delProd(elm){
	var result = confirm("Confirma exclusão do produto?");
	if(result){
		var rat = "<?php echo $rat->numRat?>";
		var cod = $(elm).parent().parent().children('#cellCod').text();
		var lote = $(elm).parent().parent().children('#cellLote').text();

		var dados = {'numrat':rat, 'codprod':cod, 'numlote':lote};

		$.ajax({
			type: 'POST',
			url: 'controle/ratProdControle.php',
			data: {'action': 'delProdRat', 'query':dados},
			success: function(response){
				console.log(response);
				if(response.includes("erro")){
					console.log("erro");
				}else{
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


	$('#produtoJson').typeahead({
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
		url: 'controle/ratProdControle.php',
		data: { 'action': 'produtoJson', 'query': '' },
		success: function (response) {
			var teste = JSON.parse(response);
			var ret = [];
			//console.log(teste[0]['DESCRICAO'])
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

	function limpa(){
		$("#prodlote").val('');
		$("#codprod").val('');
		$("#produtoJson").val('');
		$("#qt").val('');
	}

</script>

<script>
		function confirmar(){
			var numrat = '<?php echo $rat->numRat?>';
	
			$.ajax({
				type: 'POST',
				url: 'Controle/ratProdControle.php',
				data: { 'action': 'finalizarProd', 'query': numrat },
				success: function (response) {
					console.log(response);
					$.redirect('listaRat.php');
				}
			});		
		}
</script>


</html>