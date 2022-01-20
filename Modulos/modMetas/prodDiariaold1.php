<?php 
	require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMetas/model/prodDiaria.php');
    session_start();

    // echo date('d/m/y');
    $data = date('Y-m-d');
    // echo $data;

    if(isset($_GET['data'])){
        $data = $_GET['data'];

    }
    $resumo = ProdDiaria::getProdResumo($data);
    // $analitico = ProdDiaria::getProdAnalitico($data);
    $mensal = ProdDiaria::getProdMensal($data);
    $faturado = ProdDiaria::getFaturado($data)[0];
    

    $labels = array();
    
    $dataset = array();
	$dataset2 = array();
    $dataset3 = array();
    array_push($labels, 'PRODUÇÃO DIÁRIA');
    
    $temp = 0;
    foreach($resumo as $r){

        $temp += $r->qtProduzida/50000*100;
    }
    Array_push($dataset, number_format($temp,2,'.',''));
    Array_push($dataset2, 100);
    
   
   
    $metafat = $faturado['PERC_META'];
    $metaacum = $faturado['PERC_ACUM'];
   
    $dataset4 = array("100", "100");
?>
<?php
    header("refresh: 60;");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>METAS</title>

	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">
    <link href="recursos/css/table.css" rel="stylesheet">
	<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../recursos/css/style.css" rel="stylesheet">
	<link href="../../recursos/css/style-table.css" rel="stylesheet">
	<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">


</head>


<body>
    
<div class="col" style="background-color: white; ">
        <div class="row" style="padding-top:20px">
            <div class="col" style="text-align: center;">
                <p><h1>CONTROLE DIÁRIO DE PRODUÇÃO</h1></p>
            </div>
        </div>
        <form action="prodDiaria.php" method="get" hidden>
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
            <!--#GRAFICO DE META DIARIO# -->
            <div class="col-md-6" style="padding-bottom: 10px;">
                <div id="secao" style="width:100%; margin-left: auto; margin-right: auto ; font-size:x-large">
                    LANÇAMENTO DE METAS
                </div>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px;">
                <div id="secao" style="width:100%; margin-left: auto; margin-right: auto ; font-size:x-large">
                    PRODUÇÃO X FATURAMENTO
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <canvas id="mybar1" height="280" style="font-size:x-large"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <canvas id="mybar2" height="280" style="font-size:x-large"></canvas>
                </div>
            </div>
            <!--#FIM GRAFICO DE META DIARIO# -->
            
            <!--INICIO TABELA DE META DIÁRIA -->
            <div class="col-6" >
                <div id="secao" style="width:98%; margin-left: auto; font-size:x-large">
                        META DIÁRIA - PRODUÇÃO
                </div>
            <table style="width:98%; margin-left: auto;  font-size:x-large">
                    <thead>
                        <tr>
                            <th style="text-align: center;">LINHA DE PRODUTOS</th>
                            <th style="text-align: center;">REALIZADO</th>
                            <th hidden style="text-align: center;">META</th>
                            <th hidden style="text-align: center;">% VAR</th>
                            <th  style="text-align: center;"colspan="2">RESPONSÁVEL</th>

                        </tr>
                    </thead>
                    <tbody style="font-size:x-large">
                        <?php $totalMinimo = 0; $totalProduzido = 0; $totalMeta = 0;?>
                        <?php if(sizeof($resumo)>0):?>
                            <?php foreach($resumo as $r):?>
                            <tr>
                                <?php if($r->cod == 1):?>
                                    <?php $totalMinimo += 10000; $totalProduzido += $r->qtProduzida; $totalMeta += 10000;?>
                                    <td style="padding-left: 10px">TINTAS 5000</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td hidden style="text-align: center;">10.000</td>
                                    <td hidden style="text-align: center;"><?php echo number_format((($r->qtProduzida/10000)*100),2,',','.').'%'?></td>
                                    <td colspan="2" style="padding-left: 10px">LUIS CARLOS</td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 2):?>
                                    <?php $totalMinimo += 8000; $totalProduzido += $r->qtProduzida; $totalMeta += 6000;?>
                                    <td style="padding-left: 10px">TINTAS 2000</td>                                   
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td hidden style="text-align: center;">6.000</td>
                                    <td hidden style="text-align: center;"><?php echo number_format((($r->qtProduzida/6000)*100),2,',','.').'%'?></td>
                                    <td colspan="2" style="padding-left: 10px">MAYCON HANGLEY</td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 3):?>
                                    <?php $totalMinimo += 9000; $totalProduzido += $r->qtProduzida; $totalMeta += 6000;?>
                                    <td style="padding-left: 10px">TEXTURAS</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td hidden style="text-align: center;">6.000</td>
                                    <td hidden style="text-align: center;"><?php echo number_format((($r->qtProduzida/6000)*100),2,',','.').'%'?></td>
                                    <td colspan="2" style="padding-left: 10px">WILLIAM / LUIS</td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 4):?>
                                    <?php $totalMinimo += 21600; $totalProduzido += $r->qtProduzida; $totalMeta += 24000;?>
                                    <td style="padding-left: 10px">MASSAS</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td hidden style="text-align: center;">24.000</td>
                                    <td hidden style="text-align: center;"><?php echo number_format((($r->qtProduzida/24000)*100),2,',','.').'%'?></td>
                                    <td colspan="2" style="padding-left: 10px">WILLIAM</td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 5):?>
                                    <?php $totalMinimo += 2700; $totalProduzido += $r->qtProduzida; $totalMeta += 4000;?>
                                    <td style="padding-left: 10px">SOLVENTES</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td hidden style="text-align: center;">4.000</td>
                                    <td hidden style="text-align: center;"><?php echo number_format((($r->qtProduzida/4000)*100),2,',','.').'%'?></td>
                                    <td colspan="2" style="padding-left: 10px">VENILSON</td>
                                 <?php endif?>
                            </tr>
                            <?php endforeach?>
                        <?php endif?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" style="text-align: center;">PRODUÇÃO ATINGIDA</th>
                            <th style="text-align: center;"><?php echo number_format($totalProduzido,0,',','.')?></th>
                            <th style="text-align: center;"><?php echo number_format($totalMeta,0,',','.')?></th>
                            <?php if($totalMeta == 0){
                                    $totalMeta = 1;
                                }?>
                            <th style="text-align: center;"><?php echo number_format((($totalProduzido/$totalMeta)*100),2,',','.').'%'?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>    
                <!-- META DO MÊS --> 
                               
            <div class="col-6"> 
                <div id="secao" style="width:98%; margin-right: auto; font-size:x-large">
                        META MENSAL - PRODUÇÃO
                </div>   
                <table style="width:98%; margin-right: auto; font-size:x-large">
                    <thead>
                        <tr>
                            <th style="text-align: center;">LINHA DE PRODUTOS</th>
                            <th style="text-align: center;">REALIZADO</th>
                            <th style="text-align: center;">MÍNIMO</th>
                            <th style="text-align: center;">% VAR</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:x-large">
                        <?php $totalMinimo = 0; $totalProduzido = 0; $totalMeta = 0;?>
                        <?php if(sizeof($mensal)>0):?>
                            <?php foreach($mensal as $r):?>
                            <tr>
                                <?php if($r->cod == 1):?>
                                    <?php $totalMinimo += 10000; $totalProduzido += $r->qtProduzida; $totalMeta += 220000;?>
                                    <td style="padding-left: 10px">TINTAS 5000</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">220.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/220000)*100),2,',','.').'%'?></td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 2):?>
                                    <?php $totalMinimo += 8000; $totalProduzido += $r->qtProduzida; $totalMeta += 132000;?>
                                    <td style="padding-left: 10px">TINTAS 2000</td>                                   
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">132.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/132000)*100),2,',','.').'%'?></td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 3):?>
                                    <?php $totalMinimo += 9000; $totalProduzido += $r->qtProduzida; $totalMeta += 132000;?>
                                    <td style="padding-left: 10px">TEXTURAS</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">132.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/132000)*100),2,',','.').'%'?></td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 4):?>
                                    <?php $totalMinimo += 21600; $totalProduzido += $r->qtProduzida; $totalMeta += 528000;?>
                                    <td style="padding-left: 10px">MASSAS</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">528.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/528000)*100),2,',','.').'%'?></td>
                                <?php endif?>
                            </tr>
                            <tr>
                                <?php if($r->cod == 5):?>
                                    <?php $totalMinimo += 2700; $totalProduzido += $r->qtProduzida; $totalMeta += 88000;?>
                                    <td style="padding-left: 10px">SOLVENTES</td>
                                    <td style="text-align: center;"><?php echo number_format($r->qtProduzida,0,',','.')?></td>
                                    <td style="text-align: center;">88.000</td>
                                    <td style="text-align: center;"><?php echo number_format((($r->qtProduzida/88000)*100),2,',','.').'%'?></td>
                                <?php endif?>
                            </tr>
                            <?php endforeach?>
                        <?php endif?>
                        </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" style="text-align: center;">PRODUÇÃO ATINGIDA</th>
                            <th style="text-align: center;"><?php echo number_format($totalProduzido,0,',','.')?></th>
                            <th style="text-align: center;"><?php echo number_format($totalMeta,0,',','.')?></th>
                            <?php if($totalMeta == 0){
                                    $totalMeta = 1;
                                }?>
                            <th style="text-align: center;"><?php echo number_format((($totalProduzido/$totalMeta)*100),2,',','.').'%'?></th>
                        </tr>
                    </tfoot>
                </table>

                <!-- META DO MÊS #FIM -->   

            </div>
        </div>
<!--
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
        </div> -->

   
</div>
</body>

<script src="../../recursos/js/jquery.min.js"></script>
	<script src="../../recursos/js/bootstrap.min.js"></script>
	<script src="../../recursos/js/scripts.js"></script>
	<script src="../../recursos/js/Chart.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>
    //Chart.size.font =25;
	var ctx = document.getElementById("mybar1");

	var labels =  <?php echo JSON_ENCODE($labels)?>;
	var dataset = <?php echo JSON_ENCODE($dataset)?>;
    var dataset2 = <?php echo JSON_ENCODE($dataset2)?>;
		
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: labels
            ,
			datasets: [{
				label: 'PRODUÇÃO',
				data: dataset,
				backgroundColor: 'rgba(205, 0, 0, 0.8)',
				borderColor:'rgba(255,255, 0, 0.8)',
				borderWidth: 1
			},{
				label: 'META',
				data: dataset2,
				backgroundColor: 'rgba(0, 255, 0, 0.8)',
				borderColor:'rgba(54, 162, 235, 0.8)',
				borderWidth: 1
			}
            
            
        ]
		},
		options: {
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 20
                            
                        }
                    }
                }
            },
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			},
            
		}
	});

	var ctx = document.getElementById("mybar1");
	ctx.onclick = function(evt){
    var activePoints = myChart.getElementsAtEvent(evt);
    // => activePoints is an array of points on the canvas that are at the same position as the click event.
	var pos = activePoints[0]['_index']
	console.log(labels[pos]);
};
</script>
<?php 
     Array_push($dataset3, round(($totalProduzido/$totalMeta)*100,2));
     Array_push($dataset3, $metafat);
?>
<script>
    //GRAFICO COMERCIAL
	var ctx = document.getElementById("mybar2");

	var labels =  <?php echo JSON_ENCODE(["PRODUÇÃO", "FATURAMENTO"])?>;
	var dataset3 = <?php echo JSON_ENCODE($dataset3)?>;
    var dataset4 = <?php echo JSON_ENCODE($dataset4)?>;
		
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: labels
            ,
			datasets: [{
				label: '% ACUMULADO',
				data: dataset3,
                <?php if($dataset3[0]>=100 && $dataset3[1]>=100):?>
				    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                <?php  else: ?>
                    backgroundColor: 'rgba(205, 0, 0, 0.8)',
                <?php endif ?>
				borderColor:'rgba(255,255, 0, 0.8)',
				borderWidth: 1
			},{
				label: 'META',
				data: dataset4,
				backgroundColor: 'rgba(0, 255, 0, 0.8)',
				borderColor:'rgba(54, 162, 235, 0.8)',
				borderWidth: 1
			}
            
            
        ]
		},
		options: {
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 30
                        }
                    }
                }
            },
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			},
            
		}
	});

	var ctx = document.getElementById("mybar1");
	ctx.onclick = function(evt){
    var activePoints = myChart.getElementsAtEvent(evt);
    // => activePoints is an array of points on the canvas that are at the same position as the click event.
	var pos = activePoints[0]['_index']
	console.log(labels[pos]);
};
</script>

<script>
	n = new Date();
	y = n.getFullYear();
	m = ("0" + (n.getMonth() + 1)).slice(-2)
	d = ("0" + n.getDate()).slice(-2)
	document.getElementById("date").innerHTML = d + "/" + m + "/" + y;
</script>

<script>
	function listaHoverIn(elm){
		$(elm).css('background-color', 'gold')
	}
	function listaHoverOut(elm){
		$(elm).css('background-color', 'transparent')
	}


</script>


</html>