<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFaltas/controle/faltaControle.php');
session_start();
$numft =  $_GET;
$p = -1;
$dados = daoFalta::getfalta($numft['numnota']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Editar Falta</title>

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
					<a href="home.php">Gerente Faltas</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Editar Falta</li>
			</ol>
		</nav>
		<div class="row" style="padding-bottom: 20px">
			<div class="col-md-12">
				<div class="page-header">
					<h1 style="padding-top: 20px">Gerência de Faltas
						<small> - Nova Falta</small>
					</h1>
				</div>
				<div class="row" style="text-align: center">
					<div class="col-md-12">
						<div id="secao" class="col-md-12" style="text-align: center">
							<div>Falta nº <?= $dados[0]['NUMFALTA'] ?></div>
						</div>
					</div>
					<div class="col-md-2" style="padding-top: 20px">
						<div>
							<b>Data Pedido:</b>
						</div>
					</div>
					<div class="col-md-5" style="padding-top: 20px; text-align:left">
						<!-- <div id="date"></div> -->
						<div><?= $dados[0]['DATA'] ?></div>
					</div>
					<div class="col-md-2" style="padding-top: 20px">
						<div>
							<b>Data Faturamento:</b>
						</div>
					</div>
					<div class="col-md-3" style="padding-top: 20px; text-align:left">
						<div><?= $dados[0]['DTFAT'] ?></div>
					</div>
					<div class="col-md-12" style="padding-top: 20px; padding-bottom: 20px;">
						<div style="border-bottom: 1px solid gray;"></div>
					</div>
					<div class="col-md-1" style="text-align:center"></div>
					<div class="col-md-1" style="text-align:center">
						<div>
							<b>COD: </b>
						</div>
					</div>
					<div class="col-md-2" style="text-align:left">
						<div><?= $dados[0]['CODCLI'] ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Razão:</b>
						</div>
					</div>
					<div class="col-md-3" style="text-align:left">
						<div><?= $dados[0]['CLIENTE'] ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Fantasia:</b>
						</div>
					</div>
					<div class="col-md-3" style="text-align:left">
						<div><?= $dados[0]['FANTASIA'] ?></div>
					</div>
					<div class="col-md-1" style="text-align:center"></div>
					<div class="col-md-1" style="text-align:center">
						<div>
							<b>CNPJ:</b>
						</div>
					</div>
					<div class="col-md-2" style="text-align:left">
						<div><?= $dados[0]['CNPJ'] ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Cidade:</b>
						</div>
					</div>
					<div class="col-md-3" style="text-align:left">
						<div><?= $dados[0]['CIDADE'] ?></div>
					</div>
					<div class="col-md-1">
						<div>
							<b>Telefone:</b>
						</div>
					</div>
					<div class="col-md-2" style="text-align:left; padding-bottom: 20px">
						<div><?= $dados[0]['TELCLI'] ?></div>
					</div>
					<div class="col-md-12" style="padding-bottom: 20px">
						<div style="border-bottom: 1px solid gray;"></div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Representante:</b>
						</div>
					</div>
					<div class="col-md-4" style="text-align:left">
						<div><?= $dados[0]['NOMERCA'] ?></div>
					</div>
					<div class="col-md-2">
						<div>
							<b>Tel. Representante:</b>
						</div>
					</div>
					<div class="col-md-4" style="text-align:left">
						<div><?= $dados[0]['TELRCA'] ?></div>
					</div>
					<div>
						<p>
					</div>
					<div class="col-md-2">
						<div style="padding-top: 5px">
							<b>Motorista:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 10px; text-align: left">
							<div><?= $dados[0]['MOTORISTA'] ?></div>
						</div>
					</div>
					<div class="col-md-2">
						<div style="padding-top: 5px">
							<b>NFe Custo:</b>
						</div>
					</div>
					<div class="col-md-4">
						<div style="padding-bottom: 10px">
							<div><?= $dados[0]['NUMNOTACUSTO'] ?></div>
						</div>
					</div>
				</div>
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
							<div style="text-align: center;">Produtos</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div class="row" style="padding-top: 10px; padding-bottom: 10px; font-weight: 700">
									<div class="col-md-12" style="text-align: center">
										<h5>Incluir Produto:</h5>
									</div>
									<div class="col-md-12">

									</div>
									<div class="col-md-2">Cod.:</div>
									<div class="col-md-2">Qt.:</div>
									<div class="col-md-1">Motivo:</div>
									<div class="col-md-1">Custo:</div>
									<div class="col-md-2">Obs:</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<input id="codprod" type="number" class="form-control form-control-sm " tabindex="1">
									</div>
									<div class="col-md-2">
										<input id="qt" type="number" class="form-control form-control-sm" tabindex="2">
									</div>
									<div class="col-md-1">
										<select name="motivo" id="motivo" tabindex="3" style="width: 80px">
											<optgroup id="motivof" label="Motivo">
												<option value="FALTA">Falta</option>
												<option value="AVARIA">Avaria</option>
												<option value="OUTRO">Outro</option>
											</optgroup>
										</select>
									</div>
									<div class="col-md-1">
										<select name="custo" id="custo" tabindex="4" style="width: 80px">
											<optgroup id="motorista" label="Responsável">
												<option value="K">Kokar</option>
												<option value="M">Motorista</option>
											</optgroup>
										</select>
									</div>
									<div class="col-md-2">
										<input id="obs" type="text" class="form-control form-control-sm" tabindex="2">
									</div>
									<div class="col-md-1">
										<button type="submit" class="btn btn-primary btn-sm" onclick="incluirProd()" tabindex="5">Incluir</button>
									</div>
								</div>
							</div>
							<div class="row" style="padding-top: 20px; padding-bottom: 20px">
								<div class="col-md-12">
									<!-- <table id="example" class="display" width="100%"></table> -->
									<table id="table_id" class="display compact" style="width:100%; text-align: center">
										<thead>
											<tr>
												<th scope="col" style="width: 2%; text-align: left">Código</th>
												<th scope="col" style="width: 10%; text-align: left">Produto</th>
												<th scope="col" style="width: 2%">Qtd</th>
												<th scope="col" style="width: 2%">Motivo</th>
												<th scope="col" style="width: 2%">Posição</th>
												<th scope="col" style="width: 2%">Dt Envio</th>
												<th scope="col" style="width: 1%">Custo</th>
												<th scope="col" style="width: 1%">Enviar</th>
												<th scope="col" style="width: 1%">Deletar</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($dados as $d) { ?>
												<tr>
													<td scope="row" style="width: 2%;text-align: left"><b><?= $d['CODPROD'] ?></th>
													<td style="width: 10%;text-align: left"><b><?= $d['DESCRICAO'] ?></td>
													<td style="width: 2%; text-align: left; padding-left: 15px" ;><b><?= $d['QT'] ?></td>
													<td style="width: 2%; text-align: left"><b><?= $d['MOTIVO'] ?></td>
													<td style="width: 2%; text-align: left"><b>
															<?php if ($d['POSICAO'] == 'P') { ?>
																PENDENTE
															<?php $p += 1; } else if ($d['POSICAO'] == 'F'){ ?>
																ENVIADO
															<?php } ?>
													</td>
													<td style="width: 2%; text-align: left"><b><?= $d['DTENVIO'] ?></td>
													<td style="width: 1%; text-align: left"><b>
															<?php if ($d['TIPOCUSTO'] == 'M') { ?>
																MOTORISTA
															<?php } else if ($d['TIPOCUSTO'] == 'K'){ ?>
																KOKAR
															<?php } ?>
													</td>
													<?php if ($d['POSICAO'] == 'P') { ?>
														<td style="text-align: left;width: 1%; padding-left: 10px"><button tabindex="6" type="button" class="btn-xs btn-success" data-toggle="modal" data-target="#exampleModal" onclick="enviarProd('<?=$d['ROWID']?>')">✔</button></td>
													<?php } else if ($d['POSICAO'] == 'F') { ?>
														<td style="text-align: left;width: 1%; padding-left: 10px"><button tabindex="6" type="button" data-toggle="modal" data-target="#exampleModal" disabled>✔</button></td>
													<?php } ?>
													<?php if ($d['ROWID'] > '0' && $d['POSICAO'] == 'P') { ?>
													<td style="text-align: left;width: 1%; padding-left: 15px"><button tabindex="7" type="button" class="btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="delProd('<?=$d['ROWID']?>')">X</button></td>
													<?php } ?>
												</tr>
											<?php } ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="padding-left:20px">
				<div class="row"><p></div>
				<div class="row"><p></div>
				<div class="row"><p></div>
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-6" style="padding-top: 10px; text-align: right">
							<button tabindex="8" type="submit" class="btn btn-success" name="numrat" onclick="confirmar()">Confirmar</button>
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
	/*Função de Inclusão*/
	function incluirProd() {
		var numfalta = <?= $dados[0]['NUMFALTA'] ?>;
		var codcli = <?= $dados[0]['CODCLI'] ?>;
		var numnota = <?= $dados[0]['NUMNOTA'] ?>;
		var obs = $("#obs").val();
		var codprod = $("#codprod").val();
		var qt = $("#qt").val();
		var motivo = $("#motivo option:selected").val();
		var tipocusto = $("#custo option:selected").val();
		var posicao = 'P';
		var dataf = '<?= $dados[0]['DTFAT'] ?>';
	
		if (qt > 0 && codprod > 0) {
			var produto = {'numfalta' : numfalta,'codprod' : codprod, 'qt' : qt,
			 'posicao' : posicao, 'motivo': motivo, 'codcli' : codcli,'numnota': numnota,
			  'tipocusto' : tipocusto, 'dataf' : dataf, 'obs' : obs};
			
			console.log(produto);
			$.ajax({
				type: 'POST',
				url: 'controle/faltaControle.php',
				data: {
					'action': 'incluirProd',
					'query': produto
				},
				success: function(response) {
					console.log(response);
					alert('Produto: '+codprod+', Qt: '+qt+' Incluido com Sucesso.');
					
					setTimeout("location.reload(true);",1);
					
				}
			});
		} else {
			alert('Produto ou Quantidade não informada.');
		}
	}
</script>

<script>
	/*Função de Deleção*/
	function delProd(elm) {
		var result = confirm("Confirma exclusão do produto?");
		if (result) {
			var teste = {'id': elm};
			console.log(teste['id']);

			$.ajax({
				type: 'POST',
				url: 'controle/faltaControle.php',
				data: {
					'action': 'delProd',
					'query': teste
				},
				success: function(response) {
					if (response.includes('ok')) {
						alert("Produto excluído com sucesso.");
					} else {
						alert("Erro inesperado.");
					}
					setTimeout("location.reload(true);",1);

				}
			});
		}
	}
</script>
<script>
	/*Enviar Produto*/
	function enviarProd(elm) {
		var result = confirm("Confirma o envio do produto?");
		if (result) {
			var teste = {'id': elm};
			console.log(teste['id']);

			$.ajax({
				type: 'POST',
				url: 'controle/faltaControle.php',
				data: {
					'action': 'enviarProd',
					'query': teste
				},
				success: function(response) {
					if (response.includes('ok')) {
						alert("Produto enviado com sucesso.");
					} else {
						alert("Erro inesperado.");
					}
					setTimeout("location.reload(true);",1);

				}
			});
		}
	}
</script>
<script>
	function confirmar() {
		var numfalta = '<?= $dados[0]['NUMNOTA'] ?>';

		$.ajax({
			type: 'POST',
			url: 'Controle/faltaControle.php',
			data: {
				'action': 'finalizarFalta',
				'query': numfalta
			},
			success: function(response) {
				console.log(response);
				$.redirect('home.php');
			}
		});
	}
</script>

</html>