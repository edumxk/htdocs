<?php
    require_once("Controle/userControl.php");
    require_once("Controle/formatador.php");
    session_start();


    
    //echo json_encode($_SESSION);

    if(!isset($_SESSION['coduser'])){
        header("location: index.php");
    }

    if(isset($_POST['novaSenha'])){
        //echo json_encode($_POST);
        $nome = $_POST['login'];
        $senha = $_POST['senha1'];
        UserControl::novaSenha($nome, $senha);
        header("location:index.php");
    }

    if(isset($_POST['submit'])){
        $login = Formatador::limpaString($_POST['login']) ;
        $senha = Formatador::limpaString($_POST['senha']) ;

        $user = UserControl::getUserLogin($login,$senha);
        //echo json_encode($_POST);
        //echo json_encode($user['user']);
        
        $_SESSION['coduser'] =  $user['user']['CODUSER'];
        $_SESSION['nome'] =     $user['user']['NOME'];
        $_SESSION['codsetor'] = $user['user']['CODSETOR'];
        $_SESSION['setor'] =    $user['user']['SETOR'];
        $_SESSION['codrca'] =   $user['user']['CODRCA'];
        $timeout = 9999999999999999; // Number of seconds until it times out.

        // Check if the timeout field exists.
        if(isset($_SESSION['timeout'])) {
            // See if the number of seconds since the last
            // visit is larger than the timeout period.
            $duration = time() - (int)$_SESSION['timeout'];
            if($duration > $timeout) {
                // Destroy the session and restart it.
                session_destroy();
                header("location: index.php");
            }
        }

        // Update the timout field with the current time.
        $_SESSION['timeout'] = time();


        if($user['novo']==1){
            header("location:novasenha.php");
        }elseif($user['user']['CODUSER']==null || $user['user']['CODUSER']==''){
            header("location:index.php?msg=failed");
        }elseif($user['user']['CODUSER']>=0 && $user['novo']==0){
            header("location:home.php");
        }
        
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
        <link href="recursos/css/bootstrap.min.css" rel="stylesheet">
        <link href="recursos/css/reset.css" rel="stylesheet">
        <link href="recursos/css/style.css" rel="stylesheet">
        </div>
    </head>
    <header>
        <div class="logo">
            <img src="../../recursos/src/Logo-kokar5.png" alt="Logo Kokar" width="350">
        </div>
        <div class="usuario">
            <ul>
                <li>Usuário: <?php echo $_SESSION['nome']?></li>
                <li>Setor: <?php echo $_SESSION['setor']?></li>
                <li><a style="color: white" onclick="sair()" href="#">Sair</a></li>
            </ul>
        </div>		
    </header>
<body style="background-color: #002695 ; width:auto;">
    <div class="icones-principal">
		<nav id="home" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a href="homelab.php">Laboratório</a>
				</li>
			</ol>
		</nav>

			<div class="icones-titulo">
				<h1 style="padding-bottom: 20px; padding-top: 20px">Módulos
				</h1>
			</div>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==2 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Revalidar Lote</h5>
                    <input class="botao" onclick="window.location.href='/Modulos/Lab/modRevalidacao/revalidacao.php'" 
                    type="image" src="recursos/src/multiple.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=0 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Métodos</h5>
                    <input class="botao" onclick="window.location.href='/Modulos/Lab/modMetodo/index.php'" 
                    type="image" src="recursos/src/planejamento.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Contratipo</h5>
                    <input class="botao" onclick="window.location.href='/Modulos/lab/modContraTipo/index.php'" 
                    type="image" src="recursos/src/frasco.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Densidade</h5>
                    <input class="botao" onclick="window.location.href='/modulos/modDensidade/src/home.php'" 
                    type="image" src="recursos/src/densidade.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']== 12): //Apenas TI e laboratorio?>
			    <div class="icones">  
                    <h5 class="cartao-title">Corrigir Laudos</h5>
                    <input class="botao" onclick="window.location.href='/Modulos/lab/modLaudo/laudo.php'" 
                    type="image" src="recursos/src/artigo.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
        </div>
    <script src="recursos/js/jquery.min.js"></script>
    <script src="recursos/js/bootstrap.min.js"></script>
    <script src="recursos/js/scripts.js"></script>
    <script src="recursos/js/Charts.js"></script>
    <script src="recursos/js/Chart.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="/recursos/js/scripts.js"></script>
          
</body>

</html>