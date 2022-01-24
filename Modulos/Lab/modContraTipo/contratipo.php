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
        <link rel="stylesheet" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" href="css/contratipo.css">  
    </head>
<body>
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
            <section class="conteudo">
                    <div class="conteudo-tabela">
                        <table>
                            <thead>
                                <tr>
                                    <th>#<input type="checkbox"/></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
                        <div class="menu-itens"><input id="5" type="checkbox"><label for="6">Resina</label></div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Revestimentos</button>
                    <div class="dropdown-content">
                        <div class="menu-itens"><input id="6" type="checkbox"><label for="6">Texturas</label></div>
                        <div class="menu-itens"><input id="7" type="checkbox"><label for="7">Sublime</label></div>
                        <div class="menu-itens"><input id="8" type="checkbox"><label for="8">Rev. Marmore</label></div>
                        <div class="menu-itens"><input id="9" type="checkbox"><label for="9">Rev. Cimento</label></div>
                        <div class="menu-itens"><input id="1" type="checkbox"><label for="10">Minerais</label></div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Sintéticos</button>
                    <div class="dropdown-content">
                        <div class="menu-itens"><input id="11" type="checkbox"><label for="11">Esmalte</label></div>
                        <div class="menu-itens"><input id="12" type="checkbox"><label for="12">Verniz</label></div>
                        <div class="menu-itens"><input id="13" type="checkbox"><label for="13">Fundo Complemento</label></div>
                        <div class="menu-itens"><input id="14" type="checkbox"><label for="14">Pasta</label></div>
                        
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Complementos</button>
                    <div class="dropdown-content">
                        <div class="menu-itens"><input id="16" type="checkbox"><label for="16">Acrilia/Fit</label></div>
                        <div class="menu-itens"><input id="17" type="checkbox"><label for="17">Corrida/Fit</label></div>
                        <div class="menu-itens"><input id="18" type="checkbox"><label for="18">Selador/Fit</label></div>
                        <div class="menu-itens"><input id="19" type="checkbox"><label for="19">Funto Preparador/Fit</label></div>
                        
                    </div>
                </div>
                
            </section>
        </section>
    </main>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
