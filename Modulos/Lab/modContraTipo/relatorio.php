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
        <title>Contratipo - Relatório</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">  
    </head>
    <body>
        <header>
            <div class="caixa">
                <h1><img src="/Recursos/src/Logo-Kokar5.png">
                <nav>
                    <ul>
                        <li><a href="/../../homelab.php">Home</a></li>
                        <li><a href="index.php">Novo Contratipo</a></li>
                    </ul>
                </nav>
            </div>    
        </header>
        <main>
            <div id="formulario">          
                <form action="relatorio.php" method="GET">
                    <div class="form-group">
                        <label for="produto">Produto</label>
                        <input type="text" id="produto" placeholder="Código">
                        <a name="produto"> PRODUTOS CONTRATIPADOS</a>
                    </div>
                    <div class="form-group">
                        <button type="submit">Concluir</button>
                    </div>
                </form>
            </div>  
        </main>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>