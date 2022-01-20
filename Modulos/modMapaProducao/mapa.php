<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/Controle/formatador.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/modMapaProducao/Controle/controlMapa.php');
	session_start();
	define('CHARSET', 'UTF-8');
	define('REPLACE_FLAGS', ENT_COMPAT | ENT_XHTML);
	$data = date('Y-m-d');
	$hoje = date('d/m/Y');
	$datap = date('d/m/Y');
	if (isset($_GET['data'])) {
		$data = $_GET['data'];
	}

    if(!isset($_SESSION)){

            header("location:index.php?msg=failed");
	}

    $tanques = mapaControle::getProducao(Formatador::formatador2($data));
	$item = mapaControle::getItem();
    //var_dump($tanques);
	$arrLinhas = mapaControle::getLinhas();
	$produtos = mapaControle::getItemH();
	$totpesof = [0,0,0,0,0] ;
	$totpesop = [0,0,0,0,0] ;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head> 
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mapa de Produção</title>
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">
	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
    <link href="/Modulos/modMapaProducao/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
</head>

<body class="conteiner">
	<div class="header" style="width:auto; height:auto; background-color: #002695 ; width:100%; color:black">
		<div class="conteiner-fluid" style="background-color: white;">
			<div class="container" style="background-color: white; ">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item active" aria-current="page">
							<a href="../../home.php">Home</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Mapa de Produção</li>
					</ol>
				</nav>
			</div>
		</div>  
	</div>   
				<div class="row row-12 row-md-12">
					<form action="mapa.php" method="get">
						<div class="col" style="padding-left: 50px;">
							<input style="height: 40px"type="date" name="data" value="<?php echo $data ?>">
							<button style="height: 40px;" type="submit" class="btn-sm btn-primary" value="Buscar">Buscar</button>
							
						</div>
					</form>
					<div class="col" style="padding-left: 350px;">
						<button style="height: 40px;" type="submit" class="btn btn-sm btn-primary" onclick="openCadastro()">Cadastrar</button>	
					</div>
					<div class="col" >
						<button style="height: 40px; width:80px" type="submit" class="btn btn-sm btn-success" onclick="location.reload()"><i class="fas fa-sync fa-xs"></i>					</button>
					</div>
					<div class="col">
						<h5> Usuário: <?php echo $_SESSION['nome'] ?> </h5>
						<h5> Setor: <?php echo $_SESSION['setor']?></h5>
						<h5><a style="color: black" href="../../index.php">Sair</a></h5>
					</div>
					
					<div class="col col-12 col-md-12">
						<div id="secao" style=" margin-right: auto; font-size:x-large; background-color: #002695">
							PRODUÇÃO MAPA DIÁRIO <?= Formatador::formatador2($data) ?>
						</div>
						<table class="tbtanques">
							<thead style="width: 20%;">
								<?php for($i=0; $i<5; $i++): ?>
									<th onclick="openCadastro()">
									<?=$arrLinhas[$i] ?>
									</th>
								<?php endfor ?>   
							</thead>
							<tbody>
							<?php for($i=1; $i<6; $i++): ?>
								<td style="width: 20%;">
								<?php foreach($tanques as $t): ?>  
								<?php if($t->cod == $i): ?>   
									<div class="col col-md-12 col-12">
										<button value="<?=$t->status?>" class="btanque" type="button">
											<div style="padding-bottom:5px;"> 
												<table class="tbtanques2"> 
													<thead>
														<?php foreach($produtos as $p): 
															if($t->codproducao == $p->codproducao):?> 
															<th onclick="editProducao(<?=$t->codproducao?>)"><?php echo($p->categoria.' '.$p->cor.' | T '.$t->codtanque.' | '.$p->peso.' KG'); if($t->status=='FINALIZADO'){$totpesof[$t->cod-1] += $p->peso;}else{$totpesop[$t->cod-1] += $p->peso;} ?> </th>
															<th onclick="editProducao(<?=$t->codproducao?>)"style="width: 40px;">QT</th>
															<?php endif;  endforeach; ?>
													</thead>
													
														<?php foreach ($item as $it):
															if($it->codproducao == $t->codproducao):?>
															<tbody>
															<td ><?= $it->produto?></td>
															<td ><?=$it->qt?></td>
															</tbody> 
														<?php endif; endforeach; ?>
													
												</table>
											</div>
											<div> 
												<table class="tbtanques2">
													<thead>
														<?php if($t->status=='FINALIZADO'):?>
															<th class="th2">STATUS</th>
															<th class="th2">APONTADO</th>
														<?php else:?>
															<th class="th2" style="width: 100px">STATUS</th>
															<th class="th2" colspan="2" style="width: 150px">PREVISÃO</th>
															
														<?php endif ?>
													</thead>
													<tbody>
														<?php if($t->status=='FINALIZADO'):?>
															<td class="status" id="<?=$t->codproducao?>" onclick="alterarStatus(this)"><b><?=$t->status?></b></td>
															<td><b><?=$t->fechamento?></b></td>
														<?php else:?>
															<td class="status" id="<?=$t->codproducao?>" onclick="alterarStatus(this)"><b><?=$t->status?></b></td>
															<td><b><?=$t->producao?></b></td>
															<?php if($t->status=='AGUARDANDO'):?>
															<td id="<?=$t->codproducao?>" onclick="excluir(this)" style="width: 15px; background-color:red"><b>x</b></td>
															<?php endif;endif ?>
													</tbody>
													                 
												</table>
												
											</div>
											
											
										</button>
									</div>
									<?php endif ;?>
								<?php endforeach ?>
								</td>
								<?php endfor ?>
							</tbody>
							<tfoot>
								<tr >
									<th style="text-align: center; font-size:large">P: <?=$totpesop[0]?> KG | F: <?=$totpesof[0]?> KG</th>
									<th style="text-align: center; font-size:large">P: <?=$totpesop[1]?> KG | F: <?=$totpesof[1]?> KG</th>
									<th style="text-align: center; font-size:large">P: <?=$totpesop[2]?> KG | F: <?=$totpesof[2]?> KG</th>
									<th style="text-align: center; font-size:large">P: <?=$totpesop[3]?> KG | F: <?=$totpesof[3]?> KG</th>
									<th style="text-align: center; font-size:large">P: <?=$totpesop[4]?> KG | F: <?=$totpesof[4]?> KG</th>
								</tr>
								<tr >
									<th colspan="5" style="text-align: center; font-size:large">EM PRODUÇÃO: <?=array_sum($totpesop)?> KG | FINALIZADO: <?=array_sum($totpesof)?> KG | TOTAL: <?=array_sum($totpesof)+array_sum($totpesop)?> KG</th>
								</tr>
							</tfoot> 
						</div>
					</table>
				</div>
        	
	
<!-- INICIO MODAL PARA EDITAR CARGA -->
<div class="modal" tabindex="-1" role="dialog" id="modalCadastro">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title">Cadastro de Produção</h1>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row d-flex justify-content-center">
						<div class="col-md-12" style="font-size:large;">
							<div class ="row-md-12 row-12">								
									<div class="col-md-4">
										<a id="editNumcarga" hidden></a>
										<h3><label>LINHA</label></h3>
									</div>
									<div class="editar">
										<select id="Linha" style="width: 100%">																			
											<optgroup value="1" id="1" label="TINTAS 5000">							
												<option id="t7" value="7">T 7 - 5000</option>
												<option id="t8" value="8">T 8 - 5000</option>
											</optgroup>
											<optgroup value="2" id="2" label="TINTAS 2000">
												<option id="t5" value="5">T 5 - 2000</option>
												<option id="t6" value="6">T 6 - 2000</option>
												<option id="t11" value="11">T 11 - 1000</option>
												<option id="t12" value="12">T 12 - 1000</option>
												<option id="t13" value="13">T 13 - 1500</option>
											</optgroup>
											<optgroup value="3" id="3" label="TEXTURAS">
												<option id="t3" value="3">T 3 - 3000</option>
												<option id="t4" value="4">T 4 - 3000</option>
												<option id="t9" value="9">T 9 - 800</option>
												<option id="t10" value="10">T 10 - 1000</option>
											</optgroup>
											<optgroup value="4" id="4" label="MASSAS">
												<option id="t1" value="1">T 1 - 3000</option>
												<option id="t2" value="2">T 2 - 3000</option>
											</optgroup>
											<optgroup value="5" id="5" label="SOLVENTES">
												<option id="t14" value="14">T G</option>
												<option id="t15" value="15">T P</option>
											</optgroup>
										</select>
									</div>
									
									<div style="text-align: center;font-size:xx-large">
										PREVISÃO DE PRODUÇÃO
									</div>
									<div class="col-12 col-md-12" style="padding-bottom: 10px;">				
										<input type="date" id="datap" value="<?php echo $data ?>">
										<input type="time" id="horap" value="08:00">		
									</div>
							<div class="col-md-12" style="text-align: center;font-size:xx-large">PRODUTOS</div>
							<!-- Produto 1 -->
							<div class="row" style="font-size:small;">
								<div class="col-md-2">
									<div style="padding-bottom:5px;text-align:center">COD</div>
									<input style="width:90%" id="cod1">
								</div>
								<div class="col-md-8">
									<div style="padding-bottom:5px">DESCRIÇÃO</div>
									<input disabled style="width:90%" id="produto1">
								</div>
								<div class="col-md-2">
									<div style="padding-bottom:5px;text-align:center">QT</div>
									<input style="width:100%" id="qt1" disabled type="number" value="0" max="999">
								</div>
							</div>
							<!-- #fim Produto 1 -->
							<!-- Produto 2 -->
							<div class="row" style="font-size:small;">
								<div class="col-md-2">
									<div style="padding-bottom:5px;text-align:center">COD</div>
									<input style="width:90%" id="cod2">
								</div>
								<div class="col-md-8">
									<div style="padding-bottom:5px">DESCRIÇÃO</div>
									<input disabled style="width:90%" id="produto2">
								</div>
								<div class="col-md-2">
									<div style="padding-bottom:5px;text-align:center">QT</div>
									<input style="width:100%" id="qt2" disabled type="number" value="0" max="999">
								</div>
							</div>
							<!-- #fim Produto 2 -->
							<!-- Produto 3 -->
							<div class="row" style="font-size:small;">
								<div class="col-md-2">
									<div style="padding-bottom:5px;text-align:center">COD</div>
									<input style="width:90%" id="cod3">
								</div>
								<div class="col-md-8">
									<div style="padding-bottom:5px">DESCRIÇÃO</div>
									<input disabled style="width:90%" id="produto3">
								</div>
								<div class="col-md-2">
									<div style="padding-bottom:5px;text-align:center">QT</div>
									<input style="width:100%" id="qt3" disabled type="number" value="0" max="999">
								</div>
							</div>
							<!-- #fim Produto 3 -->
						</div>
						<div style="text-align: right;">
							<h3>PESO TOTAL: <label id="peso" text="0"></label> KG</h3>
						</div>
					</div>
				</div>
				<div class="modal-footer">
				<div class="status">
					<select id="sstatus" style="width: 100%">																			
						<optgroup label="STATUS">							
							<option id="S" value="S">AGUARDANDO</option>
							<option id="A" value="A">ABERTURA DE OP</option>
							<option id="P" value="P">PESAGEM</option>
							<option id="D" value="D">DISPERSÃO / BASE</option>
							<option id="L" value="L">LABORATÓRIO</option>
							<option id="C" value="C">ACERTO DE COR</option>
							<option id="E" value="E">ENVASE</option>
							<option id="B" value="B">CORREÇÃO</option>
							<option id="F" value="F">FINALIZADO</option>
						</optgroup>
					</select>
				</div>
					<input id="codproducao" value="0" hidden disabled></label>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>					
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="if ($('#codproducao').val()>0){  editar(this) }else{ cadastrar(this)}" >Confirmar</button>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- FIM MODAL -->    
</body>
<script src="../../recursos/js/jquery.min.js"></script>
<script>
    $(".btanque").each(function() {
        status = $(this).val()
        if (status == "ABERTURA/OP") {
            $(this).css("background-color",  "#B0ABA6");//cinza
        } else if (status == "PESAGEM") {
			$(this).css("background-color", "#FFA30E"); //laranja
        } else if (status == "DISPERSÃO/BASE") {
			$(this).css("background-color", "#FFFF00"); //amarelo
		}else if (status == "LABORATÓRIO") {
			$(this).css("background-color", "#B89D53"); //marrom
        } else if (status == "COR") {
            $(this).css("background-color", "#3956FA"); //blue
        } else if (status == "ENVASE") {
            $(this).css("background-color", "#42FFD0"); //LIGHTblue
        } else if (status == "CORREÇÃO") {
			$(this).css("background-color", "#FA3D10"); //VERMELHO
        } else if (status == "FINALIZADO") {
			$(this).css("background-color", "#08FF0C"); //VERDE
        }else if (status == "AGUARDANDO") {
			$(this).css("background-color", "white"); //VERDE
        }
		
    });
</script>
<!-- AÇÕES LIGADAS COM CONTROLE -->
<script>
function alterarStatus(elm){
		c = confirm("Deseja alterar para o próximo status?");
		codproducao = elm.id
		if(c){
			$.ajax({
				type: 'POST',
				url: 'controle/controlMapa.php',
				data: { 'action': 'alterarStatus', codproducao },
				success: function (response) {
					console.log(response);
					if (response.match("OK")) {
						location.reload()
					}
				}
			});
		}
	}
function excluir(elm){
	c = confirm("Deseja excluir?");
	codproducao = elm.id
	codfun = <?= $_SESSION['coduser'];?> 
	dataset = {'codproducao': codproducao,'codfun': codfun }

	if(c){
		$.ajax({
			type: 'POST',
			url: 'controle/controlMapa.php',
			data: { 'action': 'excluir', dataset },
			success: function (response) {
				console.log(response);
				if (response.match("OK")) {
					location.reload()
				}
			}
		});
	}
}
</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/Chart.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script>

function openCadastro() {
		
		
			$('#modalCadastro').modal('toggle');
			$("div.status select").val('S').change();
			$('#codproducao').val(0);
			$("div.editar select").val(7).change();
					$('#horap').val("08:00")
					$('#datap').val("<?=$data?>")
					
					for(i=1; i<4; i++){	
						$('#cod'+i).val(0)
						$('#cod'+i).keyup()	
					}		
	}

	function cadastrar(elm) {
		codtanque = $( "#Linha option:selected" ).val();
		dtprevisao = $("#datap").val();
		hrprevisao = $("#horap").val();
		codfun = <?= $_SESSION['coduser'];?>;
		dataini = '<?= Formatador::formatador2($data);?>';
		produtos={};
		for(i=1;i<4; i++){
			produtos[i-1] ={"cod":parseInt($("#cod"+i).val()), "qt":parseInt($("#qt"+i).val())}
		}
		status = $( "#sstatus option:selected" ).val();
		dataset = {"codtanque":codtanque, "dtprevisao":dtprevisao, "hrprevisao":hrprevisao, "codfun":codfun, "produtos":{produtos}, "status": status, "dataini": dataini} 
		$.ajax({
			type: 'POST',
			url: 'Controle/controlMapa.php',
			data: { 'action': 'cadastrar', 'query': dataset },
			success: function (response) {
				if (response.match("OK")) {
					location.reload()
				}
			}
		});
	}
	function editar(elm) {	
			codtanque = $( "#Linha option:selected" ).val();
			dtprevisao = $("#datap").val();
			hrprevisao = $("#horap").val();
			codfun = <?= $_SESSION['coduser'];?> 
			codproducao = $('#codproducao').val();
			produtos={};
			for(i=1;i<4; i++){
				produtos[i-1] ={"cod":parseInt($("#cod"+i).val()), "qt":parseInt($("#qt"+i).val())}
			}
			status = $( "#sstatus option:selected" ).val();
			dataset = {"codproducao":codproducao, "codtanque":codtanque, "dtprevisao":dtprevisao, "hrprevisao":hrprevisao, "codfun":codfun, "produtos":{produtos}, "status": status} 
			$.ajax({
				type: 'POST',
				url: 'Controle/controlMapa.php',
				data: { 'action': 'editar', 'query': dataset },
				success: function (response) {
					console.log(response);
					if (response.match("OK")) {
						location.reload()
					}
				}
			});
		
	}

	function editProducao(elm) {
		

			$('#modalCadastro').modal('toggle');
			codproducao = elm;
			$.ajax({
				type: 'POST',
				url: 'Controle/controlMapa.php',
				data: { 'action': 'getProducaoE', 'query': codproducao },
				success: function (response) {
					
					head = jQuery.parseJSON(response)[0][0]
					dt = head['dtproducao'].split('/')
					head['dtproducao'] = dt[2]+'-'+dt[1]+'-'+dt[0];
					produtos = jQuery.parseJSON(response)[1]
					console.log(produtos)
					$("div.status select").val(head['statusp']).change();
					$("div.editar select").val(head['codtanque']).change();;
					$('#horap').val(head['hrproducao'])
					$('#datap').val(head['dtproducao'])
					console.log((produtos.length))

					for(i=0, j=1; i<produtos.length; i=i+2, j++){
						if(produtos[i]>0){
							
					$('#cod'+j).val(produtos[i])
					$('#cod'+j).keyup()	
					$('#qt'+j).val(produtos[i+1])
					$('#qt'+j).keyup()	
						}
					}
					$('#codproducao').val(head['codproducao']);
					$('#codproducao').text(head['codproducao']);
						
				}

			});	
				
	}
	/* FUNÇÕES DE MODAL CADASTRO */
	$("#cod1").keyup(function() {
	cod = $("#cod1").val();
	$.ajax({
		  type: 'POST',
		  url: 'controle/controlMapa.php',
		  data: { 'action': 'getProduto', cod },
		  success: function (response) {
			if(response.length<50){
			$("#qt1").prop('disabled', false);	
			$("#produto1").val(response); 
			}
			else {
			$("#qt1").prop('disabled', true);
			$("#qt1").val("0");
			$("#produto1").val("CÓDIGO INVÁLIDO");
			}
		  }		  
	  });
	})
	
	$("#qt1").change(function() {
		$("#qt1").keyup()
	});
	$("#qt2").change(function() {
		$("#qt2").keyup()
	});
	$("#qt3").change(function() {
		$("#qt3").keyup()
	});
	
	$("#cod2").keyup(function() {
	cod = $("#cod2").val();
	$.ajax({
		  type: 'POST',
		  url: 'controle/controlMapa.php',
		  data: { 'action': 'getProduto', cod },
		  success: function (response) {
			if(response.length<50){
			$("#qt2").prop('disabled', false);	
			$("#produto2").val(response); 
			}
			else {
			$("#qt2").prop('disabled', true);
			$("#qt2").val("0");
			$("#produto2").val("CÓDIGO INVÁLIDO");
			}
		  }
	  });
	})

	$("#cod3").keyup(function() {
	cod = $("#cod3").val();
	$.ajax({
		  type: 'POST',
		  url: 'controle/controlMapa.php',
		  data: { 'action': 'getProduto', cod },
		  success: function (response) {
			if(response.length<50){
			$("#qt3").prop('disabled', false);	
			$("#produto3").val(response); 
			}
			else {
			$("#qt3").prop('disabled', true);
			$("#qt3").val("0")
			$("#produto3").val("CÓDIGO INVÁLIDO");
			}
		  }
	  });
	})
var pesoi= new Array(0,0,0);
	$("#qt1").keyup(function() {
		cod = $("#cod1").val();
		var arr = [parseFloat($("#qt1").val()),parseFloat($("#qt2").val()),parseFloat($("#qt3").val())];
		$.ajax({
		  type: 'POST',
		  url: 'controle/controlMapa.php',
		  data: { 'action': 'getPeso', cod },
		  success: function (response) {
			pesoi[0] = parseFloat(response);
			
			$("#peso").text((arr[0]*pesoi[0] + arr[1]*pesoi[1] + arr[2]*pesoi[2]).toFixed(0))
			
		  }
	  });
	})
	$("#qt2").keyup(function() {
		cod = $("#cod2").val();
		const arr = [parseFloat($("#qt1").val()),parseFloat($("#qt2").val()),parseFloat($("#qt3").val())];
		$.ajax({
		  type: 'POST',
		  url: 'controle/controlMapa.php',
		  data: { 'action': 'getPeso', cod },
		  success: function (response) {
			pesoi[1] = parseFloat(response);
			
			$("#peso").text((arr[0]*pesoi[0] + arr[1]*pesoi[1] + arr[2]*pesoi[2]).toFixed(0))
			
		  }
	  });
	})
	$("#qt3").keyup(function() {
		cod = $("#cod3").val();
		arr = new Array(parseFloat($("#qt1").val()), parseFloat($("#qt2").val()), parseFloat($("#qt3").val()));
		$.ajax({
		  type: 'POST',
		  url: 'controle/controlMapa.php',
		  data: { 'action': 'getPeso', cod },
		  success: function (response) {
			pesoi[2] = parseFloat(response);
			
			$("#peso").text((arr[0]*pesoi[0] + arr[1]*pesoi[1] + arr[2]*pesoi[2]).toFixed(0))
			
		  }
	  });
	})
	/* #FIM FUNÇÕES DE MODAL CADASTRO */
</script>

</HTML>