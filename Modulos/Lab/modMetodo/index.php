<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/lab/modMetodo/model/model.php');
session_start();
$dataini = date('Y-m-d');
$datafin = date('Y-m-d');
if (isset($_GET['data1'],$_GET['data2'])) {
    $dataini = $_GET['data1'];
	$datafin = $_GET['data2'];
}
$produtos = (Metodo::getProdutos());

?>
<?php
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>
	

<!DOCTYPE html>
<html lang="pt-br">
	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico">
<style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 120px;
  height: 120px;
  margin: -76px 0 0 -76px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}


@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

#myDiv {
  display: none;
  text-align: center;
}
</style>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Métodos</title>
    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">
	
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body onload="myFunction()">
  	<div id="loader"></div>
    <div id="master" class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>
					Kokar Tintas -<small> Módulo de Cadastro de Métodos</small>
				</h1>
			</div>
			<div class="row">
				<div class="col-md-4">
					<nav>
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="/home.php">Home</a>
							</li>
							<li class="breadcrumb-item">
								<a href="/homelab.php">Laboratório</a>
							</li>
							<li class="breadcrumb-item active">
								Método
							</li>
						</ol>
					</nav>
				</div>
				<div class="col-md-4">
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="conteiner-fluid" id="box" style="background-color: lightgrey;">
		<div class="row" style="padding-bottom: 20px; padding-top:20px;">
		<!-- Lista de Produtos -->
			<datalist id="produtos">
				<?php foreach($produtos as $p):?>
				<option label="<?=$p['DESCRICAO']?>" value="<?=$p['CODPROD']?>"></option> 
				<?php endforeach;?>
			</datalist>
		<!-- end Lista de Produtos -->
			<div class="col-md-3"></div>
				<div class="col-md-3" style="text-align: center;">
					<input onchange="atualizarM()" name="base" type="text" id="base" list="produtos" placeholder="Produto Base!" style="width: auto;"></input>
				</div>
				<div class="col-md-3" style="text-align: center;">
					<input onchange="atualizarM()" name="novo" type="text" id="novo" list="produtos" placeholder="Produto Novo!" style="width: auto;">
				</div>
		</div>
		<div class="row" style="padding-bottom: 20px; text-align: center;">
			<div class="col-md-3"></div>
			<div class="col-md-3">
			<h3 style="padding-bottom:12px">Produto Base</h3>	
			<select id="metodo1">
				
				</select>
			</div>
			<div class="col-md-3">
			<h3 style="padding-bottom:12px">Produto Novo</h3>	
				<select id="metodo2">
				
				</select>
			</div>
			<div class="col-md-1">
					<button onclick="duplicar()" type="submit">Duplicar</button>
			</div>
		</div>
	</div>
<script>
	function duplicar() {
		base = $('#base').val();
		novo = $('#novo').val();
		metodobase = $( "#metodo1 option:selected" ).val();
		metodonovo = $( "#metodo2 option:selected" ).val();
		dataset = {"base":base, "novo":novo, "metodobase":metodobase, "metodonovo":metodonovo} 
		console.log(dataset);
		c = confirm("Deseja realmente duplicar este método???");
		if(c && base > 0 && base < 4000 && novo > 0 && novo < 4000)  {

			$.ajax({
				type: 'POST',
				url: 'Controle/control.php',
				data: { 'action': 'duplicar', 'dataset': dataset },
				success: function (response) {
					console.log(response);
					if (response.match("ok")) {
						alert("Metodo: "+ metodonovo +"cadastrado com sucesso para o produto: "+ novo)
						location.reload()
					}
				}
			});
		}else{
			alert("Dados incorretos, preencha corretamente os produtos!!! Verifique o método!!!")
		}
	}
	var myVar;

	function myFunction() {
	myVar = setTimeout(showPage, 1000);
	} 
	  function showPage() {
		document.getElementById("loader").style.display = "none";
		document.getElementById("master").style.display = "block";
	}
	$('#base').keyup(function() {
  		atualizarM();
	});
	$('#novo').keyup(function() {
  		atualizarM();
	});


	function atualizarM(){
		
		base = $('#base').val();
		novo = $('#novo').val();
		dataset= {'base':base, 'novo': novo}
		if(base > 0){
		$.ajax({
				type: 'POST',
				url: 'Controle/control.php',
				data: { 'action': 'metodo1', 'dataset': dataset },
				success: function (response) {
					console.log(response);
					$('#metodo1').children().remove().end();
					$('#metodo1').append(response);
				}
			});
		}
		if(novo > 0){
			$.ajax({
				type: 'POST',
				url: 'Controle/control.php',
				data: { 'action': 'metodo2', 'dataset': dataset },
				success: function (response) {
					console.log(response);
					$('#metodo2').children().remove().end();
					$('#metodo2').append(response);

				}
			});
		}
	}

</script>

</div>
</body>
</html>


<style type="text/css">
#box{
	/*definimos a largura do box*/
	
	/* definimos a altura do box */
	
	/* definimos a cor de fundo do box */
	background-color:#666;
	/* definimos o quão arredondado irá ficar nosso box */
	border-radius: 20px;
	}
</style>