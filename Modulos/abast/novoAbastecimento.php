<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once 'controleAbast.php';
$lista = [];
$numreq = Abastecimento::getReq();
$numreq = ($numreq[0]['NUMREQ'] + 1);

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Abastecimento</title>

    <meta name="description" content="Lançamento de requisições de abastecimento">
    <meta name="author" content="Eduardo Patrick">

    <link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../recursos/css/style.css" rel="stylesheet">
    <link href="../../recursos/css/table.css" rel="stylesheet">
    <link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">

</head>

<body style="background-color: teal;width:100%">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../../home.php">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../../homea.php">Controle Almoxarifado</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Novo Abastecimento</li>
        </ol>
    </nav>
    <div id="abastecimento" class="col-md-12" style="padding-bottom: 20px; background-color: white; border:2px solid black">
        <div class="row" style='padding-top: 20px'>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <h2>Número Requisição :</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="numreq" value="<?= $numreq ?>" disabled><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <h2>Código do Produto :</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="codprod" maxlength="4" autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <h2>Qtd Requisitada :</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="qtdsolicitada" autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <h2>Setor/Solicitante :</h2>
            </div>
            <div class="col-md-1">

                <select name="Solicitante" id="solicitante">
                    <optgroup value="Base D'Agua" id="BASEAGUA" label="Base D'Agua">
                        <option value="BASE D'AGUA">FRANCISCO IRLAN</option>
                        <option value="BASE D'AGUA">LUCAS</option>
                        <option value="BASE D'AGUA">FILIPI</option>
                        <option value="BASE D'AGUA">ADALIU</option>
                        <option value="BASE D'AGUA">FRANCES</option>
                    </optgroup>
                    <optgroup value="Base Solvente" id="BASESOLVENTE" label="Base Solvente">
                        <option value="BASE SOLVENTE">VENILSON</option>
                        <option value="BASE SOLVENTE">WERMERSON</option>
                        <option value="BASE SOLVENTE">FELIPE</option>
                        <option value="BASE SOLVENTE">AKIRA</option>
                    </optgroup>
                    <optgroup value="Tunel" id="TUNEL" label="Túnel">
                        <option value="TUNEL">MARCIO</option>
                    </optgroup>
                    <optgroup value="LAB" id="LABORATORIO" label="Laboratório">
                        <option value="LABORATORIO">EDUARDO</option>
                    </optgroup>
                    <optgroup value="ETE" id="ESTACAO DE TRATAMENTO" label="E.T.E.">
                        <option value="E.T.E">GABRIEL</option>
                    </optgroup>
                </select>
                <br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-1">
                <button style="width:178px" type="submit" class="btn btn-sm btn-success" onclick="iniciarAbast()">
                    <b>Iniciar Requisição</b>
                </button>
            </div>
        </div>
    </div>
</body>
<script src="../../recursos/js/jquery.min.js"></script>
<script src="../../recursos/js/bootstrap.min.js"></script>
<script src="../../recursos/js/scripts.js"></script>
<script src="../../recursos/js/Chart.bundle.min.js"></script>
<script src="../../recursos/js/jquery.tablesorter.js"></script>
<script src="../../recursos/js/jquery-ui-1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#loading').hide();
    });


    function iniciarAbast() {
        $('#loading').toggle();
        $('.btn').toggle();

        numreq = document.getElementById('numreq').value;
        qtdsolicitada = document.getElementById('qtdsolicitada').value;
        codprod = document.getElementById('codprod').value;
        setor = $("#solicitante option:selected").val();
        //console.log($("#solicitante option:selected").text());
        //console.log($("#solicitante option:selected").val());
        solicitante = $("#solicitante option:selected").text();

        dataset = {
            "numreq": numreq,
            "codprod": codprod,
            "qtdsolicitada": qtdsolicitada,
            "setor": setor,
            "solicitante": solicitante
        };
        console.log(dataset);
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'novoAbastecimento',
                'query': dataset
            },
            success: function(response) {
                console.log(response);
                if (response.match("ok")) {
                    $('#loading').toggle();
                    $('.btn').toggle();
                    alert("Requisição n° " + numreq + " Gerada com Sucesso!  =>  Imprimir na Rotina 8118!!!");
                    location.reload()

                }
            }
        });
    }
</script>

</html>