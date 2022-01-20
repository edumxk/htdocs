<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/abast/controleAbast.php');
session_start();
$listaprod = Abastecimento::buscaProdEst();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Peso Padrão</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	</div>
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
	<div class="container" style="background-color: white; border-style: solid; border-width: 1px">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../home.php">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a href="../../homea.php">Controle Almoxarifado</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Peso Padrão</li>
			</ol>
		</nav>
        
		<div class="row" style="padding-bottom: 20px;">
			<div class="col-md-12">

				<!-------------------------------->
				<div>
					<p>
				</div>
                
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" id="secao">
							<div style="text-align: center;">Novo Cadastro de Peso Padrão</div>
						</div>
						
        <div class="row">
        <div class="col-md-1"></div>
        <div id="abastecimento" class="col-md-10" style="padding-bottom: 20px;padding-top: 50px; background-color: white">

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
                <h2>Peso Padrão:</h2>
            </div>
            <div class="col-md-1">
                <input type="number" id="emb1" value = 0 autocomplete="off"><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-2" style="padding-bottom: 20px;">
                <button style="width:280px;" type="submit" class="btn btn-sm btn-success" onclick="incluirDensidade()">
                    <b>Gravar</b>
                </button>
            </div>
        </div>
							
							<div class="row" style="padding-top: 30px; padding-bottom: 20px;  border-style: solid; border-width: 1px">
								<div class="col-md-12">
									<!-- <table id="example" class="display" width="100%"></table> -->
									<table id="table_id" class="display compact" style="width:100%; text-align: center">
										<thead>
											<tr>
												<th scope="col" style="width: 2%; text-align: left">Código</th>
												<th scope="col" style="width: 10%; text-align: left">Produto</th>
												<th scope="col" style="width: 2%">Densidade</th>
												<th scope="col" style="width: 2%">Peso Padrão</th>
												
                                                <th scope="col" style="width: 2%">Excluir</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($listaprod as $d) { ?>
												<tr>
													<td scope="row" style="width: 2%;text-align: left"><b><?= $d['CODPROD'] ?></th>
													<td style="width: 10%;text-align: left"><b><?= $d['DESCRICAO'] ?></td>
													<td style="width: 2%; text-align: center"><b><?= (doubleval($d['DENSIDADE'])) ?></td>
													<td style="width: 2%; text-align: center"><b><?= $d['QT1'] ?> kg</td>
                                                    
                                                    <td style="text-align: left;width: 1%; padding-left: 15px"><button tabindex="7" type="button" class="btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="deletaProdEst('<?= $d['CODPROD'] ?>')">X</button></td>
												</tr>
											<?php } ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="padding-left:20px">
				<div class="row"><p></div>
				<div class="row"><p></div>
				
				<!-------------------------------->
				<div style="height: 50px">
					<p>
				</div>
			</div>
		</div>
	</div>
	
        </div>
    </div>
    
	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/jquery.redirect.js"></script>
	<script src="../../recursos/js/typeahead.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</body>

<script>
	/*Função de Deleção*/
	function deletaProdEst(elm) {
		var result = confirm("Confirma exclusão do produto?");
		if (result) {

			$.ajax({
				type: 'POST',
				url: 'controleAbast.php',
				data: {
					'action': 'deletaProdEst',
					'query': elm
				},
				success: function(response) {			
						alert("Produto excluído com sucesso.");
					
					setTimeout("location.reload(true);",1);

				}
			});
		}
	}
</script>
<script>
	   function incluirDensidade() {
        $('#loading').toggle();
        $('.btn').toggle();

        codprod = document.getElementById('codprod').value;
        densidade = document.getElementById('densidade').value;
        descricao = document.getElementById('descricao').value;
        emb1 = document.getElementById('emb1').value;
        emb2 = 0;
        emb3 = 0;
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