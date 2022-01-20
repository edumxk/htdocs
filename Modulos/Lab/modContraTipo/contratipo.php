<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/modFrete/model/frete.php');
session_start();
if($_SESSION['nome']== null){
    header("location:\..\..\home.php");
}
?>
<?php
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Contratipo</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" href="css/contratipo.css">  
    </head>
<body>
    <header>
        <div class="logo">
            <img src="/Recursos/src/Logo-Kokar5.png" alt="Logo Kokar" width="350">
        </div>
        <div class="usuario">
            <ul>
                <li>Usuário: <?php echo $_SESSION['nome']?></li>
                <li>Setor: <?php echo $_SESSION['setor']?></li>
                <li><a style="color: white" href="../index.php">Sair</a></li>
            </ul>
        </div>		
    </header>
    <main>
        <section class="principal">
            <section class="navegacao">
                <nav id="home" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="/homelab.php">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="index.php">Contratipo</a>
                        </li>
                    </ol>
                </nav>
            </section>
            <section class="itens">
            <div class="dropdown">
                <button class="dropbtn">Tintas</button>
                <div class="dropdown-content">
                    <div class="menu-itens"><input id="1" type="checkbox"><label for="1">Fit</label></div>
                    <div class="menu-itens"><input id="2" type="checkbox"><label for="2">Economica</label></div>
                    <div class="menu-itens"><input id="3" type="checkbox"><label for="3">Standard</label></div>
                    <div class="menu-itens"><input id="4" type="checkbox"><label for="4">Premium</label></div>
                    <div class="menu-itens"><input id="5" type="checkbox"><label for="5">Piso</label></div>
                </div>
            </div>
                <button onclick="link(relatorio.php)" type="submit">Concluir</button>                  
            </section>
        </section>
    </main>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

background-color: #f4f4f4;
border-left: 6px solid #005282;