	<?php
	require_once("Controle/novaRatControle.php");
	require_once 'controle/listaRatControle.php';

    session_start();


	if(isset($_POST['id'])){
		$codcli = $_POST['id'];

	}


	$cliente = NovaRatControle::getClienteByCod($codcli);
	$numrat = NovaRatControle::getNovoNumeroRat();



  
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
					<li class="breadcrumb-item active" aria-current="page">
						<a href="listaCliente.php">Lista de Clientes</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Novo Chamado</li>
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
								<div>RAT nº
									<?php echo $numrat?>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div>
								<b>Data de Abertura:</b>
							</div>
						</div>
						<div class="col-md-5">
							<div id="date"></div>
						</div>
						<div class="col-md-2" style="padding-bottom: 20px">
							<div>
								<b>Data de Encerramento:</b>
							</div>
						</div>
						<div class="col-md-3">
							<div></div>
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
							<div>
								<?php echo $cliente['codcli']?>
							</div>
						</div>

						<div class="col-md-1">
							<div>
								<b>Razão:</b>
							</div>
						</div>
						<div class="col-md-4">
							<div>
								<?php echo $cliente['cliente']?>
							</div>
						</div>
						<div class="col-md-1">
							<div>
								<b>Fantasia:</b>
							</div>
						</div>
						<div class="col-md-4">
							<div>
								<?php echo $cliente['fantasia']?>
							</div>
						</div>

						<div class="col-md-1">
							<div>
								<b>CNPJ:</b>
							</div>
						</div>
						<div class="col-md-2">
							<div>
								<?php echo $cliente['cnpj']?>
							</div>
						</div>
						<div class="col-md-1">
							<div>
								<b>Cidade:</b>
							</div>
						</div>
						<div class="col-md-3">
							<div>
								<?php echo $cliente['cidade']?>
							</div>
						</div>
						<div class="col-md-1">
							<div>
								<b>Estado:</b>
							</div>
						</div>
						<div class="col-md-1">
							<div>
								<?php echo $cliente['uf']?>
							</div>
						</div>


						<div class="col-md-1">
							<div>
								<b>Telefone:</b>
							</div>
						</div>
						<div class="col-md-2" style="padding-bottom: 20px">
							<div>
								<?php echo $cliente['telefone']?>
							</div>
						</div>
						<div class="col-md-12">
							<div style="border-bottom: 1px solid gray;"></div>
						</div>


						<!--<form action="Controller/redirectControl.php" method="post" style="padding-left:20px" autocomplete="off">-->
						<div class="col-md-12">
							<!-- <form action="../../Controller/redirectControl.php" method="post" style="padding-left:20px" autocomplete="off"> -->
							<!-- Variavel "call" utilizada para identificar que tipo de post na pagina redirectControl.php -->
							<input name="call" value="novarat" hidden>
							<div class="row">
								<div class="col-md-2">
									<div>
										<b>Representante:</b>
									</div>
								</div>

								<div class="col-md-4">
									<div>
										<?php echo $cliente['rca']?>
									</div>
								</div>

								<div class="col-md-2">
									<div>
										<b>Tel. Representante:</b>
									</div>
								</div>

								<div class="col-md-4">
									<div>
										<?php echo $cliente['telRca']?>
									</div>
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
										<input id="solicitante" autocomplete="off" >
									</div>
								</div>
								<div class="col-md-2">
									<div style="padding-top: 5px">
										<b>Tel. Solicitante:</b>
									</div>
								</div>
								<div class="col-md-4">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input id="telsolicitante" autocomplete="off">
									</div>
								</div>
								<div class="col-md-2">
									<div>
										<b>Pintor:</b>
									</div>
								</div>
								<div class="col-md-4">
									<div style="padding-bottom: 10px">
										<input id="pintor" autocomplete="off">
									</div>
								</div>
								<div class="col-md-2">
									<div>
										<b>Tel. Pintor:</b>
									</div>
								</div>
								<div class="col-md-4">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input id="telpintor" autocomplete="off">
									</div>
								</div>
							</div>



							<div>
								<p>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="col-md-12" id="secao">
										<div style="text-align: center;">Chamados do Cliente</div>
									</div>
								</div>
								<div class="col-md-12" style="padding-top: 20px; padding-bottom: 20px">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<table id="table_id" class="display compact" style="width:100%">
													<thead>
														<tr>
															<th style="text-align: center; width: 20px">RAT</th>
															<th style="text-align: center; width: 60px">Abertura</th>
															<th style="text-align: center; width: 20px">COD</th>
															<th>Cliente</th>
															<th style="text-align: center">Patologia</th>
															<th style="text-align: center">Pendencia</th>
															<th style="text-align: center">Status</th>
															<th style="text-align: center; width: 60px">Encerramento</th>
															<th style="text-align: center; width: 60px">Ação</th>
														</tr>
													</thead>
													<tbody>
														<?php
															$rat = ListaRatControl::getListaRatByCli($codcli);
															

															if($rat!=null):
																foreach($rat as $r):
														?>
														<tr  style="border-top: solid 1px black">
															<td style="text-align: center">
																<?php echo $r->numRat?>
															</td>
															<td style="text-align: center">
																<?php echo $r->dtAbertura?>
															</td>
															<td style="text-align: center">
																<?php echo $r->codCli?>
															</td>
															<td>
																<?php echo $r->cliente?>
															</td>
															<td style="text-align: center">
																<?php echo $r->patologia?>
															</td>
															<td style="text-align: center">
																<?php echo $r->pendencia?>
															</td>
															<td style="text-align: center">
																<?php echo $r->getStatus()?>
															</td>
															<td style="text-align: center">
																<?php echo $r->dtEncerramento?>
															</td>
															<td style="text-align: center" class="row">
																<div class="col-sm-1" style="padding-left:30px">
																	<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $r->numRat?>)">
																		<i class="far fa-eye"></i>
																	</button>
																</div>
																<!-- </form> -->

															</td>
														</tr>
															<?php foreach($r->produtos as $p):?>
															<tr>
																<td colspan="3"></td>
																<td style="font-size: 11px"><b>PRODUTO: </b><?php echo $p['CODPROD'].'-'.$p['DESCRICAO']?></td>
																<td style="font-size: 11px; text-align: center"><b>LOTE: </b><?php echo $p['NUMLOTE']?></td>
																<td style="font-size: 11px; text-align: center"><b>QT: </b><?php echo $p['QT']?></td>
															</tr>
															<?php endforeach ?>

														<?php endforeach ?>
														<?php endif ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>


							<div class="row" hidden>
								<div class="col-md-12">
									<div class="col-md-12" id="secao">
										<div style="text-align: center;">Descrição da Não Conformidade</div>
									</div>
								</div>
								<!-------------------------------->
								<div class="col-md-3">
									<div>
										<b>Substrato: </b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>

								<div class="col-md-3">
									<div>
										<b>Repintura ou Novo: </b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>
								<!-------------------------------->
								<div class="col-md-3">
									<div>
										<b>Utilização de Selador ou Fundo: </b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>

								<div class="col-md-3">
									<div>
										<b>Qual:</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>
								<!-------------------------------->
								<div class="col-md-3">
									<div>
										<b>Diluição Utilizada (%):</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>

								<div class="col-md-3">
									<div>
										<b>Tempo de Secagem:</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>
								<!-------------------------------->
								<div class="col-md-3">
									<div>
										<b>Área Aplicada (m²):</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>

								<div class="col-md-3">
									<div>
										<b>Área com Defeito (m²):</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>
								<!-------------------------------->
								<div class="col-md-3">
									<div>
										<b>Temperatura na Aplicação:</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>

								<div class="col-md-3">
									<div>
										<b>Umidade na Aplicação:</b>
									</div>
								</div>
								<div class="col-md-3">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
									</div>
								</div>
								<!-------------------------------->
								<div class="col-md-3">
									<div>
										<b>Quantidade Utilizada:</b>
									</div>
								</div>
								<div class="col-md-9">
									<div style="padding-bottom: 5px; padding-top: 5px">
										<input  autocomplete="off">
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
										<div style="text-align: center;">Descrição do Problema</div>
									</div>
									<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px">
										<textarea id="problema" class="form-control" rows="3"  autocomplete="off"></textarea>
										<input id="numrat" value="<?php echo $numrat?>" hidden>
										<input id="codcli" value="<?php echo $cliente['codcli']?>" hidden>
										<input id="abertura" id="datahoje" hidden>
									</div>
								</div>
							</div>
							<!-------------------------------->
							<div>
								<p>
							</div>

							<div class="row">
								<div class="col-md-6" style="padding-top: 10px; text-align: left">
								<form action="listaRat.php" method="post" >
									<button type="submit" class="btn btn-danger" name="numrat" value="<?php echo $numrat?>">Cancelar</button>
								</form>
								</div>
								<div class="col-md-6" style="padding-top: 10px; text-align: right">
									<button type="submit" class="btn btn-success" onclick="modalConfirma()">Confirmar</button>
								</div>
							</div>

						</div>
						<!-------------------------------->


					</div>
				</div>

			</div>

			<!-- INICIO MODAL -->
			<div class="modal" tabindex="-1" role="dialog" id="myModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Abertura de Chamado:</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>Confirma a criação da RAT?</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="confirma()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->


			<script src="../../recursos/js/jquery.min.js"></script>
			<script src="../../recursos/js/bootstrap.min.js"></script>
			<script src="../../recursos/js/scripts.js"></script>
			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
			<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


	</body>

	<script>
	//function confirmar()
	</script>

	<script>
		function modalConfirma() {
			var solic = $("#solicitante").val();
			var solicTel = $("#telsolicitante").val();
			var problema = $("#problema").val();
			

			if(solic != "" && solicTel != "" && problema != ""){
				$('#myModal').modal('toggle');
			}else{
				alert('Os campos Solicitante, Tel Solicitante e Descrição do Problema devem ser preenchidos.');
			}
		}
	</script>

	<script>
		function confirma(){
			

			var numrat = "<?php echo $numrat ?>"
			var codcli = "<?php echo $codcli ?>"
			var solicitante = $("#solicitante").val();
			var telSolicitante = $("#telsolicitante").val();
			var pintor = $("#pintor").val();
			var telPintor = $("#telpintor").val();
			var problema = $("#problema").val();

			var dataset = {"numrat":numrat, "codcli":codcli, "solicitante":solicitante, "telSolicitante":telSolicitante, "pintor":pintor, "telPintor":telPintor, "problema":problema};

			console.log(dataset);

			$.ajax({
				type: 'POST',
				url: 'controle/novaRatControle.php',
				data: {'action': 'novaRat', 'query':dataset},
				success: function(response){
					console.log(response);
					console.log('ratProd.php?nurmat='+numrat);
					$(location).attr('href', 'listaRat.php');
				}
			});
		}
	</script>



	<script>
		n = new Date();
		y = n.getFullYear();
		m = ("0" + (n.getMonth() + 1)).slice(-2)
		d = ("0" + n.getDate()).slice(-2)
		document.getElementById("date").innerHTML = d + "/" + m + "/" + y;
		$("#datahoje").val(d + "/" + m + "/" + y);
	</script>

<script>
	$(document).ready(function () {
		$('#table').DataTable({
			"lengthMenu": [[10, 50, 100, -1], [10, 50, 100,"Todos"]],
			"order": [[ 0, "asc" ]],
			"bInfo": false,
			"bFilter": false,
			"bLengthChange": false,

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
	function openpdf(elm){
		window.open("recursos/visualizarRat.php?numrat="+elm);
	}
</script> 


	</html>