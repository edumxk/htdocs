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
$despFinal=0;
$totDespesa=0;
$finalPeso=0;
$finalFrete=0;
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
                    <h4><?php 
                        if ($lp->placa == null){
                            echo 'SEM CARGA'.' - Receita'; $impressao = 1;
                        }
                        else if($lp->placa == 'AAA9999' && $lp->motorista == 'TRANSUL'){
                            echo 'TRANSUL'.' - Receita'; $impressao = 1; $lp->placa = 'TRANSUL';
                    
                        }else if($lp->placa == 'AAA9999'){
                            echo 'RETIRA'.' - Receita'; $impressao = 1;
                        }
                          if($impressao == 1){}
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
                                    if($l->placa == 'AAA9999' and $l->motorista == 'TRANSUL')
                                        $l->placa = 'TRANSUL';
                                    
                                    if($l->placa == $lp->placa):?>
                                <tr>
                                    <td><?=$l->numcar ?></td>
                                    <td><?= ($l->rota) ?></td>
                                    
                                    <td style="text-align: right"><?= number_format($l->peso,2, ',', '.') ?></td>
                                    <td style="text-align: right"><?= number_format($l->vlFrete,2, ',', '.') ?></td>
                                    <td><?= number_format($l->vlFretekg,3, ',', '.') ?></td>
                                </tr>
                                <?php $totpeso += $l->peso; $totfrete += $l->vlFrete;
                                endif;endforeach?>
                                <tr style="background-color: black; color:white">
                                    <td colspan="2">TOTAIS</td>
                                    <td style="text-align: right" ><?= number_format($totpeso,2, ',', '.')?></td>
                                    <td style="text-align: right" ><?= number_format($totfrete,2, ',', '.')?></td>
                                    <td ><?php if($totpeso == 0) echo '0,00'; else echo
                                    number_format($totfrete/$totpeso,3, ',', '.')?> </td>
                                </tr>
                                <?php  
                                $finalPeso += $totpeso;
                                $finalFrete += $totfrete;
                                $totpeso=0; 
                                $totfrete=0; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                   // echo $totpeso.' R$ '.$totfrete;
                    ?>
                </div>

                <div class="col-md-6">
                   
                    <h4><?php  
                        if ($lp->placa == null){
                            echo 'SEM CARGA'.' - Despesa'; $impressao = 1;
                        }
                        else if($lp->placa == 'TRANSUL'){
                            echo 'TRANSUL'.' - Despesa'; $impressao = 1;
                    
                        }else if($lp->placa == 'AAA9999'){
                            echo 'RETIRA'.' - Despesa'; $impressao = 1;
                        }
                          if($impressao == 1){}
                        else{
                        echo $lp->placa.' - Despesa';
                        } $impressao = 0;?>
                    </h4>
                  
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
                                if($l->projeto == 33){
                                    $l->placa = 'TRANSUL';
                                }
                                
                                if( $l->placa == $lp->placa ):?>
                                <tr>
                                    <td><?=$l->codconta ?></td>
                                    <td><?= ($l->conta) ?></td>
                                    <td style="text-align: right" ><?=number_format($l->totalcontas,2, ',', '.')?></td>
                                </tr>
                                <?php $totDespesa += $l->totalcontas;
                                endif;endforeach?>
                                <tr style="background-color: black; color:white">
                                    <td colspan="2">TOTAL</td>
                                    <td style="text-align: right" ><?= number_format($totDespesa,2, ',', '.') ?></td>
                                </tr>
                                <?php $despFinal += $totDespesa;  $totDespesa=0; ?>
                            </tbody>
                            </tbody>
                        </table>
                    </div>
                 
                </div>
               
                </div>
                <?php 
                endforeach?>  
            <div class="row" style="font-weight: bold;">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="3">
                                        RECEITAS
                                    </th>
                                </tr>
                                <tr>
                                    <th>Peso</th>
                                    <th>VlFrete</th>
                                    <th>VlFrete KG</th>
                                </tr>
                            <tbody>
                                <tr>
                                    
                                    <td style="text-align: right">
                                        <?= 'R$ ' . number_format($finalPeso,2, ',', '.')?>
                                    </td>
                                    <td style="text-align: right">
                                        <?= number_format($finalFrete,2, ',', '.').' KG'?>
                                    </td>
                                    <td >
                                        <?php if($finalPeso == 0) echo '0,00'; else echo number_format($finalFrete/$finalPeso,3, ',', '.').' R$/KG'?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    
                                    <th colspan="2">
                                        DESPESAS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td >
                                        TOTAL
                                    </td>
                                    <td style="text-align: right">
                                        <?= 'R$ ' . number_format($despFinal,2, ',', '.')?>
                                    </td>
                                </tr>
                                <thead>
                                    <tr>
                                        
                                        <th colspan="2">
                                            DESPESA POR PESO
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            TOTAL
                                        </td>
                                        <td style="text-align: right">
                                            <?php if($finalPeso == 0) echo '0,00'; else echo number_format($despFinal/$finalPeso,3, ',', '.') .' R$/KG'?>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            </div>
            
        </main>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    </body>
</html>