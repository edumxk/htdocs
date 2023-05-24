<?php
require_once './control/controle.php';
    session_start();
    if ($_SESSION['nome'] == null) {
        header("location:\..\..\home.php");
    }
    //$clientes = Controle::getClientes();
    //$politicas2 = Controle::getPoliticas(271 ,2);
    $dados2 = Controle::buscaAlteracoes();
    $dados = json_encode($dados2);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas Comerciais | Histórico</title>
    <!-- CSS only -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/historico.css">
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
                <li class="head__navegaca__lista-itens"><a href="#">Histórico</a></li>
            </ul>
        </div>
    </header>
    <section class="menu">
        <div class="logo-conteudo">
            <div class="logo">
                <div class="logo-nome"><p>Histórico</p> </div>
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
                <a href="#">
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
        <section class="principal__pesquisa">
            
            <div class="principal__pesquisa-tabela_ultimos">
                <table class="table " id="historico">
                    <thead>
                        <tr class="historico_cabecalho">
                                <th scope="col" class="ultimos-cabecalho">Codcli</th>
                                <th scope="col" class="ultimos-cabecalho">Cliente</th>
                                <th scope="col" class="ultimos-cabecalho">Data Hora</th>
                                <th scope="col" class="ultimos-cabecalho">Registro</th>
                                <th scope="col" class="ultimos-cabecalho">Observação</th>
                                <th scope="col" class="ultimos-cabecalho">Visualizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dados2 as $d): ?>
                            <tr class="historico_linhas">
                                        <td scope="row" ><?= $d['CODCLI'] ?></td>
                                        <td><?= utf8_encode($d['CLIENTE']) ?></td>
                                        <td><?= $d['DATAHORA'] ?></td>
                                        <td><?= utf8_encode($d['NOME']) ?></td>
                                        <td><textArea class="form-control" readonly cols=45 rows=3><?= utf8_encode($d['OBS'])?></textArea></td>
                                        <td class="text-center row justify-content-between p-0 justify-content-center">
                                            <button class="btn btn-success col" onClick="historico.getHistorico(<?= $d['CODHIST'] ?>)">Ver</button>
                                            <button class="btn btn-warning col" onClick="historico.print(<?= $d['CODHIST'] ?>)">PDF</button>
                                        </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>



            </div>
        
        </section>
    </section>
     <!-- Modal de edição das politicas -->
    <div class="modal fade" id="modalPoliticas">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 id="modal-titulo"></h2>
                </div>
                <!-- Modal body -->
                    <div class="modal-body">
                        <div>
                            <table class="table" id="tbPoliticas">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Grupo</th>
                                        <th>Desc Antigo</th>
                                        <th>Desc Novo</th>
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
                        <button type="button" onclick="$('#modalPoliticas').modal('hide')" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
</body>
<script src="./js/historico.js"></script>
<script src="./js/sidebar.js"></script>
<script src="../js/jquery.tablesorter.js"></script>
<script src="js/scriptsGerais.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8"
		src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
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
        let itens = [];
        paginavel.forEach(function(i){
            itens+= `<tr class="historico_linhas">
                                    <td>${i.CODCLI}</td>
                                    <td>${i.CLIENTE}</td>
                                    <td>${i.DATAHORA}</td>
                                    <td>${i.NOME}</td>
                                    <td><textArea readonly cols=45 rows=3>${i.OBS}</textArea></td>
                                    <td><button onClick="historico.getHistorico(${i.CODHIST})">Ver</button></td>
                            </tr>`;
                })
                document.getElementById('tabela__itens').innerHTML= itens ;
    }    

    const dados = {
        items: '<?php echo($dados)?>'
    }

    const perPage = 7;

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
            document.getElementById('tabela__itens').innerHTML ="";
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
        //list.update()
       // button.update()

    }

   
    const historico = {
        getHistorico(id) {

            $.ajax({
                type: 'POST',
                url: 'control/controle.php',
                data: { 'action': 'getHistorico', "id":id},
                success: function (response) {
                    console.log(response);
                    arr = JSON.parse(response);
                    body = "";
                    arr.forEach(function(t){
                        body += `<tr class="politica__linha">
                                   <td class="politica__grupo">${t.codgrupo}</td>
                                   <td class="politica__descricao">${t.descricao}</td>
                                   <td class="politica__desconto"><input  readonly class="desconto" type="text" value="${Intl.NumberFormat('pt-BR', { style: 'percent', minimumSignificantDigits: 4}).format(parseFloat(t.descant).toFixed(6)/100)}"></input></td>
                                   <td class="politica__desconto"><input readonly class="desconto" type="text" value="${Intl.NumberFormat('pt-BR', { style: 'percent', minimumSignificantDigits: 4}).format(parseFloat(t.descnovo).toFixed(6)/100)}"></input></td>
                                   <td class="politica__tabela"><input  readonly class="tabela" type="text" value="${Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(parseFloat(t.tabela)) }"></input></td>
                                   
                                </tr>`
                                })
                    $('#dadosmodal').empty();
                    $('#modal-titulo').empty();
                    $('#dadosmodal').append(body);
                    $('#modalPoliticas').modal('show');
                }
            })
        }    
    }

    $(document).ready(function () {
		$('#historico').DataTable({
			"lengthMenu": [[20, 50, -1], [20, 50, "Todos"]],
			"order": [],
			"bInfo": false,

			"language": {
                
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

    init();
</script>
</html>
