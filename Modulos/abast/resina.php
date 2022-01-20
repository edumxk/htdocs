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


    <title>Controle de Resina - Kokar</title>

    <meta name="description" content="Controle de Resina">
    <meta name="author" content="Eduardo Patrick">

    <link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../recursos/css/style.css" rel="stylesheet">
    <link href="../../recursos/css/table.css" rel="stylesheet">
    <link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">

</head>

<body style="background-color: teal;">
	<div class="header">
		<div class="row" style="padding-bottom: 40px;">
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
            <li class="breadcrumb-item active" aria-current="page">Controle de Resina</li>
        </ol>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div id="abastecimento" class="col-md-5" style="text-align: left; padding-bottom: 20px; background-color: white; border:2px solid black">
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        <b>Data:
                    </div>
                    <div class="col-md-2">
                        <input type="date" id="data" style="width: 130px;" tabindex="1" onfocusout="buscaResina(this.value)" >
                    </div>                    
                </div>
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        Tanque:  
                    </div>
                    <div class="col-md-2">
                    <select name="tanque" id="tanque" tabindex="2">
                            <optgroup value="tanque" id="tanque" label="Tanque">
                                <option value="1">Resina 1</option>
                                <option value="2">Resina 2</option>
                            </optgroup>
                        </select><br><br>
                    </div>
       
                </div>
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        Produto:
                    </div>
                    <div class="col-md-2">
                    <select name="codprod" id="codprod" tabindex="3">
                            <optgroup value="codprod" id="codprod" label="Produto">
                                <option value="1276">1276 - ACRONAL BS 700</option>
                                <option value="2599">2599 - ARACRYL E -2297</option>
                            </optgroup>
                        </select><br><br>
                    </div>
                   
                </div>
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        Peso Final:
                    </div>
                    <div class="col-md-2">
                        <input type="number" id="peso" autocomplete="off" tabindex="4"><br><br>
                    </div>
                    
                </div>
               
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-4"></div>
                    <div class="col-md-1">
                        <button style="width:178px" type="submit" class="btn btn-sm btn-success" onclick="incluirResina()" tabindex="4">
                            Confirmar Lançamento</b>
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
    function incluirResina() {
        codprod = $("#codprod option:selected").val();
        peso = document.getElementById('peso').value;
        tanque = $("#tanque option:selected").val();
        data = document.getElementById('data').value;
        responsavel = "<?php echo $_SESSION['nome'] ?>";
       
        dataset = {
            "codprod": codprod,
            "peso": peso,
            "tanque": tanque,
            "data": data,
            "responsavel": responsavel
        };
       
        if(peso == '' || peso < '0' || data == '' || data <= '01/04/2021'){
            alert('Preencha corretamente a data e peso!')
            
        }else {
        
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'incluirResina',
                'query': dataset
            },
            success: function(response) {
                console.log(response);
                if (response.match("LANÇAMENTO")) {
                    alert(response);
                    location.reload();
                }
                else
                alert(response);
            }
        });
    }
    }

    function buscaResina(elm) {
        console.log(elm);
        $.ajax({
            type: 'POST',
            url: 'controleAbast.php',
            data: {
                'action': 'buscaResina',
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