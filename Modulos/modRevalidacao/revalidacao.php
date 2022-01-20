<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once($_SERVER["DOCUMENT_ROOT"]. '/Modulos/modRevalidacao/controller/controler.php');
session_start();
$lista = [];

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Revalidação de Lotes</title>

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
                <a href="../../homelab.php">Laboratório</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Revalidação de Lotes</li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div id="abastecimento" class="col-md-5" style="padding-bottom: 20px; background-color: white; border:2px solid black; text-align:center"><h1>Revalidação de Lotes</h1>
                <div class="row" style='padding-top: 20px; text-align:left'>
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                    <b> Lote:
                    </div>
                    <div class="col-md-1">
                        <input style="text-transform:uppercase" type="text" id="numlote" onfocusout="buscaLote(this.value)"><br>
                    </div>
                </div>
                <div class="row" style='padding-top: 20px; text-align:left'>
                <div class="col-md-1"></div>
                <div class="col-md-11">Revalidar:</div>
                </div>
                <div class="row" style='padding-top: 20px'>
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                      Tempo:  
                    </div>
                    <div class="col-md-1">
                    <select id="tempo">
                            <optgroup id="tempo" label="Tempo">
                                <option value="6">+ 6 meses</option>
                                <option value="12">+ 12 meses</option>
                            </optgroup>
                        </select><br><br>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-1">
                        <button style="width:178px" type="submit" class="btn btn-sm btn-success" onclick="revalidar()">
                            <b>Revalidar</b></b>
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
    window.onload = function() {
  document.getElementById("numlote").focus();
};
    function revalidar() {
        
        numlote = document.getElementById('numlote').value;
        temp = document.getElementById("vprod").innerHTML;
        numlote = numlote.toUpperCase();
        if (temp.indexOf('EXISTE')>1){
            alert("Lote inválido!")
        }else {
            var result = confirm("Confirma a revalidação do lote nº "+numlote+"?");
        }if(result){
        tempo = $("#tempo option:selected").val();
        usuario = '<?php echo $_SESSION['nome'] ?>';
        dataset = {
            "numlote": numlote,
            "tempo": tempo,
            "usuario": usuario
        };
        
        console.log(dataset);
       $.ajax({
            type: 'POST',
            url: 'controller/controler.php',
            data: {
                'action': 'revalidar',
                'query': dataset
            },
            success: function(response) {
                console.log(response);
                if (response.match("ok")) {
                    alert("Lote n° " + numlote + " revalidado com sucesso.");
                    //location.reload();

                }
            }
        });
    }
     
    }

    function buscaLote(elm) {
        numlote = elm.toUpperCase();
        $.ajax({
            type: 'POST',
            url: 'controller/controler.php',
            data: {
                'action': 'buscaLote',
                'query': numlote
            },
            success: function(response) {
                if(response.indexOf("Notice")== -1){
                    document.getElementById("vprod").innerHTML = response;
                }
                else{
                    document.getElementById("vprod").innerHTML = "LOTE Nº "+numlote+". NÃO EXISTE!";    
                }
               
            }
        })
    }
</script>

</html>