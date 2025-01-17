<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modMetas/model/prodDiaria.php');
session_start();
$data = date('Y-m-d');
if (isset($_GET['data'])) {
    $data = $_GET['data'];
}
$resumo = ProdDiaria::getProdResumo($data);
$mensal = ProdDiaria::getProdMensalNovo($data);
//var_dump(ProdDiaria::getFaturado($data));
if (sizeof(ProdDiaria::getFaturado($data)) > 0) {
    $faturado = ProdDiaria::getFaturado($data)[0];
} else {
    $faturado = 0;
}
$pesod = ProdDiaria::getPesoD($data)[0]['PESOLIQ'];
$pesom = 0;

$pesomes=[];
foreach($mensal as $m){
    $pesom += $m->qtProduzida; 
}

$labels = array();

$dataset = array();
$dataset2 = array();
$dataset3 = array();

/*PREENCHIMENTO DAS LABELS DO GRAFICO 1 */
foreach ($resumo as $r) {
    if ($r->cod != 6) {
        array_push($labels, $r->linha);
        Array_push($dataset2, 100);
    }
}

$metas = array();
$metasm = array();
$metasd = array();
$diasuteis =  ProdDiaria::getDiasUteis(ProdDiaria::getPrimeiroDiaMes($data), ProdDiaria::getUltimoDiaMes($data), ProdDiaria::feriados($data));
$diasuteish =  ProdDiaria::getDiasUteis(ProdDiaria::getPrimeiroDiaMes($data),  $data ,ProdDiaria::feriados($data));

$producaodia = 0;
//echo (ProdDiaria::getUltimoDiaMes($data));
//echo $diasuteis. ' - ' . $diasuteish;
echo $diasuteis;

foreach ($resumo as $r) {
    if ($r->cod != 6) {
        Array_push($dataset, number_format(($r->qtProduzida / $r->meta1 * 100), 0, '.', ''));
        Array_push($metas, $r->meta1);
        Array_push($metasm, $r->meta1 * $diasuteis);
        Array_push($metasd, $r->meta1 * $diasuteish);
        $producaodia += $r->qtProduzida;
    }
}

$metafat = $faturado['PERC_META'];

if(array_sum($metasm) == 0) 
$metames = 50000;
else 
$metames = array_sum($metasm);

$dataset4 = array("100", "100");
Array_push($dataset3, round(($pesom /$metames ) * 100, 2));
Array_push($dataset3, $metafat);
$somadia = 0;
?>
<?php
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MÉTRICAS DE PRODUÇÃO</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">
    <link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico">
    <link href="recursos/css/table.css" rel="stylesheet">
    <link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../recursos/css/style.css" rel="stylesheet">
    <link href="../../recursos/css/style-table.css" rel="stylesheet">
    <link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">


    </headate_default_timezone_set('America/Araguaina');>


<body onload="startTime()">

    <div class="col" style="background-color: white; ">
        <div class="row" style="padding-top:20px">
            <div class="col" style="text-align: center;">
                <p>
                <h1>CONTROLE DIÁRIO DE PRODUÇÃO</h1>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-1" style="font-size: 20px; border: 0px; border-style: groove; text-align:center" id="hora"></div>
            <div class="col-1" style="font-size: 20px; border: 0px; border-style: groove; text-align:center" id="data"><?= ProdDiaria::formatador($data) ?></div>
        </div>
        <script>
            function startTime() {
                const today = new Date();
                let h = today.getHours();
                let m = today.getMinutes();
                let s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                document.getElementById('hora').innerHTML = h + ":" + m + ":" + s;
                setTimeout(startTime, 1000);
            }

            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i
                }; // add zero in front of numbers < 10
                return i;
            }
        </script>
        <form action="prodDiaria.php" method="get" <?php if ($_SESSION['coduser'] == 17) : ?> hidden <?php endif; ?>>
            <div class="row" style="padding-bottom:20px">
                <div class="col-1" style="text-align: right;">
                    Data:
                </div>
                <div class="col-2" style="text-align: left;">
                    <input type="date" name="data" value="<?php echo $data ?>">
                </div>
                <div class="col-2" style="text-align: left">
                    <button type="submit" class="btn-sm btn-primary" value="Buscar">Buscar</button>
                </div>
                <div class="col-2" style="text-align: left">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a style="font-size:large" href="../../home.php">Home</a>
                    </li>
                </div>
            </div>
        </form>
        <div class="row">
            <!--#GRAFICO DE META DIARIO# -->
            <div class="col-md-6" style="padding-bottom: 10px;">
                <div id="secao" style="width:100%; margin-left: auto; margin-right: auto ; font-size: 20px">
                    META DIÁRIA
                </div>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px;">
                <div id="secao" style="width:100%; margin-left: auto; margin-right: auto ; font-size: 20px">
                    PRODUÇÃO X FATURAMENTO
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <canvas id="mybar1" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <canvas id="mybar2" height="300"></canvas>
                </div>
            </div>
            <!--#FIM GRAFICO DE META DIARIO# -->

            <!--INICIO TABELA DE META DIÁRIA -->
            <div class="col-md-5 col-12">
                <div id="secao" style="width:98%; margin-left: auto; font-size: 20px">
                    META DIÁRIA - PRODUÇÃO
                </div>
                <table style="width:98%; margin-left: auto;  font-size: 20px">
                    <thead>
                        <tr>
                            <th colspan="1" style="text-align: center;">TANQUES</th>
                            <th colspan="1" style="text-align: center;">OPER.</th>
                            <th style="text-align: center;">PROD.</th>
                            <th style="text-align: center;">MÍN</th>
                            <th style="text-align: center;">%</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:18px">
                        <?php if (sizeof($resumo) > 0) : ?>
                            <?php foreach ($resumo as $r) : ?>
                                
                                <?php if ($r->cod != 6) : ?>
                                    <tr>
                                        <td style="padding-left: 10px"><?= $r->linha ?></td>
                                        <td style="padding-left: 10px"><?= $r->operador ?></td>
                                        <td style="text-align: center;"><?php echo number_format($r->qtProduzida, 0, ',', '.');
                                                                        $somadia += $r->qtProduzida; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($r->meta1, 0, ',', '.') ?></td>
                                        <td class="metadiaria" style="text-align: center"><?php echo number_format((($r->qtProduzida / $r->meta1) * 100), 0, ',', '.') . '%' ?></td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: center;">PRODUÇÃO ATINGIDA</th>
                            <th style="text-align: center;"><?php echo number_format($somadia, 0, ',', '.') ?></th>
                            <th style="text-align: center;"><?php echo number_format(array_sum($metas), 0, ',', '.') ?></th>
                            <th class="metadiariat" style="text-align: center;"><?php echo number_format((($somadia / array_sum($metas)) * 100), 0, ',', '.') . '%' ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- META DO MÊS -->

            <div class="col-md-7 col-12">
                <div id="secao" style="width:98%; margin-right: auto; font-size: 20px">
                    META MENSAL - PRODUÇÃO
                </div>
                <table style="width:98%; margin-right: auto; font-size: 20px">
                    <thead>
                        <tr style="font-size:20px">
                            <th colspan="4" style="text-align: center;">ACUMULADO</th>
                            <th colspan="4" style="text-align: center; background-color:lightsteelblue">MENSAL</th>
                        </tr>
                        <tr style="font-size:20px">
                            <th colspan="1" style="text-align: center;">TANQUES</th>
                            <th style="text-align: center;">PROD</th>
                            <th style="text-align: center;">MÍN</th>
                            <th style="text-align: center;">DEF/SUP</th>
                            <th style="text-align: center; background-color:lightsteelblue">META</th>
                            <th style="text-align: center; background-color:lightsteelblue">%</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:18px">

                        <?php if (sizeof($mensal) > 0) : ?>
                            <?php foreach ($mensal as $r) : ?>
                                <tr>
                                    <?php if ($r->cod != 6) : ?>
                                        <td style="padding-left: 10px"><?= $r->linha ?></td>
                                        <td style="text-align: center;"><?php echo number_format($r->qtProduzida, 0, ',', '.'); $pesomes[]=$r->qtProduzida; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($r->meta1 * $diasuteish, 0, ',', '.') ?></td>
                                        <td class="metap" style="text-align: center;"><?php echo number_format($r->qtProduzida - ($r->meta1 * $diasuteish), 0, ',', '.') ?></td>
                                        <td style="text-align: center;background-color:lightsteelblue"><?php echo number_format($r->meta1 * $diasuteis, 0, ',', '.') ?></td>
                                        <td class="metadiariat2" style="text-align: center;background-color:lightsteelblue"><?php echo number_format((($r->qtProduzida / ($r->meta1 * $diasuteis)) * 100), 2, ',', '.') . '%' ?></td>
                                    <?php endif ?>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" style="text-align: center;">TOTAIS</th>
                            <th style="text-align: center;"><?php echo number_format(array_sum($pesomes), 0, ',', '.') ?></th>
                            <th style="text-align: center;"><?php echo number_format(array_sum($metasd), 0, ',', '.') ?></th>
                            <th class="metam" style="text-align: center;"><?php echo number_format($pesom - array_sum($metasd), 0, ',', '.') ?></th>
                            <th style="text-align: center;background-color: lightsteelblue"><?php echo number_format(array_sum($metasm), 0, ',', '.') ?></th>
                            <th class="metadiariat2" style="text-align: center;background-color:lightsteelblue"><?php echo number_format(((array_sum($pesomes)/ array_sum($metasm)) * 100), 2, ',', '.') . '%' ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
</body>

<script src="../../recursos/js/jquery.min.js"></script>
<script src="../../recursos/js/bootstrap.min.js"></script>
<script src="../../recursos/js/scripts.js"></script>
<script src="../../recursos/js/Chart.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>
    var ctx = document.getElementById("mybar1");

    var labels = <?php echo JSON_ENCODE($labels) ?>;
    var dataset = <?php echo JSON_ENCODE($dataset) ?>;
    var dataset2 = <?php echo JSON_ENCODE($dataset2) ?>;

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels

                ,
            datasets: [{
                label: 'Produção',
                data: dataset,
                <?php if ((($somadia / array_sum($metas)) * 100) >= 100) : ?>
                    backgroundColor: '#4169E1',//AZUL
                <?php else : ?>
                    backgroundColor: '#FF4500',
                <?php endif ?>
                borderColor: 'rgba(255,255, 0, 0.8)',
                borderWidth: 2

            }, {
                label: 'Meta',
                data: dataset2,
                backgroundColor: 'rgba(0, 255, 0, 0.8)',
                borderColor: 'rgba(54, 162, 235, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false,
                position: 'bottom'
            },
            layout: {
                padding: 30
            },

            events: false,
            tooltips: {
                enabled: false
            },
            hover: {
                animationDuration: 0
            },
            animation: {
                duration: 1,
                onComplete: function() {
                    var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                    ctx.font = Chart.helpers.fontString(18, 'normal', 'Arial');
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';


                    this.data.datasets.forEach(function(dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data + '%', bar._model.x, bar._model.y - 5);
                        });
                    });
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

        },
        layout: {
            padding: {
                top: 50
            }
        }
    });


    var ctx = document.getElementById("mybar1");
    ctx.onclick = function(evt) {
        var activePoints = myChart.getElementsAtEvent(evt);
        // => activePoints is an array of points on the canvas that are at the same position as the click event.
        var pos = activePoints[0]['_index']
        console.log(labels[pos]);
    };

    var i = 0
    $(".metap").each(function() {
        pos = parseFloat($(this).text())
        meta = $(this).abrr
        var arr = [<?php foreach ($resumo as $r) {
                        echo '-' . ($r->meta1 / 1000) . ',';
                    } ?>]
        i = i + 1
        if (pos >= 0) {
            $(this).css("background-color", "#4169E1"); //AZUL
        } else if (pos < 0 && pos >= arr[i]) {
           // $(this).css("background-color", "rgba(255,255, 0, 0.8)"); //amarelo /* DESATIVADO PARA MANUTENÇÃO FUTURA */
        } else {
           // $(this).css("background-color", "#FF4500"); //vermelho
        }

    });
    $(".metadiaria").each(function() {
        pos = parseFloat($(this).text())
        if (pos >= 100) {
            $(this).css("background-color",  "#4169E1"); //AZUL
        } else if (pos < 100 && pos >= 50) {
            $(this).css("background-color", "rgba(255,255, 0, 0.8)"); //amarelo
        } else {
            $(this).css("background-color", "#FF4500"); //vermelho
        }

    });
    $(".metadiariat").each(function() {
        pos = parseFloat($(this).text())
        if (pos >= 100) {
            $(this).css("background-color",  "#4169E1"); //AZUL
        } else if (pos < 100 && pos >= 50) {
            $(this).css("background-color", "rgba(255,255, 0, 0.8)"); //amarelo
        } else {
            $(this).css("background-color", "#FF4500"); //vermelho
        }

    });
    $(".metadiariat2").each(function() {
        pos = parseFloat($(this).text())
        if (pos >= 100) {
            $(this).css("background-color",  "#4169E1"); //AZUL
        } else if (pos < 100 && pos >= 90) {
            $(this).css("background-color", "rgba(255,255, 0, 0.8)"); //amarelo
        } else {
            $(this).css("background-color", "lightsteelblue"); //normal
        }

    });
    $(".metam").each(function() {
        pos = parseFloat($(this).text())
        if (pos >= 0) {
            $(this).css("background-color",  "#4169E1"); //AZUL
        } else if (pos < 0 && pos >= -50) {
            $(this).css("background-color", "rgba(255,255, 0, 0.8)"); //amarelo
        } else {
            $(this).css("background-color", "#FF4500"); //vermelho
        }

    });
</script>
<script>
    //GRAFICO COMERCIAL
    var ctx = document.getElementById("mybar2");

    var labels = <?php echo JSON_ENCODE(["PRODUÇÃO", "FATURAMENTO"]) ?>;
    var dataset3 = <?php echo JSON_ENCODE($dataset3) ?>;
    var dataset4 = <?php echo JSON_ENCODE($dataset4) ?>;


    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Atingido',
                    data: dataset3,
                    <?php if ($dataset3[0] >= 100 && $dataset3[1] >= 100) : ?>
                        backgroundColor: '#4169E1',
                    <?php else : ?>
                        backgroundColor: '#FF4500',
                    <?php endif ?>
                    borderColor: 'rgba(255,255, 0, 0.8)',
                    borderWidth: 2
                }, {
                    label: 'Meta',
                    data: dataset4,
                    backgroundColor: 'rgba(0, 255, 0, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 0.8)',
                    borderWidth: 1
                }


            ]
        },
        options: {
            legend: {
                display: false,
                position: 'bottom'
            },
            layout: {
                padding: 30
            },

            events: false,
            tooltips: {
                enabled: false
            },
            hover: {
                animationDuration: 0
            },
            animation: {
                duration: 1,
                onComplete: function() {
                    var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                    ctx.font = Chart.helpers.fontString(18, 'normal', 'Arial');
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';


                    this.data.datasets.forEach(function(dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function(bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data + '%', bar._model.x, bar._model.y - 5);
                        });
                    });
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
    ctx.onclick = function(evt) {
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
    function listaHoverIn(elm) {
        $(elm).css('background-color', 'gold')
    }

    function listaHoverOut(elm) {
        $(elm).css('background-color', 'transparent')
    }
</script>
<script src="/recursos/js/scripts.js"></script>

</html>