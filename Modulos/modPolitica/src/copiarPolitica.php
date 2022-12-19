<?php

require_once './control/controle.php';
    session_start();

    if ($_SESSION['nome'] == null) {
        header("location:\..\..\home.php");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copiar Políticas</title>
    <!-- CSS only -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/copiarPolitica.css">
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
        <div>
            <label for="origem">Origem</label>
            <input type="text" id="origem">
            <input readonly name="origem" id="clienteOrigem"></input>
        </div>
        <div>
            <label for="destino">Destino</label>
            <input name="destino" type="text" id="destino">
            <input readonly name="destino" id="clienteDestino"></input>
        </div>
        <div class="confirma">
            <button id="copiar" onclick="copiar()">Confirmar</button>
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
    function getCliente(codcli, tipo){
        
        $.ajax({
                type: 'POST',
                url: 'control/controle.php',
                data: { 'action': 'getCliente', "codCli": codcli},
                success: function (response) {

                        if(tipo === 0){
                            if(response.length<70)
                                document.getElementById('clienteOrigem').value = response;
                            else
                                document.getElementById('clienteOrigem').value = 'CÓDIGO INEXISTENTE!';
                        }
                        else{
                            if(response.length<70)
                                document.getElementById('clienteDestino').value = response;
                            else
                                document.getElementById('clienteDestino').value = 'CÓDIGO INEXISTENTE!';
                        }
                
                }
            });
        
        
    }
    $("#origem").keyup(function() {
        teste = document.getElementById('origem').value;
        getCliente(teste,0);
        });

    $("#destino").keyup(function() {
        getCliente($("#destino").val(), 1);
    })

    function copiar(){
        
        dest = document.getElementById('clienteDestino').value;
        orig = document.getElementById('clienteOrigem').value;
        cliDest = document.getElementById('destino').value;
        cliOrig = document.getElementById('origem').value;
        let codUser = <?= $_SESSION['coduser']?> ;
        if(dest == 'CÓDIGO INEXISTENTE!' || orig == 'CÓDIGO INEXISTENTE!' || cliDest == cliOrig){
            alert("Código do Cliente inválido!")
        }
        else{
        $('#copiar').attr('disable', 'disable');
        $('#copiar').removeAttr('onclick');
              
        $.ajax({
                type: 'POST',
                url: 'control/controle.php',
                data: { 'action': 'copiar', "destino": cliDest, "origem": cliOrig, "codUser": codUser},
                success: function (response) {
                    console.log(response);
                    if(response==1)
                        alert("Politica Copiada com sucesso!");
                    else
                        alert("Código do erro: "+response+" - Erro ao Copiar, não tente novamente! Verifique se a política foi copiada! procure o T.I.!");
                }
            });
        }
    }

</script>