<!DOCTYPE html>

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/Modulos/Lab/modContraTipo/control/controle.php');
session_start();

if ($_SESSION['nome'] == null) {
    header("location:\..\..\home.php");
}
$codprodOrigem = 1;
$codprodDestino = 1;
$metodo = 1;
if (isset($_POST['codigo1']) && isset($_POST['codigo2'])) {
    $codprodOrigem = $_POST['codigo1'];
    $codprodDestino = $_POST['codigo2'];
    $metodo = $_POST['metodo'];
} else {
?><script>
        if (confirm("Alguma coisa deu errado, confirme para ser redirecionado a pagina inicial ou cancele para sair")) {
            alert("Retornando a Contratipo")
            window.location.replace('index.php');
        } else {
            alert("Retornando ao login")
            window.location.replace('/index.php');
        }
    </script> <?php
            }
            $produtos = ContraTipoControle::getProdutos($codprodOrigem, $metodo);
            //var_dump($produtos);
            date_default_timezone_set('America/Araguaina');
                ?>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Formulas - Alterar</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="./src/css/reset.css">
    <link rel="stylesheet" href="./src/css/sidebar.css">
    <link rel="stylesheet" href="./src/css/contratipo.css">
    <link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
    <script src="https://use.fontawesome.com/62e43a72a9.js"></script>
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
                    <input type="text" id="produto" />
                    <button onclick="concluir()">Concluir</button>
                </div>
                <div class="conteudo__tabela">
                    <table class="conteudo__produtos" id="tabelas-busca">
                        <thead>
                            <tr>
                                <th><input class="checkbox" onclick="marcar(this)" type="checkbox" id="select-all" /></th>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Fórmula</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $p) : ?>
                                <tr class="produtos" id="#<?= $p->codprodmaster ?>">
                                    <td><input class="checkbox" type="checkbox" name="produto" id="<?= $p->codprodmaster ?>" /></td>
                                    <td onclick="marcarLinha(<?= $p->codprodmaster ?>)"><?= $p->codprodmaster ?></td>
                                    <td onclick="marcarLinha(<?= $p->codprodmaster ?>)" class="texto"><?= $p->produto ?></td>
                                    <td class="btn"><button class="botao" onclick="verFormula(<?= $p->codprodmaster ?>)"><i class="fa fa-eye"></i></button></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
        <!-- Modal -->
        <div class="modal fade" id="modal__formula" tabindex="-1" role="dialog" aria-labelledby="modal__formula-Title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal__formula-Title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="tabela__formulas">
                            <thead>
                                <tr>
                                    <th class="tabela__formulas-codprod">Codprod</th>
                                    <th class="tabela__formulas-descricao">Descrição</th>
                                    <th class="tabela__formulas-perc">Perc %</th>
                                    <th class="tabela__formulas-seq">Sequência</th>
                                    <th class="tabela__formulas-fracao">Fração</th>
                                </tr>
                            </thead>
                            <tbody id="dados">
                                <tr>
                                    <td colspan="6"> Fórmula não disponivel </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="./src/js/scripts.js"></script>
    <script src="./src/js/sidebar.js"></script>
    <script src="../../../recursos/js/jquery.tablesorter.js"></script>
    <script>
        function concluir() {
            if (confirm("Deseja mesmo confirmar esta alteração?") == true) {
                var produtos = document.getElementsByName('produto');
                var listaProdutos = []
                for (var i = 0, n = produtos.length; i < n; i++) {
                    if (produtos[i].checked == true) {

                        listaProdutos.push(produtos[i].id);
                    }
                }
                let delay = 500;
                cabecalho = {
                    codprod1: "<?= $_POST['codigo1'] ?>",
                    codprod2: "<?= $_POST['codigo2'] ?>",
                    metodo: "<?= $_POST['metodo'] ?>",
                    codfun: "<?= $_SESSION['coduser'] ?>",
                    data: novaData()
                }
                $.ajax({
                    type: 'POST',
                    url: "control/controle.php",
                    data: {
                        'action': 'concluir',
                        'lista': listaProdutos,
                        'cabecalho': cabecalho
                    },
                    success: function(response) {
                        if(response.indexOf('erro')!=-1)
                            alert(response)
                        else{
                            setTimeout(function() {}, delay);
                            //console.log(response)
                            window.location.replace('relatorio.php');
                        }
                    }
                });
            }
        }

        function verFormula(codprod) {
            let metodo = <?= $_POST['metodo'] ?>;
            $.ajax({
                type: 'POST',
                url: "control/controle.php",
                data: {
                    'action': 'verFormula',
                    'codprod': codprod,
                    'metodo': metodo
                },
                success: function(response) {
                    arr = JSON.parse(response);
                    body = "";
                    pesoTotalPen =0;
                    arr.forEach(function(t){
                        body += '<tr>'
                                +'<td>'+t['CODPROD']+'</td>'
                                +'<td class="tabela__formulas__dados-descricao">'+t['DESCRICAO']+'</td>'
                                +'<td class="tabela__formulas__dados-perc">'+parseFloat(t['PERCENTUAL']).toFixed(3)+'</td>'
                                +'<td>'+t['NUMSEQ']+'</td>'
                                +'<td>'+t['FRACAOUMIDA']+'</td>'
                                +'</tr>'
                    })
                    $('#dados').empty();
                    $('#dados').append(body);
                    $('#modal__formula').modal();
                }
                }); 
                
            }
            function marcarLinha(codprod){
                let checkbox = document.getElementById(codprod);
               
                if(checkbox.checked != true){
                    checkbox.checked = true;
                    return;
                }
                    checkbox.checked = false;
            }

    </script>
</body>

</html>