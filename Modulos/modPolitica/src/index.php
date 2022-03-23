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
            
                    <button type="button" id="close" onclick="fechar()">&times;</button>
                    
                    
                    <button id="btn_desativar" type="button" onclick="desativar()" class="btn btn-warning">Desativar</button>
                    <button id="btn_ativar" type="button" onclick="ativar()" class="btn btn-success">Ativar</button>
                    
                    <button id="btn_excluir" type="button" hidden onclick="excluir()" class="btn btn-danger">Excluir</button>
                    <button id="btn_criar" type="button" hidden onclick="criar()" class="btn btn-info">Criar</button>
                    <button type="submit" onclick="salvar(4)" class="btn btn-primary">Salvar</button>
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
                        <button type="submit" onclick="salvar(4)" class="btn btn-primary" data-dismiss="modal">Salvar Alterações</button>
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
                    status = arr[0][5];
                    arr.forEach(function(t){
                        body += '<tr>'
                                +    '<td class="politica__grupo">'+t[0]+'</td>'
                                +    '<td class="politica__descricao">'+t[1]+'</td>'
                                +    '<td class="politica__desconto"><input class="desconto" onfocusout="attDesconto(this, '+parseFloat(t[3])+')" type="text" value="'+t[2]+'"></input></td>'
                                +    '<td class="politica__tabela"><input class="tabela" type="text" disabled value="'+getTabela(parseFloat(t[2]), parseFloat(t[3]))+'"></input></td>'
                                +'</tr>'
                                })

                    $('#dadosmodal').empty();
                    $('#modal-titulo').empty();
                    $('#modal-titulo').append(arr[0][4]);
                    $('#modalObs').text('Obs da ultima alteração de politica');
                    $('#dadosmodal').append(body);
                    $('#dadosmodal').trigger("update", true);
                    console.log("abrir modal");
                    switch(status){
                        case '1':
                            console.log(status)
                            $('#btn_desativar').prop('hidden',false);
                            $('#btn_ativar').prop('hidden',true);
                            $('#btn_excluir').prop('hidden',false);
                            $('#btn_criar').prop('hidden',true);
                            break;
                        case '0':
                                console.log(status)
                                $('#btn_desativar').prop('hidden',true);
                                $('#btn_ativar').prop('hidden',true);
                                $('#btn_excluir').prop('hidden',true);
                                $('#btn_criar').prop('hidden',false);
                                
                                break;
                        case '2':
                            console.log(status)
                            $('#btn_desativar').prop('hidden',true);
                            $('#btn_ativar').prop('hidden',false);
                            $('#btn_excluir').prop('hidden',false);
                            $('#btn_criar').prop('hidden',true);
                            break;
                    }
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
        let listaAlteracoes = [];
        let pendencias = [];

        let obsGeral = $('#modalObs').text();
        let descontos = $('.desconto');
        let novoDesconto = [];
       
        /* Validação para Salvar Politica */
        descontos.each(function (){
            let grupo = $(this).parent().parent().find('.politica__grupo').text();
            let ref = $(this).val();
            arr.forEach(function(t){ 
                if(t[0]==grupo){
                    novoDesconto.push({
                        grupo: grupo,
                        novo: ref,
                        antigo: t[2]
                    })
                }
            })
        })
        console.log(validarDesconto(novoDesconto))
            
/*
arr.forEach(function(t){ 
    if($(ref).prop('id').substr(3)==(t[0])){
        if(t[2]!= novoDesconto){
            
            //inserindo alterações de desconto
            listaAlteracoes.push( {
                grupo: t[0], 
                descAntigo: t[2], 
                descNovo: novoDesconto
            })
            if($(ref).val().length < chars ){
                let grupo = $(ref).parent().parent().find('.politica__descricao').text()
                alert('Politica alterada com obs não preenchida: '+grupo);
                pendencias.push(grupo);
                listaAlteracoes = [];
            }
        } 
    }
})
*/
    }

    function validarDesconto(novoDesconto){
        console.log('inicio')
        let listaAlteracoes = [];
        novoDesconto.forEach(function(d){
            if(d.novo != d.antigo)
            listaAlteracoes.push({
                grupo:  d.grupo, 
                descAntigo: d.antigo, 
                descNovo: d.novo
            })
        })
        return listaAlteracoes;
    }

    function excluir(){

    }

    function desativar(){

    }

    function attDesconto(linha, tabela){
        let grupo = $(linha).parent().parent().find('.politica__grupo').text();
        let desconto = parseFloat($(linha).val());
        let refTabela = $(linha).parent().parent().find('.tabela');
        $(linha).val(parseFloat($(linha).val()));
        //checar se o desconto foi alterado, se sim, atribuir requered.
        arr.forEach(function(i){
            if (i[0]==grupo && desconto != i[2]){
                $('#obs'+grupo).prop('required',true);
                $('#obs'+grupo).prop('hidden',false);
                if(desconto<0 || desconto>100 || isNaN(desconto)){
                    $(linha).val(i[2]);
                    desconto = i[2];
                    $('#obs'+grupo).prop('required',false);
                    $('#obs'+grupo).prop('hidden',true);
                }
            }if(i[0]==grupo && desconto == i[2]){
                $('#obs'+grupo).prop('required',false);
                $('#obs'+grupo).prop('hidden',true);
            }
        })
        

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