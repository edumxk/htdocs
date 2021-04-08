<?php
require_once ('model/clientesModel.php');
    session_start();
    $arrRca = Clientes::getListaRca();
    $arrClientesMes = Clientes::getClientesMes();
    $arrClientesAtivos = Clientes::getClientesAtivos();
    $arrClientesInativos = Clientes::getClientesInativos();

    // echo json_encode($arrRca);
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
        <!-- <div class="container" style="background-color: white; top: 20px; border-radius: 10px"> -->
        <div class="container" style="background-color: white; top: 20px; border-radius: 10px; display:none">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="../../home.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Lista de Clientes</li>
                </ol>
            </nav>

            <div class="row" style="padding-bottom:20px">
                <div class="col-12"><h2>Lista de Clientes</h2></div>
            </div>

            <div class="row" style="padding-bottom:20px">
                <div class="col-1" style="font-weight: 700; padding-top:5px">RCA</div>
                <div class="col-6" style="font-weight: 700">
                    <select class="form-control" onchange="buscaRca(this)">
                        <option value="0">TODOS</option>
                        <?php foreach($arrRca as $r):?>
                            <option value="<?php echo $r['NOME']?>"><?php echo $r['NOME']?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-1" style="font-weight: 700">Cod.</div>
                <div class="col-6" style="font-weight: 700">Cliente</div>
            </div>
            <div class="row">
                <div class="col-1"><input id="buscaCod"class="text" style="width:100%"></div>
                <div class="col-6"><input id="buscaCliente" class="text" style="width:100%"></div>
            </div>

            <div class="row" style="padding-top:20px">
                <div class="col-3"><h2>Clientes Positivados</h2></div>
                <div class="col-4">
                    <button id="btnPositivos" class="btn btn-success" type="input" onclick="escondePositivos()">Esconder</button>
                </div>
            </div>
            <div class="row positivados" id="">
                <div class="col-12">
                    <table id="tblPositivados" style="font-size: 12px; border-collapse: collapse; width:100%">
                        <thead style="border: 2px solid darkslategray">
                            <tr style="background-color: lightgray">
                                <th style="text-align:center">COD</th>
                                <th style="padding-left:10px">CLIENTE</th>
                                <th style="text-align:center">CIDADE</th>
                                <th style="text-align:center">UF</th>
                                <th style="text-align:center">RCA</th>
                                <th style="text-align:center">ULT. COMPRA</th>
                                <th style="text-align:center">TOTAL</th>
                                <th style="text-align:center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($arrClientesMes as $a):?>
                                <tr class="trCliente" onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)" class="colunas" id="<?php echo $a['CODCLI']?>">
                                    <th class="codCli" style="text-align:center; width:40px"><?php echo $a['CODCLI']?></th>
                                    <th class="cliente" style="padding-left:10px; text-align:left"><?php echo $a['CLIENTE']?></th>
                                    <td style="text-align:left; padding-left: 10px; padding-right: 10px; width:250px"><?php echo $a['NOMECIDADE']?></td>
                                    <td style="text-align:center; width:25px"><?php echo $a['UF']?></td>
                                    <td class="nomeRca" style="text-align:left; padding-left: 10px; width:100px"><?php echo $a['NOMERCA']?></td>
                                    <td style="text-align:center; width:70px"><?php echo $a['ULTCOMPRA']?></td>
                                    <td style="text-align:right; width:70px; padding-right:10px"><a hidden><?php echo $a['TOTAL']?></a><?php echo number_format($a['TOTAL'],2,',','.')?></td>
                                    <form method="get" action="visualizarPoliticas.php">
                                        
                                        <?php if ($a['ATIVO'] == 1):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">
                                                <button type="submit" class="btn-xs btn-success" style="font-size:8px">ATIVO</button>
                                            </td>
                                        <?php elseif($a['ATIVO'] == 2):?>
                                            <td style="text-align:center; width:70px" >
                                            <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">
                                                <button type="submit" class="btn-xs btn-warning" style="font-size:8px">INATIVO</button>
                                            </td>
                                        <?php elseif($a['ATIVO'] == 0):?>
                                            <td style="text-align:center; width:70px" >
                                            <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">
                                                <button type="submit" class="btn-xs btn-danger" style="font-size:8px">VAZIO </button>
                                            </td>
                                        <?php endif?>
                                    </form>
                                </tr>
                            <?php endforeach?>  
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="row" style="padding-top:20px">
                <div class="col-3"><h2>Clientes Ativos</h2></div>
                <div class="col-4">
                    <button id="btnAtivos" class="btn btn-success" type="input" onclick="escondeAtivos()">Esconder</button>
                </div>
            </div>
            <div class="row ativos">
                <div class="col-12">
                    <table id="tblAtivos" style="font-size: 12px; border-collapse: collapse; width:100%">
                        <thead style="border: 2px solid darkslategray">
                            <tr style="background-color: lightgray">
                                <th style="text-align:center">COD</th>
                                <th style="padding-left:10px">CLIENTE</th>
                                <th style="text-align:center">CIDADE</th>
                                <th style="text-align:center">UF</th>
                                <th style="text-align:center">RCA</th>
                                <th style="text-align:center">ULT. COMPRA</th>
                                <th style="text-align:center">TOTAL</th>
                                <th style="text-align:center">POLITICA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($arrClientesAtivos as $a):?>
                                <tr class="trCliente" onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)" class="colunas" id="<?php echo $a['CODCLI']?>">
                                    <th class="codCli" style="text-align:center; width:40px"><?php echo $a['CODCLI']?></th>
                                    <th class="cliente" style="padding-left:10px; text-align:left"><?php echo $a['CLIENTE']?></th>
                                    <td style="text-align:left; padding-left: 10px; padding-right: 10px; width:250px"><?php echo $a['NOMECIDADE']?></td>
                                    <td style="text-align:center; width:25px"><?php echo $a['UF']?></td>
                                    <td class="nomeRca" style="text-align:left; padding-left: 10px; width:100px"><?php echo $a['NOMERCA']?></td>
                                    <td style="text-align:center; width:70px"><?php echo $a['ULTCOMPRA']?></td>
                                    <td style="text-align:right; width:70px; padding-right:10px"><a hidden><?php echo $a['TOTAL']?></a><?php echo number_format($a['TOTAL'],2,',','.')?></td>
                                    
                                    <form method="get" action="visualizarPoliticas.php">

                                        <?php if ($a['ATIVO'] == 1):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">    
                                                <button type="submit" class="btn-xs btn-success" style="font-size:8px">ATIVO</button>
                                            </td>
                                        <?php elseif($a['ATIVO'] == 2):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">  
                                                <button type="submit" class="btn-xs btn-warning" style="font-size:8px">INATIVO</button>
                                            </td>
                                        <?php elseif($a['ATIVO'] == 0):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">  
                                                <button type="submit" class="btn-xs btn-danger" style="font-size:8px">VAZIO </button>
                                            </td>
                                        <?php endif?>
                                    </form>
                                </tr>
                            <?php endforeach?>  
                        </tbody>
                    </table>

                </div>
            </div>


            <div class="row" style="padding-top:20px">
                <div class="col-12"><h2>Clientes Inativos</h2></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tblInativos" style="font-size: 12px; border-collapse: collapse; width:100%">
                        <thead style="border: 2px solid darkslategray">
                            <tr style="background-color: lightgray">
                                <th style="text-align:center">COD</th>
                                <th style="padding-left:10px">CLIENTE</th>
                                <th style="text-align:center">CIDADE</th>
                                <th style="text-align:center">UF</th>
                                <th style="text-align:center">RCA</th>
                                <th style="text-align:center">ULT. COMPRA</th>
                                <th style="text-align:center">TOTAL</th>
                                <th style="text-align:center">POLITICA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($arrClientesInativos as $a):?>
                                <tr class="trCliente" onmouseover="listaHoverIn(this)" onmouseout="listaHoverOut(this)" class="colunas" id="<?php echo $a['CODCLI']?>">
                                    <th class="codCli" style="text-align:center; width:40px"><?php echo $a['CODCLI']?></th>
                                    <th class="cliente" style="padding-left:10px; text-align:left"><?php echo $a['CLIENTE']?></th>
                                    <td style="text-align:left; padding-left: 10px; padding-right: 10px; width:250px"><?php echo $a['NOMECIDADE']?></td>
                                    <td style="text-align:center; width:25px"><?php echo $a['UF']?></td>
                                    <td class="nomeRca" style="text-align:left; padding-left: 10px; width:100px"><?php echo $a['NOMERCA']?></td>
                                    <td style="text-align:center; width:70px"><?php echo $a['ULTCOMPRA']?></td>
                                    <td style="text-align:right; width:70px; padding-right:10px"><a hidden><?php echo $a['TOTAL']?></a><?php echo number_format($a['TOTAL'],2,',','.')?></td>
                                    
                                    <form method="get" action="visualizarPoliticas.php">
                                        <?php if ($a['ATIVO'] == 1):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">  
                                                <button type="submit" class="btn-xs btn-success" style="font-size:8px">ATIVO</button>
                                            </td>
                                        <?php elseif($a['ATIVO'] == 2):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">  
                                                <button type="submit" class="btn-xs btn-warning" style="font-size:8px">INATIVO</button>
                                            </td>
                                        <?php elseif($a['ATIVO'] == 0):?>
                                            <td style="text-align:center; width:70px" >
                                                <input hidden name="codCli" value="<?php echo $a['CODCLI']?>">
                                                <input hidden name="numRegiao" value="<?php echo $a['NUMREGIAO']?>">  
                                                <button type="submit" class="btn-xs btn-danger" style="font-size:8px">VAZIO </button>
                                            </td>
                                        <?php endif?>
                                    </form>
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
	<script src="js/scripts.js"></script>
    <script src="../../recursos/js/jquery.tablesorter.js"></script>

    <script>

    	window.onload = function() {
            $("#tblPositivados").tablesorter();
            $("#tblAtivos").tablesorter();
            $("#tblInativos").tablesorter();
            $(".loader").toggle();
            $(".container").toggle();

            $("#buscaCod").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                // console.log(value)
                $(".trCliente").each(function() {

                    if(!$(this).find(".codCli").text().includes(value)){
                        $(this).hide()
                    }
                    if($(this).find(".codCli").text().includes(value)){
                        $(this).show()
                    }
                });
            });

            $("#buscaCliente").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                // console.log(value)
                $(".trCliente").each(function() {
                    // console.log($(this).find(".cliente").text(), value)
                    if(!$(this).find(".cliente").text().toLowerCase().includes(value)){
                        $(this).hide()
                    }
                    if($(this).find(".cliente").text().toLowerCase().includes(value)){
                        $(this).show()
                    }
                });
            });
        }   

        function escondePositivos(){
            texto = $("#btnPositivos").text();
            if(texto == 'Esconder'){
                $("#btnPositivos").text('Mostrar')
            }
            if(texto == 'Mostrar'){
                $("#btnPositivos").text('Esconder')
            }
            $(".positivados").toggle()
        }

        function escondeAtivos(){
            texto = $("#btnAtivos").text();
            if(texto == 'Esconder'){
                $("#btnAtivos").text('Mostrar')
            }
            if(texto == 'Mostrar'){
                $("#btnAtivos").text('Esconder')
            }
            $(".ativos").toggle()
        }


        function buscaRca(elm){
            console.log($(elm).find(":selected").val());

            var value = $(elm).find(":selected").val().toLowerCase();
                // console.log(value)
            if(value==0){
                $(".trCliente").each(function() {
                    // console.log($(this).find(".cliente").text(), value)
                    $(this).show()

                });
            }
            if(value != 0){
                $(".trCliente").each(function() {
                    // console.log($(this).find(".cliente").text(), value)
                    if(!$(this).find(".nomeRca").text().toLowerCase().includes(value)){
                        $(this).hide()
                    }
                    if($(this).find(".nomeRca").text().toLowerCase().includes(value)){
                        $(this).show()
                    }
                });
            }

        }

    	function resumoHoverIn(elm){
		    $(elm).css('background-color', 'yellow')
        }
        function resumoHoverOut(elm){
            $(elm).css('background-color', 'transparent')
        }


        function listaHoverIn(elm){
            console.log('ok');
            $(elm).css('background-color', 'gold')
        }
        function listaHoverOut(elm){
            $(elm).css('background-color', 'transparent')
        }






    </script>


</html>

