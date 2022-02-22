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
    <title>Políticas Comerciais</title>
    <!-- CSS only -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
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
                <li class="head__navegaca__lista-itens"><a href="#">Clientes</a></li>
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
            <div class="principal__filtros-caixa">
                <label for="rca">Rca</label>
                <select name="rca" id="">
                    <option value="0">Todos</option>
                </select>
            </div>
            <div class="principal__filtros-caixa">
                <label for="cidade">Cidade</label>
                <select name="cidade" id="">
                    <option value="0">Todos</option>
                </select>
            </div>
            <div class="principal__filtros-caixa">
                <label for="uf">UF</label>
                <select name="uf" id="">
                    <option value="0">Todos</option>
                </select>
            </div>
            <div class="principal__filtros-caixa">
                <label for="status">Status</label>
                <select name="status" id="">
                    <option value="0">Todos</option>
                </select>
            </div>
            <div class="principal__filtros-caixa">
                <label for="politica">Política</label>
                <select name="politica" id="">
                    <option value="0">Todos</option>
                </select>
            </div>
            <div class="principal__filtros-caixa pesquisa">
                <input id="busca" type="text" placeholder="Pesquisar...">
            </div>
        </div>
        <div class="pagina__loader">
            <div class="loader"></div>
        </div> 
        <div class="principal__conteudo">
            <table id="clientes">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>UF</th>
                        <th>RCA</th>
                        <th>Dias</th>
                        <th>Média</th>
                        <th>Status</th>
                        <th>Política</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $c): ?>
                    <tr class="linha">
                        <td class="principal__conteudo__clientes-codcli"><?= $c->getCodcli()?></td>
                        <td class="principal__conteudo__clientes-cliente"><?= $c->getCliente()?></td> 
                        <td class="principal__conteudo__clientes-uf"><?= $c->getUf()?></td>
                        <td class="principal__conteudo__clientes-rca"><?= $c->getRca()?></td>
                        <td class="principal__conteudo__clientes-dias"><?= $c->getDias()?></td>
                        <td class="principal__conteudo__clientes-media"><?= number_format($c->getTotal(),2,",",".")?></td>
                        <td class="principal__conteudo__clientes-status"><button class="btn__status"><?= $c->getStatus()?></button></td>
                        <td class="principal__conteudo__clientes-politica"><button class="btn__politica" onclick="ver(<?= $c->getCodcli()?>, <?= $c->getNumregiao()?>)"><?= $c->getPolitica()?></button></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
         <!-- Modal de edição das politicas -->
        <div class="modal fade" id="modalPoliticas">
            <div class="modal-dialog modal-xl">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 id="modal-titulo"></h2>
                    <textarea id="modalObs" name="obs-cliente" cols="40" rows="3"></textarea>
            
                    <button type="button" class="close" onclick="fechar()" data-dismiss="modal">&times;</button>
                    
                    <button type="button" onclick="desativar()" class="btn btn-warning" data-dismiss="modal">Desativar</button>
                    <button type="button" onclick="excluir()" class="btn btn-danger" data-dismiss="modal">Excluir</button>
                    <button type="button" onclick="salvar()" class="btn btn-primary" data-dismiss="modal">Salvar Alterações</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <div>
                        <table class="table" id="tbPoliticas">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Grupo</th>
                                    <th>Desconto</th>
                                    <th>Tabela</th>
                                    <th>Obs</th> 
                                </tr>
                            </thead>
                            <tbody id="dadosmodal">
                                <tr>
                                    <td colspan="4">ERRO</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" onclick="fechar()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="salvar()" class="btn btn-primary" data-dismiss="modal">Salvar Alterações</button>
                </div>
                
            </div>
            </div>
        </div>
    </section>
</body>
<script src="./js/sidebar.js"></script>
<script src="../js/jquery.tablesorter.js"></script>
<script>
    function ver(codcli, numregiao){
        var modal = $('#modalPoliticas')
        $.ajax({
                type: 'POST',
                url: "control/controle.php",
                data: {
                    'action': 'verPoliticas',
                    'cliente': codcli,
                    'numregiao': numregiao,
                },
                success: function(resposta) {
                    arr = JSON.parse(resposta);
                    body = "";

                    arr.forEach(function(t){
                        body += '<tr>'
                                +    '<td class="politica__grupo">'+t[0]+'</td>'
                                +    '<td class="politica__descricao">'+t[1]+'</td>'
                                +    '<td class="politica__desconto"><input class="desconto" onfocusout="attDesconto(this, '+parseFloat(t[3])+')" type="text" value="'+t[2]+'"></input></td>'
                                +    '<td class="politica__tabela"><input class="tabela" type="text" disabled value="'+getTabela(parseFloat(t[2]), parseFloat(t[3]))+'"></input></td>'
                                +    '<td class="politica__obs"><input type="text" placeholder="Informar Observação na Alteração" id="obs'+t[0]+'"></input></td>'
                                +'</tr>'
                                })

                    $('#dadosmodal').empty();
                    $('#modal-titulo').empty();
                    $('#modal-titulo').append(arr[0][4]);
                    $('#modalObs').text('Obs do cliente, obs geral');
                    $('#dadosmodal').append(body);
                    $('#dadosmodal').trigger("update", true);
                    console.log("abrir modal");
                    modal.modal('show');
                }
                }); 
    }

    function fechar(){
        $('#modalPoliticas').modal('hide');
    }

    function getTabela(desconto, tabela){
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(((100-desconto)/100)*tabela);
    }

    function salvar(){
        //1° - Salvar log da politica alterada com a respectiva alteração.
    }

    function excluir(){

    }

    function desativar(){

    }

    function attDesconto(linha, tabela){
        let grupo = $(linha).parent().parent().find('.politica__grupo').text();
        let desconto = parseFloat($(linha).val());
        let refTabela = $(linha).parent().parent().find('.tabela');
        console.log(grupo +', '+desconto+', '+ tabela);
        refTabela.val(getTabela(desconto,tabela));
    }

    window.onload = function() {
        let botao = $('.btn__politica');
        let atividade = $('.btn__status');

        botao.each(function (i){
            if($(this).text() == 'ATIVO')
                $(this).css('background-color', '#00a000');
            if($(this).text()=='VAZIO')
                $(this).css('background-color', '#e04729');
            if($(this).text()=='INATIVA')
                $(this).css('background-color', 'gold');
            if($(this).text()=='EXCLUIDA')
                $(this).css('background-color', '#4B0082');
        });

        atividade.each(function (i){
            if($(this).text() == 'POSITIVO')
                $(this).css('background-color', '#00a000');
            if($(this).text()=='ATIVO')
                $(this).css('background-color', 'gold');
            if($(this).text()=='INATIVO')
                $(this).css('background-color', '#e04729');
        });

        $("#clientes").tablesorter();
        $("#tbPoliticas").tablesorter();

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
        $(".pagina__loader").css('height', '90px');
        $(".principal__conteudo").toggle();
    }
</script>
</html>