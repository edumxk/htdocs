<?php
	require_once 'controle/ratControl.php';
	require_once 'model/aTec.php';
	session_start();

$numrat = $_POST['numrat'];

$chamado = new Chamado();

$chamado = $chamado->getRat($numrat);

$at = new ATec();
$at->getATec($numrat);

$chamado->ATec = $at;


//echo json_encode($chamado);


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
									<?php echo $chamado->razao?>
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

					<!-------------------------------->

					<div>
						<p>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12" id="secao">
								<div class="row">
									<div class="col-md-10">
										<div style="text-align: center;">Análise Técnica</div>
									</div>
									<div class="col-md-2">
										<div style="text-align: center; font-size: 12px">
											<?php echo $chamado->ATec->data?>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="col-md-12">
										<h5>Testes Realizados:</h5>
										<textarea class="form-control" id="taTestes" rows="4" style="resize: none"><?php echo $chamado->ATec->testes?></textarea>
										<div class="col-md-12" style="padding-top: 10px; text-align: right">
											<p>
										</div>
									</div>
									<div class="col-md-12">
										<div style="border-bottom: 1px solid gray;"></div>
									</div>
									<div class="col-md-12">
										<h5>Parecer Técnico:</h5>
										<textarea class="form-control" id="taParecer" rows="4" style="resize: none"><?php echo $chamado->ATec->parecer?></textarea>
									</div>
									<div class="col-md-12">
										<div style="border-bottom: 1px solid gray; padding-top: 20px"></div>
									</div>
									<div class="col-md-12">
										<div class="row">
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
											<div class="col-md-6">
												<h5>Usuário:</h5>
												<input type="text" class="form-control" value="<?php echo $chamado->ATec->nome ?>" readonly>
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
					<form action="editarRat.php" method="post" style="padding-left:20px">
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



	</body>




	<script>
		function modalConfirma() {
			var testes = $("#taTestes").val();
			var parecer = $("#taParecer").val();
			var procedente = $("#ATecParecer").find(":selected").val();
			
			if(testes == "<?php echo $chamado->ATec->testes ?>" && parecer == "<?php echo $chamado->ATec->parecer ?>" && procedente == "<?php echo $chamado->ATec->procedente ?>"){
				console.log("igual");
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

			var testes = $("#taTestes").val();
			var parecer = $("#taParecer").val();
			var procedente = $("#ATecParecer").find(":selected").val();
			var codusur = 2;
			var final = "<?php echo $chamado->ATec->final?>";

			var codusur = <?php echo $_SESSION['codusur']?>;


			var dataset = {"numrat":numrat,"data":data, "parecer":parecer, "testes":testes, "procedente":procedente, "codusur":codusur, "final":final};

			//console.log(dataset);

			$.ajax({
				type: 'POST',
				url: 'controle/aTecControle.php',
				data: {'action': 'setATec', 'query':dataset},
				success: function(response){
					console.log(response);
					if(response="ok"){
						$.redirect('editarRat.php', {'numrat': numrat});
					}
				}
			});
		}

		function addZero(i) {
		if (i < 10) {
			i = "0" + i;
		}
		return i;
		}
	</script>



</html>