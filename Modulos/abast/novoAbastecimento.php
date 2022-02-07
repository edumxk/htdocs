<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once 'controleAbast.php';
session_start();
$lista = [];
$numreq = Abastecimento::getReq();
$numreq = ($numreq[0]['NUMREQ'] + 1);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Abastecimento - Kokar</title>

    <meta name="description" content="Lançamento de requisições de abastecimento">
    <meta name="author" content="Eduardo Patrick">

    <link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../recursos/css/style.css" rel="stylesheet">
    <link href="../../recursos/css/table.css" rel="stylesheet">
    <link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">

</head>

<body style="background-color: teal;">
	<div class="header">
		<div class="row">
			<div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
				<img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
			</div>
			<div class="float-md-right">
				<div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
					Usuário: <?php echo $_SESSION['nome'] ?>
				</div>
				<div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
					Setor: <?php echo $_SESSION['setor'] ?>
				</div>
			</div>
		</div>
	</div>
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


<div class="container-fluid">
    <div class="row">
        <div class="col-md-1"></div>
        <div id="abastecimento" class="col-md-10" style="padding-bottom: 20px; background-color: white; border:2px solid black">

        <div class="row" style='padding-top: 20px'>
            
            <div class="col-md-12" style="text-align: center">
                <h1>Nova Requisição</h1>
            </div>
        </div>
        <div class="row" style='padding-top: 20px'>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Número Requisição:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="numreq" value="<?= $numreq ?>" disabled><br><br>
            </div>
        </div>
        <div class="row">
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
                <h2>Peso Solicitado(KG):</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="qtdsolicitada" onfocusout="limpa1()" autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Unidade Solicitado(UN):</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="undsolicitada" onfocusout="limpa2()" autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2>Setor/Solicitante:</h2>
            </div>
            <div class="col-md-1">

                <select name="Solicitante" id="solicitante">
                    <optgroup value="Base D'Agua" id="BASEAGUA" label="Base D'Agua">
                        <option value="BASE D'AGUA">FRANCISCO IRLAN</option>
                        <option value="BASE D'AGUA">FILIPI</option>
                        <option value="BASE D'AGUA">WILLIAS</option>
                        <option value="BASE D'AGUA">FRANCES</option>
                        <option value="BASE D'AGUA">WERMERSON</option>
                    </optgroup>
                    <optgroup value="Base Solvente" id="BASESOLVENTE" label="Base Solvente">
                        <option value="BASE SOLVENTE">LEONARDO</option>
                        <option value="BASE SOLVENTE">VENILSON</option>
                        <option value="BASE SOLVENTE">AKIRA</option>
                        <option value="BASE SOLVENTE">WANDERSON</option>
                    </optgroup>
                    <optgroup value="Tunel" id="TUNEL" label="Túnel">                       
                        <option value="TUNEL">VALDEILSON</option>                        
                    </optgroup>
                    <optgroup value="LAB" id="LABORATORIO" label="Laboratório">
                        <option value="LABORATORIO">LEANDRO</option>
                        <option value="LABORATORIO">MARCELO</option>
                        <option value="LABORATORIO">EDUARDO</option>
                        <option value="LABORATORIO">ADALIU</option>
                    </optgroup>
                    <optgroup value="ETE" id="ESTACAO DE TRATAMENTO" label="E.T.E.">
                        <option value="E.T.E">MAURICIO</option>
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
        codprod = document.getElementById('codprod').value;
        qtdsolicitada = document.getElementById('qtdsolicitada').value;
        qtdsolicitada2 = document.getElementById('undsolicitada').value;
        setor = $("#solicitante option:selected").val();
        solicitante = $("#solicitante option:selected").text();
        dataset = {
            "numreq": numreq,
            "codprod": codprod,
            "qtdsolicitada": qtdsolicitada,
            "qtdsolicitada2": qtdsolicitada2,
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
    function limpa1(){
        if(document.getElementById('qtdsolicitada').value>0){
        document.getElementById('undsolicitada').value = 0;
        }
    }
    function limpa2(){
        if(document.getElementById('undsolicitada').value>0){
        document.getElementById('qtdsolicitada').value = 0;
        }
        
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