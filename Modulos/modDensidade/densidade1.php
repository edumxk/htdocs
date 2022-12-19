<?php

require_once "src/mvc/Model.php";
require_once "src/Produto2.php";


$data1 = date('01/m/Y', time());
$data2 = date('t/m/Y', time());
$dataBusca1 = date('Y-m-01', time());
$dataBusca2 = date('Y-m-t', time());

if(isset($_GET['data1'], $_GET['data2'])){
    // echo json_encode($_GET);
    $d = explode('-',$_GET['data1']);
    $d2 = explode('-',$_GET['data2']);
    $data1 =  $d[2].'/'.$d[1].'/'.$d[0];
    $data2 =  $d2[2].'/'.$d2[1].'/'.$d2[0];
    $dataBusca1 = $_GET['data1'];
    $dataBusca2 = $_GET['data2'];
    
    $laudos =  Produto2::mediaDensidade(Model::getDadosNovo($data1, $data2), Model::getDados($data1, $data2),$data1, $data2);
}else{
    $laudos =  Produto2::mediaDensidade(Model::getDadosNovo($data1, $data2), Model::getDados($data1, $data2),$data1, $data2);
}

$laudosP = Produto2::planilha(Model::getDados($data1, $data2));
$medias = Produto2::planilhaMedias(Produto2::mediaDensidade(Model::getDadosNovo($data1, $data2), Model::getDados($data1, $data2),$data1, $data2));

// Create an array of elements
   
// Open a file in write mode ('w')
$fp = fopen('densidade.csv', 'w');
$fp2 = fopen('densidadeMedias.csv', 'w');


// Loop through file pointer and a line
foreach ($laudosP as $l) {
    fputcsv($fp, $l, ";");
}
foreach ($medias as $l) {
    fputcsv($fp2, $l, ";");
}

fclose($fp);
fclose($fp2);

session_start();
	//header("refresh: 180;");
	date_default_timezone_set('America/Araguaina');
//var_dump($laudos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="/Recursos/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="/Recursos/js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Recursos/bootstrap-5.0.2-dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="./css/densidade.css"/>
    <script type="text/javascript" src="/Recursos/js/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="/Recursos/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

    <title>Densidade</title>
</head>
<body>
    <div class="navegacao">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<a href="/Modulos/modDensidade/src/home.php">Home</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Densidade Geral</li>
				</ol>
			</nav>
		</div>
		<div>
			<h5> Usuário: <?php echo $_SESSION['nome'] ?> </h5>
			<h5> Setor: <?php echo $_SESSION['setor']?></h5>
			<h5><a style="color: black" href="../../index.php">Sair</a></h5>
		</div>
	</div>
    <div class="conteiner">
        <form action="#" method="GET">
            <label for="data1"> De: </label>
            <input type="date" name="data1" id="data1" value="<?php echo $dataBusca1?>"/>
            <label for="data2"> Até: </label>
            <input type="date" name="data2" id="data2" value="<?php echo $dataBusca2?>"/>
            <button class="btn btn-primary" type="submit" name="busca"> Buscar </button>
        </form>
        <div class="exportar"><a href="densidadeMedias.csv"><Button class="btn btn-primary">Download Planilha</Button></a></div>
    </div>
    <div class="tabela">
        <table id="laudos" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><span class="laudo">Codprod</span></th>
                    <th><span class="laudo">Descrição</span></th>
                    <th><span class="laudo">Valor Padrão</span></th>
                    <th><span class="laudo">Mínimo</span></th>
                    <th><span class="laudo">Máximo</span></th>
                    <th><span class="laudo">Média</span></th>
                    <th><span class="laudo">Densidade Fórmula</span></th>
                    <th><span class="laudo">Quantidade</span></th>
                    <th><span class="laudo">Peso Fórmula</span></th>
                    <th><span class="laudo">Litragem</span></th>
                    <th><span class="laudo">Codprod SA</span></th>
                    <th><span class="laudo">Descrição SA</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($laudos as $p):?>
                <tr>
                    <td><?= $p->codprodPa ?></td>
                    <td><?= $p->descricaoPa ?></td>
                    <td><?= $p->padrãoLaudo ?></td>
                    <td><?= number_format($p->min,3,',','.') ?></td>
                    <td><?= number_format($p->max,3,',','.') ?></td>
                    <td><?= number_format($p->media,3,',','.') ?></td>
                    <td><?= number_format($p->pesoFormula/ $p->litragem,3,',','.') ?></td>
                    <td><?= $p->contagem ?></td>
                    <td><?= number_format($p->pesoFormula,3,',','.') ?></td>
                    <td><?= number_format($p->litragem,1,',','.') ?></td>
                    <td><?= $p->codprodSa ?></td>
                    <td><?= $p->descricaoSa ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php ?>
</body>
</html>

<script>

$(document).ready(function () {
		$('#laudos').DataTable({
			"lengthMenu": [[50, 100, -1], [50, 100, "Todos"]],
			"order": [[1, "asc"]],
			"bInfo": false,
            dom: 'B<"clear">lfrtip',
            buttons: ['copy', 'print', 'csv', 'excelHtml5'],
            


			"language": {
                
				"sEmptyTable": "Nenhum registro encontrado",
				"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de _MAX_ registros)",
				"sInfoPostFix": "",
				"sInfoThousands": ".",
				"sLengthMenu": "_MENU_ resultados por página",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhum registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Próximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "Último"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				}
			}
		});
    });

</script>