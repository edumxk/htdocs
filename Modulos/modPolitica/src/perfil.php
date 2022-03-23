<?php
require_once './control/controle.php';
    session_start();
    if ($_SESSION['nome'] == null) {
        header("location:\..\..\home.php");
    }
    $clientes = Controle::getClientes();
    //$politicas2 = Controle::getPoliticas(271 ,2);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas Comerciais | Perfil</title>
    <!-- CSS only -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/perfil.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <header class="head">
        <div class="head__logo">
            <img src="/Recursos/src/Logo-Kokar5.png" alt="Logo da Kokar Tintas">
        </div>
        <div class="head__navegacao">
            <ul class="head__navegaca__lista">
                <li class="head__navegaca__lista-itens"><a href="#">Home</a></li>
                <li class="head__navegaca__lista-itens"><a href="#">Politicas</a></li>
                <li class="head__navegaca__lista-itens"><a href="#">Perfil</a></li>
            </ul>
        </div>
    </header>
    <section class="menu">
        <div class="logo-conteudo">
            <div class="logo">
                <div class="logo-nome"><p>Políticas Comerciais</p> </div>
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
                    <span class="link-nome">Copiar Política</span>
                </a>
                <span class="tooltip">Copiar Política</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-copy-alt'></i>
                    <span class="link-nome">Histórico</span>
                </a>
                <span class="tooltip">Histórico</span>
            </li>
            <li>
                <a href="index.php">
                    <i class='bx bx-merge'></i>
                    <span class="link-nome">Perfil Política</span>
                </a>
                <span class="tooltip">Perfil Política</span>
            </li>
        </ul>
        <div class="perfil-conteudo">
            <div class="perfil">
                <div class="perfil-detalhes">
                    <img src="/Recursos/src/imgfun/curr.jpg" alt="imagem de perfil">
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
    <section class="principal">
        <div class="principal__filtros">
            <div class="principal__filtros-caixa pesquisa">
                <input id="busca" type="text" placeholder="Pesquisar...">
            </div>
        </div>
        <div class="pagina__loader">
            <div class="loader"></div>
        </div> 
        <div class="principal__conteudo">
            <div class="principal__conteudo-conteudo">
                <section class="principal__conteudo-cadastro">
                    <div class="principal__conteudo-cadastro-btnnovo">
                        <button>Novo</button>
                    </div>
                    <div class="principal__conteudo-cadastro-origem">
                        <select><option value="Origem">Origem1</option> </select>
                        <select><option value="Origem">Origem2</option> </select>
                        <select><option value="Origem">Origem3</option> </select>

                    </div>
                    <div class="principal__conteudo-cadastro-formulario">
                        <div class="principal__conteudo-cadastro-formulario-campo">
                            <label for="descricao">Descrição</label>
                            <input type="text" id="descricao" placeholder="Descrição do Perfil">
                        </div>
                        <div class="principal__conteudo-cadastro-formulario-campo">
                            <label for="regiao">Região</label>
                            <input type="text" id="regiao" placeholder="Descrição da Região">
                        </div>
                        <div class="principal__conteudo-cadastro-formulario-campo">
                            <label for="rca">Representante Comercial</label>
                            <input type="text" id="rca" placeholder="Selecione o RCA">
                        </div>
                        <div>
                            <button>Avançar</button>
                        </div>
                    </div>
                </section>
                <section class="principal__conteudo-listagem">
                    <div class="principal__conteudo-listagem-titulo">
                        <h3>Listagem</h3>
                    </div>
                    <div class="principal__conteudo-listagem-lista">
                        <ul>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                            <li>Perfil Para Edição</li>
                        </ul>
                    </div>

                </section>
            </div>
        </div>
    </section>
</body>
<script>
        window.onload = function() {
        let botao = $('.btn__politica');
        let atividade = $('.btn__status');

        //Pesquisa da tabela clientes
        $("#busca").on("keyup", function() {
            const valor = $(this).val().toUpperCase().split(' ');

        $(".linha").each(function() {
            const busca = $(this).text().toUpperCase();
            let referencia = $(this);
            let flag= 0;
            valor.filter(function(element) {                            
                if (busca.indexOf(element) !== -1) {   // se for encontrado um valor nos dois arrays
                    flag  ++;
                }else{
                    flag = 0;
                }
                if(valor.length === flag){
                    referencia.show()
                }else{
                    referencia.hide()
                }
                });
            }); 
        });
        //carregamento da página
        $(".loader").toggle();
        $(".pagina__loader").css('height', '60px');
        $(".principal__conteudo").toggle();
    }
</script>
</html>