<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Modulos/Lab/modContraTipo/control/controle.php');
session_start();
if($_SESSION['nome']== null){
    header("location:\..\..\home.php");
}
$produtos = ContraTipoControle::getProdutos(1276, 1);
//var_dump($produtos);
date_default_timezone_set('America/Araguaina');
?>
<html lang="pt-BR">
    <head>
        meta:
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <title>Formulas - Alterar</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="./src/css/reset.css">
        <link rel="stylesheet" href="./src/css/sidebar.css">
        <link rel="stylesheet" href="./src/css/contratipo.css">
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
                        <a href="#">
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
                        <a href="#">
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
                        <i class='bx bxs-log-out' id="sair"></i>
                    </div>
                </div>
            </section>
            <section class="home-conteudo"> 
                <section class="home-conteudo__itens">
                    <div class="dropdown">
                        <button class="dropbtn">Tintas</button>
                        <div class="dropdown-content">
                            <div class="menu-itens unselectable"><input id="1" type="checkbox"><label for="1">Fit</label></div>
                            <div class="menu-itens unselectable"><input id="2" type="checkbox"><label for="2">Economica</label></div>
                            <div class="menu-itens unselectable"><input id="3" type="checkbox"><label for="3">Standard</label></div>
                            <div class="menu-itens unselectable"><input id="4" type="checkbox"><label for="4">Premium</label></div>
                            <div class="menu-itens unselectable"><input id="5" type="checkbox"><label for="5">Piso</label></div>
                            <div class="menu-itens unselectable"><input id="15" type="checkbox"><label for="15">Resina</label></div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="dropbtn">Revestimentos</button>
                        <div class="dropdown-content">
                            <div class="menu-itens unselectable"><input id="6" type="checkbox"><label for="6">Texturas</label></div>
                            <div class="menu-itens unselectable"><input id="7" type="checkbox"><label for="7">Sublime</label></div>
                            <div class="menu-itens unselectable"><input id="8" type="checkbox"><label for="8">Rev. Marmore</label></div>
                            <div class="menu-itens unselectable"><input id="9" type="checkbox"><label for="9">Rev. Cimento</label></div>
                            <div class="menu-itens unselectable"><input id="10" type="checkbox"><label for="10">Minerais</label></div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="dropbtn">Sintéticos</button>
                        <div class="dropdown-content">
                            <div class="menu-itens unselectable"><input id="11" type="checkbox"><label for="11">Esmalte</label></div>
                            <div class="menu-itens unselectable"><input id="12" type="checkbox"><label for="12">Verniz</label></div>
                            <div class="menu-itens unselectable"><input id="13" type="checkbox"><label for="13">Fundo Complemento</label></div>
                            <div class="menu-itens unselectable"><input id="14" type="checkbox"><label for="14">Pasta</label></div>
                            
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="dropbtn">Complementos</button>
                        <div class="dropdown-content">
                            <div class="menu-itens unselectable"><input id="16" type="checkbox"><label for="16">Acrílica/Fit</label></div>
                            <div class="menu-itens unselectable"><input id="17" type="checkbox"><label for="17">Corrida/Fit</label></div>
                            <div class="menu-itens unselectable"><input id="18" type="checkbox"><label for="18">Selador/Fit</label></div>
                            <div class="menu-itens unselectable"><input id="19" type="checkbox"><label for="19">Fundo Preparador/Fit</label></div>
                            
                        </div>
                    </div>                    
                </section>
                <section class="conteudo">    
                        <div class="busca-itens">    
                            <input type="text" id="produto"/>
                            <button onclick="concluir()">Concluir</button>
                        </div>
                        <div class="conteudo__tabela">
                            <table class="conteudo__produtos" id="tabelas-busca">
                                <thead>
                                    <tr>
                                        <th><input class="checkbox" onclick="marcar(this)" type="checkbox" id="select-all"/></th>
                                        <th>Código</th>
                                        <th>Descricao</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($produtos as $p):?>
                                    <tr class="produtos" id="#<?= $p->codprodmaster?>">
                                        <td><input class="checkbox" type="checkbox" name="produto" id="<?= $p->codprodmaster?>"/></td>
                                        <td><?= $p->codprodmaster?></td>
                                        <td class="texto"><?= $p->produto?></td>
                                    </tr>
                                    <?php endforeach?>
                                </tbody>
                            </table>
                        </div>             
                </section>
            </section>
        </main>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="./src/js/scripts.js"></script>
        <script src="./src/js/sidebar.js"></script>
        <script src="../../../recursos/js/jquery.tablesorter.js"></script>
        <script>
            //window.onload = carregar()
        </script>
    </body>
</html>
