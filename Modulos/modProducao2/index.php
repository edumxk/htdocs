<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/modProducao2/model/model.php');
session_start();
$dataini = date('Y-m-d');
$datafin = date('Y-m-d');
if (isset($_GET['data1'],$_GET['data2'])) {
    $dataini = $_GET['data1'];
	$datafin = $_GET['data2'];
}

$producaom = Producao::getProducao($dataini, $datafin, 10000);
$dias = Producao::getDia($dataini, $datafin, 10000);
$resumo = Producao::getProdMensal($dataini);
$diasuteis = Producao::getDiasUteis(date('Y-m-01'), date('Y-m-t'), Producao::feriados($dataini));
$producaop = Producao::getEmProducao();
$pesoTotal=[];
$somam = 0;
?>
<?php
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Resumo de Produção</title>

    <meta name="Resumo de Produção" content="Resumo de produção da kokar tintas">
    <meta name="Eduardo Patrick" content="T.I.">
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico">
    <link href="/Modulos/modProducao2/recurso/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Modulos/modProducao2/recurso/css/style.css" rel="stylesheet">
  </head>
  <body>

    <div class="container-fluid">
	<div class="row" style="padding-bottom: 50PX;">
		<div class="col-md-8">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<a href="../../home.php">Home</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Produção</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-4">
			<h5> Usuário: <?php echo $_SESSION['nome'] ?> </h5>
			<h5> Setor: <?php echo $_SESSION['setor']?></h5>
			<h5><a style="color: black" href="../../index.php">Sair</a></h5>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
		<form action="index.php" method="get">
				<div class="form-group">
					<div class="row">
						<div class="col-md-2">
							<label for="data1" style="font-size: larger;">
								<b>De:</b>
							</label>
						</div>
						<div class="col-md-6">
							<input type="date" class="form-control" name="data1" value="<?=$dataini?>">
						</div>
						<div class="col-md-4">
							
						</div>
					</div>
				</div>
				<div class="form-group"> 
					<div class="row">
						<div class="col-md-2">
							<label for="data2" style="font-size: larger;">
								<b>Até:</b>
							</label>
						</div>
						<div class="col-md-6">
							<input type="date" class="form-control" name="data2" value="<?=$datafin?>">
						</div>
						<div class="col-md-4">
							<button type="submit" value="Buscar" class="btn btn-primary">Buscar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-4">
			<h1 style="text-align: center;">
				Resumo de Produção
			</h1>
		</div>
		<div class="col-md-4">
			<!-- TABELA DE RESUMO--> 
			<h4 style="text-align: center;">
				Resumo Mensal
			</h4>
			<table class="table table-hover table-bordered table-sm">
				<thead>
					<tr>
						<th>
							LINHA
						</th>
						<th>
							PRODUÇÃO
						</th>
						<th>
							META
						</th>
						<th>
							 %
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($resumo as $r):?>
					<tr>
						<td>
							<?= $r->linha ?>
						</td>
						<td>
						<?php echo number_format($r->qtProduzida,0,',','.'); $pesoTotal[] = $r->qtProduzida;  ?>
						</td>
						<td>
						<?php if($r->meta1>0){echo number_format($r->meta1*$diasuteis,0,',','.'); $somam+=$r->meta1*$diasuteis;}else{echo '42.000';} ?>
						</td>
						<td>
						<?php if($r->meta1>0){echo number_format(($r->qtProduzida/($r->meta1*$diasuteis)*100),2,',','');}
						else{echo number_format(($r->qtProduzida/(42000)*100),2,',','');} ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr style="background-color:darkslategrey; color:white">
						<th style="text-align: center;">TOTAL</th>
						<th style="text-align: center;"><?=number_format(array_sum($pesoTotal),0,',','.')?></th>
						<th style="text-align: center;"><?=number_format($somam,0,',','.')?></th>
						<th style="text-align: center;"><?=number_format((array_sum($pesoTotal)/$somam)*100,2,',','.')?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
		<?php foreach($dias as $m2):
			 ?>
			<h2 class="text-center" style="padding-bottom:20px;">
				FECHAMENTO DO DIA <?= $m2['DTFECHA'] ?>
			</h2>
			<table class="table table-hover table-bordered table-sm">
				<thead>
					<tr>
						<th>
							#COD
						</th>
						<th class="produto">
							DESCRIÇÃO
						</th>
						<th>
							APONTAMENTO
						</th>
						<th>
							QT PROD
						</th>
						<th>
							PESO LIQ
						</th>
						<th>
							PESO BRUTO
						</th>
						<th>
							LITRAGEM
						</th>
						<?php if($_SESSION['codsetor']<= 1):?> 
						<th>
							CUSTO
						</th>
						<?php endif?>
					</tr>
				</thead>
				<tbody>
					<?php $arr = array(0=>[0],1=>[0],2=>[0]);
					foreach($producaom as $m): 
						if($m2['DTFECHA'] == $m['DTFECHA']):?>
						<tr class="apontado">
							<td>
							<?= $m["CODPRODMASTER"] ?>
							</td>
							<td class="produto">
							<?= $m["DESCRICAO"] ?>
							</td>
							<td>
							<?= $m["HORA"] ?>
							</td>
							<td>
							<?= $m["QTPRODUZIDA"] ?>
							</td>
							<td class="numeros">
							<?php echo number_format($m["PESOLIQ"],2,',',''); array_push($arr[0], $m["PESOLIQ"])?>
							</td>
							<td class="numeros">
							<?php echo number_format($m["PESOBRUTO"],2,',',''); array_push($arr[1], $m["PESOBRUTO"]) ?>
							</td>
							<td class="numeros">
							<?php echo number_format($m["LITRAGEM"],2,',',''); array_push($arr[2], $m["LITRAGEM"]) ?>
							</td>
							<?php if($_SESSION['codsetor']<= 1):?> 
								<td class="numeros">
									<?php echo number_format($m["CUSTOREAL"],2,',','') ?>
								</td>
							<?php endif?>
							
						</tr>
					<?php endif; endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:right; padding-right:10px">
								TOTAIS
						</td>
						<td class="numeros">
							<?= number_format(array_sum($arr[0]),2,',','.') ?>
						</td>
						<td class="numeros">
							<?= number_format(array_sum($arr[1]),2,',','.')?>
						</td>
						<td class="numeros">
							<?= number_format(array_sum($arr[2]),2,',','.') ?>
						</td>
						<?php if($_SESSION['codsetor']<= 1):?> 
								<td>	
								</td>
							<?php endif?>	
					</tr>
				</tfoot>
			</table>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h2 class="text-center" style="padding-bottom:20px;">
				EM PRODUÇÃO
			</h2>
			<table id="table2" class="table table-hover table-bordered table-sm">
				<thead>
					<tr>
						<th>
							#COD
						</th>
						<th class="produto">
							DESCRIÇÃO
						</th>
						<th>
							ABERTURA
						</th>
						<th>
							PREVISÃO
						</th>
						<th>
							DIAS
						</th>
						<th>
							QT PROD
						</th>
						
						<th>
							PESO LIQ
						</th>
						<th>
							LITRAGEM
						</th>
						<th>
							STATUS
						</th>
					</tr>
				</thead>
				<tbody>
					<?php $arr2 = array(0=>[0],1=>[0]);
					foreach($producaop as $p): ?>
						<tr>
							<td>
							<?= $p->codprod ?>
							</td>
							<td  class="produto">
							<?= $p->descricao ?>
							</td>
							<td >
							<?= $p->dtabertura ?>
							</td>
							<td >
							<?= $p->dtproducao ?>
							</td>
							<td>
							<?= $p->tempo?>
							</td>
							<td>
							<?= $p->qt ?>
							</td>
							
							<td class="numeros">
							<?php echo number_format($p->peso*$p->qt,2,',',''); array_push($arr2[0], $p->peso*$p->qt)?>
							</td>
							<td class="numeros">
							<?php echo number_format($p->litragem*$p->qt,2,',',''); array_push($arr2[1],$p->litragem*$p->qt) ?>
							</td>
							<td>
							<span class="em-producao__status"><?php echo $p->status?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" style="text-align:right;padding-right:10px">
								TOTAIS
						</td>
						<td class="numeros">
							<?= number_format(array_sum($arr2[0]),2,',','.') ?>
						</td>
						<td class="numeros">
							<?= number_format(array_sum($arr2[1]),2,',','.') ?>
						</td>
						<td></td>	
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

    <script src="/Modulos/modProducao2/recurso/js/jquery.min.js"></script>
    <script src="/Modulos/modProducao2/recurso/js/bootstrap.min.js"></script>
    <script src="/Modulos/modProducao2/recurso/js/scripts.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
			<script src="/Modulos/modProducao2/recurso/js/jquery.tablesorter.js"></script>
	<script>
		$(document).ready(function () {
		   
			var lista = $('.em-producao__status');
			//console.log(lista)
			lista.each(function (i){
				if($(this).text() == 'ENVASE')
				$(this).parent().css('background-color', '#42FFD0');
			})
			

			$("table").tablesorter(
			{
				dateFormat:'mmddYYYY',
                headers: {
                    2: { sorter: "shortDate", dateFormat: "ddmmyyyy h:i:s" }
					
				}
			}
		);
		$("#table2").tablesorter(
			{
				dateFormat:'mmddYYYY',
                headers: {
                    2: { sorter: "shortDate", dateFormat: "ddmmyyyy h:i" },
					3: { sorter: "shortDate", dateFormat: "ddmmyyyy h:i" }
					
				}
			}
		);
});


	</script>
  </body>
</html>