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
                <a href="copiarPolitica.php">
                    <i class='bx bx-message-rounded-edit'></i>
                    <span class="link-nome">Copiar Política</span>
                </a>
                <span class="tooltip">Copiar Política</span>
            </li>
            <li>
                <a href="historico.php">
                    <i class='bx bx-copy-alt'></i>
                    <span class="link-nome">Histórico</span>
                </a>
                <span class="tooltip">Histórico</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-merge'></i>
                    <span class="link-nome">Perfil Política</span>
                </a>
                <span class="tooltip">Perfil Política</span>
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
                <div>
                    <h1>Perfis Cadastrados</h1>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Representante</th>
                            <th>Região</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabela__itens">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5"> 
                                <div class="controles">
                                    <button onclick="controls.goTo(1)"><<</button>
                                    <button onclick="controls.prev()"><</button>
                                    <div class="botoes" id="botoes_num_pages"></div>
                                    <button onclick="controls.next()">></button>
                                    <button onclick="controls.goTo(999)">>></button>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</body>
<script>
    function listItems(items, pageActual, limitItems){
        let result = [];
        totalPage = state.totalPage
        let count = ( pageActual * limitItems ) - limitItems;
        let delimiter = count + limitItems;
        //console.log(totalPage, count, delimiter)

        if(pageActual <= totalPage){
            for(let i=count; i<delimiter; i++){
                if(items[i] != null){
                    result.push(items[i]);
                }
                count++;
            }
        }

        return result;
    }

    function listarPagina(){
        
        let paginavel = listItems(dados.items, state.page, state.perPage);
        console.log(paginavel)
        let itens = [];
        paginavel.forEach(function(i){
            itens+= `<tr class="perfil_linhas">
                                    <td>${i}</td>
                                    <td>${i}</td>
                                    <td>${i}</td>
                                    <td>${i}</td>
                                    <td><button onClick="historico.getHistorico(${i})">Ver</button></td>
                            </tr>`;
                })
                document.getElementById('tabela__itens').innerHTML= itens ;
    }    

    const dados = {
        items: [1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0]
                
    }

    const perPage = 15;

     const state = {
        page: 1,
        perPage,
        totalPage : Math.ceil( dados.items.length / perPage ),
        maxVisibleButtons : 3,
    }

    const controls = {
        next() {
            if(state.page < state.totalPage)
                state.page++
            //console.log(state.page)
            list.update()
            button.update();
        },
        prev() {
            if(state.page > 1 )
                state.page--
            //console.log(state.page)
            list.update()
            button.update();
        },
        goTo(page) {
            state.page = +page;

            if(page <= 1)
                state.page = 1;

            if(page >= state.totalPage)
                state.page = state.totalPage;
            //console.log(state.page);
            list.update()
            button.update();
        },
    }

    const list = {
        create(){
            let item= dados.items
        },
        update(){
            document.getElementById('tabela__itens').innerHTML ="<div></div>";
            listarPagina(dados.items, state.page);
        }

    }

    const button = {
        element:  document.getElementById('botoes_num_pages'),
        create(number) {
            const button2 = document.createElement('button');
            button2.innerHTML = number;

            button2.addEventListener('click', (event)=>{
                const page = event.target.innerText;

                controls.goTo(page)
            })
            button.element.appendChild(button2); //
        },
        update() {
            button.element.innerHTML = "";
            const {maxLeft, maxRight} = button.calculateMaxVisible();
            //console.log(maxLeft, maxRight)

            for(let page = maxLeft; page <= maxRight; page ++)
                button.create(page)
            let btn = button.element.children;
            
            for(let cont = 0; cont < btn.length; cont ++){
                if(btn[cont].innerText == state.page){
                    btn[cont].style.backgroundColor = "red";
                }
            };

        },
        calculateMaxVisible() {
            let maxLeft = (state.page - Math.floor(state.maxVisibleButtons/2))
            let maxRight = (state.page + Math.floor(state.maxVisibleButtons/2))

            if(maxLeft < 1){
                maxLeft = 1;
                maxRight = state.maxVisibleButtons
            }
            if(maxRight >= state.totalPage ){
                maxLeft = state.totalPage - (state.maxVisibleButtons - 1)
                maxRight = state.totalPage
            }
            if(maxLeft < 1)
                maxLeft = 1
            return {maxLeft, maxRight}
        },
        eventos() {
            
        }
    }

    function init(){
        list.update()
        button.update()
    }

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
    init();
</script>
</html>