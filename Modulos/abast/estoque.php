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
            <li class="breadcrumb-item active" aria-current="page">Cadastro de Densidade</li>
        </ol>
    </nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-1"></div>
        <div id="abastecimento" class="col-md-10" style="padding-bottom: 20px; background-color: white; border:2px solid black">

        <div class="row" style='padding-top: 20px'>
            
            <div class="col-md-12" style="text-align: center">
                <h1>Novo Cadastro de Densidade</h1>
            </div>
        </div>
        <div class="row" style='padding-top: 20px'>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Código do Produto:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="codprod" maxlength="4" onfocusout="buscaProd(this.value)" autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Descrição do Produto:</h2>
            </div>
            <div class="col-md-4">
                <input style=" width: 350px" type="text" id="descricao"  disabled autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Densidade:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="densidade" value = 1 autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Embalagem 1 KG:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="emb1" value = 0 autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Embalagem 2 KG:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="emb2" value = 0 autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Embalagem 3 KG:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="emb3" value = 0 autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-1">
                <button style="width:278px" type="submit" class="btn btn-sm btn-success" onclick="incluirDensidade()">
                    <b>Gravar</b>
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


    function incluirDensidade() {
        $('#loading').toggle();
        $('.btn').toggle();

        codprod = document.getElementById('codprod').value;
        densidade = document.getElementById('densidade').value;
        descricao = document.getElementById('descricao').value;
        emb1 = document.getElementById('emb1').value;
        emb2 = document.getElementById('emb2').value;
        emb3 = document.getElementById('emb3').value;
        dataset = {
            "densidade": densidade,
            "codprod": codprod,
            "emb1": emb1,
            "emb2": emb2,
            "emb3": emb3
        };
        if(densidade > 0 && emb1 > 0 && codprod > 0 && codprod < 3600){
        //console.log(dataset);
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'incluirDensidade',
                'query': dataset
            },
            success: function(response) {
                //console.log(response);
                if (response.match("OK")) {
                    $('#loading').toggle();
                    $('.btn').toggle();
                    alert("Produto " + descricao + " Cadastrado com Sucesso!");
                    location.reload()

                }else{
                    alert("Algo deu errado com o produto " + descricao + ".\nJá deve estar cadastrado, verifique!");
                    location.reload()
                }
            }
        });
    }else {alert("Preencha todos os campos corretamente");}
    }
    function buscaProd(elm){
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'buscaProd',
                'query': elm
            },
            success: function(response) {
               //alert(response);
                if(strval(response).lenght>0){
                alert("Produto não Cadastrado!!!");
                }
            }
        })
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'buscaProd',
                'query': elm
            },
            success: function(response) {
                //console.log(response);
                document.getElementById('descricao').value = response;
            }
        })
    } 

</script>
   
</html>