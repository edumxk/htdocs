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
        <link rel="stylesheet" href="css/style.css">  
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
                <section class="titulo">
                    <h1>Contratipo de Produtos em Fórmulas</h1>
                    <h2>Este módulo substitui o produto "Origem" pelo produto "Destino" de acordo com os produtos selecionados na próxima tela.</h2>
                </section> 

                <section class="formulario">     
                    <form action="contratipo.php?" method="GET"> 
                        <div class="formulario-itens">
                            <div class="formulario-itens-titulo">
                                <h2>Produto Origem</h2>
                            </div>
                            <div class="formulario-itens-img">
                                <img src="/Recursos/src/frente.png" id="contrario" alt="">
                            </div>
                            <div class="formulario-itens-codigo"> 
                                <label for="codigo1">Código</label> 
                                <input type="number" id="codigo1"> 
                            </div>
                            <div class="formulario-itens-produto"> 
                                <label for="produto1">Produto</label> 
                                <input type="text"  placeholder="Origem" disabled>
                            <a id="produto1" action="#" onclick="busca()">...</a>  
                            </div>                
                        </div>
                        <div class="formulario-itens">
                            <div class="formulario-itens-titulo">
                                <h2>Produto Destino</h2>
                            </div>
                            <div class="formulario-itens-img" >
                                <img src="/Recursos/src/frente.png"  alt="">
                            </div>
                            <div class="formulario-itens-codigo">
                                <label for="codigo2">Código</label>
                                <input type="number" id="codigo2">
                            </div>
                            <div class="formulario-itens-produto">
                                <label for="produto2">Produto</label>
                                <input type="text" id="produto2" placeholder="Destino"  autocomplete="off" disabled>                        
                                <a id="produto2" action="#" onclick="busca()">...</a>  
                            </div>
                        </div>
                        <div class="formulario-botao">
                            <button type="submit">Avançar</button>
                        </div>
                    </form>
                </section>
            </section>  
        </main>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>