<?php
	require_once 'controle/ratControl.php';
	require_once 'controle/ratProdControle.php';
	require_once 'model/aTec.php';
	require_once 'model/aLab.php';
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	if(isset($_POST['numrat'])){
		$numrat = $_POST['numrat'];
	}
	if(isset($_GET['numrat'])){
		$numrat = $_GET['numrat'];
	}

$chamado = new Chamado();

$chamado = $chamado->getRat($numrat);


//echo var_dump($chamado);


?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Nova RAT</title>

		<meta name="description" content="Source code generated using layoutit.com">
		<meta name="author" content="LayoutIt!">

		<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../recursos/css/style.css" rel="stylesheet">
		<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
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
					<li class="breadcrumb-item active" aria-current="page">Editar RAT</li>
				</ol>
			</nav>
			<div class="row" style="padding-bottom: 20px">
				<div class="col-md-12">
					<div class="page-header">
						<h1 style="padding-top: 20px">Gerência de Chamados
							<small> - Editar RAT</small>
						</h1>
					</div>

					<div>
						<div id="secao" class="col-md-12" style="text-align: center">
							<div id="numrat">RAT nº
								<?php echo $chamado->numRat?>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">

							<div class="col-md-2">
								<div>
									<b>Data de Abertura:</b>
								</div>
							</div>
							<div class="col-md-5">
								<div>
									<?php echo $chamado->dtAbertura?>
								</div>
							</div>
							<div class="col-md-2" style="padding-bottom: 20px">
								<div>
									<b>Data de Encerramento:</b>
								</div>
							</div>
							<div class="col-md-3">
								<div>
									<?php echo $chamado->dtEncerramento?>
								</div>
							</div>
							<div class="col-md-12">
								<div style="border-bottom: 1px solid gray;"></div>
							</div>

							<div class="col-md-1">
								<div>
									<b>COD: </b>
								</div>
							</div>
							<div class="col-md-2">
								<div id="codcli">
									<?php echo $chamado->codCli?>
								</div>
							</div>

							<div class="col-md-1">
								<div>
									<b>Razão:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<?php echo $chamado->cliente?>
								</div>
							</div>
							<div class="col-md-1">
								<div>
									<b>Fantasia:</b>
								</div>
							</div>
							<div class="col-md-3">
								<div>
									<?php echo $chamado->fantasia?>
								</div>
							</div>

							<div class="col-md-1">
								<div>
									<b>CNPJ:</b>
								</div>
							</div>
							<div class="col-md-2">
								<div>
									<?php echo $chamado->cnpj?>
								</div>
							</div>
							<div class="col-md-1">
								<div>
									<b>Cidade:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<?php echo $chamado->cidade?> -
									<?php echo $chamado->uf?>
								</div>
							</div>

							<div class="col-md-1">
								<div>
									<b>Telefone:</b>
								</div>
							</div>
							<div class="col-md-2" style="padding-bottom: 20px">
								<div>
									<?php echo $chamado->telCliente?>
								</div>
							</div>

						</div>


						<div class="col-md-12">
							<div style="border-bottom: 1px solid gray;"></div>
						</div>


						<!-------------------------------->
						<div class="col-md-12">
							<div style="border-bottom: 1px solid gray;"></div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="col-md-12">
									<h5 style="text-align: center; padding-top:10px">Descrição do Problema</h5>
								</div>
								<div class="col-md-12">

									<textarea class="form-control" rows="4" style="resize: none" readonly><?php echo $chamado->problema?></textarea>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
							<table id="table_id" class="display compact">
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
								<tbody>
									<?php $vltotal = 0; 
										foreach($chamado->produtos as $p) :?>
									<tr>
										<th scope="row" style="widht:50px" id="cellCod">
											<?php echo $p->codprod?>
										</th>
										<td id="tbProduto">
											<?php echo $p->produto?>
										</td>
										<td>
											<?php echo $p->qt?>
										</td>
										<td id="cellLote">
											<?php echo $p->numlote?>
										</td>
										<td>
											<?php echo $p->dtFabricacao?>
										</td>
										<td>
											<?php echo $p->dtValidade?>
										</td>
										<td id="vltotal">
											<?php echo number_format($p->pVenda*$p->qt,2,',','.');
												$vltotal += ($p->pVenda*$p->qt)?>
										</td>
									</tr>
									<?php endforeach?>
								</tbody>
								<tfoot>
									<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL:</th>
									<td><?php echo number_format($vltotal,2,",",".");?></td>
									</tr>
								</tfoot>

								</table>
								
							</div>
						</div>

						<div class="row" style="padding-top:10px">
							<div class="col-md-2">
								<h5>Parecer Laboratorial:</h5> 
							</div>
							<div class="col-md-6">
								<?php if($chamado->ALab->conforme == 'P'):?> <h5>PENDENTE</h5>
								<?php endif?>
								<?php if($chamado->ALab->conforme == 'S'):?> <h5>PRODUTO DENTRO DOS PADRÕES</h5>
								<?php endif?>
								<?php if($chamado->ALab->conforme == 'N'):?> <h5>PRODUTO FORA DOS PADRÕES</h5>
								<?php endif?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<h5>Patologia:</h5> 
							</div>
							<div class="col-md-6">
								<?php echo $chamado->ALab->patologia?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<h5>Análise Laboratorial:</h5> 
							</div>
							<div class="col-md-6">
								<?php echo $chamado->ALab->parecer?>
							</div>
						</div>
						<?php if($chamado->motivo !== null){
							echo "<div class=\"row\">
									<div class=\"col-md-2\">
										<h5>Motivo Reprovação:</h5> 
									</div>
									<div class=\"col-md-6\">$chamado->motivo
									</div>
								</div>";
							} 
						?>
					</div>

					<!-------------------------------->
					<div>
						<p>
					</div>

					<!-------------------------------->

					<div>
						<p>
					</div>

					<div class="row">
						<div class="col-md-12">
						
							<div class="col-md-12" id="secao">
								<div class="row">
									<div class="col-md-10">
										<div style="text-align: center;">Ação Corretiva</div>
									</div>
									<div class="col-md-2">
										<div style="text-align: center; font-size: 12px">
											<?php echo $chamado->ALab->data?>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">


									<div class="col-md-12">
										<div class="row">
										<div class="col-md-3"></div>
										<div class="col-md-6">
												<div class="form-group">
													<h5>Situação:</h5>
													<select class="form-control" id="ATecParecer">
														<?php if($chamado->ALab->procedente == 'P' || $chamado->ALab->procednete == null):?>
														<option value="P">Pendente</option>
														<option value="S">Reclamação Procedente</option>
														<option value="N">Reclamação Não Procedente</option>
														<?php endif?>
														<?php if($chamado->ATec->procedente == 'S'):?>
														<option value="P">Pendente</option>
														<option value="S" selected>Reclamação Procedente</option>
														<option value="N">Reclamação Não Procedente</option>
														<?php endif?>
														<?php if($chamado->ATec->procedente == 'N'):?>
														<option value="P">Pendente</option>
														<option value="S">Reclamação Procedente</option>
														<option value="N" selected>Reclamação Não Procedente</option>
														<?php endif?>
													</select>
												</div>
											</div>
											<div class="col-md-12">
												<div class="col-md-12" style=" padding-top:20px; padding-bottom:10px; text-align: center">
													<button type="submit" class="btn btn-warning" id="btnTroca" onclick="btnTroca()">Trocar</button>
													<button type="submit" class="btn btn-primary" id="btnBonif" onclick="btnBonifica()">Bonificar</button>
													<button type="submit" class="btn btn-primary" id="btnCusto" onclick="btnCustear()">Custear</button>
												</div>
											</div>

											<div class="col-md-12">
												<div class="col-md-12" style="padding-top:20px" id="TROCAR">
													<div class="form-group">
														<div class="row" style="padding-top: 10px; padding-bottom: 10px; font-weight: 700">
															<div class="col-md-12" style="text-align: center">
																<h5>Trocar Produto:</h5>
															</div>
															<div class="col-md-1"></div>
															<div class="col-md-1">Cod.:</div>
															<div class="col-md-1"></div>
															<div class="col-md-6">Produto:</div>
															<div class="col-md-1">Qt.:</div>
															<div class="col-md-2"></div>
														</div>

														<div class="row">

															<div class="col-md-1">
															</div>
															<div class="col-md-1">
																<input type="text" id="codprod" class="form-control form-control-sm" onfocusout="getProdTroca(this)" >
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-primary btn-sm" onclick="liberaCod()">Libera</button>
															</div>
															<div class="col-md-6">
																<input type="text"  id="trocaJson" class="form-control form-control-sm" disabled> 
															</div>
															<div class="col-md-1">
																<input id="qt" type="text" class="form-control form-control-sm">
															</div>

															<div class="col-md-1">
																<button type="submit" class="btn btn-primary btn-sm" onclick="incluirProd(this)">Incluir</button>
															</div>
														</div>

													</div>
												</div>


												<div class="col-md-12" style="padding-top:20px" id="CUSTEAR" style="display: none;">
													<div class="form-group">
														<div class="row" style="padding-top: 10px; padding-bottom: 10px; font-weight: 700">
															<div class="col-md-12" style="text-align: center">
																<h5>Custear Cliente:</h5>
															</div>
															<div class="col-md-2"></div>
															<div class="col-md-2">Tipo:</div>
															<div class="col-md-5">Despesa:</div>
															<div class="col-md-1">Valor.:</div>
															<div class="col-md-2"></div>
														</div>

														<div class="row">
															<div class="col-md-2">
															</div>
															<div class="col-md-2">
																<div class="form-group" id="form-custos">
																	<select class="form-control form-control-sm" id="custos">
																		
																	</select>
																</div>
															</div>
															<div class="col-md-5">
																<input id="despesa" type="text" class="form-control form-control-sm">
															</div>
															<div class="col-md-1">
																<input id="valor" type="text" class="form-control form-control-sm">
															</div>

															<div class="col-md-1">
																<button type="submit" class="btn btn-primary btn-sm" onclick="incluirProd(this)">Incluir</button>
															</div>
														</div>

													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12" style="padding-top: 10px; text-align: right">
											<p>
										</div>

										<div class="col-md-12">
											<div style="border-bottom: 1px solid gray;"></div>
										</div>
										<div class="col-md-12" style="padding-top: 10px; text-align: right">
											<p>
										</div>
										<div class="col-md-12">
											<div class="row" style="padding-top: 20px; padding-bottom: 20px">
												<div class="col-md-12">
													<table id="corretiva" class="display compact">
														<thead>
															<tr>
																<th scope="col" style="width: 10%">Ação</th>
																<th scope="col" style="width: 5%">Cod</th>
																<th scope="col" style="width: 10%">Tipo</th>
																<th scope="col">Despesa</th>
																<th scope="col" style="text-align: center; width: 5%">QT</th>
																<th scope="col" style="text-align: center; width: 10%">Valor</th>
																<th scope="col" style="text-align: center; width: 5%">Del</th>
															</tr>
														</thead>
														<tbody id="lista_json">
														</tbody>
														<tfoot>
															<th></th>
															<th></th>
															<th></th>
															<th colspan=2>TOTAL:</th>
															<th id="vltotalAcao" style="text-align: center"></th>
														</tfoot>
													</table>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-------------------------------->
				<div>
					<p>
				</div>

				<div class="col-md-12">
					<div style="padding-left:20px">
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-md-6" style="padding-top: 10px; text-align: right">
								<button type="submit" class="btn btn-success" name="numrat" onclick="confirmar()">Confirmar</button>
							</div>
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
		<script src="../../recursos/js/typeahead.jquery.js"></script>
		<script src="../../recursos/js/jquery.redirect.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>



	</body>



	<script>
		function liberaCod() {
			var a = $("#codprod").attr('disabled');
			if(a == null){
				$("#codprod").attr('disabled','disabled');
				$("#trocaJson").removeAttr('disabled');
				
			}else{
				$("#trocaJson").attr('disabled','disabled');
				$("#codprod").removeAttr('disabled');
			}
		}

		function btnTroca() {
			$("#btnTroca").attr('class', 'btn btn-warning');
			$("#btnBonif").attr('class', 'btn btn-primary');
			$("#btnCusto").attr('class', 'btn btn-primary');

			$("#TROCAR").show();
			$("#CUSTEAR").hide();
		}

		function btnBonifica() {
			$("#btnTroca").attr('class', 'btn btn-primary');
			$("#btnBonif").attr('class', 'btn btn-warning');
			$("#btnCusto").attr('class', 'btn btn-primary');

			$("#TROCAR").show();
			$("#CUSTEAR").hide();
		}

		function btnCustear() {
			$("#btnTroca").attr('class', 'btn btn-primary');
			$("#btnBonif").attr('class', 'btn btn-primary');
			$("#btnCusto").attr('class', 'btn btn-warning');

			$("#TROCAR").hide();
			$("#CUSTEAR").show();
		}



	</script>



	<script>
		$(document).ready(function () {
			$("#BONIFICAR").toggle();
			$("#CUSTEAR").toggle();
		});
	</script>


	<script>
		$(document).ready(function () {
			$('#corretiva').DataTable({
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
		/*Função para preencher a tabela de produtos da RAT*/
		$(document).ready(function () {
			setTabela();
		})
	</script>
	<script>
		function setTabela() {
			var numrat = "<?php echo $chamado->numRat?>";
			$.ajax({
				type: 'POST',
				url: 'Controle/corretivaControle.php',
				data: { 'action': 'getCorretiva', 'query': numrat },
				success: function (response) {
					//console.log(response);
					var arr = JSON.parse(response)['acoes'];
					var vltotal = JSON.parse(response);
					//console.log(vltotal);
					var vl = vltotal['total'].toLocaleString('pt-BR');


					var tabela = '';
					$("#lista_json").empty();

					for (i = 0; i < arr.length; i++) {
						tabela += '<tr>'
						tabela += '<th scope="row" id="tbTipo">' + arr[i].tipo + '</th>'
						tabela += '<td>' + arr[i].codprod + '</td>'
						tabela += '<td>'+arr[i].custo+'</td>'
						tabela += '<td id="tbDespesa">' + arr[i].despesa + '</td>'
						tabela += '<td style="text-align: center">' + arr[i].qt + '</td>'
						tabela += '<<td style="text-align: center">' + arr[i].valor.toLocaleString('pt-BR') + '</td>'
						tabela += '<td style="text-align: center;"><button type="button" class="btn-xs btn-danger" onclick="delProd(this)">x</button></td>'
						tabela += '</tr>'

						//vltotal += arr[i].valor;
						//vltotal = vltotal.toLocaleString('pt-BR')

					}

					$("#lista_json").append(tabela);
					$("#vltotalAcao").text(vl);


				}
			});
		}
	</script>


<script>
	/*TypeAhead para busca de produtos para bonificar*/

	var substringMatcher = function (strs) {
		return function findMatches(q, cb) {
			var matches, substringRegex;
			matches = [];
			substrRegex = new RegExp(q, 'i');
			$.each(strs, function (i, str) {
				if (substrRegex.test(str)) {
					matches.push(str);
				}
			});
			cb(matches);
		};
	};
	var states = [];
	$('#trocaJson').typeahead({
		hint: false,
		highlight: true,
		minLength: 1,
	},
		{name: 'states',source: substringMatcher(states)}
	);
	
	$.ajax({
		type: 'POST',
		url: 'controle/ratProdControle.php',
		data: { 'action': 'produtoJson', 'query': '' },
		success: function (response) {
			console.log(response)
			var teste = JSON.parse(response);
			var ret = [];
			for (i = 0; i < teste.length; i++) {
				states.push(teste[i]['DESCRICAO']);
			}
		}
	});
</script>

	

	<script>
		function getProdTroca(elm) {
			var cod = $(elm).val();
			var cli = <?php echo $chamado->codCli?>;

			
			dataset = {'codprod':cod, 'codcli':cli};

			if (cod != null) {
				$.ajax({
					type: 'POST',
					url: 'Controle/corretivaControle.php',
					data: { 'action': 'getProduto', 'query': cod },
					success: function (response) {
						//console.log(response);
						if (response == 0) {
							$("#trocaJson").val('');
						} else {
							//console.log(response);
							var ret = JSON.parse(response);
							$(elm).parent().parent().find("#trocaJson").val(ret['produto'])

						}
					}
				});
			}
		}
	</script>


	<script>
		/*Função de Inclusão*/
		function incluirProd(elm) {
			var numrat = "<?php echo $numrat?>";
			var codcli = "<?php echo $chamado->codCli?>"//$("#codcli").val();


			////console.log(codcli);

			var codprod = $(elm).parent().parent().find("#codprod").val();
			var produto = $(elm).parent().parent().find("#trocaJson").val();
			var qt = $(elm).parent().parent().find("#qt").val();
			if(qt === null){
				qt = 0;
			}
			

			var acao = $(elm).parent().parent().parent().parent().attr('id');

			var custo = $(elm).parent().parent().find("#custos").val();;



			if (acao != 'CUSTEAR') {
				if($("#btnTroca").attr('class') != 'btn btn-warning'){
					acao = 'BONIFICAR';
				}
				if (/*qt > 0 && */produto != "") {
					var dados = { 'acao': acao, 'numrat': numrat, 'codcli':codcli, 'codprod': codprod, 'produto': produto, 'qt': qt, 'custo':0 };
					$.ajax({
						type: 'POST',
						url: 'controle/corretivaControle.php',
						data: { 'action': 'setAcao', 'query': dados },
						success: function (response) {
							if (response.includes("ok")) {
								setTabela();
								acao = null;
								dados = null;
							}
							if (response == 'EXISTE') {
								alert("Correção já cadastrada no chamado.");
							}
							$("#codprod").val("");
							$("#trocaJson").val("");
							$("#qt").val("");
							$("#despesa").val("");
							$("#valor").val("");


						}
					});
				} else {
					alert("Dados incompletos!");
				}
			} else {
				var despesa = $("#despesa").val();
				
				var valor = $("#valor").val();
				if (valor > 0 && despesa != "") {

					var dados = { 'acao': acao, 'numrat': numrat, 'codcli':codcli, 'codprod': 0, 'produto': despesa, 'qt': valor, 'custo':custo };
					$.ajax({
						type: 'POST',
						url: 'controle/corretivaControle.php',
						data: { 'action': 'setAcao', 'query': dados },
						success: function (response) {
							//console.log(response);
							if (response.includes("ok")) {
								setTabela();
								acao = null;
								dados = null;
							}
							if (response == 'EXISTE') {
								alert("Correção já cadastrada no chamado.");
							}
							$("#codprod").val("");
							$("#produto").val("");
							$("#qt").val("");
							$("#despesa").val("");
							$("#valor").val("");
						}
					});
				}
			}
		}
	</script>

	<script>
		/*Função de Deleção*/
		function delProd(elm) {
			var result = confirm("Confirma exclusão do produto?");
			//console.log(result);
			if (result) {
				var numrat = '<?php echo $numrat?>';
				var tipo = $(elm).parent().parent().children('#tbTipo').text();
				var despesa = $(elm).parent().parent().children('#tbDespesa').text();
				

				var dados = { 'numrat': numrat, 'tipo': tipo, 'despesa': despesa };
				//console.log(dados);

				$.ajax({
					type: 'POST',
					url: 'controle/corretivaControle.php',
					data: { 'action': 'delAcao', 'query': dados },
					success: function (response) {
						////console.log(response);
						if (response.includes("ok")) {
							//console.log(response);
							setTabela();
						} else {
							//console.log('response');
							setTabela();
						}

					}
				});
			}



		}
	</script>
	<script>
		$(document).ready(function () {
			getCustos();
		});
		function getCustos(){
			$.ajax({
				type: 'POST',
				url: 'controle/corretivaControle.php',
				data: {'action': 'getCustos'},
				success: function(response){
					//console.log(response);
					arr = JSON.parse(response);

					custos = '<option value="-1">ESCOLHA UM CUSTO</option>';
					$("#custos").empty();

					for (i = 0; i < arr.length; i++) {
						custos += '<option value="'+arr[i]['CODCUSTO']+'">'+arr[i]['CUSTO']+'</option>'

					}

					$("#custos").append(custos);
					
				}
				
			});
		}
	</script>

	<script>
	//Verifica se existe correções lançadas
		function confirmar(){
			var numrat = '<?php echo $chamado->numRat?>';
			////console.log(numrat);
			var procedente = $("#ATecParecer").find(":selected").val();
			var corretiva = function() {
				var tmp = null
				$.ajax({
					async: false,
					type: 'POST',
					url: 'Controle/corretivaControle.php',
					data: { 'action': 'getCorretiva', 'query': numrat },
					success: function (response) {
						//console.log('response');
						var arr = JSON.parse(response)['acoes'];
						//console.log(arr);
						tmp = arr.length;
					}
				});
				return tmp;
			}();

			////console.log(corretiva);
			parecer(numrat, procedente, corretiva);
		}
	</script>

	<script>
		function parecer(numrat, procedente, corretiva){
			final = false;
			////console.log(corretiva);

			
			if(corretiva == 0){
				if(procedente == 'P'){
					alert("Situação do Parecer não informada!");
				}else if(procedente == 'S'){
					alert("Parecer positivo precisa de ação corretiva!")
				}else if(procedente == 'N'){
					final = true;
				}
			}else{
				if(procedente == 'P'){
					alert("Situação do Parecer não informada!");
				}else if(procedente == 'S'){
					final = true;
				}else if(procedente == 'N'){
					alert("Parecer negativo não pode ter ação corretiva!");
				}
			}
			
			if(final){
				var dados = { 'numrat': numrat, 'procedente': procedente};
				console.log(dados);
				$.ajax({
					type: 'POST',
					url: 'Controle/corretivaControle.php',
					data: { 'action': 'setParecer', 'query': dados },
					success: function (response) {
						console.log(response);
						$.redirect('listaRat.php');

					}
				});
			}
		}
	</script>




	</html>