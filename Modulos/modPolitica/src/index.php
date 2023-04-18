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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
                <a href="/home.php"><i class='bx bxs-log-out' id="sair"></i></a>
            </div>
        </div>
    </section>
    <section class="principal">
       
        <div class="pagina__loader">
            <div class="loader"></div>
        </div> 
        <div class="principal__conteudo">
            <table id="clientes">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th id="linha_cliente">Cliente</th>
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
                    <tr class="linha" id="ln<?=$c->getCodcli()?>">
                        <td class="principal__conteudo__clientes-codcli"><?= $c->getCodcli()?></td>
                        <td class="principal__conteudo__clientes-cliente"><?= utf8_encode($c->getCliente())?></td> 
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
                        <button id="btn_desativar" type="button" class="btn btn-warning">Desativar</button>
                        <button id="btn_ativar" type="button" class="btn btn-success">Ativar</button>
                        <!-- <button id="btn_excluir" type="button" hidden class="btn btn-danger">Excluir</button> -->
                        <button id="btn_criar" type="button" hidden class="btn btn-info">Criar</button>
                        <button id="btn_salvar" type="submit" class="btn btn-primary">Salvar</button>
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
                        <button id="btn_salvar2" type="submit" class="btn btn-primary" data-dismiss="modal">Salvar Alterações</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="./js/sidebar.js"></script>
<script src="../js/jquery.tablesorter.js"></script>
<script src="js/scriptsGerais.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8"
		src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
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
                    console.log(resposta.length);
                    if(resposta.length>0){
                    
                  
                        arr = JSON.parse(resposta);
                    body = "";
                    status = arr[0][5];
                    arr.forEach(function(t){
                        body += '<tr class="politica__linha">'
                                +    '<td class="politica__grupo">'+t[0]+'</td>'
                                +    '<td class="politica__descricao">'+t[1]+'</td>'
                                +    '<td class="politica__desconto"><input tabindex="1" class="desconto" onfocusout="attDesconto(this, '+parseFloat(t[3])+')" type="text" value="'+parseFloat(t[2]).toFixed(6)+'"></input></td>'
                                +    '<td class="politica__tabela"><input tabindex="-1" class="tabela" type="text" onfocusout="attValor(this, '+parseFloat(t[3])+')" value="'+getTabela(parseFloat(t[2]), parseFloat(t[3]))+'"></input></td>'
                                +'</tr>'    
                            })
                            

                    $('#btn_salvar').removeAttr('onclick');
                    $('#btn_salvar2').removeAttr('onclick');
                    $('#btn_desativar').removeAttr('onclick');
                    $('#btn_ativar').removeAttr('onclick');
                    $('#btn_criar').removeAttr('onclick');
                    $('#dadosmodal').empty();
                    $('#modal-titulo').empty();
                    $('#modal-titulo').append(codcli+' : '+arr[0][4]);
                    $('#btn_salvar').attr('onClick', 'atualizaPolitica('+codcli+');');
                    $('#btn_salvar2').attr('onClick', 'atualizaPolitica('+codcli+');');
                    $('#btn_desativar').attr('onClick', 'desativar('+codcli+');');
                    $('#btn_ativar').attr('onClick', 'ativar('+codcli+');');
                    $('#btn_criar').attr('onClick', 'atualizaPolitica('+codcli+');');
                    $('#dadosmodal').append(body);
                    $('#dadosmodal').trigger("update", true);
                    switch(status){
                        case '1':
                            $('#btn_desativar').prop('hidden',false);
                            $('#btn_ativar').prop('hidden',true);
                            $('#btn_excluir').prop('hidden',false);
                            $('#btn_criar').prop('hidden',true);
                            break;
                        case '0':
                              
                                $('#btn_desativar').prop('hidden',true);
                                $('#btn_ativar').prop('hidden',true);
                                $('#btn_excluir').prop('hidden',true);
                                $('#btn_criar').prop('hidden',false);
                                
                                break;
                        case '2':
                           
                            $('#btn_desativar').prop('hidden',true);
                            $('#btn_ativar').prop('hidden',false);
                            $('#btn_excluir').prop('hidden',false);
                            $('#btn_criar').prop('hidden',true);
                            break;
                    }
                    ordenaPolitica();
                    modal.modal('show');
                    
                }else
                alert("Erro, procure o TI")
            } 
            
                
            }); 
            $.ajax({
                type: 'POST',
                url: "control/controle.php",
                data: {
                    'action': 'getObs',
                    'cliente': codcli,
                },
                success: function(resposta) {
                    console.log(resposta);
                    $('#modalObs').empty();
                    $('#modalObs').val(resposta.replaceAll('\"', '' ));
                    obsOriginal =  $('#modalObs').val();
                }
            })
    }

    function fechar(){
        $('#modalPoliticas').modal('hide');
    }

    function getTabela(desconto, tabela){
        return parseFloat(((100-desconto)/100)*tabela).toFixed(6);
    }

    function validarDesconto(referencia, tabela){
        attDesconto(referencia, tabela);
    }

    function attValor(elemento, tabela){
        let referencia = $(elemento).parent().parent().find('.politica__desconto').find('input');
        //se elemento valor contem virgula, trocar por ponto
        if(elemento.value.includes(',')){
            elemento.value = elemento.value.replace(',', '.');
    }
        let desconto = ((1 - (((elemento.value)/tabela)))*100);
        console.log(desconto.toFixed(6));
        
        $(referencia).val(desconto.toFixed(6));
        validarDesconto(referencia, tabela);
    }


    function desativar(codcli){
        let element = $('#ln'+codcli).children('.principal__conteudo__clientes-politica').children();
        let codUser = <?= $_SESSION['coduser']?> 
        $.ajax({
            type: 'POST',
            url: "control/controle.php",
            data: {
                'action': 'desativar',
                'codcli': codcli,
                'coduser': codUser,
            },
            success: function(resposta) {
                element.text('INATIVA');
                element.css('background-color', 'gold');
                alert('Politica Desativada!')
            }
        })
        fechar()
    }

    function ativar(codcli){
        let codUser = <?= $_SESSION['coduser']?> 
        let element = $('#ln'+codcli).children('.principal__conteudo__clientes-politica').children();
        
        $.ajax({
            type: 'POST',
            url: "control/controle.php",
            data: {
                'action': 'ativar',
                'codcli': codcli,
                'coduser': codUser,
            },
            success: function(resposta) {
                element.text('ATIVO');
                element.css('background-color', '#00a000');
                alert('Politica Ativa!')
            }
        })
        fechar()
    }

    function attDesconto(linha, tabela){
        let grupo = $(linha).parent().parent().find('.politica__grupo').text();
        let desconto = ($(linha).val());
        let refTabela = $(linha).parent().parent().find('.tabela');
        //se desconto conter virgula, substituir por ponto.
        if(desconto.includes(',')){
            desconto = desconto.replace(',','.');
        }
        //parsefloat em desconto
        desconto = parseFloat(desconto);
        $(linha).val(desconto.toFixed(6));
        //checar se o desconto foi alterado.
        arr.forEach(function(i){
            if (i[0]==grupo && desconto != i[2]){
               
                if(desconto<0 || desconto>80 || isNaN(desconto)){
                    $(linha).val(i[2]);
                    desconto = i[2];
                    desconto = parseFloat(desconto);
                    desconto = desconto.toFixed(6);
                    alert('Desconto inválido! Entre 0 e 80%\nTabela: '+tabela+'\nDesconto: '+desconto+'\nValor: '+getTabela(desconto,tabela));
                }
            }
        })
        

        refTabela.val(getTabela(desconto,tabela));
        
    }

    function atualizaPolitica(codCli, obs){   
        $('#btn_salvar').removeAttr('onclick');
        $('#btn_salvar2').removeAttr('onclick'); 
        let element = $('#ln'+codCli).children('.principal__conteudo__clientes-politica').children();      
        let linha = $('.politica__linha');
        let desconto = [];
        let obsGeral = retira_acentos($('#modalObs').val());
        let codUser = <?= $_SESSION['coduser']?> ;
        
        if(obsGeral != obsOriginal){
            linha.each(function( i, element ){
                
                desconto.push({
                    codGrupo: $(element).find('.politica__grupo').text(),
                    percDesc: parseFloat($(element).find('.politica__desconto').find('input').val()),
                    tabela: parseFloat($(element).find('.politica__tabela').find('input').val())
                })
            })
            console.log(desconto);
            $.ajax({
                type: 'POST',
                url: 'control/controle.php',
                data: { 'action': 'atualizarPolitica', "desconto":desconto, "codCli": codCli, "obs": obsGeral, "codUser": codUser},
                success: function (response) {
                    console.log(response);
                    element.text('ATIVO');
                    element.css('background-color', '#00a000');
                    alert("Politica Gravada!")
                }
            });
        fechar()
        }else{
            alert('Altere a Observação para Salvar a Politica!!!')
        }
        
    }
    
    $(document).ready(function () {
		$('#clientes').DataTable({
			"lengthMenu": [[50, 100, -1], [50, 100, "Todos"]],
			"order": [[4, "asc"]],
			"bInfo": false,

			"language": {
                "funcao":attTela(),
				"sEmptyTable": "Nenhum registro encontrado",
				"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de _MAX_ registros)",
				"sInfoPostFix": "",
				"sInfoThousands": ".",
				"sLengthMenu": "_MENU_ resultados por página",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhum registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Próximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "Último"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				}
			}
		});
	});
    
    function ordenaPolitica(){
        //ordenar coluna 1

        $("#tbPoliticas").tablesorter({
            sortList: [[1,0]]
        });


    }

    function attTela(){
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

        $('#linha_cliente').css('width', '35%');
    }

    window.onload = function() {
        
        attTela();

        $("#clientes").tablesorter();

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
        $(".pagina__loader").css('height', '20px');
        $(".principal__conteudo").toggle();
    }

    
</script>
</html>