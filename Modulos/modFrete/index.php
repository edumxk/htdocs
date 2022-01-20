<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/modulos/modFrete/model/frete.php');
session_start();
$dataini = date('Y-m-01');
$datafin = date('Y-m-t');
if (isset($_GET['dataini'])) {
    $dataini = date($_GET['dataini']);
	$datafin = date($_GET['datafin']);
}
//echo $dataini.' / '.$datafin;
$lista = Frete::Lista($dataini, $datafin);
$despesas = Frete::Despesas($dataini, $datafin);
$placa = Frete::Placa($dataini, $datafin);
$totpeso=0;
$totfrete=0;
$impressao=0;
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
                        <li><a href="home.php">Home</a></li>
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
                <?php 
                foreach($placa as $lp):?>
                <div class="row" style="margin: 50px 0; border: 1px solid black">
                <div class="col-md-6" style="border-right: 1px solid black;">
                   
                    <div class="col-md-12">
                    <h4><?php switch ($lp->placa){
                            case null: echo 'SEM CARGA'.' - Receita'; $impressao = 1;
                            break;
                            case 'AAA9999': echo 'RETIRA'.' - Receita'; $impressao = 1;
                            break;
                        }  if($impressao == 1){}
                        else{
                        echo $lp->placa.' - Receita';} ?></h4>
                        <table class="table table-sm table-bordered">
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
                            <?php 
                                foreach($lista as $l):
                                    if($l->placa == $lp->placa):?>
                                <tr>
                                    <td><?=$l->numcar ?></td>
                                    <td><?= ($l->rota) ?></td>
                                    
                                    <td><?=$l->peso ?></td>
                                    <td><?=$l->vlFrete ?></td>
                                    <td><?=$l->vlFretekg ?></td>
                                </tr>
                                <?php $totpeso += $l->peso; $totfrete += $l->vlFrete;
                                endif;endforeach?>
                                <tr style="background-color: black; color:white">
                                    <td colspan="2">TOTAIS</td>
                                    <td ><?= $totpeso?></td>
                                    <td ><?= $totfrete?></td>
                                    <td ></td>
                                </tr>
                                <?php  $totpeso=0; ?>
                                <?php  $totfrete=0; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                   // echo $totpeso.' R$ '.$totfrete;
                    ?>
                </div>

                <div class="col-md-6">
                   
                    <h4><?php switch ($lp->placa){
                            case null: echo 'SEM PLACA'.' - Despesa'; $impressao = 1;
                            break;
                            case 'AAA9999': echo 'RETIRA'.' - Despesa'; $impressao = 1;
                            break;
                        }
                        if($impressao == 1){}
                        else{
                        echo $lp->placa.' - Despesa';
                        } $impressao = 0;?></h4>
                  
                    <div class="col-md-12">
                        
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Conta</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                               foreach($despesas as $l):
                                if($l->placa == $lp->placa):?>
                                <tr>
                                    <td><?=$l->codconta ?></td>
                                    <td><?= ($l->conta) ?></td>
                                    <td><?=$l->totalcontas?></td>
                                </tr>
                                <?php $totpeso += $l->totalcontas;
                                endif;endforeach?>
                                <tr style="background-color: black; color:white">
                                    <td colspan="2">TOTAL</td>
                                    <td ><?= $totpeso?></td>
                                </tr>
                                <?php  $totpeso=0; ?>
                            </tbody>
                            </tbody>
                        </table>
                    </div>
                 
                </div>
               
                </div>
                <?php 
                endforeach?>  
            </div>
        </main>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    </body>
</html>