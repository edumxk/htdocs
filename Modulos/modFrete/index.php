<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/modFrete/model/frete.php');
session_start();
$dataini = '2023-01-01'; //date('Y-m-01');
$datafin = '2023-01-31';//date('Y-m-t');
if (isset($_GET['dataini'],$_GET['datafin'])) {
    $dataini = $_GET['dataini'];
	$datafin = $_GET['datafin'];
    // se dataini maior que hoje retorna inicio do mes atual
    if (strtotime($dataini) > strtotime(date('Y-m-d'))) {
        $dataini = date('Y-m-01');
        $datafin = date('Y-m-t');
    }
    
}
$lista = Frete::Lista($dataini, $datafin);
$despesas = Frete::getDespesas2($dataini, $datafin);
$placa = Frete::Placa($dataini, $datafin);
$contas = Frete::getContas($dataini, $datafin);
$totpeso=0;
$totfrete=0;
$tot= 0;
$arrays = [];
$temp = [];
$totall = 0;
?>
<?php
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Centro de Custo - Frete</title>
        <link rel="stylesheet" href="css/reset.css"/>
        <link rel="stylesheet" href="css/index.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@500&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="caixa">
                <h1><img src="/Recursos/src/Logo-Kokar5.png"/>
                <nav>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="index.php">Centro de Custo</a></li>
                    </ul>
                </nav>
            </div>    
        </header>
        <div class="container-fluid">
            <div class="tabela">
                <h3 style="padding: 10px;">PERIODO:  <?= Formatador::formatador2($dataini) ?> A <?= Formatador::formatador2($datafin) ?></h3>
                <table class="table table-sm" id="tabela">
                    <thead>
                        <tr>
                            <th style="text-align:left">RECEITAS</th>
                        <?php foreach($placa as $p): ?>
                            <th style="font-stretch: 50%; text-align: right"> <?= $p->placa ?> </th>
                        <?php endforeach; ?>
                            <th> TOTAL </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-stretch: 50%; text-align: left"> PESO BRUTO </td>
                        <?php foreach($lista as $l): ?>
                            <td><?= number_format($l->peso, 2,',', '.') ?> </td>
                        <?php endforeach; ?>
                            <td style="font-weight: bold;"> <?php foreach($lista as $l){
                                $totpeso += $l->peso;
                            } 
                            echo number_format($totpeso, 2,',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-stretch: 50%; text-align: left"> VALOR FRETE </td>
                        <?php foreach($lista as $l): ?>
                            <td><?= number_format($l->vlFrete, 2,',', '.') ?> </td>
                        <?php endforeach; ?>
                            <td style="font-weight: bold;"> <?php foreach($lista as $l){
                                $totfrete += $l->vlFrete;
                            } 
                            echo number_format($totfrete, 2,',', '.'); ?>
                        </tr>
                        <tr>
                            <td style="font-stretch: 50%; text-align: left"> VALOR FRETE/KG </td>
                        <?php foreach($lista as $l): ?>
                            <td><?php if(is_nan($l->vlFretekg))echo 0;else echo number_format($l->vlFretekg, 3,',', '.') ?> </td>
                        <?php endforeach; ?>
                            <td style="font-weight: bold;"> <?php if($totpeso==0)echo 0;else echo number_format($totfrete/$totpeso, 3,',', '.'); ?>
                        </tr>
                        
                    </tbody>
                    <thead>
                        <tr>
                            <th>DESPESAS</th>
                        <?php foreach($placa as $p): ?>
                            <th style="font-stretch: 50%; text-align: right"> <?= $p->placa ?> </th>
                        <?php endforeach; ?>
                            <th> TOTAL </th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($despesas as $d): ?>
                        <tr>
                            <td style="font-family: 'Sofia Sans Extra Condensed', sans-serif; text-align: left; font-size:1.1rem "> <?= $d['CODCONTA'] . ' - ' . utf8_encode($d['CONTA'])?> </td>
                        <?php foreach($placa as $p): ?>
                            <td> <?php echo number_format($d["'$p->placa'"], 2,',', '.'); $tot += $d["'$p->placa'"]; $temp[$p->placa] = floatval($d["'$p->placa'"]); ?> </td>
                        <?php endforeach; ?>
                            <td style="font-weight: bold;"> <?= number_format($tot, 2,',', '.'); $tot=0;  $arrays[] = $temp; $temp = []; ?> </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th > TOTAL </th>
                            <?php foreach($placa as $p): ?>
                                <?php foreach($arrays as $a): ?>
                                    <?php $tot += $a[$p->placa]; ?>
                                <?php endforeach; ?>
                                <th> <?= number_format($tot, 2,',', '.'); $totall += $tot; $tot=0; ?> </th>
                            <?php endforeach; ?>
                            <th> <?= number_format($totall, 2,',', '.');?> </th>
                        </tr>
                    </tfoot>    
                </table>
            </div>
        </div>
    </body>
</html>
<style>
    tfoot th, td{
        text-align: right;
    }
    tfoot th{
        font-weight: bold;
    }
</style>
