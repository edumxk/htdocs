<?php 
	require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modProducao/model/prodDiaria.php');
    session_start();

    // echo date('d/m/y');
    $data = date('Y-m-d');
    // echo $data;

    if(isset($_GET['data'])){
        $data = $_GET['data'];

    }


    $resumo = ProdDiaria::getProdResumo($data);
    $analitico = ProdDiaria::getProdAnalitico($data);





?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Produção Diária</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">

	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/style-table.css" rel="stylesheet">
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
                    Setor: <?php echo $_SESSION['setor']?>
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
                <li class="breadcrumb-item active" aria-current="page">Produção</li>
            </ol>
        </nav>
        <div class="row" style="padding-top:20px">
            <div class="col" style="text-align: center;">
                <h4>CONTROLE DIÁRIO DE PRODUÇÃO</h4>
            </div>
        </div>
        <form action="prodDiaria.php" method="get">
            <div class="row" style="padding-bottom:20px">
                <div class="col-1" style="text-align: right;">
                    Data:
                </div>
                <div class="col-2" style="text-align: left;">
                    <input type="date" name="data" value="<?php echo $data?>">
                </div>
                <div class="col-2" style="text-align: left">
                    <button type="submit" class="btn-sm btn-primary" value="Buscar">Buscar</button>
                </div>
            </div>
        </form>
        <div class="row">            
            <div class="col-12" style="text-align: center;">
                <h5>ANALÍTICO</h5>
            </div>
            <div class="col">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ITEM</th>
                            <th style="padding-left: 10px">LINHA DE PRODUTOS</th>
                            <th style="text-align: center;">TANQUES</th>
                            <th style="text-align: center;">CAPACIDADE KG</th>
                            <th style="text-align: center;">PROD MÍNIMA</th>
                            <th style="text-align: center;">REALIZADO</th>
                            <th style="text-align: center;">META</th>
                            <th style="text-align: center;">% VAR</th>
                            <th style="padding-left: 10px">RESPONSÁVEL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalMinimo = 0; $totalProduzido = 0; $totalMeta = 0;?>
                        <?php if(sizeof($resumo)>0):?>
                            <?php foreach($resumo as $r):?>
                            <tr>
                                <?php if($r->cod == 1):?>
                                    <?php $totalMinimo += 10000; $totalProduzido += $r->qtProduzida; $totalMeta += 20000;?>
                                    <td style="text-align: center;">1</td>
                                    <td style="padding-left: 10px">LINHA TINTAS</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">5.000</td>
                                    <td style="text-align: center;">10.000</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">20.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/20000)*100),2,',','.').'%'?></td>
                                    <td style="padding-left: 10px">Leandro</td>
                                <?php endif?>


                            </tr>
                            <tr>
                                <?php if($r->cod == 2):?>
                                    <?php $totalMinimo += 8000; $totalProduzido += $r->qtProduzida; $totalMeta += 12000;?>
                                    <td style="text-align: center;">2</td>
                                    <td style="padding-left: 10px">LINHA TINTAS</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">2.000</td>
                                    <td style="text-align: center;">8.000</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">12.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/12000)*100),2,',','.').'%'?></td>
                                    <td style="padding-left: 10px">Leandro</td>
                                <?php endif?>

                            </tr>
                            <tr>
                                <?php if($r->cod == 3):?>
                                    <?php $totalMinimo += 9000; $totalProduzido += $r->qtProduzida; $totalMeta += 12000;?>
                                    <td style="text-align: center;">3</td>
                                    <td style="padding-left: 10px">LINHA TEXTURAS</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">3.000</td>
                                    <td style="text-align: center;">9.000</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">12.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/12000)*100),2,',','.').'%'?></td>
                                    <td style="padding-left: 10px">Leandro</td>

                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 4):?>
                                    <?php $totalMinimo += 21600; $totalProduzido += $r->qtProduzida; $totalMeta += 27000;?>
                                    <td style="text-align: center;">4</td>
                                    <td style="padding-left: 10px">LINHA MASSAS</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">2.700</td>
                                    <td style="text-align: center;">21.600</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">27.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/27000)*100),2,',','.').'%'?></td>
                                    <td style="padding-left: 10px">Leandro</td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 5):?>
                                    <?php $totalMinimo += 2700; $totalProduzido += $r->qtProduzida; $totalMeta += 5400;?>
                                    <td style="text-align: center;">5</td>
                                    <td style="padding-left: 10px">LINHA SOLVENTES</td>
                                    <td style="text-align: center;">3</td>
                                    <td style="text-align: center;">900</td>
                                    <td style="text-align: center;">2.700</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">5.400</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/5400)*100),2,',','.').'%'?></td>
                                    <td style="padding-left: 10px">Leandro</td>

                                <?php endif?>
                            </tr>
                            <?php endforeach?>
                        <?php endif?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" style="text-align: center;">PRODUÇÃO ATINGIDA</th>
                            <th style="text-align: center;"><?php echo number_format($totalMinimo,0,',','.')?></th>
                            <th style="text-align: center;"><?php echo number_format($totalProduzido,0,',','.')?></th>
                            <th style="text-align: center;"><?php echo number_format($totalMeta,0,',','.')?></th>
                            <?php if($totalMeta == 0){
                                    $totalMeta = 1;
                                }?>
                            <th style="text-align: center;"><?php echo number_format((($totalProduzido/$totalMeta)*100),2,',','.').'%'?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="row" style="padding-top:40px; padding-bottom:20px">
            <div class="col-12" style="text-align: center;">
                <h5>SINTÉTICO</h5>
            </div>
            <div class="col">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ITEM</th>
                            <th style="text-align: center">LINHA DE PRODUTOS</th>
                            <th style="text-align: center;">OP</th>
                            <th style="text-align: center;">COD</th>
                            <th style="padding-left: 10px;">DESCRICAO</th>
                            <th style="text-align: center;">PRODUZIDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($analitico as $a):?>
                            <tr>
                                <td style="text-align: center;"><?php echo $a->cod?></td>
                                <td style="text-align: center;"><?php echo $a->linha?></td>
                                <td style="text-align: center;"><?php echo $a->numOp?></td>
                                <td style="text-align: center;"><?php echo $a->codProd?></td>
                                <td style="padding-left: 10px;"><?php echo $a->descricao?></td>
                                <td style="text-align:right; padding-right: 10px;"><?php echo number_format($a->qtProduzida,2,',','.')?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2"></th>
                            <th style="text-align: center;"><?php echo sizeof($analitico)?></th>
                            <th style="text-align:right; padding-right: 10px;" colspan="2">TOTAL</th>
                            <th style="text-align:right; padding-right: 10px;"><?php echo number_format($totalProduzido,2,',','.')?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

</body>
</html>