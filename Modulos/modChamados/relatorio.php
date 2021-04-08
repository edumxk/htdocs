<?php
	require_once 'controle/listaRatControle.php';
	require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/relatorioDao.php');
	session_start();

	$de = '';
	$ate = '';

	if(isset($_GET['de']) || isset($_GET['ate'])){
		if($_GET['de'] != ""){
			$de = $_GET['de'];
		}else{
			$de = date(date('Y-m-01'));
		}
		
		if($_GET['ate'] != ""){
			$ate = $_GET['ate'];
		}else{
			$ate = date(date('Y-m-t'));
		}
	}else{
		$de = date(date('Y-m-01'));
		$ate = date(date('Y-m-t'));
	}
	//echo json_encode($_GET);
	

	//echo $y;
	$relatorio = RelatorioDao::getRelatorio($de, $ate);

	$anual = $relatorio['anual'];
	// echo json_encode($anual);

	$ratRca = $relatorio['ratRca'];
	$ratPatologia = $relatorio['ratPatologia'];
	$ratCategoria = $relatorio['ratCategoria'];
	$ratCusto = $relatorio['ratCusto'];

	$analiticoPatologia = RelatorioDao::getAnaliticoPatologia($de, $ate);

	//echo json_encode($analiticoPatologia);

	//echo json_encode($ratCusto);

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Lista de RAT's</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">


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


	<div class="container" style="background-color: white; border-style: solid; border-width: 1px">

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a href="listaRat.php">Lista de RAT</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Resultados</li>
			</ol>
		</nav>



		<div class="row">
			<div class="col-md-8">
				<!-- ss-->
				<h3>Resultados de Chamados</h3>
			</div>
			<div class="col-md-12" style="padding-top: 10px">
				<div class="col-md-12">
					<div class="row">
						<div clss="col-md-2">
							<h3><small>Período:</small></h3>
						</div>
						<div class="col-md-12" style="padding-bottom: 20px">
							<form action="relatorio.php" method="GET">
								<div class="form-group">
									<div class="row">
										<div class="col-md-2">
											<label for="usr" style="font-weight: bold">De:</label>
											<div style="width: 100px"><input type="date" name="de" value="<?php echo $de?>"></div>
										</div>										
										<div class="col-md-2">
											<label for="usr" style="font-weight: bold">Ano:</label>
											<div style="width: 100px"><input type="date" name="ate" value="<?php echo $ate?>"></div>
										</div>
											<button type="submit" class="btn btn-primary">Busca</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			
			
			<div class="col-md-12">
				<div class="col-md-12" id="secao"> <!-- ************************** -->
					<div class="row">
						<div class="col-md-12" id="secao">
							Sintético
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="row">
						<div class="col-1">
							<form >
								<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $ap['NUMRAT']?>)">
									12 Meses
								</button>
							</form>
						</div>
						<div class="col-1">
							<form >
								<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $ap['NUMRAT']?>)">
									24 Meses
								</button>
							</form>
						</div>
						<div class="col-1">
							<form >
								<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $ap['NUMRAT']?>)">
									36 Meses
								</button>
							</form>
						</div>
						<div class="col-1">
							<form >
								<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $ap['NUMRAT']?>)">
									48 Meses
								</button>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-12" style="padding-bottom: 20px" >
					<table id="table_idF" class="display compact" style="width:100%">
						<thead style="border: 2px solid darkslategray">
							<tr  style="background-color: lightgray">
								<th style="text-align: center; width: 200px">CHAMADO</th>
								<?php foreach($anual as $a):?>
									<th style="text-align: center; width: 60px"><?php echo $a['DATA']?></th>
								<?php endforeach?>
								<th style="text-align: center">TOTAL</th>
							</tr>
						</thead>
						<tbody style="border: 2px solid darkslategray">
							<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
								<th style="text-align: left; padding-left: 10px">VALOR</th>
								<?php $totalValor=0; $totalAprova=0; $totalReprova=0;;foreach($anual as $a):?>
									<td style="text-align: center; width: 60px"><?php echo number_format($a['VALOR'],2,',','.')?></td>
								<?php $totalValor+=$a['VALOR']; endforeach?>
								<th style="text-align: center"><?php echo number_format($totalValor,2,',','.')?></th>
							</tr>
							<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
								<th style="text-align: left; padding-left: 10px">PROCEDENTE</th>
								<?php foreach($anual as $a):?>
									<td style="text-align: center; width: 60px"><?php echo $a['APROV']?></td>
								<?php $totalAprova+=$a['APROV']; endforeach?>
								<th style="text-align: center"><?php echo $totalAprova?></th>
							</tr>

							<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
								<th style="text-align: left; padding-left: 10px">NÃO PROCEDENTE</th>
								<?php foreach($anual as $a):?>
									<td style="text-align: center; width: 60px"><?php echo $a['REP']?></td>
								<?php $totalReprova+=$a['REP']; endforeach?>
								<th style="text-align: center"><?php echo $totalReprova?></th>
							</tr>
						</tbody>
						<tfoot style="border: 2px solid darkslategray">
							<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
								<td style="text-align: left; padding-left: 10px">TOTAIS</td>
								<?php foreach($anual as $a):?>
									<td style="text-align: center; width: 60px"><?php echo $a['APROV']+$a['REP']?></td>
								<?php endforeach?>
								<th style="text-align: center"><?php echo $totalAprova+$totalReprova?></th>
							</tr>
						</tfoot>
					</table>
				</div> 
			</div>

			<div class="col-md-12" style="padding-top: 20px">
				<div class="row">
					<div class="col-md-12" style="padding-top: 20px">
						<div class="row">
							<div class="col-md-6" style="padding-bottom: 20px">
								<table class="display compact" style="width:100%">
									<thead style="border: 2px solid darkslategray">
										<tr style="background-color: lightgray">
											<th colspan="5" style="text-align: center">RAT POR REPRESENTANTE</th>
										</tr>
										<tr style="background-color: lightgray">
											<th style="text-align: center">COD</th>
											<th style="text-align: center">RCA</th>
											<th style="text-align: center">NÃO PROCEDE</th>
											<th style="text-align: center">PROCEDE</th>
											<th style="text-align: center">VALOR</th>
										</tr>
									</thead>
									<tbody style="border: 2px solid darkslategray">
									<?php $totalRatRca = 0; 
										$qtRatProcede = 0;
										$qtRatNaoProcede = 0;
										foreach($ratRca as $r):
											?>
										<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
											<td style="text-align: center"><?php echo $r['CODUSUR']?></td>
											<td style="text-align: left; padding-left: 10px"><?php echo $r['NOME']?></td>
											<td style="text-align: center"><?php echo $r['NAO_PROCEDE']?></td>
											<td style="text-align: center"><?php echo $r['PROCEDE']?></td>
											<td style="text-align: right"><?php echo number_format($r['VALOR'],2,",",".")?></td>
											<?php $totalRatRca += $r['VALOR'];
												$qtRatNaoProcede += $r['NAO_PROCEDE'];
												$qtRatProcede += $r['PROCEDE'];?>
										</tr>
									<?php endforeach?>
									</tbody>
									<tfoot style="border: 2px solid darkslategray">
										<tr>
											<td colspan="2"style="text-align: center; padding-left: 10px">TOTAL</td>
											<td style="text-align: center; width: 100px"><?php echo number_format($qtRatNaoProcede,0,",",".")?></td>
											<td style="text-align: center; width: 100px"><?php echo number_format($qtRatProcede,0,",",".")?></td>
											<td style="text-align: right; width: 100px"><?php echo number_format($totalRatRca,2,",",".")?></td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="col-md-6" style="padding-bottom: 20px">
								<table class="display compact" style="width:100%">
									<thead style="border: 2px solid darkslategray">
										<tr style="background-color: lightgray">
											<th colspan="4" style="text-align: center">RAT POR PATOLOGIA</th>
										</tr>
										<tr style="background-color: lightgray">
											<th style="text-align: center">PATOLOGIA</th>
											<th style="text-align: center">PROCEDE</th>
											<th style="text-align: center">VALOR</th>
										</tr>
									</thead>
									<tbody style="border: 2px solid darkslategray">
									<?php $totalRatPatologia = 0; 
										$qtRatPatologia = 0;
										foreach($ratPatologia as $p):
											?>
										<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
											<td style="text-align: left; padding-left: 10px; font-size:11px"><?php echo utf8_encode($p['PATOLOGIA'])?></td>
											<td style="text-align: center; width: 100px"><?php echo $p['QUANT']?></td>
											<td style="text-align: right; width: 100px"><?php echo number_format($p['TOTAL'],2,",",".")?></td>
											<?php $totalRatPatologia += $p['TOTAL'];
												$qtRatPatologia += $p['QUANT'];?>
										</tr>
									<?php endforeach?>
									</tbody>
									<tfoot style="border: 2px solid darkslategray">
										<tr>
											<td style="text-align: center">TOTAL</td>
											<td style="text-align: center"><?php echo number_format($qtRatPatologia,0,",",".")?></td>
											<td style="text-align: right"><?php echo number_format($totalRatPatologia,2,",",".")?></td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="col-md-6" style="padding-bottom: 20px">
								<table class="display compact" style="width:100%">
									<thead style="border: 2px solid darkslategray">
										<tr style="background-color: lightgray">
											<th colspan="3" style="text-align: center">RAT POR CATEGORIA</th>
										</tr>
										<tr style="background-color: lightgray">
											<th style="text-align: center">CATEOGORIA</th>
											<th style="text-align: center; width: 100px">APROVADAS</th>
											<th style="text-align: center; width: 100px">VALOR</th>
										</tr>
									</thead>
									<tbody style="border: 2px solid darkslategray">
									<?php $totalRatCategoria = 0; 
										$qtRatCategoria = 0;
										foreach($ratCategoria as $c):
											?>
										<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
											<td style="text-align: left; padding-left: 10px; font-size:11px"><?php echo utf8_encode($c['CATEGORIA'])?></td>
											<td style="text-align: center"><?php echo $c['QT']?></td>
											<td style="text-align: right"><?php echo number_format($c['TOTAL'],2,",",".")?></td>
											<?php $totalRatCategoria += $c['TOTAL'];
												$qtRatCategoria += $c['QT'];?>
										</tr>
									<?php endforeach?>
									</tbody>
									<tfoot style="border: 2px solid darkslategray">
										<tr>
											<td style="text-align: center">TOTAL</td>
											<td style="text-align: center"><?php echo number_format($qtRatCategoria,0,",",".")?></td>
											<td style="text-align: right"><?php echo number_format($totalRatCategoria,2,",",".")?></td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="col-md-6" style="padding-bottom: 20px">
								<table class="display compact" style="width:100%">
									<thead style="border: 2px solid darkslategray">
										<tr style="background-color: lightgray">
											<th colspan="4" style="text-align: center">RAT POR CUSTO</th>
										</tr>
										<tr style="background-color: lightgray">
											<th style="text-align: center">CUSTO</th>
											<th style="text-align: center; width: 100px">APROVADAS</th>
											<th style="text-align: center; width: 100px">VALOR</th>
										</tr>
									</thead>
									<tbody style="border: 2px solid darkslategray">
									<?php $totalCusto = 0; 
										$qtCusto = 0;
										foreach($ratCusto as $c):
											?>
										<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
											<td style="text-align: left; padding-left: 10px; font-size:11px"><?php echo utf8_encode($c['CUSTO'])?></td>
											<td style="text-align: center"><?php echo $c['QT']?></td>
											<td style="text-align: right"><?php echo number_format($c['VALOR'],2,",",".")?></td>
											<?php $totalCusto += $c['VALOR'];
												$qtCusto += $c['QT'];?>
										</tr>
									<?php endforeach?>
									</tbody>
									<tfoot style="border: 2px solid darkslategray">
										<tr>
											<td style="text-align: center">TOTAL</td>
											<td style="text-align: center"><?php echo number_format($qtCusto,0,",",".")?></td>
											<td style="text-align: right"><?php echo number_format($totalCusto,2,",",".")?></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="patologia">
					<div class="col-md-12" id="secao"> <!-- ************************** -->
						<div class="row">
							<div class="col-md-12" id="secao">
								Analítico por Patologia
							</div>
						</div>
					</div>
				
					<?php $totalPatologias=0;
					foreach($ratPatologia as $p):
						$subTotalPatologia=0;?>
					<div class="col-md-12" style="padding-bottom: 30px;">
							<table class="display compact" style="width: 100%">
								<thead style="border: 2px solid darkslategray">
									<tr style="background-color: lightgray; border-left: 3px solid darkslategray; border-right: 3px solid darkslategray; border-top: 3px solid darkslategray">
										<th colspan="7" style="text-align:center; font-size:18px"><?php echo utf8_encode($p['PATOLOGIA'])?></th>
									</tr>
									<tr style="background-color: lightgray; border-left: 3px solid darkslategray;  border-right: 3px solid darkslategray">
										<th style="text-align: center; width: 40px">RAT</th>
										<th style="text-align: center; width: 40px">COD</th>
										<th style="text-align: center">PRODUTO</th>
										<th style="text-align: center; width: 80px">LOTE</th>
										<th style="text-align: center; width: 120px">FABRICAÇÃO</th>
										<th style="text-align: center; width: 100px">VALOR</th>
										<th style="text-align: center; width: 40px">VER</th>
									</tr>
								</thead>
								<tbody style="border: 2px solid darkslategray; border-left: 3px solid darkslategray; border-right: 3px solid darkslategray">
									<?php foreach($analiticoPatologia as $ap):?>
										<?php if($ap['PATOLOGIA']==$p['PATOLOGIA']):?>
											<tr onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)">
												<td style="text-align:right; padding-right:5px"><?php echo $ap['NUMRAT']?></td>
												<td style="text-align:right; padding-right:5px"><?php echo $ap['CODPROD']?></td>
												<td style="text-align:left; padding-left:5px"><?php echo utf8_encode($ap['DESCRICAO'])?></td>
												<td style="text-align:right; padding-right:5px"><?php echo $ap['NUMLOTE']?></td>
												<td style="text-align:right; padding-right:5px"><?php echo $ap['DATAFABRICACAO']?></td>
												<td style="text-align:right; padding-right:5px"><?php echo number_format($ap['VALOR'],2,',','.')?></td>
												<td style="text-align: center">
													<button type="submit" class="btn btn-sm btn-success" onclick="openpdf(<?php echo $ap['NUMRAT']?>)">
														<i class="far fa-eye"></i>
													</button>
												</td>
											</tr>
										<?php $subTotalPatologia+= $ap['VALOR']; $totalPatologias+= $ap['VALOR']; 
										endif?>
									<?php endforeach?>
								</tbody>
								<tfoot>
									<tr style="; border-left: 3px solid darkslategray; border-right: 3px solid darkslategray; border-bottom: 3px solid darkslategray">
										<th colspan="5"></th>
										<th colspan="1" style="background-color: lightgray; text-align:right; padding-right:5px"><?php echo number_format($subTotalPatologia,2,',','.') ?></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
					</div>
					<?php endforeach?>
					
					<div class="row">
						<div class="col-8">

						</div>
						<div class="col" style="background-color: lightgray; text-align:right; padding-right:40px">
							<h5>TOTAL DE CHAMADOS: R$ <?php echo number_format($totalPatologias,2,',','.')?></h5>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>




	<div class="header">

	</div>


	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/Chart.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8"
		src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>

<script>
	$(document).ready(function () {
		$('#table_id').DataTable({
			"lengthMenu": [[50, 100, -1], [50, 100, "Todos"]],
			"order": [[0, "asc"]],
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
	function openpdf(elm){
		window.open("recursos/visualizarRat.php?numrat="+elm);
	}

	function listaHoverIn(elm){
		$(elm).css('background-color', 'gold')
	}
	function listaHoverOut(elm){
		$(elm).css('background-color', 'transparent')
	}
</script> 




</html>