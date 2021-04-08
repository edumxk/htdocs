<?php
  include 'controle/novaRatControle.php';
  session_start();

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Kokar - Gerência de Chamados</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
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

	<div class="container" style="background-color: white; border-style: solid; border-width: 1px">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a href="listaRat.php">Lista de RAT's</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Lista de Clientes</li>
			</ol>
		</nav>
		<div class="row">
			<div class="col-md-8">
				<h1 style="padding-bottom: 20px; padding-top: 20px">Gerência de Chamados<small> - Listar Clientes</small></h1>
			</div>

				<div class="col-md-12" style="padding-botton: 40px">
						<table  id="example" class="display compact" style="width:100%">
							<thead>
								<tr>
									<th style="width:20px">COD</th>
									<th>Cliente</th>
									<th>Fantasia</th>
									<th style="width:120px">CNPJ</th>
									<th>Cidade</th>
									<th style="width:20px">UF</th>
									<th>RCA</th>
									<th>Iniciar</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$clientes=NovaRatControle::getClientes();
									if($clientes!=null):
										for($i=0; $i<count($clientes); $i++):
										?>
										<tr>
											<td id="codcli"><?php echo $clientes[$i]['CODCLI'] ?></td>
											<td style="width: 8cm"><?php echo $clientes[$i]['CLIENTE'] ?></td>
											<td><?php echo $clientes[$i]['FANTASIA'] ?></td>
											<td><?php echo $clientes[$i]['CGCENT'] ?></td>
											<td><?php echo $clientes[$i]['NOMECIDADE'] ?></td>
											<td><?php echo $clientes[$i]['UF'] ?></td>
											<td><?php echo $clientes[$i]['NOME'] ?></td>
											<td style="text-align: center">
												<form method="post" action="novaRat.php">
													<button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i></button>
													<input type="hidden" name="id" value="<?php echo $clientes[$i]['CODCLI']?>"/>
												</form>
											</td>
										</tr>
										<?php endfor ?>
									<?php endif ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>

	</div>


	<script src="../../../../recursos/js/jquery.min.js"></script>
	<!-- <script src="js/bootstrap.min.js"></script> -->
	<script src="../../recursos/js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>


<script>
	$(document).ready(function () {
		var table = $('#example').DataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			
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

</html>