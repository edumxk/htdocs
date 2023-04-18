<?php
session_start();
date_default_timezone_set('America/Araguaina');
define('CHARSET', 'UTF-8');
define('REPLACE_FLAGS', ENT_COMPAT | ENT_XHTML);

if (!array_key_exists('coduser', $_SESSION)) {
	header("location:/index.php?msg=failed");
}

require_once './control/CentralOP.php';
$listaOPs = Controle::getOP();
$produtos = Controle::getProdutos();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Central de OP</title>
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico">
	<meta name="author" content="Eduardo Patrick">
	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
	<link href="css/mapa.css" rel="stylesheet">
	<link href="css/modal.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css"/>
</head>

<body>
    <header>
        <div class="logo">
            <img src="/recursos/src/Logo-kokar5.png" alt="Logo Kokar" width="350">
        </div>
        <div class="usuario">
            <ul>
                <li>Usuário: <?php echo $_SESSION['nome']?></li>
                <li>Setor: <?php echo $_SESSION['setor']?></li>
                <li><a style="color: white" onclick="sair()" href="#">Sair</a></li>
            </ul>
        </div>		
    </header>
    <datalist id="produtos">
<?php
    foreach($produtos as $produto): ?>
        <option value="<?= $produto['CODPROD']; ?>" ><?= $produto['DESCRICAO']; ?></option>
    <?php endforeach;?>
    </datalist>

    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="../../home.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Produção</li>
            </ol>
        </nav>
    </div>
    
	
	<div class="principal">
        <div class="title">
            <h1>Controle de Ordem de Produção</h1>
        </div>
		<div class="listagem">
			<table class="table table-dark">
                <thead>
                    <tr>
                        <th>OP</th>
                        <th>LOTE</th>
                        <th>PRODUTO</th>
                        <th>QTD</th>
                        <th>REND. PREV.</th>
                        <th>STATUS</th>
                        <th>REND</th>
                        <th>CUSTO FORM</th>
                        <th>CUSTO PREV</th>
                        <th>CUSTO</th>
                        <th>AÇOES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listaOPs as $op): ?> 
                    <tr>
                        <td><?= $op->numOP?></td>
                        <td><?= $op->numLote?></td>
                        <td><?= $op->descricao?></td>
                        <td><?= number_format($op->qtProduzir, 0, ',', '.').' KG'?></td>
                        <td><?= number_format($op->qtPrevista, 0, ',', '.').' KG'?></td>
                        <td><?= $op->status?></td>
                        <td><?= number_format($op->rendimentoAjuste*100, 2, ',', '.').'%'?></td>
                        <td><?= "R$ ".number_format($op->custo, 3, ',', '.') . '/KG'?></td>
                        <td><?= "R$ ".number_format($op->custoAjuste, 3, ',', '.') . '/KG'?></td>
                        <td><?= number_format($op->previsaoAjuste*100, 2, ',', '.').'%'?></td>
                        <td><button class="btn btn-warning" onclick="editar(<?=$op->numOP?>, <?= $op->custo?>, <?=$op->qtProduzir?>, <?=$op->rendimentoAjuste?>, <?=$op->previsaoAjuste?>, <?= $op->custoAjusteTotal?>)">EDITAR</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
		</div>
	</div>

	<script src="../../recursos/js/jquery.min.js"></script>
	<!-- INICIO MODAL EDITAR OP -->
	<div class="modal" tabindex="-1" role="dialog" id="modalEditar">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">Correções OP</h1>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal_principal">
                            <table class="table table-dark" id="table-produtos">
                                <thead>
                                    <tr>
                                        <th>CODPROD</th>
                                        <th>DESCRICAO</th>
                                        <th>AJUSTE</th>
                                        <th>CUSTO</th>
                                        <th>% AJUSTE</th>
                                        <th>AÇOES</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-dados">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: right;">RESULTADO</th>
                                        <th id="custo_qt" class="numero"></th>
                                        <th id="custo_tot" class="numero"></th>
                                        <th id="custo_lanc" class="numero"></th>
                                        <th><button class="btn btn-danger"data-dismiss="modal" aria-label="Close" onclick="confirm('Solicitar Autorização da Diretoria?')">Autorizar</button></th>
                                    </tr>
                                </tfoot>
                                <button class="btn btn-warning" onclick="editarOP()">NOVO PRODUTO</button>
                            </table>
					</div>
				</div>
				<div class="modal-footer">
                </div>
			</div>
		</div>
	</div>

	<!-- FIM MODAL -->

    <!-- INICIO MODAL PARA EDITAR CARGA -->
	<div class="modal" tabindex="-1" role="dialog" id="modalProdutos">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">Produtos</h1>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group modal-add-prod">
                        
                        <input class="form-control codigo" placeholder="Código" tabindex="0" list="produtos" type="text" name="codprod" id="codprod">

                        <input class="form-control form-text" tabindex="-1" placeholder="Produto" readonly type="text" name="descricao" id="descricao">

                        <!-- <label for="qtdProd">Qtd: </label> -->
                        <input class="form-control codigo"  tabindex="0" type="number" placeholder="Quantidade" name="qtdProd" id="qtdProd">
                        <!-- <label for="estoque">Estoque: </label> -->
                        <input class="form-control codigo" tabindex="-1" type="number" readonly placeholder="Estoque" name="estoque" id="estoque">
                        <button class="btn btn-info" tabindex="0" onclick="addItem()">Adicionar</button>
					</div>
				</div>
				<div class="modal-footer">
                </div>
			</div>
		</div>
	</div>

	<!-- FIM MODAL -->
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../../recursos/js/jquery.min.js"></script>
<script src="../../recursos/bootstrap4/js/bootstrap.bundle.min.js"></script>
<script src="../../recursos/js/Chart.bundle.min.js"></script>
<script type="text/javascript" src="/DataTables/datatables.min.js"></script>
<script src="js/script.js"></script>

</HTML>