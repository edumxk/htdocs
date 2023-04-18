<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Controle/cargasControle2.php');
session_start();
header('Content-Type: text/html; charset=UTF-8');
if ($_SESSION['nome'] == null) {
	header("location:\..\..\home.php");
}
date_default_timezone_set('America/Araguaina');

if (isset($_GET['rca'])) {
	$buscaRca = $_GET['rca'];
} else {
	$buscaRca = '';
}

$data = date("Y-m-d", time() - (15 * 24 * 60 * 60));
//echo $data;
$ret = CargasControle::getPedidosView($buscaRca);


$pedidosSemCarga = $ret['pedidosSemCarga'];
$rcaSemCarga = $ret['rca'];
$pedidosComCarga = $ret['pedidosComCarga'];
$cargas = $ret['cargas'];
$faltas = CargasControle::getFaltas();

$totPesoL = 0; //contador roda pé
$totPesoP = 0;
$qtPedCarg = 0;
$cargaNum = 0;
$faux = 0;
$totValorCarga = 0;
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Recarrega a cada 5 min. -->


	<title>Cargas |Kokar</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico">
	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="recursos/css/table.css" rel="stylesheet">
	<link href="recursos/css/estilo.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

</head>

<body style="background-color: teal;width:100%">
	<div class="menu">
		<nav aria-label="breadcrumb" class="navegacao">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Cargas</li>
			</ol>
		</nav>
		<div class="perfil">
			<div class="perfil-detalhes">
				<!--<img src="./src/img/curr.jpg" alt="imagem de perfil">-->
				<div class="nome-setor">
					<div class="nome"><?= $_SESSION['nome'] ?></div>
					<div class="cargo"><?= $_SESSION['cargo'] ?></div>
					<div class="setor"><?= $_SESSION['setor'] ?></div>
				</div>
			</div>
			<a href="#" onclick="sair()">
				<i class='bx bx-exit bx-fade-right' style='color:black; font-size: 20px'></i>
			</a>
		</div>
	</div>

	<div class="row" style="width: 100%;">
		<div class="col-md-4">
		</div>
		<div class="col-md-1">
			<div style="padding-bottom: 20px">
				<button style="width:100px" type="submit" class="btn btn-sm btn-warning" onclick="openNovaCarga()">
					Nova Carga
					<i style="padding-left:10px" class="fas fa-truck"></i>
				</button>
			</div>
		</div>
		<div class="col-md-1">
			
		</div>
		<div class="col-md-2">
		</div>

		<div class="col-md-1">
			<button style="width:100%" type="submit" class="btn btn-sm btn-success" onclick="location.reload()">
				<i class="fas fa-sync fa-xs"></i>
			</button>
		</div>
		<div class="col-md-4">
		</div>
	</div>
	<div class="div" style="padding:20px">
		<div class="row">
			<div id="semCarga" class="col-md-12" style="padding-bottom: 30px; padding-top: 30px; background-color: white">
				<h4 style="text-align:center">Sem Carga</h4>
				<div class="row" style="padding-bottom:20px">
					<div class="col-md-12">
						<div class="row" style="padding-bottom:10px">
							<div class="col-md-1">
								<label for="selectRCA" style="font-size: 20px;">RCA</label>
							</div>
							<div class="col-md-2">
								<select id="selectRCA" onchange="if (this.selectedIndex)  selectRca(this)" style="width: 100%">
									<option value="">TODOS</option>
									<?php foreach ($rcaSemCarga as $r) :
										if ($buscaRca == $r['nome']) :
									?>
											<option value="<?php echo $r['nome'] ?>" selected><?php echo $r['nome'] ?></option>
										<?php else : ?>
											<option value="<?php echo $r['nome'] ?>"><?php echo $r['nome'] ?></option>
									<?php endif;
									endforeach ?>
								</select>
							</div>
							<div class="col-sm-1">
								<form action="cargasView.php" method="get">
									<button style="width: 100%" type="submit" name="rca" class="btn btn-sm btn-success" id="valueRca" value="">BUSCAR</button>
								</form>
							</div>
							<div class="col-sm-4" style="text-align: right;">
								<div style="padding-bottom: 20px">
									<button style="width:110px" type="submit" class="btn btn-sm btn-primary" onclick="enviaSaldo(this)">
										Enviar Saldo
										<i style="padding-left:10px" class="fas fa-truck"></i>
									</button>
								</div>
							</div>
							<div class="col-sm-1">
								<h5 class="float-right">Trocar:</h5>
							</div>
							<div class="col-sm-2">
								<select id="selectCarga" class="select" style="width: 100%">
									<option value="" selected>ESCOLHER</option>
									<?php foreach ($cargas as $c) :
										if ($c->status == 'A') : ?>
											<option value="<?php echo $c->numcarga ?>"><?php echo $c->nome ?></option>
									<?php endif;
									endforeach ?>
								</select>
							</div>
							<div class="col-sm-1">
								<button style="width:100%" type="submit" class="btn btn-sm btn-success" onclick="trocaCargas()">
									<i class="fas fa-check fa-xs"></i>
								</button>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<table style="width: 100%" id="tableSemCarga" class="table_pedidos">
							<thead style="border: 2px solid darkslategray">
								<tr style="background-color: lightgray">
									<th style="text-align: center; font-size:10px; ">DATA</th>
									<th style="text-align: center; font-size:10px; ">HORA</th>
									<th style="text-align: center; font-size:10px; ">RCA</th>
									<th style="text-align: center; font-size:10px; ">NUMPED</th>
									<th style="text-align: center; font-size:10px; "><input type="checkbox" onClick=toggle2(this) name="select-all" id="select-all" /></th>
									<th style="text-align: center; font-size:10px; ">COD</th>
									<th style="text-align: center; font-size:10px">CLIENTE</th>
									<th style="text-align: center; font-size:10px; ">CARGA</th>
									<th style="text-align: center; font-size:10px; ">POS</th>
									<th style="text-align: center; font-size:10px; ">PESO</th>
									<th style="text-align: center; font-size:10px; ">VALOR</th>
									<th style="text-align: center; font-size:10px; ">PRACA</th>
									<th style="text-align: center; font-size:10px; ">CIDADE</th>
									<th style="text-align: center; font-size:10px; ">UF</th>
									<th style="text-align: center; font-size:10px; ">OBS</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pedidosSemCarga as $p) : ?>
									<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
										<td style="text-align: center"><?php echo $p->data ?></td>
										<td style="text-align: center"><?php echo $p->hora ?></td>
										<td style="text-align: left; padding-left: 10px"><?php echo $p->rca ?></td>
										<td style="text-align: right; padding-right: 10px"><?php echo $p->numped ?></td>
										<td style="text-align: center"><input type="checkbox" name="semcarga" value="<?php echo $p->numped ?>" class="ckbIncluir"></td>
										<td style="text-align: center"><?php echo $p->cod ?></td>
										<td style="text-align: left; padding-left: 10px"><?php echo $p->cliente ?></td>
										<td style="text-align: center"><?php echo $p->numcar ?></td>
										<td style="text-align: center" class="pos"><?php echo $p->pos ?></td>
										<td style="text-align: right; padding-right: 10px"><?php echo $p->peso ?></td>
										<td style="text-align: right; padding-right: 10px"><?php echo $p->valor ?></td>
										<td style="text-align: left; padding-left: 10px"><?php echo $p->praca ?></td>
										<td style="text-align: left; padding-left: 10px"><?php echo $p->cidade ?></td>
										<td style="text-align: center"><?php echo $p->uf ?></td>
										<td style="text-align: center"><?php echo $p->obs ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-12" style="padding-bottom: 10px; background-color: white; width: 100%">
				<div class="row" style="padding-bottom: 10px">
					<div class="col-sm-5" style="text-align:center; left: 15%">
						<h4>Cargas Montadas</h4>
					</div>
					<div class="col-sm-2" style="text-align:center"></div>
					<div class="col-sm-1" style="text-align:center">
						<button class="btn btn-warning" onClick="saldoCargas()">Pendente</button>
					</div>
				</div>
				<div class="row">

					<style>
						.resumo {
							width: 100%;
							border: solid 2px black;
						}

						.resumo td {
							font-size: 12px
						}

						.resumo th {
							font-size: 12px;
							border: solid 2px black;
						}
					</style>
					<!-- Função para selecionar todas as checkbox pelo nome -->
					<script language="JavaScript">
						var vresumo

						function toggle(source) {
							checkboxes = document.getElementsByName('resumo');
							for (var i = 0, n = checkboxes.length; i < n; i++) {
								checkboxes[i].checked = source.checked;
							}
						}

						function toggle2(source) {
							checkboxes = document.getElementsByName('semcarga');
							for (var i = 0, n = checkboxes.length; i < n; i++) {
								checkboxes[i].checked = source.checked;
							}
						}
					</script>

					<div class="col">
						<table class="resumo">
							<thead>
								<tr>
									<th style="width:15px; text-align:center"><input type="checkbox" onClick=toggle(this) name="select-all" id="select-all" /></th>
									<th style="padding-left:15px">CARGA</th>
									<th style="width: 90px; text-align:center">FECHAMENTO</th>
									<th style="width: 90px; text-align:center">PRODUÇÃO</th>
									<th style="width: 90px; text-align:center">SAIDA</th>
									<th style="width:150px; text-align:center">PESO</th>
									<th style="width:150px; text-align:center">VALOR</th>
								</tr>
							</thead>
							<tbody>
								<?php $totPesoResumo = 0;
								$totValorResumo = 0; ?>
								<?php foreach ($cargas as $c) : ?>
									<tr onmouseover="resumoHoverIn(this)" onmouseout="resumoHoverOut(this)">
										<td class="chbResumo" style="margin: auto; text-align:center"><input name="resumo" type="checkbox" id="<?php echo $c->numcarga ?>" value="<?= $c->valor ?>"></td>
										<td style="padding-left:15px"><?php echo $c->nome ?></td>
										<td style="text-align:center"><?php echo $c->dtFechamento ?></td>
										<td style="text-align:center"><?php echo $c->dtProducao ?></td>
										<td style="text-align:center"><?php echo $c->dtSaida ?></td>
										<td style="text-align:right; padding-right:15px"><?php echo number_format($c->peso, 2, ',', '.');
																							$totPesoResumo += $c->peso;  ?> KG</td>
										<td style="text-align:right; padding-right:15px">R$ <?php echo number_format($c->valor, 2, ',', '.');
																							$totValorResumo += $c->valor; ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5"></th>
									<th style="text-align:right; padding-right:15px"><?php echo number_format($totPesoResumo, 2, ',', '.');  ?> KG</th>
									<th style="text-align:right; padding-right:15px">R$ <?php echo number_format($totValorResumo, 2, ',', '.');  ?></th>
								</tr>
							</tfoot>
						</table>

					</div>
					<div class="col-4">

						<table class="legenda">
							<thead>
								<tr>
									<th style="width:100px; text-align:center">Legenda</th>
									<th style="width:250px; text-align:center">Descrição</th>

								</tr>
							</thead>
							<tbody>

								<tr>
									<td style="text-align: center;"> <img src="../modCargas/Recursos/img/st.JPG" alt="ST"> </td>
									<td style="padding-left:10px;">
										<h5>Conferir Calculo de ST </h5>
									</td>

								</tr>
								<tr>
									<td style="text-align: center;"> <img src="../modCargas/Recursos/img/icon3.JPG" alt="Parc"> </td>
									</td>
									<td style="padding-left:10px;">
										<p>
										<h5> Azul: Valor suficiente para venda.</h5>
										</p>
										<p>
										<h5> Vermelho: Valor inferior ao mínimo para venda.</h5>
										</p>
										<p>
										<h5> Branco: Valor sem parcelamento.</h5>
										</p>

									</td>

								</tr>
								<tr>
									<td style="text-align: center;"> <img src="../modCargas/Recursos/img/icon1compra.png" width="60px" height="60px" alt="Pcompra"> </td>
									</td>
									<td style="padding-left:10px;">
										<h5> Primeira Compra</h5>
									</td>

								</tr>
								<td style="text-align: center;"> <button style="width:75px" type="submit" class="btn btn-sm btn-success"><i class="fas fa-truck fa-4x"></i></button> </td>
								</td>
								<td style="padding-left:10px;">
									<h5> Indicativo de Falta</h5>
								</td>
								</tr>
								<td style="text-align: center;"> <button style="width:55px" type="submit" class="btn btn-sm btn-warning"> <i class="fa fa-male fa-4x"></i> </button> </td>
								</td>
								<td style="padding-left:10px;">
									<h5> Consumidor Final</h5>
								</td>


								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div>.</div>
				<?php foreach ($cargas as $c) : ?>
					<div id="comCargas" class="col-md-12" style="padding-bottom: 20px;">
						<div class="cabecaTabela" style="background-color: lightblue">
							<div class="cabecalho">
								<div class="status">
									<?php if ($c->status != 'A') : ?>
										<button class="btn btn-status"><span class="status1"><?= 'F' ?></span></button> <span class="data1"><?php echo $c->dtFechamento ?></span>
									<?php endif ?>
									<?php if ($c->status == 'A') : ?>
										<button class="btn btn-status"><span class="status1"><?= 'A' ?></span></button> <span class="data1"><?php echo $c->dtPrevisao ?></span>
									<?php endif ?>
								</div>
								<div class="status">
									<?php if ($c->dtProducao != '') : ?>
										<button class="btn btn-status"><span class="status2"><?php echo 'P' ?></span></button> <span class="data2"><?php echo $c->dtProducao ?></span>
									<?php endif ?>
								</div>
								<div class="status">
									<?php if ($c->dtSaida != '') : ?>
										<button class="btn btn-status"><span class="status2"><?php echo 'S' ?></span></button> <span class="data2"><?php echo $c->dtSaida ?></span>
									<?php endif ?>
								</div>
								<div class="nome-cabecalho">
									<span><?php echo $c->nome ?></span>
								</div>
								<div class="nome-cabecalho">
									<span> Peso: <?php echo number_format($c->peso, '2', ',', '.') ?> kg</span>
								</div>
								<div class="btn-carga">
									<div>
										<button type="button" style="width:35px; height:28px" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCarga" onclick="openModalChapa(<?php echo $c->numcarga ?>)">
											<i class='bx bxs-dollar-circle' style="font-size: 20px"></i>
									</div>
									<?php if ($c->status == 'A') : ?>
										<div class="">
											<button style="width:35px; height:28px" type="submit" class="btn btn-sm btn-success" value="<?php echo $c->numcarga ?>" onclick="travar(this)">
												<!-- <img style="width:20px; height:20px" src="/Recursos/src/rca.png"> -->
												<i class='bx bxs-lock-open' style="font-size: 20px"></i>
											</button>
										</div>
									<?php else : ?>
										<div class="">
											<button style="width:35px; height:28px" type="submit" class="btn btn-sm btn-warning" value="<?php echo $c->numcarga ?>" onclick="destravar(this)">
												<!-- <img style="width:20px; height:20px" src="/Recursos/src/rca2.png"> -->
												<i class='bx bxs-lock bx-tada' style="font-size: 20px"></i>
											</button>
										</div>
									<?php endif ?>
									<div class="">
										<button style="width:35px; height:28px" type="submit" class="btn btn-sm btn-warning" value="<?php echo $c->numcarga ?>" onclick="openEditCarga(this)" <?php if ($_SESSION['codsetor'] > 1 && $_SESSION['codsetor'] != 5 && $_SESSION['codsetor'] != 101) : ?>disabled <?php endif ?>>
											<i class="fas fa-edit fa-lg"></i>
										</button>
									</div>
									<div class="">
										<button style="width:35px; height:28px" type="submit" class="btn btn-sm btn-success" onclick="getPendencias('<?php echo $c->numcarga ?>')">
											<i class="fas fa-eye fa-lg"></i>
										</button>
									</div>
									<div class="">
										<button style="width:35px; height:28px" type="submit" class="btn btn-sm btn-primary" onclick="getDisponivel('<?php echo $c->numcarga ?>')">
											<i class="fas fa-cog fa-lg"></i>
										</button>
									</div>
									<div class="">
										<button style="width:35px; height:28px" type="submit" class="btn btn-sm btn-danger" value="<?php echo $c->numcarga ?>-<?php echo $c->nome ?>" onclick="openDeleteCarga(this)" <?php if ($_SESSION['codsetor'] > 1 && $_SESSION['codsetor'] != 5 && $_SESSION['codsetor'] != 101) : ?>disabled <?php endif ?>>
											<i class="fas fa-trash fa-lg"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="row" style="padding-bottom:20px">
							<div class="col-md-12">
								<table style="width: 100%" class="table_pedidos">
									<thead style="border: 2px solid darkslategray">
										<tr style="background-color: lightgray">
											<th style="text-align: center; font-size:10px; width:6%">DATA</th>
											<th style="text-align: center; font-size:10px; width:3%">HORA</th>
											<th style="text-align: center; font-size:10px; width:8%">RCA</th>
											<th style="text-align: center; font-size:10px; width:6%">NUMPED</th>
											<th style="text-align: center; font-size:10px; width: 3%">COD</th>
											<th style="text-align: center; font-size:10px; width:30%">CLIENTE</th>
											<th style="text-align: center; font-size:10px; width:3%">PARC</th>
											<th style="text-align: center; font-size:10px; width:3%">CARGA</th>
											<th style="text-align: center; font-size:10px; width:2%">POS</th>
											<th style="text-align: center; font-size:10px; width:2%">VER</th>
											<th style="text-align: center; font-size:10px; width:6%">PESO</th>
											<th style="text-align: center; font-size:10px; width:6%">VALOR</th>
											<th style="text-align: center; font-size:10px; width: 12%">PRACA</th>
											<th style="text-align: center; font-size:10px; width: 12%">CIDADE</th>
											<th style="text-align: center; font-size:10px; width: 2%">UF</th>
											<th style="text-align: center; font-size:10px; width: 2%">OBS</th>
											<th style="text-align: center; font-size:10px">#</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pedidosComCarga as $p) :
											if ($p->numcarga == $c->numcarga) :
												if ($cargaNum != $c->numcarga) {
													$cargaNum = $c->numcarga;
												} else {
												} ?>
												<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
													<?php if ($p->obs == 'SD' && strtotime(Formatador::dataFormatUs2($p->data)) <= strtotime($data)) { ?>
														<td style="text-align: center; background-color:tomato"><?php echo $p->data; ?></td>
													<?php } else { ?>
														<td style="text-align: center"><?php echo $p->data; ?></td>
													<?php } ?>
													<td style="text-align: center"><?php echo $p->hora; ?></td>
													<td style="text-align: left; padding-left: 10px"><?php echo $p->rca ?></td>
													<td style="text-align: right; padding-right: 10px" id="montNumped"><?php echo $p->numped ?></td>
													<td style="text-align: center"><?php echo $p->cod ?></td>

													<!-- inicio de impressão de faltas e consumidor final -->
													<?php foreach ($faltas as $f) {
														if ($f->posicaof == 'P' && $f->clientef == $p->cod && $faux == 0) {
															if ($p->consumidorfinal == 'S') { ?>
																<td style="text-align: left; padding-left: 5px"> <button style="width:25px" type="submit" class="btn btn-sm btn-warning"> <i class="fa fa-male fa-lg"></i> </button> <button style="width:35px" type="submit" class="btn btn-sm btn-success" onclick="getFaltasCli2('<?php echo $p->cod ?>')">
																		<i class="fas fa-truck fa-lg"></i>
																	</button> <?php echo $p->cliente ?> </td>
															<?php $faux = 1;
															} else if ($p->st == 0 && $p->calculast == 'S') {  ?>
																<td style="text-align: left; padding-left: 10px; background-color:cyan"> <button style="width:35px" type="submit" class="btn btn-sm btn-success" onclick="getFaltasCli2('<?php echo $p->cod ?>')">
																		<i class="fas fa-truck fa-lg"></i>
																	</button> <?php echo $p->cliente ?> </td>
															<?php $faux = 1;
															} else {  ?>
																<td style="text-align: left; padding-left: 10px"> <button style="width:35px" type="submit" class="btn btn-sm btn-success" onclick="getFaltasCli2('<?php echo $p->cod ?>')">
																		<i class="fas fa-truck fa-lg"></i>
																	</button> <?php echo $p->cliente ?> </td>
															<?php $faux = 1;
															}
														}
													}
													if ($faux == 0) {
														if ($p->consumidorfinal == 'S') { ?>
															<td style="text-align: left; padding-left: 5px"> <button style="width:25px" type="submit" class="btn btn-sm btn-warning"> <i class="fa fa-male fa-lg"></i> </button> <?php echo $p->cliente ?></td>
														<?php } else if ($p->st == 0 && $p->calculast == 'S') { ?>
															<td style="text-align: left; padding-left: 10px; background-color:cyan"><?php echo $p->cliente ?> </td>
														<?php } else { ?>
															<td style="text-align: left; padding-left: 10px"><?php echo $p->cliente ?></td>
													<?php }
													}
													$faux = 0; ?>
													<!-- fim de impressão de faltas e consumidor final -->

													<?php if ($p->vlparc >= 270 && $p->qtparc > 1) { ?>
														<td style="text-align: center; background-color:lightblue"><?php echo $p->qtparc ?></td>
													<?php } else if ($p->qtparc > 1) { ?>
														<td style="text-align: center; background-color:red"><?php echo $p->qtparc ?></td>
													<?php } else { ?>
														<td style="text-align: center"><?php echo $p->qtparc ?></td>
													<?php } ?>

													<td style="text-align: center"><?php echo $p->numcar ?></td>
													<td style="text-align: center" class="pos" id="<?php echo $p->numped ?>"><?php echo $p->pos ?></td>
													<td style="text-align: center">
														<button type="submit" style="font-size:5px" class="btn-success btn-xs" onclick="getPendenciasPedidoEspecial('<?php echo $p->numped ?>')">
															<i class="fas fa-eye fa-2x"></i>
														</button>
													</td>
													<td style="text-align: right; padding-right: 10px"><?php echo number_format($p->peso, '2', ',', '.');
																										$qtPedCarg += 1 ?></td>
													<?php
													if ($p->pos == 'B' || $p->pos == 'P') {
														$totPesoP += $p->peso;
													} else {
														if ($p->pos == 'L' || $p->pos == 'M') {
															$totPesoL += $p->peso;
														}
													}

													?>
													<td style="text-align: right; padding-right: 10px"><?php echo $p->valor;
																										$totValorCarga += $p->valor ?></td>
													<?php if ($p->primeiracompra != '01/01/9999') { ?>
														<td style="text-align: left; padding-left: 10px"><?php echo $p->praca ?></td>
													<?php } else { ?>
														<td style="text-align: left; padding-left: 10px"><img src="../modCargas/Recursos/img/icon1compra.png" width="20px" height="20px" alt="Pcompra"> <?php echo $p->praca ?></td>
													<?php } ?>
													<td style="text-align: left; padding-left: 10px"><?php echo $p->cidade ?></td>
													<td style="text-align: center"><?php echo $p->uf ?></td>
													<td style="text-align: center"><?php echo $p->obs ?></td>
													<td style="text-align: center">
														<button type="submit" style="font-size:5px" class="btn-danger btn-xs" value="' + iprod['numped'] + '" onclick="setSemCarga(this)">
															<i class="fas fa-trash"></i>
														</button>
													</td>
												</tr>
											<?php endif ?>
										<?php endforeach ?>
									</tbody>
								</table>
								<!-- Roda pé -->
								<div class="" style="background-color: lightblue">
									<div class="row">
										<div class="col-md-2">
											<h4 style="text-align:center" class="">R$ <?php echo number_format($totValorCarga, '2', ',', '.') ?></h4>
										</div>
										<div class="col-md-4">
											<h4 style="text-align:center" class="">Peso Pendente: <?php echo number_format($totPesoP, '2', ',', '.') ?> kg</h4>
										</div>
										<div class="col-md-4">
											<h4 style="text-align:center" class="">Peso Liberado: <?php echo number_format($totPesoL, '2', ',', '.') ?> kg</h4>
										</div>
										<div class="col-md-2">
											<h4 style="text-align:right" class="">Pedidos: <?php echo $qtPedCarg;
																							$qtPedCarg = 0;
																							$totPesoL = 0;
																							$totPesoP = 0;
																							$cargaNum = 0;
																							$totValorCarga = 0 ?></h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			</div>

			<!-- INICIO MODAL DE INCLUSÃO DE CARGA -->
			<div class="modal" tabindex="-1" role="dialog" id="modalNovaCarga">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Cadastrar Nova Carga</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row d-flex justify-content-center">
								<div class="col-md-6">
									<div class="col-md-12">
										<label for="novoNome">Nome da Carga</label>
										<input id="novoNome" autocomplete="off">
									</div>
									<div class="col-md-12" style="padding-bottom:5px">
										<label for="novoVeiculo">Veiculo</label>
										<input id="novoVeiculo" autocomplete="off">
									</div>
									<div class="col-md-12">
										<div style="padding-bottom:5px">Previsão</div>
										<input style="width:90%" type="date" id="novoPrevisao">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="setNovaCarga()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->
			<!-- INICIO MODAL PARA REMOVER CARGA -->
			<div class="modal" tabindex="-1" role="dialog" id="modalDeleteCarga">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Remover Carga</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="modal-body">
							<div id="detele">
							</div>
							<div id="idDelete" hidden>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="deleteCarga()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->
			<!-- INICIO MODAL PARA EDITAR CARGA -->
			<div class="modal" tabindex="-1" role="dialog" id="modalEditarCarga">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Editar Carga</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="modal-body">
							<div class="row d-flex justify-content-center">
								<div class="col-md-6">
									<div class="col-md-12">
										<a id="editNumcarga" hidden></a>
										<label for="editNome">Nome da Carga</label>
										<input id="editNome" autocomplete="off">
									</div>
									<div class="col-md-12" style="padding-top:5px">
										<label for="editVeiculo">Veiculo</label>
										<input id="editVeiculo" autocomplete="off">
									</div>
									<div class="col-md-12">
										<div style="padding-top:5px">Programação de Carga</div>
										<input style="width:90%" type="date" id="editAbertura">
									</div>
									<div class="col-md-12">
										<div style="padding-top:5px">Produção</div>
										<input style="width:90%" type="date" id="editProducao">
									</div>
									<div class="col-md-12">
										<div style="padding-top:5px">Saida</div>
										<input style="width:90%" type="date" id="editSaida">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="editCarga()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->
			<!-- INICIO MODAL PARA EDITAR CARGA -->
			<div class="modal" tabindex="-1" role="dialog" id="modalEditarChapa">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Editar Valor de Chapa</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row d-flex justify-content-center">
								<div class="col-md-6">
									<div class="col-md-12">
										<label for="nomeMotorista">Motorista</label>
										<input id="nomeMotorista" autocomplete="off">
									</div>
									<div class="col-md-12" style="padding-top:5px">
										<label for="placaChapa">Placa</label>
										<input id="placaChapa" autocomplete="off">
									</div>
									<div class="col-md-12" style="padding-top:5px">
										<label for="valorChapa">Valor de Chapa</label>
										<input id="valorChapa" autocomplete="off">
										<input id="numCargaChapa" hidden type="text">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="valorChapa()">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->
			<!-- INICIO MODAL PARA VISUALIZAR PENDENCIAS -->
			<div class="modal" tabindex="-1" role="dialog" id="modalVerPendencias">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Pendencias da Carga</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto; height: 450px">
									<table style="width:100%">
										<thead>
											<tr>
												<th colspan="4" style="width:40px; text-align:center">COMERCIAL</th>
												<th colspan="3" style="width:40px; text-align:center">PCP</th>
											</tr>
											<tr>
												<th style="width:40px; text-align:center">COD</th>
												<th style="padding-left:10px">PRODUTO</th>
												<th style="width:40px; text-align:center">PEDIDO</th>
												<th style="width:40px; text-align:center">ESTOQUE</th>

												<th style="width:40px; text-align:center">STATUS</th>
												<th style="width:40px; text-align:center">QTD</th>
												<th style="width:40px; text-align:center">PREVISÃO</th>

											</tr>
										</thead>
										<tbody id="pendencias">
										</tbody>
									</table>
									<b>
										<div id="pesoTotal" style="text-align:right">
										</div>
									</b>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->

			<!-- INICIO MODAL PARA VISUALIZAR PENDENCIAS PEDIDOS -->
			<div class="modal" tabindex="-1" role="dialog" id="modalVerPendenciasPedidos">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Pendencias do Pedido</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto; height: 450px">
									<table style="width:100%">
										<thead>
											<tr>
												<th colspan="4" style="width:40px; text-align:center">COMERCIAL</th>
												<th colspan="3" style="width:40px; text-align:center">PCP</th>
											</tr>
											<tr>
												<th style="width:40px; text-align:center">COD</th>
												<th style="padding-left:10px">PRODUTO</th>
												<th style="width:40px; text-align:center">PEDIDO</th>
												<th style="width:40px; text-align:center">ESTOQUE</th>
												<th style="width:40px; text-align:center">STATUS</th>
												<th style="width:40px; text-align:center">QTD</th>
												<th style="width:40px; text-align:center">PREVISÃO</th>
											</tr>
										</thead>
										<tbody id="pendenciasPedidos">
										</tbody>
									</table>
									<div id="pesoTotal9" style="text-align:right">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->

			<!-- INICIO MODAL PARA VISUALIZAR PENDENCIAS FALTAS -->
			<div class="modal" tabindex="-1" role="dialog" id="modalVerPendenciasfaltas">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Faltas do Cliente</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto; height: 400px">
									<table style="width:100%">
										<thead>
											<div>
												<h2>Kokar Indústria e Comércio de Tintas</h1>
											</div>
											<tr>
												<th style="width:35px; text-align:center">NFE</th>
												<th style="width:35px; text-align:center">COD</th>
												<th style="padding-left:10px">PRODUTO</th>
												<th style="width:25px; text-align:center">QT</th>
												<th style="width:25px; text-align:center">EST</th>
												<th style="width:55px; text-align:center">DATA</th>
												<th style="width:55px; text-align:center">MOTIVO</th>
											</tr>
										</thead>
										<tbody id="pendenciasFaltas">
										</tbody>
									</table>
									<b>
										<p>
										<p>
										<div id="info" style="text-align:left"></div>
									</b>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->

			<!-- INICIO MODAL PARA VISUALIZAR SALDO CARGA -->
			<div class="modal" role="dialog" id="modalSaldoCarga">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content" id="contentSaldo">
						<div class="modal-header">
							<h5 class="modal-title">Consolidado Pendente por Carga</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto; height: 450px; ">
									<table id="tblSaldoCarga" style="width:100%">
										<thead>
											<tr>
												<th colspan="4" style="width:40px; text-align:center">COMERCIAL</th>
												<th colspan="3" style="width:40px; text-align:center">PCP</th>
											</tr>
											<tr>
												<th style="width:40px; text-align:center">COD</th>
												<th style="padding-left:10px">PRODUTO</th>
												<th style="width:40px; text-align:center">PEDIDO</th>
												<th style="width:40px; text-align:center">ESTOQUE</th>
												<th style="width:40px; text-align:center">STATUS</th>
												<th style="width:40px; text-align:center">QTD</th>
												<th style="width:40px; text-align:center">PREVISÃO</th>

											</tr>
										</thead>
										<tbody id="saldoCarga">
										</tbody>
									</table>
									<b>
										<div id="pesoTotal4" style="text-align:right"></div>
									</b>
									<b>
										<div id="pesoTotal5" style="text-align:right"></div>
									</b>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- FIM MODAL -->


			<script src="../../recursos/js/jquery.min.js"></script>
			<script src="../../recursos/css/jquery.formatNumber-master/jquery.formatNumber-0.1.1.min.js"></script>
			<script src="../../recursos/js/bootstrap.min.js"></script>
			<script src="../../recursos/js/scripts.js"></script>
			<script src="../../recursos/js/Chart.bundle.min.js"></script>
			<script src="../../recursos/js/jquery.tablesorter.js"></script>
			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
			<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>

<script>
	$(document).ready(function() {

		$.each($('.pos'), function(index, value) {
			//value.addEventListener("click", console.log('ok'));
			//console.log($(value).prop("id"));
		});


		$(".cabecaTabela").each(function() {
			d = $(this).find('.data1').text();
			s = d.split("/");
			elm = $(this).find('.data1');

			if ($(this).find('.status1').text() == 'F') {
				d2 = $(this).find('.data2').text();
				prev = corPrevisao(d2)

				if ($(this).find('.status2').text() == 'S') {
					if (prev < 0) {
						$(elm).parent().parent().css("background-color", "tomato");
					} else if (prev == 0) {
						$(elm).parent().parent().css("background-color", "yellow");
					} else if (prev > 0) {
						$(elm).parent().parent().css("background-color", "lightgreen");
					}
				} else if (prev < 0) {
					$(elm).parent().parent().css("background-color", "tomato");
				} else if (prev < 2) {
					$(elm).parent().parent().css("background-color", "yellow");
				} else if (prev < 100) {
					$(elm).parent().parent().css("background-color", "lightgreen");
				}
			}

		});




		$(".pos").each(function() {
			pos = $(this).text()

			if (pos == "L") {
				$(this).css("background-color", "lightgreen");
			} else if (pos == "P") {
				$(this).css("background-color", "yellow");
			} else if (pos == "B") {
				$(this).css("background-color", "red");
			} else if (pos == "M") {
				$(this).css("background-color", "lightblue");
			}

		});
		$(".table_pedidos").tablesorter({
			dateFormat: 'mmddYYYY',
			headers: {
				0: {
					sorter: "shortDate",
					dateFormat: "ddmmyyyy"
				}
			}
		});
		var btn = $(".btn-status");
		btn.each(function(i) {
			switch (this.children[0].innerHTML) {
				case 'A':
					this.classList.add('btn-secondary');
					break;
				case 'F':
					this.classList.add('btn-primary');
					break;
				case 'P':
					this.classList.add('btn-warning');
					break;
				case 'S':
					this.classList.add('btn-success');
					break;
			}
		})


	});


	function selectRca(elm) {
		a = $("option:selected", elm).text();
		$("#valueRca").val(a)
	}

	function getPendencias(elm) {
		console.log(elm)
		nome = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'getPendencias',
				'query': nome
			},
			success: function(response) {
				console.log(response);
				arr = JSON.parse(response);

				body = "";
				$('#pendencias').text("");
				peso = 0;
				arr.forEach(function(t) {
					console.log(t)
					peso = peso + parseFloat(t['PESO']);
					body += '<tr>' +
						'<td style="width:40px; text-align:center">' + t['CODPROD'] + '</td>' +
						'<td style="padding-left:10px">' + t['DESCRICAO'] + '</td>' +
						'<td style="width:40px; text-align:center">' + t['QT'] + '</td>' +
						'<td style="width:40px; text-align:center">' + t['QTDISP'] + '</td>' +
						'<td style="width:120px; text-align:center">' + t['STATUS'] + '</td>' +
						'<td style="width:40px; text-align:center">' + t['QTPROD'] + '</td>' +
						'<td style="width:80px; text-align:center">' + t['PREVISAO'] + '</td>' +
						'</tr>';
				})
				$('#pendencias').empty();
				$('#pendencias').append(body);
				$('#pesoTotal').text("Peso da pendência " + parseFloat(peso).toFixed(2) + " kg");
				$('#modalVerPendencias').modal('toggle');
			}
		});
	}

	function getDisponivel(elm) {
		console.log(elm);

		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'getDisponivel',
				'query': elm
			},
			success: function(response) {
				// console.log(response);
				arr = JSON.parse(response);
				arr.forEach(function(t) {
					console.log($('#' + t['NUMPED']))
					$('#' + t['NUMPED']).css('background-color', 'cyan');

				})
			}
		})
	}
	//MODAL DE PEDIDOS PENDENTES
	function getPendenciasPedidoEspecial(elm) {
		console.log(elm)
		numped = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'getPendenciasPedidoEspecial',
				'query': numped
			},
			success: function(response) {
				console.log(response);
				arr = JSON.parse(response);
				body = "";
				peso2 = 0;
				//$('#pendencias').text("");
				arr.forEach(function(t) {
					console.log(t)
					peso2 = peso2 + parseFloat(t['PESOP']);
					if (t['DISP'] == 'S') {
						body += '<tr>' +
							'<td style="width:40px; text-align:center">' + t['CODPROD'] + '</td>' +
							'<td style="padding-left:10px;">' + t['DESCRICAO'] + '</td>' +
							'<td style="width:40px; text-align:center">' + t['QT'] + '</td>' +
							'<td style="width:40px; text-align:center">' + t['QTDISP'] + '</td>' +
							'<td style="width:120px; text-align:center">' + t['STATUS'] + '</td>' +
							'<td style="width:40px; text-align:center">' + t['QTPROD'] + '</td>' +
							'<td style="width:80px; text-align:center">' + t['PREVISAO'] + '</td>' +
							'</tr>';
					} else {
						body += '<tr>' +
							'<td style="width:40px; color:red; text-align:center">' + t['CODPROD'] + '</td>' +
							'<td style="padding-left:10px; color:red">' + t['DESCRICAO'] + '</td>' +
							'<td style="width:40px; color:red; text-align:center">' + t['QT'] + '</td>' +
							'<td style="width:40px; color:red; text-align:center">' + t['QTDISP'] + '</td>' +
							'<td style="width:120px; text-align:center">' + t['STATUS'] + '</td>' +
							'<td style="width:40px; text-align:center">' + t['QTPROD'] + '</td>' +
							'<td style="width:80px; text-align:center">' + t['PREVISAO'] + '</td>' +
							'</tr>';
					}
				})
				$('#pendenciasPedidos').empty();
				$('#pendenciasPedidos').append(body);
				$('#pesoTotal9').text("Peso da pendência " + parseFloat(peso2).toFixed(2) + " kg");
				$('#modalVerPendenciasPedidos').modal('toggle');
			}
		});
	}

	//MODAL DE FALTAS
	function getFaltasCli2(elm) {
		cod = elm
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'getFaltasCli',
				'query': cod
			},
			success: function(response) {
				arr = JSON.parse(response);
				body = "";
				arr.forEach(function(t) {
					console.log(t)
					if (parseFloat(t['ESTOQUE']) >= parseFloat(t['QTFALTA'])) {
						body += '<tr>' +
							'<td style="text-align:center">' + t['NUMNOTA'] + '</td>' +
							'<td style="text-align:center">' + t['CODPROD'] + '</td>' +
							'<td style="padding-left:10px">' + t['DESCRICAO'] + '</td>' +
							'<td style="text-align:center">' + t['QTFALTA'] + '</td>' +
							'<td style="text-align:center">' + t['ESTOQUE'] + '</td>' +
							'<td style="text-align:center">' + t['DATA'] + '</td>' +
							'<td style="text-align:center">' + t['MOTIVO'] + '</td>'

							+
							'</tr>';
					} else {
						body += '<tr>' +
							'<td style="color:red; text-align:center">' + t['NUMNOTA'] + '</td>' +
							'<td style="color:red; text-align:center">' + t['CODPROD'] + '</td>' +
							'<td style="color:red; padding-left:10px">' + t['DESCRICAO'] + '</td>' +
							'<td style="color:red; text-align:center">' + t['QTFALTA'] + '</td>' +
							'<td style="color:red; text-align:center">' + t['ESTOQUE'] + '</td>' +
							'<td style="color:red; text-align:center">' + t['DATA'] + '</td>' +
							'<td style="color:red; text-align:center">' + t['MOTIVO'] + '</td>'

							+
							'</tr>';
					}
				})
				$('#pendenciasFaltas').empty();
				$('#pendenciasFaltas').append(body);
				$('#info').text("No winthor, imprimir o recibo da falta na rotina 8116.");
				$('#modalVerPendenciasfaltas').modal('toggle');
			}
		});
	}
</script>

<script>
	function corPrevisao(data) {
		d = data.split("/")
		daux = d[0]
		strData = d[2] + '/' + d[1] + '/' + daux
		dataPedido = new Date(strData);
		dAtualAux = new Date()
		dAtual = dAtualAux.getFullYear()
		dAtual += "/" + (dAtualAux.getMonth() + 1)
		dAtual += "/" + dAtualAux.getDate()
		dataAtual = new Date(dAtual);
		dias = (dataPedido - dataAtual);
		if (dias != 0)
			dias = dias / 100000 / 24 / 24;

		return dias;
	}
</script>

<script>
	//SCRIPT DE AÇÃO COM O BANCO DE DADOS.

	function setSemCarga(elm) {
		numped = $(elm).parent().parent().find('#montNumped').text();
		status = $(elm).parent().parent().parent().parent().parent().parent().parent().find('.btn-status').text();
		console.log(status)
		if (status[0] == 'A') {
			$.ajax({
				type: 'POST',
				url: 'controle/cargasControle2.php',
				data: {
					'action': 'setSemCarga',
					'query': numped
				},
				success: function(response) {
					console.log(response);
					if (response.match("ok")) {
						location.reload()
					}
				}
			});
		} else {
			alert("Carga está fechada!")
		}

	}

	function selectRca(elm) {
		a = $("option:selected", elm).text();
		$("#valueRca").val(a)
	}

	function trocaCargas() {
		trocaCarga = $('#selectCarga').val();
		arr = [];
		$('#tableSemCarga').find('input[type="checkbox"]:checked').each(function() {
			arr.push($(this).val())
		});

		//console.log(trocaCarga)
		dataset = {
			"numcarga": trocaCarga,
			"pedidos": arr
		};
		console.log(dataset)

		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'setGroupCarga',
				'query': dataset
			},
			success: function(response) {
				//console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}
	// Listen for click on toggle checkbox
	function selectall() {
		$('#select-all').click(function(event) {
			if (this.checked) {
				// Iterate each checkbox
				$(':checkbox').each(function() {
					this.checked = true;
				});
			} else {
				$(':checkbox').each(function() {
					this.checked = false;
				});
			}
		});
	}
	/* Função Ajax para somar cargas montadas selecionadas com checkbox */
	function saldoCargas() {
		arr = [];
		soma = 0;
		var elements = document.getElementsByClassName("hid_id");
		//var value = elements[0].value;
		$(".chbResumo").find('input[type="checkbox"]:checked').each(function(c, elm) {
			arr.push(elm.id)
			soma += (parseFloat(elm.value));
		});
		if (arr.length > 0) {
			$.ajax({
				type: 'POST',
				url: 'controle/cargasControle2.php',
				data: {
					'action': 'getSaldoCarga',
					'query': arr
				},
				success: function(response) {
					arr = JSON.parse(response);
					body = "";
					$('#saldoCarga').text("");
					pesoTotalPen = 0;
					arr.forEach(function(t) {
						if (t['PROMO'] == t['CODPROD']) {
							body += '<tr style="font-size: 13px">' +
								'<td style="width:40px; text-align:center; background-color: yellow">' + t['CODPROD'] + '</td>' +
								'<td style="padding-left:10px">' + t['DESCRICAO'] + '</td>' +
								'<td style="width:60px; text-align:center">' + t['QT'] + '</td>' +
								'<td style="width:60px; text-align:center">' + t['QTEST'] + '</td>' +
								'<td style="width:120px; text-align:center">' + t['STATUS'] + '</td>' +
								'<td style="width:40px; text-align:center">' + t['QTPROD'] + '</td>' +
								'<td style="width:80px; text-align:center">' + t['PREVISAO'] + '</td>'
						} else {
							body += '<tr style="font-size: 13px">' +
								'<td style="width:40px; text-align:center">' + t['CODPROD'] + '</td>' +
								'<td style="padding-left:10px">' + t['DESCRICAO'] + '</td>' +
								'<td style="width:60px; text-align:center">' + t['QT'] + '</td>' +
								'<td style="width:60px; text-align:center">' + t['QTEST'] + '</td>' +
								'<td style="width:120px; text-align:center">' + t['STATUS'] + '</td>' +
								'<td style="width:40px; text-align:center">' + t['QTPROD'] + '</td>' +
								'<td style="width:80px; text-align:center">' + t['PREVISAO'] + '</td>'
						}


						pesoTotalPen = pesoTotalPen + parseFloat(t['PESOPENDENTE']);
					})
					$('#pesoTotal4').empty();
					$('#pesoTotal5').empty();
					$('#saldoCarga').empty();
					$('#saldoCarga').append(body);
					$('#pesoTotal4').text("Peso da pendência " + parseFloat(pesoTotalPen).toFixed(2) + " kg");
					$('#pesoTotal5').text("Valor Total: R$ " + (soma).toFixed(2));
					$('#modalSaldoCarga').modal('toggle');
				}
			});
		}
	}

	function openNovaCarga() {
		$('#modalNovaCarga').modal('toggle');
	}

	function setNovaCarga() {
		nome = $('#novoNome').val();
		veiculo = $('#novoVeiculo').val();
		data = $('#novoPrevisao').val();

		dataset = {
			"nome": nome,
			"veiculo": veiculo,
			"data": data
		};
		//console.log(dataset)
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'setNovaCarga',
				'query': dataset
			},
			success: function(response) {
				console.log(response);
				if (response.match("ok")) {
					location.reload()
				}
			}
		});
	}

	function openEditCarga(elm) {
		//console.log($(elm).val())
		$('#modalEditarCarga').modal('toggle');

		numcargaEdit = $(elm).val()

		//console.log(numcargaEdit)

		numcargaEdita = '';
		nomeEdita = '';
		nomeVeiculo = '';
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'getDadoCarga',
				'query': numcargaEdit
			},
			success: function(response) {
				//console.log(response);
				arr = JSON.parse(response);
				numcargaEdita = arr[0]['NUMCARGA']
				nomeEdita = arr[0]['NOME']
				nomeVeiculo = arr[0]['VEICULO']
				
				dt = {
					0: arr[0]['DTPREVISAO'],
					1: arr[0]['DTPRODUCAO'],
					2: arr[0]['DTSAIDA']
				}
				data = {};
				$('#editNumcarga').val(numcargaEdita);
				$('#editNome').val(nomeEdita);
				$('#editVeiculo').val(nomeVeiculo);

				for (i = 0; i < 3; i++) {
					if (dt[i] != null) {
						dtaux = dt[i].split("/", 3);
						data[i] = dtaux[2] + '-' + dtaux[1] + '-' + dtaux[0];
					} else
						data[i] = null;
				}
				$('#editAbertura').val(data[0]);
				$('#editProducao').val(data[1]);
				$('#editSaida').val(data[2]);
			}
		});


	}

	function editCarga() {
		numcarga = $('#editNumcarga').val();
		nome = $('#editNome').val();
		veiculo = $('#editVeiculo').val();
		dt = {
			0: $('#editAbertura').val(),
			1: $('#editProducao').val(),
			2: $('#editSaida').val()
		};
		dtaux = '';
		data = {};
		for (i = 0; i < 3; i++) {
			if (dt[i] != '') {

				dtaux = dt[i].split("-", 3);
				data[i] = dtaux[2] + '/' + dtaux[1] + '/' + dtaux[0];
			} else
				data[i] = '';
		}

		dataset = {
			"numcarga": numcarga,
			"nome": nome,
			"veiculo": veiculo,
			"data": data
			
		};

		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'editarCarga',
				'query': dataset
			},
			success: function(response) {
				console.log(response);
				if (response.match("ok")) {
					 location.reload()
				}
			}
		});
	}

	function openDeleteCarga(elm) {
		console.log($(elm).val)
		$('#modalDeleteCarga').modal('toggle');
		split = elm.value.split("-", 3)
		numcargaDel = split[0]
		nomeDel = split[1]

		$('#detele').text('Excluir Carga:' + nomeDel);
		$('#idDelete').text(numcargaDel);
	}

	function openDeleteCarga(elm) {
		$('#modalDeleteCarga').modal('toggle');
		split = elm.value.split("-", 3)
		numcargaDel = split[0]
		nomeDel = split[1]
		//console.log(numcargaDel+' - '+nomeDel)
		$('#detele').text('Excluir Carga: ' + nomeDel + '?');
		$('#idDelete').text(numcargaDel);
	}

	function deleteCarga() {
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'deleteCarga',
				'query': numcargaDel
			},
			success: function(response) {
				console.log(response);
				if (response.match("ok")) {
					location.reload()
				}else{
					alert("Existem pedidos no carregamento, primeiro remova todos os pedidos da carga antes de exluir!");
				}
			}
		});
	}

	function enviaSaldo(elem){
		$(elem).attr("disabled","disabled");
		arr = [];
		$('#tableSemCarga').find('input[type="checkbox"]:checked').each(function() {
			arr.push($(this).val())
		});

		dataset = {
			"pedidos": arr
		};
		
		//console.log(dataset)

		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'setSaldo',
				'query': dataset
			},
			success: function(response) {
				if (response.match("ok")) {
					alert('Saldo enviado com sucesso')
					location.reload()
				}else{
					alert('Saldo não encontrado');
					$(elem).removeAttr('disabled');
				}
			}
		});
	
	}

	function resumoHoverIn(elm) {
		$(elm).css('background-color', 'yellow')
	}

	function resumoHoverOut(elm) {
		$(elm).css('background-color', 'transparent')
	}

	function listaHoverIn(elm) {
		$(elm).css('background-color', 'gold')
	}

	function listaHoverOut(elm) {
		$(elm).css('background-color', 'transparent')
	}

	function travar(elm) {
		// console.log('destravar '+$(elm).val())
		c = confirm("Confirma o fechamento dessa carga?");
		if (c) {
			console.log('teste')
			$.ajax({
				type: 'POST',
				url: 'controle/cargasControle2.php',
				data: {
					'action': 'travaCarga',
					'query': $(elm).val()
				},
				success: function(response) {
					console.log(response);
					if (response.match("ok")) {
						location.reload()
					}
				}
			});
		}
	}

	function destravar(elm) {
		// console.log('destravar '+$(elm).val())
		c = confirm("Confirma a abertura dessa carga?");
		senha = prompt("Senha:");
		if (c && senha=='open23') {
			$.ajax({
				type: 'POST',
				url: 'controle/cargasControle2.php',
				data: {
					'action': 'abreCarga',
					'query': $(elm).val()
				},
				success: function(response) {
					console.log(response);
					if (response.match("ok")) {
						location.reload()
					}
				}
			});
		}else{
			alert("Senha Incorreta ou Ação negada.")
		}
	}

	function valorChapa(){
		let numcarga = $('#numCargaChapa').val();
		let valor = $('#valorChapa').val();
		let motorista = $('#nomeMotorista').val();
		let placa = $('#placaChapa').val();

		if(valor == '' || motorista == '' || placa == ''){
			alert('Preencha todos os campos');
			return false;
		}
		//placa maximo 7 caracteres
		if(placa.length > 7){
			alert('Placa inválida, maximo 7 caracteres');
			return false;
		}
		//motorista maximo 50 caracteres
		if(motorista.length > 50){
			alert('Nome do motorista inválido, maximo 50 caracteres');
			return false;
		}
		//valor maximo 10 caracteres
		if(valor.length > 10){
			alert('Valor inválido, maximo 10 caracteres');
			return false;
		}

		console.log(numcarga+' - '+valor+' - '+motorista+' - '+placa);
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'valorChapa',
				'query': {
					'numcarga': numcarga,
					'valor': valor,
					'motorista': motorista,
					'placa': placa
				}
			},
			success: function(response) {
				alert('Chapa atualizada com sucesso\nValor: '+valor+'\nMotorista: '+motorista+'\nPlaca: '+placa);
			}
		});
	}

	function openModalChapa(numcarga){
		$('#numCargaChapa').val(numcarga);
		$('#modalEditarChapa').modal('toggle');
		carregarChapa(numcarga);
	}

	function carregarChapa(numcarga){
		$.ajax({
			type: 'POST',
			url: 'controle/cargasControle2.php',
			data: {
				'action': 'carregarChapa',
				'query': numcarga
			},
			success: function(response) {
				response = JSON.parse(response)
				$('#valorChapa').val(response.VALOR);
				$('#nomeMotorista').val(response.MOTORISTA);
				$('#placaChapa').val(response.PLACA);
			}
		});
	}
</script>
<script src="/recursos/js/scripts.js"></script>

</html>