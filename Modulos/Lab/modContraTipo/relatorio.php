<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Modulos/Lab/modContraTipo/control/controle.php');
session_start();
if($_SESSION['nome']== null){
    header("location:\..\..\home.php");
}
date_default_timezone_set('America/Araguaina');

$produtos = ProdutoPesquisa::getProduto();
?>
<html lang="pt-BR">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <title>Relatórios</title>
        <link rel="stylesheet" href="./src/css/reset.css">
        <link rel="stylesheet" href="./src/css/sidebar.css">
        <link rel="stylesheet" href="./src/css/index.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="./src/css/style.css">  
    </head>
    <body>
        <main>
            <section class="menu">
                <div class="logo-conteudo">
                    <div class="logo">
                        <img class='kokar_logo' src="/Recursos/src/Logo-kokar.png"></img>
                        <div class="logo-nome">Laboratório</div>
                    </div>
                    <i class='bx bx-menu' id="btn"></i>
                </div>
                <ul class="navegacao">
                    <li>
                        <a href="index.php">
                            <i class='bx bx-home'></i>
                            <span class="link-nome">Página Inicial</span>
                        </a>
                        <span class="tooltip">Página Inicial</span>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bx-message-rounded-edit'></i>
                            <span class="link-nome">Revalidar Lotes</span>
                        </a>
                        <span class="tooltip">Revalidar Lotes</span>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bx-copy-alt'></i>
                            <span class="link-nome">Copiar Métodos</span>
                        </a>
                        <span class="tooltip">Copiar Métodos</span>
                    </li>
                    <li>
                        <a href="index.php">
                            <i class='bx bx-merge'></i>
                            <span class="link-nome">Alterar Fórmulas</span>
                        </a>
                        <span class="tooltip">Alterar Fórmulas</span>
                    </li>
                </ul>
                <div class="perfil-conteudo">
                    <div class="perfil">
                        <div class="perfil-detalhes">
                            <img src="./src/img/curr.jpg" alt="imagem de perfil">
                            <div class="nome-setor">
                                <div class="nome">Eduardo Patrick</div>
                                <div class="cargo">Desenvolvedor</div>
                                <div class="setor">T.I.</div>
                            </div>
                        </div>
                        <a href="/homelab.php"><i class='bx bxs-log-out' id="sair"></i></a>
                    </div>
                </div>
            </section>  
        </main>
    </body>
</html>