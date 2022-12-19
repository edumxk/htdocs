<?php 
 session_start();

 date_default_timezone_set('America/Araguaina');
 if ($_SESSION['nome'] == null) {
    header("location:../../../home.php");
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="/Recursos/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="/Recursos/js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Recursos/bootstrap-5.0.2-dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="./css/laudo.css"/>
    <script type="text/javascript" src="/Recursos/js/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="/Recursos/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

    <title>Edição de Laudo</title>
</head>
<body>
<div class="navegacao">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<a href="/homelab.php">Home</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Alteração de Laudo</li>
				</ol>
			</nav>
		</div>
		<div>
			<h5> Usuário: <?php echo $_SESSION['nome'] ?> </h5>
			<h5> Setor: <?php echo $_SESSION['setor']?></h5>
			<h5><a style="color: black" href="../../../index.php">Sair</a></h5>
		</div>
	</div>
        <div class="container-fluid principal">
            <div class="principal_edicao" style="margin: 50px;">
                <label for="lote" class="lote">Lote</label>
                <input type="text" id="lote"/>
                <button type="button" class="btn btn-outline-primary" id="btn-buscar">Buscar Laudo</button>
            </div>

            <div class="conteiner principal_edicao">
                <div class="formulario-dinamico">
                <table class=" table-striped compact table-hover" id="tabela" style="width: 100%">
                </table>
                </div>
            </div>
        </div>








    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" />
    <!--  dataTables.buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" />
    <!--  dataTables.select -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css" />
    <!--  dataTables.responsive -->
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <!--  dataTables.buttons -->
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <!--  dataTables.select -->
    <script src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>
    <!--  dataTables.responsive -->

</body>
</html>
<script src="js/actions.js"></script>