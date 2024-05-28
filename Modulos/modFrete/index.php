<!DOCTYPE html>

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFrete/model/frete.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/modulos/modFrete/model/km.php');
session_start();
//primeiro dia do m~es anterior
$mesAnterior = strtotime("-1 month");
$dataini = date('Y-m-01', $mesAnterior);
$datafin = date('Y-m-t', $mesAnterior);
if (isset($_GET['dataini'], $_GET['datafin'])) {
    $dataini = $_GET['dataini'];
    $datafin = $_GET['datafin'];
    // se dataini maior que hoje retorna inicio do mes atual
    if (strtotime($dataini) > strtotime(date('Y-m-d'))) {
        $dataini = date('Y-m-01', $mesAnterior);
        $datafin = date('Y-m-t', $mesAnterior);
    }
}
$lista = Frete::Lista($dataini, $datafin);
$despesas = Frete::Despesas($dataini, $datafin);
$despesasInd = Frete::getDespesas2($dataini, $datafin);
$placa = Frete::Placa($dataini, $datafin);
$folha = Frete::getDadosFolha($dataini, $datafin);
$kmtemp = [];
$kms = Km::tratamentoDados(Frete::getKms($dataini, $datafin), $placa);
$kmIni = Km::tratamentoKms(Frete::getKmInicial($dataini, $datafin));
//var_dump($kmIni);
foreach ($kmIni[0] as $key => $value) {
    $kmtemp[] = $value;
}

$totall = [];
$resultadol = [];
$receitas = [];
$totalconta = 0;
$placaSize = count($placa);
$resultado = [];

date_default_timezone_set('America/Araguaina');
$titulo = "DRE Frete";

include($_SERVER["DOCUMENT_ROOT"] . '/includes/header.php');
?>

<head>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css" />
</head>
<style>
.sticky-header th {
    position: sticky!important;
    top: 0!important;
    z-index: 1!important;
    background-color: #212529!important;
    }
</style>

<body>
    <section class="container-fluid">
        <form class="row g-3 align-items-center my-1" action="index.php" method="get" >
            <div class="col-6 col-md-2 m-0 text-md-end text-center">
                <label class="col-form-label " for="dataini">Data Inicial</label>
            </div>
            <div class="col-6 col-md-3 m-0">
                <input class="form-control" type="date" class="form-date" name="dataini" value="<?=$dataini?>">
            </div>
            <div class="col-6 col-md-2 m-0 text-md-end text-center">
                <label for="datafin" class="labeldata">Data Final</label>
            </div>
            <div class="col-6 col-md-3 m-0">
                <input class="form-control" type="date" name="datafin" value="<?=$datafin?>">
            </div>
            <div class="col-12 col-md-2 m-0 text-center">
                <button class="btn btn-success" style="background-color: #0000FF" type="submit">Buscar</button>
            </div>
        </form>
        <div class="row">
            <div class="col p-1">
                <div class="">
                    <table class="table table-striped" style="font-size: 12px;">
                        <thead class="sticky-header">
                            <tr class="text-center text-bg-dark" style="font-size: 13px;">
                                <th scope="col">DESCRIÇÃO</th>
                                <?php foreach ($placa as $p) : ?>
                                    <th class="text-end" scope="col" id="TH<?= $p->placa ?>"><?= $p->placa ?> <?= $p->motorista ?></th>
                                    <?php $totall[$p->placa] = 0; $resultadol[$p->placa] = 0; $receitas[$p->placa] = 0; ?>
                                <?php endforeach; ?>
                                <th class="text-end" scope="col">TOTAL</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="text-center text-bg-dark">
                                <th colspan="<?= $placaSize + 2 ?>" class="text-center h4 text-white">DADOS GERAIS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="text-left">KM INICIAL</th>
                                <?php
                                foreach ($kmIni[0] as $km) :
                                        echo '<td class="text-end">' . number_format($km, 0, ',', '.') . '</td>';
                                endforeach;
                                ?>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th class="text-left">KM FINAL</th>
                                <?php
                                foreach ($kmIni[1] as $km) :
                                    echo '<td class="text-end">' . number_format($km, 0, ',', '.') . '</td>';
                                endforeach;
                                ?>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th class="text-left">LITRAGEM</th>
                                <?php
                                foreach ($placa as $k => $p) :
                                    $totalconta = $totalconta + $kms[$k]['litragem'];
                                    echo '<td class="text-end">' . number_format($kms[$k]['litragem'], 2, ',', '.') . '</td>';
                                endforeach;
                                echo '<td class="text-end">' . number_format($totalconta, 2, ',', '.') . '</td>';
                                $dd['litragem'] = $totalconta;
                                $totalconta = 0;
                                ?>

                            </tr>
                            <tr>
                                <th class="text-left">VALOR</th>
                                <?php
                                foreach ($placa as $k => $p) :
                                    $totalconta = $totalconta + $kms[$k]['valor'];
                                    echo '<td class="text-end">' . number_format($kms[$k]['valor'], 2, ',', '.') . '</td>';
                                endforeach;
                                echo '<td class="text-end">' . number_format($totalconta, 2, ',', '.') . '</td>';
                                $dd['valor'] = $totalconta;
                                $totalconta = 0;
                                ?>


                            </tr>
                            <tr>
                                <th class="text-left">KM RODADO</th>
                                <?php
                                foreach ($placa as $k => $p) :
                                    try{
                                        $totalconta = $totalconta + ($kmIni[1][$k] - $kmIni[0][$k]);
                                        echo '<td class="text-end">' . number_format($kmIni[1][$k] - $kmIni[0][$k], 0, ',', '.') . '</td>';
                                    }catch(Exception $e){
                                        echo '<td class="text-end">0</td>';
                                    }
                                    
                                endforeach;
                                echo '<td class="text-end">' . number_format($totalconta, 0, ',', '.') . '</td>';
                                $dd['kmrodado'] = $totalconta;
                                $totalconta = 0;
                                ?>
                            </tr>
                            <tr>
                                <th class="text-left">KM/L</th>
                                <?php
                                foreach ($placa as $k => $p) :
                                    if ($kms[$k]['litragem'] == 0)
                                        echo '<td class="text-end">' . number_format(0, 2, ',', '.') . '</td>';
                                    else {
                                        echo '<td class="text-end">' . number_format(($kmIni[1][$k] - $kmIni[0][$k]) / $kms[$k]['litragem'], 2, ',', '.') . '</td>';
                                    }

                                endforeach;
                                if ($dd['litragem'] == 0)
                                    echo '<td class="text-end">' . number_format(0, 2, ',', '.') . '</td>';
                                else
                                    echo '<td class="text-end">' . number_format($dd['kmrodado'] / $dd['litragem'], 2, ',', '.') . '</td>';
                                ?>
                            </tr>
                            <tr>
                                <th class="text-left">PREÇO MÉDIO</th>
                                <?php
                                foreach ($placa as $k => $p) :
                                    if ($kms[$k]['litragem'] == 0)
                                        echo '<td class="text-end">' . number_format(0, 2, ',', '.') . '</td>';
                                    else
                                        echo '<td class="text-end">' . number_format($kms[$k]['valor'] / $kms[$k]['litragem'], 3, ',', '.') . '</td>';
                                endforeach;
                                if ($dd['litragem'] == 0)
                                    echo '<td class="text-end">' . number_format(0, 2, ',', '.') . '</td>';
                                else
                                    echo '<td class="text-end">' . number_format($dd['valor'] / $dd['litragem'], 3, ',', '.') . '</td>';
                                ?>
                            </tr>


                        </tbody>
                        <thead>
                            <tr class="text-center text-bg-dark">
                                <th colspan="<?= $placaSize + 2 ?>" class="text-center h4 text-white">RECEITAS</th>
                            </tr>
                        </thead>
                        <tbody id="dadostabela">
                            <?php
                            foreach ($lista as $k => $l) :
                                echo '<tr>';
                                switch ($k):
                                    case 0:
                                        echo '<th>RECEITA DE FRETES</th>';
                                        break;
                                    case 1:
                                        echo '<th>PESO</th>';
                                        break;
                                    case 2:
                                        echo '<th>FRETE/KG</th>';
                                        break;
                                endswitch;
                                foreach ($l as $value) :
                                    echo '<td class="text-end">' .
                                        number_format($value, 2, ',', '.')
                                        . '</td>';
                                endforeach;
                                if ($k != 2)
                                    echo '<th class="text-end">' . number_format(array_sum($l), 2, ',', '.') . '</th>';
                                else
                                    echo '<th class="text-end">' . number_format(array_sum($lista[0]) / array_sum($lista[1]), 4, ',', '.') . '</th>';
                                echo '</tr>';
                            endforeach; ?>


                        </tbody>

                        <thead>
                            <tr class="text-center text-bg-dark">
                                <th colspan="<?= $placaSize + 2 ?>" class="text-center h4 text-white">DESPESAS DIRETAS</th>
                            </tr>
                        <tbody>
                            <?php foreach ($despesas as $k => $d) :
                                echo '<tr>';
                                echo '<th class="text-left">' . $d['CODCONTA'] . ' ' . mb_encode_numericentity(($d['CONTA']), array(0x80, 0xffff, 0, 0xffff), 'ASCII') . '</th>';
                                foreach ($placa as $pl) :
                                    if (isset($d["'$pl->placa'"])) {
                                        echo '<td class="text-end">' . number_format($d["'$pl->placa'"], 2, ',', '.') . '</td>';
                                        $totalconta = $totalconta + $d["'$pl->placa'"];
                                        $totall[$pl->placa] +=  $d["'$pl->placa'"];
                                        $resultadol[$pl->placa] +=  $d["'$pl->placa'"];
                                    } else
                                        echo '<td class="text-end"> 0,00</td>';

                                endforeach;
                                echo '<th class="text-end">' . number_format($totalconta, 2, ',', '.') . '</th>';
                                $totalconta = 0;
                                echo '</tr>';

                            endforeach;
                            
                            echo '<tr class="fw-bold"><th class="text-left">CONSOLIDADO</th>';
                            foreach ($totall as $k => $t)
                                echo '<td class="text-end"> ' . number_format($t, 2, ',', '.') . ' </td>';
                            echo '<th class="text-end">' . number_format(array_sum($totall), 2, ',', '.') . '</th></tr>';
                            ?>
                        </tbody>
                        <thead>
                            <tr class="text-center text-bg-dark">
                                <th colspan="<?= $placaSize + 2 ?>" class="text-center h4 text-white">DESPESAS INDIRETAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chaves = array_keys($totall);
                            $totall = array_fill_keys($chaves, 0);
                            foreach ($despesasInd as $k => $d) :
                                echo '<tr>';
                                echo '<th class="text-left">' . $d['CODCONTA'] . ' ' . mb_encode_numericentity(($d['CONTA']), array(0x80, 0xffff, 0, 0xffff), 'ASCII') . '</th>';
                                foreach ($placa as $pl) :
                                    if (isset($d["'$pl->placa'"])) {
                                        echo '<td class="text-end">' . number_format($d["'$pl->placa'"], 2, ',', '.') . '</td>';
                                        $totalconta = $totalconta + $d["'$pl->placa'"];
                                        $totall[$pl->placa] +=  $d["'$pl->placa'"];
                                        $resultadol[$pl->placa] +=  $d["'$pl->placa'"];
                                    } else
                                        echo '<td class="text-end"> 0,00</td>';

                                endforeach;
                                echo '<th class="text-end">' . number_format($totalconta, 2, ',', '.') . '</th>';
                                $totalconta = 0;
                                echo '</tr>';

                            endforeach;
                            echo '<tr class="fw-bold"><th class="text-left">CONSOLIDADO</th>';
                            foreach ($totall as $k => $t)
                                echo '<td class="text-end"> ' . number_format($t, 2, ',', '.') . ' </td>';
                            echo '<th class="text-end">' . number_format(array_sum($totall), 2, ',', '.') . '</th></tr>';
                            
                            ?>
                        </tbody>
                        <thead>
                            <tr class="text-center text-bg-dark">
                                <th colspan="<?= $placaSize + 2 ?>" class="text-center h4 text-white">FOLHA DE PAGAMENTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $chaves = array_keys($totall);
                             $totall = array_fill_keys($chaves, 0);
                                foreach($folha as $f):
                                    echo '<tr>';
                                    echo '<th class="text-left">' . $f['TIPO'] . '</th>';
                                    foreach ($placa as $pl) :
                                        if (isset($f[$pl->placa])) {
                                            echo '<td class="text-end">' . number_format($f[$pl->placa], 2, ',', '.') . '</td>';
                                            $totalconta = $totalconta + $f[$pl->placa];
                                            $totall[$pl->placa] +=  $f[$pl->placa];
                                            $resultadol[$pl->placa] +=  $f[$pl->placa];
                                        } else
                                            echo '<td class="text-end"> 0,00</td>';

                                    endforeach;
                                    echo '<th class="text-end">' . number_format($totalconta, 2, ',', '.') . '</th>';
                                    $totalconta = 0;
                                    echo '</tr>';
                                endforeach;
                                echo '<tr class="fw-bold"><th class="text-left">CONSOLIDADO</th>';
                            foreach ($totall as $k => $t)
                                echo '<td class="text-end"> ' . number_format($t, 2, ',', '.') . ' </td>';
                            echo '<th class="text-end">' . number_format(array_sum($totall), 2, ',', '.') . '</th></tr>';
                            ?>
                        </tbody>
                        <thead>
                            <tr class="text-center text-bg-dark">
                                <th colspan="<?= $placaSize + 2 ?>" class="text-center h4 text-white">RESULTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class=" fw-bold">TOTAL</th>
                            <?php
                                foreach ($lista as $k => $l)
                                    if($k == 0){
                                        foreach ($l as $p => $value)
                                            $receita[$p] = $value;                 
                                    }
                                foreach ($resultadol as $k => &$t)
                                    echo '<td class="text-end fw-bold"> ' . number_format($receita[$k] - $t, 2, ',', '.') .' </td>'; 
                                echo '<th class="text-end fw-bold">' . number_format(array_sum($receita) - array_sum($resultadol), 2, ',', '.') . '</th></tr>';
                                ?>
                            </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="js/frete.js"></script>
<?php include($_SERVER["DOCUMENT_ROOT"] . '/includes/footer.php'); ?>
</html>