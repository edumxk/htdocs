	<?php
	require_once("Controle/novaRatControle.php");
	require_once 'controle/listaRatControle.php';
	require_once 'controle/ctdControl.php';

	session_start();


	if (isset($_POST['id'])) {
		$codcli = $_POST['id'];
	} else {
		header("Location: listaRatCtd2.php");
	}


	$cliente = NovaRatControle::getClienteByCod($codcli);
	$numctd = ctdControl::getNumctd();




	?>


	<!DOCTYPE html>
	<html lang="pt-br">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Nova CTD</title>

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
						<a href="listaRatCtd2.php">Lista de CTD's</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">
						<a href="listaClienteCtd.php">Lista de Clientes</a>
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

						<div class="container">
							<div class="row p-4">
								<div class="col-md-12 col-12">
									<div id="secao" class="col-md-12" style="text-align: center">
										<div>CTD nº
											<?php echo $numctd ?>
										</div>
									</div>
								</div>

								<div class="d-flex justify-content-center space-between col-12 col-md-12 m-3" style="font-size:1.5rem;">
									<label for="date">Data de Abertura</label>
									<span id="date" style="margin-left: 10px;"></span>
								</div>

								<div class="col-2 col-md-2 p-2">
									<label for="codcli">Código do Cliente</label>
									<input type="text" class="form-control" id="codcli" name="codcli" value="<?php echo $cliente['codcli'] ?>" readonly>
								</div>

								<div class="col-4 col-md-4 p-2">
									<label for="cliente">Cliente</label>
									<input type="text" class="form-control"  name="cliente" value="<?php echo $cliente['cliente'] ?>" readonly>
								</div>

								<div class="col-4 col-md-4 p-2">
									<label for="fantasia">Nome Fantasia</label>
									<input type="text" class="form-control"  name="fantasia" value="<?php echo $cliente['fantasia'] ?>" readonly>
								</div>

								<div class="col-2 col-md-2 p-2">
									<label for="cnpj">CNPJ</label>
									<input type="text" class="form-control"  name="cnpj" value="<?php echo $cliente['cnpj'] ?>" readonly>
								</div>

								<div class="col-4 col-md-4 p-2">
									<label for="">Cidade</label>
									<input type="text" class="form-control"  name="contato" value="<?php echo $cliente['cidade'] ?>" readonly>
								</div>

								<div class="col-4 col-md-4 p-2">
									<label for="">Estado</label>
									<input type="text" class="form-control" name="email" value="<?php echo $cliente['uf'] ?>" readonly>
								</div>

								<div class="col-4 col-md-4 p-2">
									<label for="telefone">Telefone</label>
									<input type="text" class="form-control" name="telefone" value="<?php echo $cliente['telefone'] ?>" readonly>
								</div>


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

							<div class="container" style="margin: 30px 0">
								<div class="row">
									<div class="col-8 col-md-8 p-2">
										<label for="" class="form-label">Representante</label>
										<input type="text" class="form-control" readonly value="<?php echo $cliente['rca'] ?>">
									</div>
									<div class="col-4 col-md-4 p-2">
										<label for="" class="form-label">Telefone RCA</label>
										<input type="text" class="form-control" readonly value="<?php echo $cliente['telRca'] ?>">
									</div>
									<div class="col-4 col-md-4 p-2">
										<label for="solicitante" class="form-label">Solicitante</label>
										<input type="text" class="form-control" id="solicitante" name="name" placeholder="Nome do Solicitante">
									</div>
									<div class="col-4 col-md-4 p-2">
										<label for="telsolicitante" class="form-label">Telefone Solicitante</label>
										<input type="tel" class="form-control" id="telsolicitante" placeholder="(63) 99999-9999">
									</div>
									<div class="col-4 col-md-4 p-2">
										<label for="email" class="form-label">E-mail</label>
										<input type="email" class="form-control" id="email" placeholder="email@mail.com">
									</div>
								</div>
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
														if ($rat != null) :
															foreach ($rat as $r) :
														?>
																<tr style="border-top: solid 1px black">
																	<td style="text-align: center">
																		<?php echo $r->numRat ?>
																	</td>
																	<td style="text-align: center">
																		<?php echo $r->dtAbertura ?>
																	</td>
																	<td style="text-align: center">
																		<?php echo $r->codCli ?>
																	</td>
																	<td>
																		<?php echo $r->cliente ?>
																	</td>
																	<td style="text-align: center">
																		<?php echo $r->patologia ?>
																	</td>
																	<td style="text-align: center">
																		<?php echo $r->pendencia ?>
																	</td>
																	<td style="text-align: center">
																		<?php echo $r->getStatus() ?>
																	</td>
																	<td style="text-align: center">
																		<?php echo $r->dtEncerramento ?>
																	</td>
																	<td style="text-align: center" class="row">
																		<div class="col-sm-1" style="padding-left:30px">
																			<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $r->numRat ?>)">
																				<i class="far fa-eye"></i>
																			</button>
																		</div>
																		<!-- </form> -->

																	</td>
																</tr>
																<?php foreach ($r->produtos as $p) : ?>
																	<tr>
																		<td colspan="3"></td>
																		<td style="font-size: 11px"><b>PRODUTO: </b><?php echo $p['CODPROD'] . '-' . $p['DESCRICAO'] ?></td>
																		<td style="font-size: 11px; text-align: center"><b>LOTE: </b><?php echo $p['NUMLOTE'] ?></td>
																		<td style="font-size: 11px; text-align: center"><b>QT: </b><?php echo $p['QT'] ?></td>
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
							<div>
								<p>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="col-md-12" id="secao">
										<div style="text-align: center;">Descrição do Problema</div>
									</div>
									<div class="col-md-12" style="padding-bottom: 20px; padding-top: 20px">
										<textarea id="problema" class="form-control" rows="3" autocomplete="off"></textarea>
										<input id="numrat" value="<?php echo $numctd ?>" hidden>
										<input id="codcli" value="<?php echo $cliente['codcli'] ?>" hidden>
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
									<form action="listaRat.php" method="post">
										<button type="submit" class="btn btn-danger" name="numrat" value="<?php echo $numctd ?>">Cancelar</button>
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
							<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="salvarCtd()">Confirmar</button>
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
		var requisicao = false;
		function salvarCtd() {
			let numctd = "<?php echo $numctd ?>";
			let codcli = "<?php echo $codcli ?>";
			let solicitante = $("#solicitante").val();
			let telSolicitante = $("#telsolicitante").val();
			let emailSolicitante = $("#email").val();
			let problema = $("#problema").val();
			let user = "<?= $_SESSION['coduser'] ?>";

			let dataset = {
				"numctd": numctd,
				"codcli": codcli,
				"solicitante": solicitante,
				"telSolicitante": telSolicitante,
				"problema": problema,
				"user": user,
				"emailSolicitante": emailSolicitante
			};

			if(!requisicao){
				$.ajax({
					type: 'POST',
					url: 'controle/ctdControl.php',
					data: {
						'action': 'novaCtd',
						'query': dataset
					},
					success: function(response) {
						console.log(response);
						console.log('ratProd.php?nurmat='+numrat);
						//$(location).attr('href', 'listaRat.php');
					}
				});
			}
			requisicao = true;
		}

		function modalConfirma() {
			var solic = $("#solicitante").val();
			var solicTel = $("#telsolicitante").val();
			var problema = $("#problema").val();


			if (solic != "" && solicTel != "" && problema != "") {
				$('#myModal').modal('toggle');
			} else {
				alert('Os campos Solicitante, Tel Solicitante e Descrição do Problema devem ser preenchidos.');
			}
		}

		n = new Date();
		y = n.getFullYear();
		m = ("0" + (n.getMonth() + 1)).slice(-2)
		d = ("0" + n.getDate()).slice(-2)
		document.getElementById("date").innerHTML = d + "/" + m + "/" + y;
		$("#datahoje").val(d + "/" + m + "/" + y);

		function openpdf(elm) {
			window.open("recursos/visualizarRat.php?numrat=" + elm);
		}
	</script>


	</html>