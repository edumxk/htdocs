<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/modFrete/model/frete.php');
session_start();
$dataini = '2021-09-01'; //date('Y-m-01');
$datafin = '2021-09-30';//date('Y-m-t');
if (isset($_GET['data1'],$_GET['data2'])) {
    $dataini = $_GET['data1'];
	$datafin = $_GET['data2'];
}
$lista = Frete::Lista($dataini, $datafin);
$despesas = Frete::Despesas($dataini, $datafin);
$placa = Frete::Placa($dataini, $datafin);
$totpeso=0;
$totfrete=0;
?>
<?php
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Centro de Custo - Frete</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        
    </head>
    <body>
        <header>
            <div class="caixa">
                <h1><img src="/Recursos/src/Logo-Kokar5.png">
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php">Centro de Custo</a></li>
                    </ul>
                </nav>
            </div>    
        </header>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">
                            Plano de contas
                        </h1>
                    </div>
                </div>

                <div class="row">
                <div class="col-md-6">
                    <h3>
                        Receitas
                    </h3>
                    
                    <?php foreach($placa as $lp): ?>
                    <div class="col-md-12">
                    <h4><?php switch ($lp->placa){
                            case null: echo 'SEM CARGA';
                            break;
                            case 'AAA9999': echo 'RETIRA';
                            break;
                        }if($lp->placa=='AAA9999'){}
                        else{
                        echo $lp->placa;} ?></h4>
                        <table class="table table-sm table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Carga</th>
                                    <th>Rota</th>
                                    <th>Peso</th>
                                    <th>VlFrete</th>
                                    <th>VlFrete KG</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista as $l): 
                                    if($l->placa==$lp->placa):?>
                                <tr>
                                    <td><?=$l->numcar ?></td>
                                    <td><?= ($l->rota) ?></td>
                                    
                                    <td><?=$l->peso ?></td>
                                    <td><?=$l->vlFrete ?></td>
                                    <td><?=$l->vlFretekg ?></td>
                                </tr>
                                <?php $totpeso += $l->peso; $totfrete += $l->vlFrete;
                                endif;endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach;
                    echo $totpeso.' R$ '.$totfrete;
                    ?>

                </div>
                <div class="col-md-6">
                    <h3>
                        Despesas
                    </h3>
                    <h4><?php switch ($lp->placa){
                            case null: echo 'SEM CARGA';
                            break;
                            case 'AAA9999': echo 'RETIRA';
                            break;
                        }if($lp->placa=='AAA9999'){}
                        else{
                        echo $lp->placa;} ?></h4>
                    <?php foreach($placa as $lp): ?>
                    <div class="col-md-12">
                        <h4><?= ($lp->placa) ?></h4>
                        <table class="table table-sm table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Conta</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($despesas as $l): 
                                    if($l->placa==$lp->placa):?>
                                <tr>
                                    <td><?=$l->codconta ?></td>
                                    <td><?= ($l->conta) ?></td>
                                    <td><?=$l->totalcontas?></td>
                                </tr>
                                <?php 
                                endif;endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach?>
                </div>
                    
                </div>
            </div>
        </main>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    </body>
</html>