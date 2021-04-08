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


<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>INTRANET</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="recursos/css/style.css" rel="stylesheet">
    </div>
</head>

<body style="background-color: teal;">
    <div class="header">
        <div class="row" style="width: 100%">
            <div class="col-md-9" style="left: 10%; top:2px;">
                <img src="recursos/src/Logo-kokar.png" alt="Logo Kokar">
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
                    <a href="home.php">Home</a>
                </li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-8">
                <h1 style="padding-bottom: 20px; padding-top: 20px">Módulos
                </h1>
            </div>
        </div>
        <div class="row">
            <?php if($_SESSION['codsetor']<=5): //Se setores de 0 a 5?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> RAT</h5>
                    <form action="modulos/modChamados/listaRat.php" method="GET">
                        <button type="input"> <img alt="W3Schools" src="recursos/src/preview.png"
                                style="width: 50%;"></button>
                    </form>
                </div>
            </div>
            <?php endif?>
            <?php if($_SESSION['codsetor']==1 || $_SESSION['codsetor']==5): //Apenas diretoria e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Bonificações</h5>
                    <form action="//172.168.1.254:8099/modulos/modBonific/listaBnf.php" method="POST">
                    <!-- <form action="//172.168.1.254:8099/teste.php" method="POST"> -->
                    <input hidden name="coduser" value=<?php echo $_SESSION['coduser']?>>
                        <input hidden name="nome" value=<?php echo $_SESSION['nome']?>>
                        <input hidden name="codsetor" value=<?php echo $_SESSION['codsetor']?>>
                        <input hidden name="setor" value=<?php echo $_SESSION['setor']?>>
                        <input hidden name="codrca" value=<?php echo $_SESSION['codrca']?>>
                        <button type="input" name="origem" value="interno"> <img alt="W3Schools" src="recursos/src/gift.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>

            <!-- Testes temporario -->
            <?php if($_SESSION['codsetor']==0): //Apenas diretoria e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Bonificações</h5>
                    <form action="//172.168.1.254:8099/modulos/modBonific/listaBnf2.php" method="POST">
                    <!-- <form action="//172.168.1.254:8099/teste.php" method="POST"> -->
                    <input hidden name="coduser" value=<?php echo $_SESSION['coduser']?>>
                        <input hidden name="nome" value=<?php echo $_SESSION['nome']?>>
                        <input hidden name="codsetor" value=<?php echo $_SESSION['codsetor']?>>
                        <input hidden name="setor" value=<?php echo $_SESSION['setor']?>>
                        <input hidden name="codrca" value=<?php echo $_SESSION['codrca']?>>
                        <input hidden name="aprov" value="on">
                        <input hidden name="pend" value="on">
                        <button type="input" name="origem" value="interno"> <img alt="W3Schools" src="recursos/src/gift.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>
            <!-- Fim teste -->


            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==4 || $_SESSION['codsetor']==5 ): //Apenas diretoria, comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Cargas</h5>
                    <form action="modulos/modcargas/cargasControl.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/shipping.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']==2 || $_SESSION['codsetor']==6 || $_SESSION['codsetor']==10): //Apenas Laboratório Produção e fiscal?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Cargas</h5>
                    <form action="modulos/modcargas/cargasVisual.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/shipping.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>


            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==4 || $_SESSION['codsetor']==5): //Apenas diretoria e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Faturamento do Dia</h5>
                    <form action="modulos/modrelatorio/painelVendas.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/panel.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==5): //Apenas diretoria e comercial?>
                <div class="col-md-3" style="padding-bottom:30px">
                    <div class="card" style="width: 18rem;">
                        <h5 class="card-title" style="text-align:center"> Políticas</h5>
                        <form action="modulos/modPolitica/clientes.php" method="GET">
                            <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/politica.png"
                                    style="width:50%"></button>
                        </form>
                    </div>
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==6): //Apenas diretoria e produção?>
                <div class="col-md-3" style="padding-bottom:30px">
                    <div class="card" style="width: 18rem;">
                        <h5 class="card-title" style="text-align:center"> Produção</h5>
                        <form action="modulos/modProducao/prodDiaria.php" method="GET">
                            <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/efficiency.png"
                                    style="width:50%"></button>
                        </form>
                    </div>
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']==0 || $_SESSION['codsetor']==5): //Apenas TI e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Múltiplos</h5>
                    <form action="modulos/modMultiplos/multProd.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/multiple.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']==0 || $_SESSION['codsetor']==5): //Apenas TI e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center"> Mapa Carregamento</h5>
                    <form action="modulos/modMultiplos/carreg.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/shipping.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==6 || $_SESSION['codsetor']==7): //Apenas TI e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center">Controle de Almoxarifado</h5>
                    <form action="homea.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/multiple.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']==0 || $_SESSION['codsetor']==4 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==10): //Apenas TI e comercial?>
            <div class="col-md-3" style="padding-bottom:30px">
                <div class="card" style="width: 18rem;">
                    <h5 class="card-title" style="text-align:center">Gerente de Faltas</h5>
                    <form action="modulos/modfaltas/home.php" method="GET">
                        <button type="input" value="teste"> <img alt="W3Schools" src="recursos/src/multiple.png"
                                style="width:50%"></button>
                    </form>
                </div>
            </div>
            <?php endif?>


        </div>



    </div>

    <div class="header">

    </div>


    <script src="recursos/js/jquery.min.js"></script>
    <script src="recursos/js/bootstrap.min.js"></script>
    <script src="recursos/js/scripts.js"></script>
    <script src="recursos/js/Charts.js"></script>
    <script src="recursos/js/Chart.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</body>

</html>