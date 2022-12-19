<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMapaProducao/Controle/controle.php');
session_start();
date_default_timezone_set('America/Araguaina');
define('CHARSET', 'UTF-8');
define('REPLACE_FLAGS', ENT_COMPAT | ENT_XHTML);
$data = date('Y-m-d');
$hoje = date('d/m/Y');
$datap = date('d/m/Y');
$datahora = date('d/m/Y H:i:s');

if (isset($_GET['data'])) {
	$data = $_GET['data'];
}

if (!isset($_SESSION)) {
	header("location:index.php?msg=failed");
}

$tanques = Controle::getProducaoFeed(Formatador::formatador2($data));
$item = Controle::getItem();
//var_dump($tanques);
$arrLinhas = Controle::getLinhas();
$produtos = Controle::getItemH();
$totpesof = [0, 0, 0, 0, 0];
$totpesop = [0, 0, 0, 0, 0];
$produtosList = Controle::getProdutosLista();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mapa de Produção</title>
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico">
	<meta name="author" content="Edaurdo Patrick">
	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Modulos/modMapaProducao/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
	<link href="css/mapa.css" rel="stylesheet">
	<link href="css/modal.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css"/>
</head>

<body>
	<div class="container-fluid">
		<div class="row" style="padding-top: 10px;">
			<div class="col-md-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item active" aria-current="page">
							<a href="../../home.php">Home</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Mapa de Produção</li>
					</ol>
				</nav>
			</div>
			<div class="col-md-4">
				<form action="index.php" method="get">
					<input style="height: 40px" type="date" name="data" value="<?php echo $data ?>">
					<button style="height: 40px;" type="submit" class="btn-sm btn-primary" value="Buscar">Buscar</button>
				</form>
			</div>
			<div class="col-md-1">
				
			</div>
			<div class="col-md-1">
				<button style="height: 40px; width:80px" type="submit" class="btn btn-sm btn-success" onclick="location.reload()"><i class="fas fa-sync fa-xs"></i> </button>
			</div>
			<div class="col-md-3" style="color: white; font-weight: 700">
				<h5> Usuário: <?php echo $_SESSION['nome'] ?> </h5>
				<h5> Setor: <?php echo $_SESSION['setor'] ?></h5>
				<h5><a style="color: white; font-weight: 700" href="../../index.php">Sair</a></h5>
			</div>
		</div>
	</div>
	<div class="dev">
		<button onclick="getDadosUpdate()">Atualizar OPs Com Lote</button>
	</div>
	<div class="mapa">
		<div class="mapa_linhas">
			<?php $i = 1;
			foreach ($arrLinhas as $l) :
			?>
				<div class="mapa_linha_card <?= $l ?>">
					<h3>
						<button type="button" onclick="openCadastro(this)" class="btanque" value="<?= $i ?>">
							<?= $l ?>
						</button>
					</h3>
					<div class="mapa_linhas_producao">
						<?php $i++;
						foreach ($tanques as $t) : ?>
							<?php if ($t->linha == $l) : ?>
								<span>
									<button type="button" class="btanque" value="<?= $t->status ?>">
										<table class="tbtanques2">
											<thead <?php $temp = []; $peso = 0;
														if ($t->status == 'FINALIZADO') {
													} else { ?> class="atraso" id="<?= $t->producao . ':00' ?>" <?php } ?> onclick="editProducao(<?= $t->codproducao ?>)">
												<?php foreach ($produtos as $p) :
													if ($t->codproducao == $p->codproducao) : 
														$temp[] = [$p->categoria, $p->cor, $t->codtanque, $t->status, $t->lote];
														$peso = $p->peso + $peso;
												endif;
												endforeach; ?>
											<th class="titulo-card" colspan="2" onclick="editProducao(<?= $t->codproducao ?>)">
											<span class="btanque2" onclick="editProducao(<?= $t->codproducao ?>)" >
												<?php echo ($temp[0][0] . ' ' . $temp[0][1] . '<br>T ' . $temp[0][2] . ' | ' . $peso . ' KG' . ' | ' . $temp[0][3]);
													
													if ($t->status == 'FINALIZADO') {
														$totpesof[$t->cod - 1] += $peso;
													} else {
														$totpesop[$t->cod - 1] += $peso;
													}?>
													</span>
											</th>
										</thead>
											<tbody>
												
													<?php foreach ($item as $it):
														if($it->codproducao == $t->codproducao):?>
														<tr>
														<td class="tabela_produto"><?php echo $it->produto.' | '.$it->qt.' UN' ?></td>
														
														<?php if($t->status=='AGUARDANDO'):?>
															<td onclick="excluir(<?=$t->codproducao?>)" class="delete"><b>x</b></td>
														<?php endif; ?>

														</tr>
													<?php endif; endforeach; ?>
												
												<tr>
													<td colspan="2">
														<div class="contador" id="<?= $t->codproducao ?>"><input type="text" hidden disabled value="<?= $t->codproducao . "-" . $t->cadastro?>"></div>
														<?php if($t->lote!= '') echo "LOTE: ".$t->lote?>
													</td>
												</tr>
											</tbody>
										</table>
									</button>
								</span>
							<?php endif; ?>
						<?php endforeach ?>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>

	<div class="resultado">
		<div class="resultado_main">
			<table class="table-hover table-lg" id="tabelaresultado">
				<thead>
					<tr style="font-size: x-large;">
						<th>LINHA DE PRODUÇÃO</th>
						<th>EM PRODUÇÃO</th>
						<th>FINALIZADAS</th>
						<th>TOTAL</th>
					</tr>
				</thead>
				<tbody>
					<?php $linha = ['TINTAS 5000', 'TINTAS 2000', 'TEXTURAS', 'MASSAS', 'SOLVENTES'];
					for ($i = 0; $i < 5; $i++) : ?>
						<tr>
							<td style="text-align: left; padding-left:20px"><?= $linha[$i] ?> </td>
							<td><?= number_format($totpesop[$i], 0, ',', '.') ?> KG </td>
							<td> <?= number_format($totpesof[$i], 0, ',', '.') ?> KG</td>
							<td><?= number_format($totpesof[$i] + $totpesop[$i], 0, ',', '.') ?> KG</td>
						</tr>
					<?php endfor; ?>
				</tbody>
				<tfoot>
					<tr>
						<td><b>TOTAIS</b></td>
						<td><b><?= number_format(array_sum($totpesop), 0, ',', '.') ?> KG </b></td>
						<td><b><?= number_format(array_sum($totpesof), 0, ',', '.') ?> KG </b></td>
						<td><b><?= number_format(array_sum($totpesop) + array_sum($totpesof), 0, ',', '.') ?> KG </b></td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div class="resultado_tabela">
			<table class="table-hover table-lg" id="tabelaresultado" style="border: 1px solid black;">
				<thead>
					<tr style="font-size: large; width: auto;">
						<th colspan="2">BASE D'AGUA</th>
						<th colspan="2">BASE SOLVENTE</th>
					</tr>
				</thead>
				<thead>
					<tr style="font-size: large; width: auto;">
						<th class="pendente">EM PRODUÇÃO</th>
						<th class="finalizada">FINALIZADO</th>
						<th class="pendente">EM PRODUÇÃO</th>
						<th class="finalizada">FINALIZADO</th>
					</tr>
				</thead>

				<body>
					<tr style="font-size: large;width: auto;">
						<td class="pendente"> <?= number_format(array_sum($totpesop) - ($totpesop[4]), 0, ',', '.') ?> KG </td>
						<td class="finalizada"> <?= number_format(array_sum($totpesof) - ($totpesof[4]), 0, ',', '.') ?> KG </td>
						<td class="pendente"> <?= number_format($totpesop[4], 0, ',', '.') ?> KG </td>
						<td class="finalizada"> <?= number_format($totpesof[4], 0, ',', '.') ?> KG </td>
					</tr>
				</body>
			</table>
		</div>

	</div>

	<script src="../../recursos/js/jquery.min.js"></script>
	<!-- INICIO MODAL PARA EDITAR CARGA -->
	<div class="modal" tabindex="-1" role="dialog" id="modalCadastro">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title">Cadastro de Produção</h1>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal_principal">
						<div class="modal_principal-form">
							<div class="form-cabecalho">
								<div>
									<a id="editNumcarga" hidden></a>
									<label for="Linha">Linha</label>
								</div>
								<div id="editar" class="editar">
									<select id="Linha"></select>
								</div>
							</div>
							<div class="form-previsao">
								<div>
									<label for="datap">Previsão</label>
								</div>
								<div>
									<input type="date" id="datap" value="<?php echo $data ?>">
									<input type="time" id="horap" value="08:00">
								</div>
							</div>
							<div class="form-previsao">
								<div id="lapontamento">
									<label for="dataf">Fechamento</label>
								</div>
								<div id="iapontamento">
									<input type="date" id="dataf" value="">
									<input type="time" id="horaf" value="">
								</div>
							</div>
								<!-- #inicio Produto -->
								<datalist id="produtos">
									<?php foreach ($produtosList as $p) : ?>
										<option label="<?= $p['DESCRICAO'] ?>" value="<?= $p['CODPROD'] ?>"></option>
									<?php endforeach; ?>
								</datalist>
								<?php for($i=1; $i<=3; $i++): ?>
								<div class="form-produtos">
									<div>
										<label class="produto-title" for="cod<?=$i?>">Produtos</label>
										<div  class="produto-codigo">
											<label for="cod<?=$i?>">Código</label>
											<input id="cod<?=$i?>" list="produtos">
										</div>
										<div class="produto-descricao">
											
											<input disabled id="produto<?=$i?>">
										</div>
										<div class="produto-qt">
											<label for="qt<?=$i?>">Qtd</label>
											<input id="qt<?=$i?>" disabled type="number" value="0" max="999">
										</div>
									</div>
								</div>
								<?php endfor ?>
								<!-- #fim Produto -->
							<div class="form-lote">
								<label for="lote">Lote</label>
								<input type="text" id="lote">
							</div>
							<div class="form-status">
								<select id="sstatus">
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
							<div class="form-peso">
								<span>Peso total: <label id="peso" text="0"></label> kg</span>
							</div>
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					
					<input id="codproducao" value="0" hidden disabled></label>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="if ($('#codproducao').val()>0){  editar(this) }else{ cadastrar(this)}">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- FIM MODAL -->
</body>
<script>
	$(document).ready(function() {
		$(".atraso").each(function() {
			previsao = $(this).get(0).id;
			datap = previsao.split(' ')
			data = '<?= explode(' ', $datahora)[0] ?>'
			hora = '<?= explode(' ', $datahora)[1] ?>'
			if (datap[0] == data) {
				if (datap[1] <= hora) {
					$(this).css("background-color", "red"); //red
				}

			}
		})
	});

	$(".pendente").each(function() {
		$(this).css("background-color", "#FFA30E"); //laranja
	});
	$(".finalizada").each(function() {
		$(this).css("background-color", "#08FF0C"); //VERDE
	});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../../recursos/js/jquery.min.js"></script>
<script src="../../recursos/bootstrap4/js/bootstrap.bundle.min.js"></script>
<script src="../../recursos/js/Chart.bundle.min.js"></script>
<script type="text/javascript" src="/DataTables/datatables.min.js"></script>
<script src="js/scripts.js"></script>
<script>
	$(".btanque").each(function() {
		status = $(this).val()
		if (status == "ABERT/OP") {
			$(this).css("background-color", "#B0ABA6"); //cinza
		} else if (status == "PESAGEM") {
			$(this).css("background-color", "#FFA30E"); //laranja
		} else if (status == "DISP/BASE") {
			$(this).css("background-color", "#FFFF00"); //amarelo
		} else if (status == "LAB") {
			$(this).css("background-color", "#B89D53"); //marrom
		} else if (status == "COR") {
			$(this).css("background-color", "#3956FA"); //blue
		} else if (status == "ENVASE") {
			$(this).css("background-color", "#42FFD0"); //LIGHTblue
		} else if (status == "CORREÇÃO") {
			$(this).css("background-color", "#FA3D10"); //VERMELHO
		} else if (status == "FINALIZADO") {
			$(this).css("background-color", "#08FF0C"); //VERDE
		} else if (status == "AGUARDANDO") {
			$(this).css("background-color", "white"); //BRANCO
		}

	});

// AÇÕES LIGADAS COM CONTROLE

	function excluir(codproducao) {
		c = confirm("Deseja excluir?");
		codfun = <?= $_SESSION['coduser']; ?>;
		dataset = {
			'codproducao': codproducao,
			'codfun': codfun
		}

		if (c) {
			$.ajax({
				type: 'POST',
				url: 'controle/controle.php',
				data: {
					'action': 'excluir',
					'query': dataset
				},
				success: function(response) {
				
					if (response.match("ok")) {
						location.reload()
					}
					else
						alert('Erro ao excluir, erro: ' + response);
				}
			});
		}else{
			console.log("Exclusão não autorizada")
		}
	}

	function openCadastro(elm) {
		$('#lapontamento').hide();
		$('#iapontamento').hide();
		$('#horaf').val('')
		$('#dataf').val('')
		$("#lote").val('');
		valor = 7;
		
		switch (elm.value) {
			case '4':
				valor = 7;
				break;
			case '1':
				valor = 5;
				break;
			case '2':
				valor = 3;
				break;
			case '3':
				valor = 1;
				break;
			case '5':
				valor = 14;
				break;
		}
		$.ajax({
			type: 'POST',
			url: 'controle/control.php',
			data: {
				'action': 'linhas',
				valor
			},
			success: function(response) {
				
				$('#Linha').children().remove().end();
				$('#Linha').append(response);
			}
		});
		$('#modalCadastro').modal('toggle');
		$("div.form-status select").val('S').change();
		$('#codproducao').val(0);
		$("div.editar select").val(valor).change();
		$('#horap').val("08:00")
		$('#datap').val("<?= $data ?>")

		for (i = 1; i < 4; i++) {
			$('#cod' + i).val('')
			$('#cod' + i).keyup()
		}
	}

	function cadastrar(elm) {
		let codtanque = $("#Linha option:selected").val();
		let dtprevisao = $("#datap").val();
		let hrprevisao = $("#horap").val();
		let codfun = <?= $_SESSION['coduser']; ?>;
		let lote = $("#lote").val();
		let dataini = '<?= Formatador::formatador2($data); ?>';
		let produtos = {};
		const retornoConsulta = [];
		status = $("#sstatus option:selected").val();
		for (i = 1; i < 4; i++) {
			produtos[i - 1] = {
				codprod : parseInt($("#cod" + i).val()),
				qt: parseInt($("#qt" + i).val()),
				op: ''
			}
		}
		dataset = {	"codproducao": null,
					"categoria": null,
					"codtanque": codtanque,
					"dtprevisao": dtprevisao,
					"hrprevisao": hrprevisao,
					"codfun": codfun,
					"produtos": produtos ,
					"status": status,
					"dataini": dataini,
					"dtfecha": null,
					"hrfecha": null,
					"lote": lote }

		if(lote!=''){ //Se lote preenchido, cadastrar pelo lote. Se não, cadastrar pelos produtos informados.
			$.ajax({
				type: 'POST',
				url: 'Controle/controle.php',
				data: {
					'action': 'cadastrarPorLote',
					'query' : dataset
				},
				success: function(response) {
					console.log(response);
					produtos = {};
					if(response.length > 4){
						JSON.parse(response).forEach((e,i) => {
							
							produtos[i] = {
								codprod: e.codprod,
								lote : e.lote,
								op : e.op,
								qt : e.qt,
								dtapontamento : e.dtApontamento,
								codproducao: e.codproducao,
							}
						});

						dataset.produtos = produtos;
						console.log(dataset.produtos);
						if(produtos[0].codproducao == null){
							
							$.ajax({
								type: 'POST',
								url: 'Controle/controle.php',
								data: {
									'action': 'cadastrar',
									'query': dataset
								},
								success: function(response) {
									console.log(response);
									
									if (response.match("ok")) {
										if(confirm("Cadastro realizado com Sucesso.\n\nAtualizar a página?"))
										location.reload()
									} else {
										alert('Erro de cadastro!: ' + response);
									}
								}
							});	
						}else
						alert("Lote já cadastrado! Action: cadastrarNovo")					
					}
					else{
						alert("Lote já cadastrado! Action: cadastrarPorLote, Produtos não encontrados: "+response.length+", Erro: " + response)
					}
				}
			});
		}else{
			
		$.ajax({
			type: 'POST',
			url: 'Controle/controle.php',
			data: {
				'action': 'cadastrar',
				'query': dataset
			},
			success: function(response) {
				
				if (response.match("ok")) {
					location.reload() //Aqui vai chamar método de refresh da producao.
				} else {
					console.log('Erro desconhecido! Action: cadastrar, Erro: ' + response);
				}
			}
		});
		}	
	}

	function editar(elm) {
		let codtanque = $("#Linha option:selected").val();
		let dtprevisao = $("#datap").val();
		let hrprevisao = $("#horap").val();
		let codfun = <?= $_SESSION['coduser']; ?>;
		let codproducao = $('#codproducao').val();
		let dtfecha = $('#dataf').val();
		let hrfecha = $('#horaf').val();
		let lote = $("#lote").val();
		let produtos = {};
		let dataini = '<?= Formatador::formatador2($data); ?>';
		for (i = 1; i < 4; i++) {
			produtos[i - 1] = {
				codprod: parseInt($("#cod" + i).val()),
				qt: parseInt($("#qt" + i).val()),
				op: ''
			}
		}
		status = $("#sstatus option:selected").val();
		dataset = {
			"codproducao": codproducao,
			"categoria": null,
			"codtanque": codtanque,
			"dtprevisao": dtprevisao,
			"hrprevisao": hrprevisao,
			"codfun": codfun,
			"produtos": 
				produtos
			,
			"status": status,
			"dtfecha": dtfecha,
			"hrfecha": hrfecha,
			"lote": lote,
			"dataini": dataini,
		}
		
		
		$.ajax({
				type: 'POST',
				url: 'Controle/control.php', //Migrar para Controle Novo
				data: {
					'action': 'consultarProdutos',
					'lote': lote, 'codproducao': codproducao
				},
				success: function(response) {
					produtos = {};
					$.ajax({
						type: 'POST',
						url: 'Controle/controle.php',
						data: {
							'action': 'editar',
							'query': dataset
						},
						success: function(response) {
							
							if (response.match("FAIL")) {
								alert("Erro na edição. Error: \n\n" + response);
							}
							else{
								if(confirm(response+"\n\nAtualizar a página? ")){
									location.reload()
								}
							}
						}
					});
				}
		});
	}

	function editProducao(elm) {
		for (i = 1; i < 4; i++) {
			$('#cod' + i).val('')
			$('#cod' + i).keyup()
		}
		
		let codproducao = elm;
		$('#modalCadastro').modal('toggle');
		$.ajax({
			type: 'POST',
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'linhas2',
				codproducao
			},
			success: function(response) {
				$('#Linha').children().remove().end();
				$('#Linha').append(response);
			}
		});

		$.ajax({
			type: 'POST',
			url: 'Controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getProducaoE',
				'codproducao': codproducao,
			},
			
			success: function(response) {
				head = jQuery.parseJSON(response)[0][0]
				dt = head['dtproducao'].split('/')
				head['dtproducao'] = dt[2] + '-' + dt[1] + '-' + dt[0];
				if (head['dtfecha'] != null) {
					dt2 = head['dtfecha'].split('/')
					head['dtfecha'] = dt2[2] + '-' + dt2[1] + '-' + dt2[0];
				}
				produtos = jQuery.parseJSON(response)[1]

				$("div.form-status select").val(head['statusp']).change();
				$("div.editar select").val(head['codtanque']).change();;
				$('#horap').val(head['hrproducao'])
				$('#datap').val(head['dtproducao'])
				$('#lote').val(head['lote'])
				if (head['status'] != "FINALIZADO") {
					$('#lapontamento').hide();
					$('#iapontamento').hide();
					$('#horaf').val('')
					$('#dataf').val('')
				} else {
					$('#horaf').val(head['hrfecha'])
					$('#dataf').val(head['dtfecha'])
					$('#lapontamento').show(1);
					$('#iapontamento').show(1);
				}
				
				
				for(i=1; i<=produtos.length; i++){
					$('#cod' + i).val(produtos[i-1].codprod)
					$('#cod' + i).keyup()
					$('#qt' + i).val(produtos[i-1].qt)
					$('#qt' + i).keyup()
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
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getProduto',
				cod
			},
			success: function(response) {
				if (response.length < 50) {
					$("#qt1").prop('disabled', false);
					$("#produto1").val(response);
				} else {
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
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getProduto',
				cod
			},
			success: function(response) {
				if (response.length < 50) {
					$("#qt2").prop('disabled', false);
					$("#produto2").val(response);
				} else {
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
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getProduto',
				cod
			},
			success: function(response) {
				if (response.length < 50) {
					$("#qt3").prop('disabled', false);
					$("#produto3").val(response);
				} else {
					$("#qt3").prop('disabled', true);
					$("#qt3").val("0")
					$("#produto3").val("CÓDIGO INVÁLIDO");
				}
			}
		});
	})
	var pesoi = new Array(0, 0, 0);
	$("#qt1").keyup(function() {
		cod = $("#cod1").val();
		var arr = [parseFloat($("#qt1").val()), parseFloat($("#qt2").val()), parseFloat($("#qt3").val())];
		$.ajax({
			type: 'POST',
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getPeso',
				cod
			},
			success: function(response) {
				pesoi[0] = parseFloat(response);

				$("#peso").text((arr[0] * pesoi[0] + arr[1] * pesoi[1] + arr[2] * pesoi[2]).toFixed(0))

			}
		});
	})
	$("#qt2").keyup(function() {
		cod = $("#cod2").val();
		const arr = [parseFloat($("#qt1").val()), parseFloat($("#qt2").val()), parseFloat($("#qt3").val())];
		$.ajax({
			type: 'POST',
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getPeso',
				cod
			},
			success: function(response) {
				pesoi[1] = parseFloat(response);

				$("#peso").text((arr[0] * pesoi[0] + arr[1] * pesoi[1] + arr[2] * pesoi[2]).toFixed(0))

			}
		});
	})
	$("#qt3").keyup(function() {
		cod = $("#cod3").val();
		arr = new Array(parseFloat($("#qt1").val()), parseFloat($("#qt2").val()), parseFloat($("#qt3").val()));
		$.ajax({
			type: 'POST',
			url: 'controle/control.php', //Migrar para Controle Novo
			data: {
				'action': 'getPeso',
				cod
			},
			success: function(response) {
				pesoi[2] = parseFloat(response);

				$("#peso").text((arr[0] * pesoi[0] + arr[1] * pesoi[1] + arr[2] * pesoi[2]).toFixed(0))

			}
		});
	})
	/* #FIM FUNÇÕES DE MODAL CADASTRO */

	/* #INICIO FUNÇÕES PARA UPDATE OP APONTADA / STATUS ALTERADO */
	function getDadosUpdate(dados){

		$.ajax({
			type: 'POST',
			url: 'controle/controle.php', //Migrar para Controle Novo
			data: {
				'action': 'getDadosAtualizados',
				'query' : dados
			},
			success: function(response) {
				console.log(response);
			}
		});
	}

</script>

</HTML>