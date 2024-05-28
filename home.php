<?php
    require_once("Controle/userControl.php");
    require_once("Controle/formatador.php");
    
    session_start();
    //var_dump(isset($_POST['submit']));
    
    if(!isset($_SESSION)){
        if($_SESSION==array())
        header("location: index.php");
        else
        echo 'bugou aqui';
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
        $_SESSION['coduser'] =  $user['user']['CODUSER'];
        $_SESSION['nome'] =     $user['user']['NOME'];
        $_SESSION['codsetor'] = $user['user']['CODSETOR'];
        $_SESSION['setor'] =    $user['user']['SETOR'];
        $_SESSION['codrca'] =   $user['user']['CODRCA'];
        $_SESSION['cargo'] =   $user['user']['CARGO'];
        
        //$timeout = 7200; // Number of seconds until it times out.

        // Check if the timeout field exists.
       if(isset($_SESSION['timeout'])) {
            // See if the number of seconds since the last
            // visit is larger than the timeout period.
            $duration = time() - (int)$_SESSION['timeout'];
            if($duration > $timeout) {
                // Destroy the session and restart it.
                $_SESSION = array();
                header("location: index.php");
            }
        }
        
        if($user['novo']==1){
            header("location:novasenha.php");
        }elseif($user['user']['CODUSER']==null || $user['user']['CODUSER']==''){
            header("location:index.php?msg=failed");
        }elseif($user['user']['CODUSER']>=0 && $user['novo']==0){
            header("location:home.php");
        }
    }

    //var_dump($_SESSION);
    if (!isset($_SESSION['coduser'])) {
        header("location: index.php?msg=usuario-nao-logado");
    }

?>

<!DOCTYPE html>
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
            <img src="/recursos/src/Logo-kokar5.png" alt="Logo Kokar" width="350">
        </div>
        <div class="usuario">
            <ul>
                <li>Usuário: <?php echo $_SESSION['nome']?></li>
                <li>Setor: <?php echo $_SESSION['setor']?></li>
                <li><a style="color: white" href="../index.php">Sair</a></li>
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
			</ol>
		</nav>
			<div class="icones-titulo">
				<h1 style="padding-bottom: 20px; padding-top: 20px">Módulos
				</h1>
			</div>
            <?php if($_SESSION['codsetor']<=5 || $_SESSION['codsetor']==101 || $_SESSION['codsetor']==12): //Se setores de 0 a 5?>
			    <div class="icones">  
                    <h5 class="cartao-title">RAT</h5>
                    <input class="botao" onclick="window.location.href='modulos/modChamados/listaRat.php'" 
                    type="image" src="recursos/src/preview.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==101): //Apenas diretoria e comercial?>
                <div class="icones">  
                <h5 class="cartao-title">Bonificações</h5>
                    <form action="//172.168.1.254:8099/modulos/modBonific/listaBnf.php" method="POST">
                        <!-- <form action="//172.168.1.254:8099/teste.php" method="POST"> -->
                        <input hidden name="coduser" value=<?php echo $_SESSION['coduser']?>>
                        <input hidden name="nome" value=<?php echo $_SESSION['nome']?>>
                        <input hidden name="codsetor" value=<?php echo $_SESSION['codsetor']?>>
                        <input hidden name="setor" value=<?php echo $_SESSION['setor']?>>
                        <input hidden name="codrca" value=<?php echo $_SESSION['codrca']?>>
                        <input hidden name="origem" value="interno">
                        <button style="border: none; background: none" type="submit"><input class="botao" type="image" src="recursos/src/gift.png" height="140" width="140"/> </button>
                    </form>
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=0): //Apenas diretoria e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Bonificações 2</h5>
                    <input class="botao" onclick="bnf2()" 
                    type="image" src="recursos/src/gift.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=0 ): //Apenas diretoria, comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Campanha</h5>
                    <input class="botao" onclick="window.location.href='modulos/modpoliticasEst/index.php'" 
                    type="image" src="recursos/src/beneficiar.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']>=0 ): //Apenas diretoria, comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Cargas</h5>
                    <input class="botao" onclick="window.location.href='modulos/modcargas/cargasControl.php'" 
                    type="image" src="recursos/src/shipping.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==4 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==101) : //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Alterar Praça</h5>
                    <input class="botao" onclick="window.location.href='modulos/modPracas/mudaPraca.php'" 
                    type="image" src="recursos/src/efficiency.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==4 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==101 || $_SESSION['codsetor']==11 || $_SESSION['coduser']==42): //Apenas diretoria e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Faturamento</h5>
                    <input class="botao" onclick="window.location.href='modulos/modrelatorio/painelVendas.php'" 
                    type="image" src="recursos/src/panel.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==101 || $_SESSION['codsetor']==10): //Apenas diretoria e comercial?> 
                <div class="icones">  
                    <h5 class="cartao-title">Políticas</h5>
                    <input class="botao" onclick="window.location.href='modulos/modPolitica/src/index.php'" 
                    type="image" src="recursos/src/politica.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor'] == 14 || $_SESSION['coduser'] == 15): //Apenas diretorian RH e Wesley Sanzio?>
                <div class="icones">  
                    <h5 class="cartao-title">Frete</h5>
                    <input class="botao" onclick="window.location.href='modulos/modFrete/home.php'" 
                    type="image" src="recursos/src/logistica.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=0 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==101): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Múltiplos</h5>
                    <input class="botao" onclick="window.location.href='modulos/modMultiplos/multProd.php'" 
                    type="image" src="recursos/src/multiple.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==101): //Apenas TI e comercial?>
                <div class="icones">                      
                    <h5 class="cartao-title">Via de Mapa</h5>
                    <input class="botao" onclick="window.location.href='modulos/modMultiplos/carreg.php'" 
                    type="image" src="recursos/src/shipping.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==6 || $_SESSION['codsetor']==8 || $_SESSION['codsetor']==61 || $_SESSION['codsetor']==7 || $_SESSION['codsetor']==71 || $_SESSION['codsetor']==2): //Apenas TI e comercial?>
                <div class="icones">      
                    <h5 class="cartao-title">Almoxarifado</h5>
                    <input class="botao" onclick="window.location.href='homea.php'" 
                    type="image" src="recursos/src/multiple.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==4 || $_SESSION['codsetor']==5 || $_SESSION['codsetor']==10): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Faltas de Clientes</h5>
                    <input class="botao" onclick="window.location.href='modulos/modfaltas/home.php'" 
                    type="image" src="recursos/src/multiple.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==2 || $_SESSION['codsetor']==12 || $_SESSION['codsetor']== 8): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Laboratório</h5>
                    <input class="botao" onclick="window.location.href='homelab.php'" 
                    type="image" src="recursos/src/politica.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']>=0): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Metas Produção</h5>
                    <input class="botao" onclick="window.location.href='modulos/modMetas/prodDiaria.php'" 
                    type="image" src="recursos/src/metricas.png" height="140" width="140"/> 
                </div>
            <?php endif?>

            <?php if($_SESSION['codsetor']<=1 || $_SESSION['codsetor']==6 || $_SESSION['codsetor']==71): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Mapa Produção</h5>
                    <input class="botao" onclick="window.location.href='modulos/modMapaProducao/index.php'" 
                    type="image" src="recursos/src/planejamento.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
            
            <div class="icones">  
                <h5 class="cartao-title">Resumo Produção</h5>
                <input class="botao" onclick="window.location.href='modulos/modProducao2/INDEX.php'" 
                type="image" src="recursos/src/producao2.png" height="140" width="140"/> 
            </div>
            
            <?php if($_SESSION['codsetor']<=0): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Email RAT</h5>
                    <input class="botao" onclick="window.location.href='modulos/modChamados/recursos/email/testes.php'" 
                    type="image" src="recursos/src/shipping.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            <?php if($_SESSION['codsetor']<=0): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">BI teste</h5>
                    <input class="botao" onclick="window.location.href='modulos/BI/index.php'" 
                    type="image" src="recursos/src/planejamento.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            <?php if($_SESSION['codsetor']<=0): //Apenas TI e comercial?>
                <div class="icones">  
                    <h5 class="cartao-title">Status</h5>
                    <input class="botao" onclick="window.location.href='modulos/modstatus/status.php'" 
                    type="image" src="recursos/src/planejamento.png" height="140" width="140"/> 
                </div>
            <?php endif?>
            
        </div>
	<div class="complemento"></div>
	<script src="recursos/js/jquery.min.js"></script>
	<script src="recursos/js/bootstrap.min.js"></script>
	<script src="recursos/js/scripts.js"></script>
    <script>
            function sair(){
                $_SESSION = array();
            }
            function bnf1() {
                window.location.assign("http://172.168.1.254:8099/modulos/modBonific/listaBnf.php");
    
            }
            function bnf2() {
                window.location.assign("http://172.168.1.254:8099/modulos/modBonific/listaBnf2.php");
            }
    </script>
</body>
</html>