<?php
	session_start();
	require_once 'controle/ratControl.php';
	//require_once 'model/aTec.php';
	require_once 'model/rat.php';
	require_once 'model/aLab.php';
	header('Content-Type: text/html; charset=UTF-8');


$setor = $_SESSION['codsetor'];

$numrat = $_POST['numrat'];

$chamado = new Chamado();

$chamado = Chamado::getRat($numrat);

//echo json_encode($chamado->produtos);

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
				<div class=class="float-md-right">
					<div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
						Usuário:
						<?php echo $_SESSION['nome'] ?>
					</div>
					<div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
						Setor:
						<?php echo $_SESSION['setor']?>
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
							<div>RAT nº
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
								<div>
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
							<div class="col-md-12">
								<div style="border-bottom: 1px solid gray;"></div>
							</div>

							<div class="col-md-2">
								<div>
									<b>Representante:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<?php echo $chamado->rca?>
								</div>
							</div>
							<div class="col-md-2">
								<div>
									<b>Tel. Representante:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<?php echo $chamado->telRca?>
								</div>
							</div>

							<div>
								<p>
							</div>

							<div class="col-md-2">
								<div>
									<b>Solicitante:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<div>
										<?php echo $chamado->solicitante?>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div>
									<b>Tel. Solicitante:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<div>
										<?php echo $chamado->telSolicitante?>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div>
									<b>Pintor:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div style="padding-bottom: 5px">
									<div>
										<?php echo $chamado->pintor?>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div>
									<b>Tel. Pintor:</b>
								</div>
							</div>
							<div class="col-md-4">
								<div>
									<div>
										<?php echo $chamado->telPintor?>
									</div>
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
					</div>


					<!-------------------------------->

					<div>
						<p>
					</div>

					<div class="row">

						<div class="col-md-12" >
							<div class="col-md-12" id="secao" >
								<div class="row">
									<div class="col-md-12">
										<div style="text-align: center">Aprovação</div>
									</div>
								</div>
							</div>

							<div class="row"  >
								<div class="col-md-12" >
									<div class="col-md-12" >
										<div class="row" style="padding-top:10px">
											<div class="col-md-2">
												<h5>Parecer Laboraorial:</h5> 
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
										<div class="row">
											<div class="col-md-2">
												<h5>Parecer Técnico:</h5>
											</div>
											<div class="col-md-6">
												<h5>
													<?php if($chamado->ATec == 'P'):?> PENDENTE
													<?php endif?>
													<?php if($chamado->ATec == 'S'):?> CHAMADO PROCEDENTE
													<?php endif?>
													<?php if($chamado->ATec == 'N'):?> CHAMADO NÃO PROCEDENTE
													<?php endif?>
												</h5>	
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<h5>Custo Total:</h5> 
											</div>
											<div class="col-md-6">
												<h5>R$ <?php echo number_format($chamado->getCorretivaTotal(),2,",",".")?></h5>
											</div>
										</div>
									</div>

									<div class="col-md-12">
										<div style="border-bottom: 1px solid gray; padding-top: 20px"></div>
									</div>
									<div class="col-md-12" >
										<div class="row" style="padding-top: 10px">
											<div class="col-md-8" id="reabrir">
												<button type="submit"  class="btn btn-warning" onclick="finalizar(this)">Reabrir</button>
											</div>
											<div class="col-md-2" id="reprovar">
												<button type="submit"  class="btn btn-danger" onclick="finalizar(this)">Reprovar</button>
											</div>
											<div class="col-md-2" id="aprovar">
												<button type="submit"  class="btn btn-success" onclick="finalizar(this)">Aprovar</button>
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


					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12" id="secao">
								<div style="text-align: center;">Produtos</div>
							</div>
							<div class="col-md-12">
								<div class="row" style="padding-top: 20px">
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
													<th scope="col" style="text-align: center; width: 10%">Valor</th>
												</tr>
											</thead>
											<tbody id="lista_json">
												<?php foreach($chamado->produtos as $p) :?>
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
													<td id="vltotal"  scope="col" style="text-align: center; width: 10%">
														<?php echo number_format($p->pVenda,2,",",".")?>
													</td>
													</th>
													<?php endforeach?>
											</tbody>
											<tfoot>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td scope="col" style="text-align: center; width: 10%"><b>Total:</b></td>
												<td scope="col" style="text-align: center; width: 10%"><b><?php echo number_format($chamado->getValorTotal(),2,",",".")?></b></td>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
			<div class="row">
			</div>

			<!-------------------------------->

			<div class="row">
				<div class="col-md-12">
					<div class="col-md-12" id="secao">
						<div style="text-align: center;">Ação Corretiva</div>
					</div>

					<div class="col-md-12">
							<div style="border-bottom: 1px solid gray;"></div>
						</div>

					<div class="col-md-12">
						<div class="row" style="padding-top: 20px; padding-bottom: 20px">
							<div class="col-md-12">
								<table id="corretiva" class="display compact">
									<thead>
										<tr>
											<th scope="col" style="width: 10%">Tipo</th>
											<th scope="col" style="width: 5%">Cod</th>
											<th scope="col">Despesa</th>
											<th scope="col" style="text-align: center; width: 5%">QT</th>
											<th scope="col" style="text-align: center; width: 10%">Valor</th>
										</tr>
									</thead>
									<tbody id="lista_json_acao">

									</tbody>
									<tfoot>
										<td></td>
										<td></td>
										<td></td>
										<td><b>Total:</b></td>
										<td scope="col" style="text-align: center; width: 10%"><b><?php echo number_format($chamado->getCorretivaTotal(),2,",",".")?></b></td>
									</tfoot>
								</table>

							</div>
						</div>
						
					</div>
				</div>
			</div>

			<!-------------------------------->

			<div class="row">
				<div class="col-md-6" style="padding-top: 10px; text-align: left">
					<form action="listaRat.php" method="post">
						<button type="submit" class="btn btn-primary">Retornar</button>
					</form>
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
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


	</body>

	<script>
		$(document).ready(function() {
			var aprova = '<?php echo $chamado->ADir?>';
			var btnAprovar = $("#aprovar").children();
			var btnReprovar = $("#reprovar").children();

			if (aprova !== 'P'){
				btnAprovar.attr('disabled', 'disabled');
				btnReprovar.attr('disabled', 'disabled');

			}
		});
	</script>


	<!-- <script>
		$(document).ready(function () {


			/*Checa se o produto é final e se o usuário tem acesso à função*/
			if ('<?php echo $chamado->prodFinal ?>' == 'S' || 
			'<?php echo $setor ?>' != 0 &&
			'<?php echo $setor ?>' != 3) {
				$("#btnprodutos").prop('hidden', true);
				
				if('<?php echo $setor ?>' == 0){
					//console.log('teste');
					$("#btnReabrirProdutos").prop('hidden', false);
				}
			}
			

			var setor = '<?php echo $setor ?>';

			/*
			Checa Analise Laboratorial:
			Se ALab ainda estiver em branco ou se é Final;
			|	Se Não é Final ou Nulo: Verifica se o usuário tem acesso para editar:
			|	|	Se tem: Revela os botões para Editar ALab;
			|	|	Se não tem: Esconde os botões;
			|	Se é Final: Verifica se o usuário tem acesso para reabrir:
			|	|	Se tem: Revela o botão para Editar ALab;
			|	|	Se não tem: Esconde os botões;
			|	Fim se;
			Fim se;
			*/
			if ('<?php echo $chamado->alabFinal ?>' == 'N') {
				if(setor == '0' || setor == '2'){
					$("#btnalab").prop('hidden', false);
					$("#reabrirAlab").prop('hidden', true);
				}else{
					$("#btnalab").prop('hidden', true);
					$("#reabrirAlab").prop('hidden', true);
				}
			}else{
				if(setor == '0' || setor == '1'){
					$("#btnalab").prop('hidden', true);
					$("#reabrirAlab").prop('hidden', false);
				}else{
					$("#btnalab").prop('hidden', true);
					$("#reabrirAlab").prop('hidden', true);
				}
			}

			/*
			Checa Analise Laboratorial:
			Se ALab ainda estiver em branco ou se é Final;
			|	Se Não é Final ou Nulo: Verifica se o usuário tem acesso para editar:
			|	|	Se tem: Revela os botões para Editar ALab;
			|	|	Se não tem: Esconde os botões;
			|	Se é Final: Verifica se o usuário tem acesso para reabrir:
			|	|	Se tem: Revela o botão para Editar ALab;
			|	|	Se não tem: Esconde os botões;
			|	Fim se;
			Fim se;
			*/
			if ('<?php echo $chamado->acaoFinal ?>' == 'N') {
					console.log(setor);
				if(setor == '0' || setor == '3'){
					$("#finalAcao").prop('hidden', false);
					$("#reabrirAcao").prop('hidden', true);
					$("#editarAcao").prop('hidden', false);
				}else{
					$("#finalAcao").prop('hidden', true);
					$("#reabrirAcao").prop('hidden', true);
					$("#editarAcao").prop('hidden', true);
				}
			}else{
				if(setor == '0' || setor == '1'){
					$("#finalAcao").prop('hidden', true);
					$("#reabrirAcao").prop('hidden', false);
					$("#editarAcao").prop('hidden', true);
				}else{
					$("#finalAcao").prop('hidden', true);
					$("#reabrirAcao").prop('hidden', true);
					$("#editarAcao").prop('hidden', true);
				}
			}


		});
	</script> -->


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
		function changeButton(elm, id) {
			var edit = $(elm).parent().children().get(0);
			var cancel = $(elm).parent().children().get(1);
			var save = $(elm).parent().children().get(2);
			var testes = $("#taTestes");
			var parecer = $("#taParecer");
			var aTec = $("#ATecParecer");


			if (id == 'edit') {
				edit.hidden = true;
				save.hidden = false;
				cancel.hidden = false
				testes.prop('readonly', false);
				parecer.prop('readonly', false);
				aTec.prop('disabled', false);
			}
			if (id == 'save') {
				edit.hidden = false;
				save.hidden = true;
				cancel.hidden = true;
				testes.prop('readonly', true)
				parecer.prop('readonly', true)
				aTec.prop('disabled', true);
			}
			if (id == 'cancel') {
				edit.hidden = false;
				save.hidden = true;
				cancel.hidden = true;
				testes.prop('readonly', true)
				parecer.prop('readonly', true)
				aTec.prop('disabled', true);
			}

		}

		function salvar() {
			$.ajax({
				type: 'POST',
				url: 'Controle/ratProdControle.php',
				data: { 'action': 'getProdRat', 'query': numrat },
				success: function (response) {
					//console.log(response);

				}
			});
		}
	</script>


	<script>
		$(document).ready(function () {
			var numrat = "<?php echo $chamado->numRat?>";
			$.ajax({
				type: 'POST',
				url: 'Controle/corretivaControle.php',
				data: { 'action': 'getCorretiva', 'query': numrat },
				success: function (response) {

					var arr = JSON.parse(response)['acoes'];
					var vltotal = JSON.parse(response);
					var vl = vltotal['total'].toLocaleString('pt-BR');


					var tabela = '';
					$("#lista_json_acao").empty();

					for (i = 0; i < arr.length; i++) {
						//console.log(arr[i].valor);


						tabela += '<tr>'
						tabela += '<th scope="row" id="tbTipo">' + arr[i].tipo + '</th>'
						tabela += '<td>' + arr[i].codprod + '</td>'
						tabela += '<td id="tbDespesa">' + arr[i].despesa + '</td>'
						tabela += '<td style="text-align: center">' + arr[i].qt + '</td>'
						tabela += '<<td style="text-align: center">' + arr[i].valor + '</td>'
						tabela += '</tr>'

						vltotal += Number.parseFloat(arr[i].valor);
						vltotal = vltotal.toLocaleString('pt-BR');

					}

					$("#lista_json_acao").append(tabela);

					$("#vltotalAcao").text(vl);


				}
			});
		});
	</script>


	<!-- Script para alterar o parametro de leitura dos campos de input -->
	<script>
		function finalizar(elm) {
			console.log($(elm).parent().prop('id'));
			var numrat = '<?php echo $numrat ?>';
			var val = $(elm).parent().prop('id');

			var dataset = { 'numrat': numrat, 'tipo': val };

			$.ajax({
				type: 'POST',
				url: 'Controle/ratControl.php',
				data: { 'action': 'finalizar', 'query': dataset },
				success: function (response) {
					console.log(response);
					location.href = "listaRat.php";
				}
			});
		}
	</script>

	</html>