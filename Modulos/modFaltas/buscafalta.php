<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/Controle/userControl.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/Controle/formatador.php");
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
            header("location:../../home.php");
        }
    }



?>


<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>INTRANET</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="../../../../recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../recursos/css/style.css" rel="stylesheet">
    </div>
</head>

<body style="background-color: teal;">
    <div class="header">
        <div class="row" style="width: 100%">
            <div class="col-md-9" style="left: 10%; top:2px;">
                <img src="/../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
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
                Gerente de Faltas
                </li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-8">
                <h1 style="padding-bottom: 20px; padding-top: 20px">Editar Falta
                </h1>
            </div>
        </div>
        <div class="row">
        <div class="col-md-5"></div>
            <div class="col-md-7" style="padding-left: 30px;"> <b><i>NÚMERO NFE </div>
        </div>
        <div class="row"style="text-align: center">
        <div class="col-md-5"></div>
            <div class="col-md-7" style="text-align: center"> 
                <input id="numnota" style="width: 120px; text-align: center; border-style: inset; border: 2px solid black; border-radius: 4px;" type="number" class="form-control form-control-sm" tabindex="1">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"> </div>
                <div class="col-md-6" style="padding-top: 10px; text-align: right">
                    <button tabindex="3" type="submit" class="btn btn-success" name="numrat" onclick="confirmar()">Confirmar</button>
                </div>
        </div>
        <div class="row"> <p> </div>
    </div>

    <div class="header">

    </div>


    <script src="../../recursos/js/jquery.min.js"></script>
    <script src=".../../recursos/js/bootstrap.min.js"></script>
    <script src="../../recursos/js/scripts.js"></script>
    <script src="../../recursos/js/Charts.js"></script>
    <script src="../../recursos/js/Chart.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</body>
<script>
		function confirmar(){
			var numnota =  $("#numnota").val();
			
			$.ajax({
				type: 'POST',
				url: 'editarfalta.php',
				data: { 'action': 'getnumfalta', 'query':numnota},
				success: function (response) {

					//console.log('editarfalta.php?numnota='+numnota);
					//$.redirect('editarfalta.php?numnota=256');
					//console.log('editarfalta.php?nurmat='+numrat);
					$(location).attr('href', 'editarfalta.php?numnota='+numnota);
				}
			});	
		}
</script>
</html>