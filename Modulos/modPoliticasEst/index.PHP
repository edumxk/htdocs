<?php 
	require_once ('./model/Politicas.php');
	$politicaS = Politicas::getPoliticasGeral();
	$politicaP = Politicas::getPoliticasPrem();
	$politicaStand = Politicas::getPoliticasStand();
	$politicaA = Politicas::getPoliticasAnalitico();
	$politicaG = Politicas::getPoliticasAnalitico2();
	session_start();
	//header("refresh: 180;");
	date_default_timezone_set('America/Araguaina');
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RESUMO CAMPANHAS</title>

    <meta name="Eduardo" content="Resumo de Politicas!">

    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="politica.css" rel="stylesheet">

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
					<li class="breadcrumb-item active" aria-current="page">Campanha</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-4">
			<h5> Usuário: <?php echo $_SESSION['nome'] ?> </h5>
			<h5> Setor: <?php echo $_SESSION['setor']?></h5>
			<h5><a style="color: black" href="../../index.php">Sair</a></h5>
		</div>
	</div>


	<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         	LINHA ECONOMICO
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      	<div class="card-body">
			<!-- TABELA ECONOMICO -->
			<div class="col-md-12">
				<table class="table table-hover table-bordered table-sm table-striped">
					<thead>
						<tr>
							<th>
								Código
							</th>
							<th>
								Produto
							</th>
							<th>
								Qtd Vendido
							</th>
							<th>
								Qtd Campanha
							</th>
							<th>
								Estoque 
							</th>
							<th>
								Qtd Disp 
							</th>
							<th>
								Ativa
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($politicaS as $s):?>
							<tr>
								<td class="numero">
									<?=$s['CODPROD']?>
								</td>
								<td>
									<?=$s['DESCRICAO']?>
								</td>
								<td class="numero">
									<?=$s['QT']?>
								</td>
								<td class="numero">
									<?=$s['QTMAX']?>
								</td>
								<td class="numero">
									<?=$s['ESTOQUE']?>
								</td>
								<td class="numero">
									<?=$s['QTMAX'] - $s['QT']?>
								</td>
								<td class="numero">
									<?=$s['VALIDO']?>
								</td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
			<!-- #FIM TABELA ECONOMICO -->	
		</div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          LINHA STANDARD
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
		<div class="col-md-12">
			<table class="table table-hover table-bordered table-sm table-striped">
				<thead>
					<tr>
						<th>
							Código
						</th>
						<th>
							Produto
						</th>
						<th>
							Qtd Vendido
						</th>
						<th>
							Qtd Campanha
						</th>
						<th>
							Estoque 
						</th>
						<th>
							Qtd Disp 
						</th>
						<th>
							Ativa
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($politicaStand as $s):?>
						<tr>
							<td class="numero">
								<?=$s['CODPROD']?>
							</td>
							<td>
								<?=$s['DESCRICAO']?>
							</td>
							<td class="numero">
								<?=$s['QT']?>
							</td>
							<td class="numero">
								<?=$s['QTMAX']?>
							</td>
							<td class="numero">
								<?=$s['ESTOQUE']?>
							</td>
							<td class="numero">
								<?=$s['QTMAX'] - $s['QT']?>
							</td>
							<td class="numero">
								<?=$s['VALIDO']?>
							</td>
						</tr>
					<?php endforeach?>
				</tbody>
			</table>
		</div>	
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
         LINHA PREMIUM
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
	  <div class="col-md-12">
			<table class="table table-hover table-bordered table-sm table-striped">
				<thead>
					<tr>
						<th>
							Código
						</th>
						<th>
							Produto
						</th>
						<th>
							Qtd Vendido
						</th>
						<th>
							Qtd Campanha
						</th>
						<th>
							Estoque 
						</th>
						<th>
							Qtd Disp 
						</th>
						<th>
							Ativa
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($politicaP as $s):?>
						<tr>
							<td class="numero">
								<?=$s['CODPROD']?>
							</td>
							<td>
								<?=$s['DESCRICAO']?>
							</td>
							<td class="numero">
								<?=$s['QT']?>
							</td>
							<td class="numero">
								<?=$s['QTMAX']?>
							</td>
							<td class="numero">
								<?=$s['ESTOQUE']?>
							</td>
							<td class="numero">
								<?=$s['QTMAX'] - $s['QT']?>
							</td>
							<td class="numero">
								<?=$s['VALIDO']?>
							</td>
						</tr>
					<?php endforeach?>
				</tbody>
			</table>
		</div>
	</div>
    </div>
  </div>
</div>
<div class="card">
    <div class="card-header" id="heading4">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapseThree">
          STATUS GERAL - TODOS OS ITENS EM PROMOÇÃO
        </button>
      </h5>
    </div>
    <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
      <div class="card-body">
	  <div class="col-md-12">
			<table class="table table-hover table-bordered table-sm table-striped">
				<thead>
					<tr>
						<th>
							Código
						</th>
						<th>
							Produto
						</th>
						<th>
							Qtd Vendido
						</th>
						<th>
							Qtd Campanha
						</th>
						<th>
							Estoque 
						</th>
						<th>
							Qtd Disp 
						</th>
						<th>
							Ativa 
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($politicaG as $s):?>
						<tr>
							<td class="numero">
								<?=$s['CODPROD']?>
							</td>
							<td>
								<?=$s['DESCRICAO']?>
							</td>
							<td class="numero">
								<?=$s['QT']?>
							</td>
							<td class="numero">
								<?=$s['QTMAX']?>
							</td>
							<td class="numero">
								<?=$s['ESTOQUE']?>
							</td>
							<td class="numero">
								<?=$s['QTDISP']?>
							</td>
							<td class="numero">
								<?=$s['VALIDO']?>
							</td>
						</tr>
					<?php endforeach?>
				</tbody>
			</table>
		</div>				
	</div>
	</div>
</div>
<div class="card">
    <div class="card-header" id="heading5">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapseThree">
          ITENS EM PEDIDOS - ANALÍTICO
        </button>
      </h5>
    </div>
    <div id="collapse5" class="collapse show" aria-labelledby="heading5" data-parent="#accordionExample">
      <div class="card-body">
	  <div class="col-md-12">
			<table class="table table-sm table-hover table-bordered table-striped">
			<thead>
					<tr>
						<th>
							Codcli
						</th>
						<th>
							Data
						</th>
						<th>
							Pedido
						</th>
						<th>
							Pos
						</th>
						<th>
							Código
						</th>
						<th>
							Produto
						</th>
						<th>
							Desconto
						</th>
						<th>
							Valor
						</th>
						<th>
							Qtd Vendido
						</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach($politicaA as $s):?>
						<tr>
							<td class="numero">
								<?=$s['CODCLI']?>
							</td>
							<td class="numero">
								<?=$s['DATA']?>
							</td>
							<td>
								<?=$s['NUMPED']?>
							</td>
							<td>
								<?=$s['POSICAO']?>
							</td>
							<td class="numero">
								<?=$s['CODPROD']?>
							</td>
							<td>
								<?=$s['DESCRICAO']?>
							</td>
							<td class="numero">
								<?php echo(number_format($s['DESCONTO'],0,',','.').'%')?>
							</td>
							<td class="numero">
							<?php echo('R$ '.number_format($s['PVENDA'],2,',','.'))?>
							</td>
							<td class="numero">
								<?=$s['QT']?>
							</td>
						</tr>
					<?php endforeach?>
				</tbody>
			</table>
		</div>			
	</div>
	</div>
</div>				
</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>