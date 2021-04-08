<?php
require_once ('model/visualizarPoliticasModel.php');
require_once ('model/calculaPoliticas.php');
    session_start();


    $codCli = $_GET['codCli'];
    $numRegiao = $_GET['numRegiao'];

    $info = VisualizarPoliticas::infoPolitica($codCli);
    $cliente = VisualizarPoliticas::getCliente($codCli);
    $politicas = VisualizarPoliticas::getPoliticas($codCli, $numRegiao);
    
    if($info==0){
        echo CalculaPol::calcular($codCli);
        header("Refresh:0");
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Gestão de Políticas</title>
        <meta charset="utf-8">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/table.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/fawsome/css/all.css" rel="stylesheet">
        <script src="js/scriptsGerais.js"></script>
    </head>
    <body style="background-color: teal;">
        <div class="header">
            <div class="row">
                <div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
                    <img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
                </div>
                <div class="float-md-right">
                    <div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
                        <!-- Usuário: <?php echo $_SESSION['nome'] ?> -->
                    </div>
                    <div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
                        <!-- Setor: <?php echo $_SESSION['setor']?> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="loader"></div>
        <div class="container" style="background-color: white; top: 20px; border-radius: 10px; display:none">
        <!-- <div class="container" style="background-color: white; top: 20px; border-radius: 10px; "> -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="../../home.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="clientes.php">Lista de Clientes</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Políticas</li>
                </ol>
            </nav>

            <div class="row" style="padding-bottom:20px">
                <div class="col-2"><h2>Políticas</h2></div>
                <div class="col-2">
                    <button class="btn btn-danger" type="input" onclick="excluir()">Excluir Política</button>
                </div>
                <?php if($info['ATIVO']==1):?>
                    <div class="col-2">
                        <button class="btn btn-warning" type="input" onclick="inativar()">Desativar Política</button>
                    </div>
                <?php elseif($info['ATIVO']==2):?>
                    <div class="col-2">
                        <button class="btn btn-success" type="input" onclick="ativar()">Ativar Política</button>
                    </div>
                <?php endif?>
            </div>

            <div class="row">
                <div class="col-1"><b>Cliente:</b></div>
                <div class="col-11"><?php echo $cliente['CODCLI'].' - '.$cliente['CLIENTE'] ?></div>
            </div>
            <div class="row">
                <div class="col-1"><b>Fantasia:</b></div>
                <div class="col-11"><?php echo $cliente['FANTASIA']?></div>
            </div>
            <div class="row">
                <div class="col-1"><b>Cidade:</b></div>
                <div class="col-11"><?php echo $cliente['NOMECIDADE'].' - '.$cliente['UF']?></div>
            </div>
            <div class="row" style="padding-bottom:20px">
                <div class="col-1"><b>RCA:</b></div>
                <div class="col-11"><?php echo $cliente['CODUSUR'].' - '.$cliente['NOME']?></div>
            </div>

            <div class="row" style="padding-bottom:20px">
                <div class="col-12"><b>Observações:</b></div>
                <div class="col-12">
                    <textarea id="textObs" rows="4" style="width:100%"><?php echo utf8_encode($info['OBSERVACAO'])?></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-success" type="input" onclick="atualizaObs()">Salvar Alterações</button>
                </div>
            </div>


            <div class="row" >
                <div class="col-2"></div>
                <div class="col-8" style="padding-bottom:20px">
                    <table id="tblPoliticas" style="font-size: 12px; border-collapse: collapse; width:100%">
                        <thead style="border: 2px solid darkslategray">
                            <tr style="background-color: lightgray; font-size:12px">
                                <th style="text-align:center">COD</th>
                                <th style="padding-left:10px">LINHA</th>
                                <th style="text-align:center">TABELA</th>
                                <th style="text-align:center">DESCONTO</th>
                                <th style="text-align:center">PARTIDA</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php foreach($politicas as $p):?>
                                <tr class="trCliente" onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)" class="colunas" style="; font-size:12px">
                                    <td class="codGrupo" style="text-align:center; width:50px"><?php echo $p['CODGRUPO']?></td>
                                    <td style="padding-left:10px; text-align:left;"><?php echo utf8_encode($p['DESCRICAO'])?></td>
                                    <td class="tabela" style="padding-right:10px; text-align:right"><?php echo number_format($p['TABELA'],2,',','.')?></td>
                                    <td style="text-align:center; width:80px"><a hidden><?php echo $p['PERCDESC']?></a>
                                        <input class="desconto" type="text" value="<?php echo number_format($p['PERCDESC'],4,',','.')?>" 
                                            style="width:100%; text-align:right"
                                            onfocusout="atualizaDesconto(this)">
                                    </td>
                                    <td style="text-align:center; width:80px">
                                        <input class="partida" type="text" value="<?php echo number_format( $p['TABELA']-($p['TABELA']*($p['PERCDESC']/100))   ,4,',','.')?>" 
                                            style="width:100%; text-align:right"
                                            onfocusout="atualizaPartida(this)">
                                    </td>
                                </tr>
                                <?php endforeach?>

                        </tbody>
                    </table>

                </div>
            </div>



        </div>
    </body>
    <script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

    <script src="../../recursos/js/jquery.tablesorter.js"></script>

    <script>

    	window.onload = function() {
            $("#tblPoliticas").tablesorter();
            $(".loader").toggle();
            $(".container").toggle();
        }   


        function listaHoverIn(elm){
            $(elm).css('background-color', 'gold')
        }
        function listaHoverOut(elm){
            $(elm).css('background-color', 'transparent')
        }


        function atualizaDesconto(elm){

            codGrupo = visualParaNumero($(elm).parent().parent().find('.codGrupo').text());
            tabela = visualParaNumero($(elm).parent().parent().find('.tabela').text());
            desconto = visualParaNumero($(elm).parent().parent().find('.desconto').val());
            
            novoPartida = numeroParaVisual(tabela-(tabela*(desconto/100)));
            descontoN = numeroParaVisual(desconto);

            $(elm).parent().parent().find('.desconto').val(descontoN);
            $(elm).parent().parent().find('.partida').val(novoPartida);

            console.log(codGrupo, desconto)
            atualizaPolitica(codGrupo, desconto);

        }

        function atualizaPartida(elm){

            codGrupo = visualParaNumero($(elm).parent().parent().find('.codGrupo').text());
            tabela = visualParaNumero($(elm).parent().parent().find('.tabela').text());
            partida = visualParaNumero($(elm).parent().parent().find('.partida').val());
            
            novoDesconto = numeroParaVisual(((partida-tabela)/tabela)*-100);
            partidaN = numeroParaVisual(partida);


            $(elm).parent().parent().find('.desconto').val(novoDesconto);
            $(elm).parent().parent().find('.partida').val(partidaN);

            console.log(codGrupo, visualParaNumero(novoDesconto))
            atualizaPolitica(codGrupo, visualParaNumero(novoDesconto));

        }

        function atualizaPolitica(codGrupo, desconto){            

            var codCli = <?php echo $codCli;?>;

            var dataSet = {"codGrupo":codGrupo, "desconto":desconto, "codCli":codCli}
            // console.log(dataSet)

            $.ajax({
                type: 'POST',
                url: 'model/visualizarPoliticasModel.php',
                data: { 'funcao': 'atualizarPolitica', 'query': dataSet },
                success: function (response) {
                    console.log(response);
                    // $(".loader").toggle();
                    // location.reload()
                }
            });
        }

        function atualizaObs(){            

            var codCli = <?php echo $codCli;?>;

            var obs = $("#textObs").val();

            var dataSet = {"obs":obs, "codCli":codCli}
            // console.log(obs);
            $.ajax({
                type: 'POST',
                url: 'model/visualizarPoliticasModel.php',
                data: { 'funcao': 'atualizarObs', 'query': dataSet },
                success: function (response) {
                    console.log(response);
                    // $(".loader").toggle();
                    // location.reload()
                }
            });
        }

        function inativar(){            

            var codCli = <?php echo $codCli;?>;

            var dataSet = {"codCli":codCli}
            // console.log(obs);
            $.ajax({
                type: 'POST',
                url: 'model/visualizarPoliticasModel.php',
                data: { 'funcao': 'inativar', 'query': dataSet },
                success: function (response) {
                    console.log(response);
                    // $(".loader").toggle();
                    location.reload()
                }
            });
        }

        function ativar(){            
            var codCli = <?php echo $codCli;?>;

            var dataSet = {"codCli":codCli}
            // console.log(obs);
            $.ajax({
                type: 'POST',
                url: 'model/visualizarPoliticasModel.php',
                data: { 'funcao': 'ativar', 'query': dataSet },
                success: function (response) {
                    console.log(response);
                    // $(".loader").toggle();
                    location.reload()
                }
            });
        }

        function excluir(){            
            var codCli = <?php echo $codCli;?>;

            var dataSet = {"codCli":codCli}

            var c = confirm("Confirma exclusão da política?");
            // console.log(c);
            if(c){
                $.ajax({
                    type: 'POST',
                    url: 'model/visualizarPoliticasModel.php',
                    data: { 'funcao': 'excluir', 'query': dataSet },
                    success: function (response) {
                        // console.log(response);
                        // $(".loader").toggle();
                        location.href = "clientes.php";
                    }
                });
            }

        }


    </script>


</html>

