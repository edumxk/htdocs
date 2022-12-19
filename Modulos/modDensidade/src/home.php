<?php
    //require_once("Controle/userControl.php");
    //require_once("Controle/formatador.php");
    session_start();


    
    //echo json_encode($_SESSION);

    if(!isset($_SESSION['coduser'])){
        header("location: /index.php");
    }

?>

<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <title>Kokar Intranet</title>

        <meta name="Home Extranet" content="By Edumxk">
        <meta name="Home" content="Home">
        <link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
        <link href="/Recursos/css/bootstrap.min.css" rel="stylesheet">
        <link href="/Recursos/css/reset.css" rel="stylesheet">
        <link href="/Recursos/css/style.css" rel="stylesheet">
        </div>
    </head>
    <header>
        <div class="logo">
            <img src="/Recursos/src/Logo-kokar5.png" alt="Logo Kokar" width="350">
        </div>
        <div class="usuario">
            <ul>
                <li>Usuário: <?php echo $_SESSION['nome']?></li>
                <li>Setor: <?php echo $_SESSION['setor']?></li>
                <li><a style="color: white" href="/index.php">Sair</a></li>
            </ul>
        </div>		
    </header>
<body style="background-color: #002695 ; width:auto;">
    <div class="icones-principal">
		<nav id="home" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="/homelab.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a href="home.php">Laboratório - Densidade</a>
				</li>
			</ol>
		</nav>

			<div class="icones-titulo">
				<h1 style="padding-bottom: 20px; padding-top: 20px">Módulos
				</h1>
			</div>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Densidade Geral</h5>
                    <input class="botao" onclick="window.location.href='/modulos/modDensidade/densidade.php'" 
                    type="image" src="/Recursos/src/densidade.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Densidade Médias</h5>
                    <input class="botao" onclick="window.location.href='/modulos/modDensidade/densidade1.php'" 
                    type="image" src="/Recursos/src/densidade.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            

        </div>
    <script src="/Recursos/js/jquery.min.js"></script>
    <script src="/Recursos/js/bootstrap.min.js"></script>
    <script src="/Recursos/js/scripts.js"></script>
    <script src="/Recursos/js/Charts.js"></script>
    <script src="/Recursos/js/Chart.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</body>

</html>