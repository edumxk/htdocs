<?php
	require_once 'controle/ratControl.php';
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

//$at = new ATec();
$al = new ALab();

//$at->getATec($numrat);
$al->getALab($numrat);

//$chamado->ATec = $at;
$chamado->ALab = $al;


//echo json_encode($chamado->ATec);


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
										<div style="text-align: center;">Análise Laboratorial</div>
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
											<div class="col-md-6">
												<div class="form-group">
													<h5>Patologia:</h5>
													<select class="form-control" id="patologia">
														
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<h5>Nova Patologia:</h5>
												<div class="input-group mb-3">
													<input type="text" class="form-control" id="novaPatologia">
													<div class="input-group-append">
														<button class="btn btn-outline-primary" type="button"  onclick="novaPatologia()">Incluir</button>
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
									<div class="col-md-12">
										<h5>Parecer Laboratorial:</h5>
										<textarea class="form-control" id="taParecer" rows="4" style="resize: none"><?php echo $chamado->ALab->parecer?></textarea>
									</div>
									<div class="col-md-12" style="padding-top: 10px; text-align: right">
										<p>
									</div>
									<div class="col-md-12">
										<div style="border-bottom: 1px solid gray; padding-top: 20px"></div>
									</div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<h5>Situação:</h5>
													<select class="form-control" id="ALabParecer">
														<?php if($chamado->ALab->conforme == 'P' || $chamado->ALab->conforme == null):?>
														<option value="P">Pendente</option>
														<option value="S">Produto Dentro dos Padrões</option>
														<option value="N">Produto Fora dos Padrões</option>
														<?php endif?>
														<?php if($chamado->ALab->conforme == 'S'):?>
														<option value="P">Pendente</option>
														<option value="S" selected>Produto Dentro dos Padrões</option>
														<option value="N">Produto Fora dos Padrões</option>
														<?php endif?>
														<?php if($chamado->ALab->conforme == 'N'):?>
														<option value="P">Pendente</option>
														<option value="S">Produto Dentro dos Padrões</option>
														<option value="N" selected>Produto Fora dos Padrões</option>
														<?php endif?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<h5>Usuário:</h5>
												<input type="text" class="form-control" value="<?php echo $chamado->ALab->nome ?>" readonly>
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
				<div class="col-md-6" style="padding-top: 10px; text-align: left">
					<form action="listaRat.php" method="post" style="padding-left:20px">
						<button type="submit" class="btn btn-warning" name="numrat" value="<?php echo $numrat?>">Cancelar</button>
					</form>
				</div>
				<div class="col-md-6" style="padding-top: 10px; text-align: right">
					<div style="padding-right:20px">
						<button class="btn btn-primary" onclick="modalConfirma()">Salvar</button>
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

		<!-- INICIO MODAL -->
		<div class="modal" tabindex="-1" role="dialog" id="myModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Análise Laboratórial</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Confirma lançamento de Análise?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="confirma()">Confirmar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL -->

		<script src="../../recursos/js/jquery.min.js"></script>
		<script src="../../recursos/js/jquery.redirect.js"></script>
		<script src="../../recursos/js/bootstrap.min.js"></script>
		<script src="../../recursos/js/scripts.js"></script>
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
		function modalConfirma() {
			var patologia = $("#patologia").find(":selected").val();
			var parecer = $("#taParecer").val();
			var procedente = $("#ALabParecer").find(":selected").val();
			if(patologia == "" || parecer == "" || procedente == 'P'){
				alert("Dados incompletos!")
			}else{
				$('#myModal').modal('toggle');
			}
			

		}
	</script>

	<script>
		function confirma(){

			var numrat = "<?php echo $chamado->numRat?>"

			n = new Date();
			y = n.getFullYear();
			m = ("0" + (n.getMonth() + 1)).slice(-2)
			d = ("0" + n.getDate()).slice(-2)
			h = addZero(n.getHours());
			min = addZero(n.getMinutes());
			sec = addZero(n.getSeconds());

			var data = (y + "/" + m + "/" + d + " " + h + ":" + min + ":" + sec);
			//console.log(data);


			var patologia = $("#patologia").find(":selected").val();
			var parecer = $("#taParecer").val();

			var procedente = $("#ALabParecer").find(":selected").val();
			//var codusur = 2;
			var final = "<?php echo $chamado->ALab->final?>";

			var codusur = <?php echo $_SESSION['coduser']?>;


			var dataset = {"numrat":numrat,"data":data, "parecer":parecer, "patologia":patologia, "procedente":procedente, "codusur":codusur, "final":final};

			//console.log(dataset);
			if (parecer.length <= 500){
				$.ajax({
					type: 'POST',
					url: 'controle/aLabControle.php',
					data: {'action': 'setALab', 'query':dataset},
					success: function(response){
						console.log(response);
						if(response="ok"){
							$.redirect('listaRat.php');
						}
					}
				});
			}else{
				alert('Parecer não pode ter mais de 500 caracteres. ('+parecer.length+')');
			}
		}

		function addZero(i) {
		if (i < 10) {
			i = "0" + i;
		}
		return i;
		}
	</script>

	<script>
		$(document).ready(function () {
			getPatologias();
		});
		function getPatologias(){
			$.ajax({
				type: 'POST',
				url: 'controle/aLabControle.php',
				data: {'action': 'getPatologias'},
				success: function(response){
					console.log(response);
					arr = JSON.parse(response);
					//console.log(arr[0]['PATOLOGIA']);
					
					pat = "<?php echo $chamado->ALab->codpatologia ?>"

					patologias = '<option value="0">Escolha uma Patologia</option>';
					$("#patologia").empty();

					for (i = 0; i < arr.length; i++) {
						if(pat == arr[i]['CODPATOLOGIA']){
							patologias += '<option value="'+arr[i]['CODPATOLOGIA']+'" selected>'+arr[i]['PATOLOGIA']+'</option>'
							
						}else{
							patologias += '<option value="'+arr[i]['CODPATOLOGIA']+'">'+arr[i]['PATOLOGIA']+'</option>'
						}
					}

					$("#patologia").append(patologias);
					
				}
				
			});
		}
	</script>

	<script>
		function novaPatologia(){
			var patologia = $("#novaPatologia").val();

			if(patologia != ""){
				$.ajax({
					type: 'POST',
					url: 'controle/aLabControle.php',
					data: {'action': 'newPatologia', 'query':patologia},
					success: function(response){
							console.log(response);
						if(response == "existe"){
							alert("Já existe esta Patologia!");
						}else{
							alert("Nova Patologia cadastrada!");
							getPatologias();
						}
						
					}
					
				});
			}else{
				alert("Nova Patologia inválida.");
			}
		}
	</script>



</html>