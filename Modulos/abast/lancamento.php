<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once 'controleAbast.php';
session_start();
$lista = [];

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
            <li class="breadcrumb-item active" aria-current="page">Ajuste de Abastecimento</li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div id="abastecimento" class="col-md-5" style="padding-bottom: 20px; background-color: white; border:2px solid black">
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                    <b> Número da Requisição:
                    </div>
                    <div class="col-md-1">
                        <input type="number" id="numreq" onfocusout="buscaReq(this.value)"><br>
                    </div>
                </div>
                <div class="row" style='padding-top: 20px'>
                <div class="col-md-1"></div>
                <div class="col-md-11">Se for resina, selecione o tanque:</div>
                </div>
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                      Tanque:  
                    </div>
                    <div class="col-md-1">
                    <select name="tanque" id="tanque">
                            <optgroup value="tanque" id="tanque" label="Tanque">
                                <option value="0">Requisição Normal</option>
                                <option value="1">Resina 1</option>
                                <option value="2">Resina 2</option>
                            </optgroup>
                        </select><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        Estoque Físico:
                    </div>
                    <div class="col-md-1">
                        <input type="number" id="estfisico" autocomplete="off"><br><br>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        Qtd Abastecida:
                    </div>
                    <div class="col-md-1">
                        <input type="number" id="qtdabast" autocomplete="off"><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        Estoque Final:
                    </div>
                    <div class="col-md-1">
                        <input type="number" id="estfinal" autocomplete="off"><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        Responsável :
                    </div>
                    <div class="col-md-1">
                        <select name="Solicitante" id="solicitante">
                            <optgroup value="abastecimento" id="abastecimento" label="Responsável pelo Abastecimento">
                                <option value="EVERALDO">Everaldo</option>
                                <option value="VINICIUS">Vinicius</option>
                                <option value="WALLYSON">Wallyson</option>
                                <option value="GERFESON">Gerfeson</option>
                                <option value="DANILLO">Danillo</option>
                            </optgroup>
                        </select><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        Data/hora :
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="dataabast" style="width: 120px;">
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        <input type="time" id="horaabast">
                    </div><br><br>
                </div>
                <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-1">
                        <button style="width:178px" type="submit" class="btn btn-sm btn-success" onclick="atualizarAbast()">
                            <b>Confirmar Lançamento</b></b>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-md-1"></div>
            <div id="abastecimento" class="col-md-11" style="padding-bottom: 20px; background-color: white; border:2px solid black">
            <div class="row" style='padding-top: 10px'>
                    <div class="col-md-12">
                        <P><t><b>INFORMAÇÕES:</b>
                        <b>________________________________________________________________________________</b>
                    </div>
                </div>
                <div class="row" style='padding-top: 10px'>
                    <div class="col-md-12" style="text-align:left">
                    <p id="vprod" value="<p><p><p><p><p><p>"></p>
                    </div>
                </div>
               
                <div class="row" style='padding-top: 10px; padding-bottom: 20px'>
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>
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
    function atualizarAbast() {
        numreq = document.getElementById('numreq').value;
        estfisico = document.getElementById('estfisico').value;
        qtdabast = document.getElementById('qtdabast').value;
        estfinal = document.getElementById('estfinal').value;
        dataabast = document.getElementById('dataabast').value;
        respabast = $("#solicitante option:selected").val();
        tipo = $("#tanque option:selected").val();
        horaabast = document.getElementById('horaabast').value;
        dataset = {
            "numreq": numreq,
            "estfisico": estfisico,
            "qtdabast": qtdabast,
            "estfinal": estfinal,
            "dataabast": dataabast,
            "respabast": respabast,
            "horaabast": horaabast,
            "tipo": tipo
        };
        console.log(dataset);
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'atualizaAbast',
                'query': dataset
            },
            success: function(response) {
                console.log(response);
                if (response.match("ok")) {
                    alert("Requisição n° " + numreq + " Atualizada!  =>  Imprimir na Rotina 8118!!!");
                    location.reload();

                }
            }
        });
    }

    function buscaReq(elm) {

        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'buscaReq',
                'query': elm
            },
            success: function(response) {
                if(response.indexOf("Notice")== -1){
                    document.getElementById("vprod").innerHTML = response;
                }
                else{
                    document.getElementById("vprod").innerHTML = "REQUISIÇÃO Nº "+elm+". NÃO EXISTE!";    
                }
               
            }
        })
    }
</script>

</html>