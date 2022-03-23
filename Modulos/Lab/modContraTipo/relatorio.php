<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Modulos/Lab/modContraTipo/control/controle.php');
session_start();
if($_SESSION['nome']== null){
    header("location:\..\..\home.php");
}
date_default_timezone_set('America/Araguaina');
$agora = date('d/m/Y');
$alteracoes = ContraTipoControle::getAlteracoes();
$produtos = ProdutoPesquisa::getProduto();
?>
<html lang="pt-BR">
    <head>
        <meta name="Home Extranet" content="By Edumxk">
        <link rel="icon" href="../../../Recursos/img/favicon.ico" type="image/x-icon">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <title>Relatórios</title>
        <link rel="stylesheet" href="./src/css/reset.css">
        <link rel="stylesheet" href="./src/css/sidebar.css">
        <link rel="stylesheet" href="./src/css/relatorio.css"> 
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
                        <a href="relatorio.php">
                            <i class='bx bx-message-rounded-edit'></i>
                            <span class="link-nome">Histórico</span>
                        </a>
                        <span class="tooltip">Histórico</span>
                    </li>
                    <li>
                        <a href="./../modRevalidacao/revalidacao.php">
                            <i class='bx bx-copy-alt'></i>
                            <span class="link-nome">Revalidar Lotes</span>
                        </a>
                        <span class="tooltip">Revalidar Lotes</span>
                    </li>
                    <li>
                        <a href="./../modMetodo/index.php">
                            <i class='bx bx-merge'></i>
                            <span class="link-nome">Copiar Fórmulas</span>
                        </a>
                        <span class="tooltip">Copiar Fórmulas</span>
                    </li>
                </ul>
                <div class="perfil-conteudo">
                    <div class="perfil">
                        <div class="perfil-detalhes">
                            <!--<img src="./src/img/curr.jpg" alt="imagem de perfil">-->
                            <div class="nome-setor">
                                <div class="nome"><?=$_SESSION['nome']?></div>
                                <div class="cargo"><?=$_SESSION['cargo']?></div>
                                <div class="setor"><?=$_SESSION['setor']?></div>
                            </div>
                        </div>
                        <a href="/homelab.php"><i class='bx bxs-log-out' id="sair"></i></a>
                    </div>
                </div>
            </section> 
            <section class="conteudo">
                <div class="conteudo__ultimos">
                    <table class="conteudo__ultimos__tabela">
                        <thead>
                            <tr>
                                <th class="conteudo__titulo" colspan="4">Últimas Fórmulas Alteradas</th>
                            </tr>
                            <tr>
                                <th class="conteudo__cabecalho conteudo__cabecalho-id">Id</th>
                                <th class="conteudo__cabecalho conteudo__cabecalho-data">Data</th>
                                <th class="conteudo__cabecalho conteudo__cabecalho-produtos">Produtos</th>
                                <th class="conteudo__cabecalho conteudo__cabecalho-nome">Responsável</th>
                                <th class="conteudo__cabecalho conteudo__cabecalho-botao"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($alteracoes as $p): ?>
                                <tr onclick="preencher('<?= $p[0]?>')">
                                    <td class="conteudo__dados conteudo__dados-data"><?= $p[0]?></td>
                                    <td class="conteudo__dados conteudo__dados-data"><?= $p[5]?></td>
                                    <td class="conteudo__dados conteudo__dados-produtos"><?= $p[1]?> => <?= $p[2]?></td>
                                    <td class="conteudo__dados conteudo__dados-nome"><?= $p[3]?></td>
                                    <td class="conteudo__dados conteudo__dados-cancelar"><button data-toggle="modal" data-target="#modal-cancelar" onclick="itemDeletar(<?= $p[0]?>)">Cancelar</button></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div class="conteudo__produtos">
                    <table class="conteudo__produtos__tabela">
                        <thead>
                            <tr>
                                <th class="conteudo__titulo" colspan="2">Produtos Alterados</th>
                            </tr>
                            <tr>
                                <th class="conteudo__cabecalho conteudo__cabecalho-codprod">Codprod</th>
                                <th class="conteudo__cabecalho conteudo__cabecalho-descricao">Descrição</th>
                                <th class="conteudo__cabecalho conteudo__cabecalho-descricao">Fração</th>
                                
                            </tr>
                        </thead>
                        <tbody id="dados">
                            <tr>
                                <td colspan="2" class="conteudo__dados conteudo__dados-codprod">Selecione um Registro</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        <section class="modal-senha">
            <div class="modal fade" tabindex="-1" id="modal-cancelar" aria-labelledby="modal-cancelar" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Senha de Acesso</h5>
                        <button type="button" class="btn-close" onclick="fechar()" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input id="modal-senha"type="password" placeholder="Senha para Cancelamento" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="cancelar()">Confirmar</button>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
    <script src="/Recursos/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="./src/js/sidebar.js"></script>
    <script src="./src/js/relatorio.js"></script>
    
</html>