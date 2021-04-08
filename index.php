<?php


if(isset($_GET['msg'])){
    echo "<script>alert('Login ou Senha incorretos')</script>";
}




?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Lista de RAT's</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="recursos/css/style.css" rel="stylesheet">
	</div>
</head>

<body style="background-color: teal;">


	<div class="container" style="background-color: antiquewhite; border-style: solid; border-width: 1px; width: 300px; height: 400px; top:20%">
		<div class="row">
			<div class="col-md-12" style="border-bottom: solid; border-width: 1px; height: 70px; background-color: teal; padding-bottom: 20px">
				<div>
					<img src="recursos/src/Logo-kokar.png" alt="Logo Kokar" style="display: block; margin-left: auto; margin-right: auto;">
				</div>
			</div>

			<div class="com-md-12" style="display:inline-block; width: 80%; padding: 10px; margin-left: auto; margin-right: auto; padding-top:50px">
				<form action="home.php" method="POST" autocomplete="off"> 
					<div class="form-group">
						<label for="login">Usuário</label>
						<input type="text" class="form-control" name="login">
					</div>
					<div class="form-group">
						<label for="senha">Senha</label>
						<input type="password" class="form-control" name="senha">
					</div>
					<button type="submit" class="btn btn-primary" name="submit">Entrar</button>

				</form>
			</div>

		</div>


	</div>



	<script src="recursos/js/jquery.min.js"></script>
	<script src="recursos/js/bootstrap.min.js"></script>
	<script src="recursos/js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</body>


</html>