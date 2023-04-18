<?php
require_once  ('../../Model/pdf/fpdf.php');
require_once ('Controle/cargasControle2.php');
//define a timezone brasileira
date_default_timezone_set('America/Sao_Paulo');

class pdf{
    public $produtos;

    public static function gerarPdf($dados){
        $pdf = new FPDF();
        $pdf->AddPage();
        /*Pagina tem 190 de largura*/
        $pdf->SetFont('Arial','B',14);
        $pdf->SetFillColor(120,120,120);
        $pdf->Cell(40,20,'',1,0,'', 1);
        $pdf->Image('../../recursos/src/Logo-kokar.png',20,11,20); 
        $pdf->SetFillColor(200,220,255);
        if($dados[0]['DTSAIDA']!= null)
            $pdf->MultiCell(0,20,utf8_decode("PENDÊNCIAS - ".$dados[0]['NOME']." - ".$dados[0]['DTSAIDA']), 1,'C', 1);
        else
            $pdf->MultiCell(0,20,utf8_decode("PENDÊNCIAS - ".$dados[0]['NOME']), 1,'C', 1);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        //linha para iniciar impressao de produtos
        $pdf->Cell(0,10,utf8_decode("PRODUTOS"), 1, 1, 'C', 1);
        $pdf->SetFont('Arial','B',9);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(18,6,utf8_decode("CÓDIGO"), 1, 0, 'C', 1);
        $pdf->Cell(85,6,utf8_decode("DESCRIÇÃO"), 1,0, 'C', 1);
        $pdf->Cell(20,6,utf8_decode("PEDIDO"), 1, 0, 'C', 1);
        $pdf->Cell(20,6,utf8_decode("ESTOQUE"), 1, 0, 'C', 1);
        $pdf->Cell(22,6,utf8_decode("PENDENTE"), 1, 0, 'C', 1);
        $pdf->Cell(0,6,utf8_decode("PESO - KG"), 1, 1, 'C', 1);
        $pdf->SetFont('Arial','',9);
        foreach($dados as $produto){
            $pdf->Cell(18,6,utf8_decode($produto['CODPROD']), 1, 0, 'C', 1);
            $pdf->Cell(85,6,utf8_decode($produto['DESCRICAO']), 1, 0, 'L', 1);
            $pdf->Cell(20,6,utf8_decode($produto['QT']), 1, 0, 'C', 1);
            $pdf->Cell(20,6,utf8_decode($produto['QTDISP']), 1, 0, 'C', 1);
            //qtdisp - qt
            $pdf->Cell(22,6,utf8_decode($produto['QTDISP'] - $produto['QT']), 1, 0, 'C', 1);
            $pdf->Cell(0,6,utf8_decode(number_format($produto['PESO'],2,',','.')), 1, 1, 'R', 1);
        }
        //Totais
        $pdf->SetFont('Arial','B',11);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(103,6,utf8_decode("TOTAL"), 1, 0, 'C', 1);
        $pdf->Cell(20,6,array_sum(array_column($dados, 'QT')), 1, 0, 'C', 1);
        $pdf->Cell(20,6,array_sum(array_column($dados, 'QTDISP')), 1, 0, 'C', 1);
        $pdf->Cell(22,6,array_sum(array_column($dados, 'QTDISP')) - array_sum(array_column($dados, 'QT')), 1, 0, 'C', 1);
        $pdf->Cell(0,6,number_format(array_sum(array_column($dados, 'PESO')),2,',','.'), 1, 1, 'R', 1);
        if($dados[0]['NOME'] == 'CONSOLIDADO'){
            //TOTALIZAR VALORES DOS ITEMS
            $pdf->SetFont('Arial','B',11);
            $pdf->SetFillColor(200,220,255);
            $pdf->Cell(0,6,utf8_decode("R$ ".number_format($dados[0]['TOTAL'],2,',','.')), 1, 1, 'R', 1);
        }

        /* {"CODPROD":"783",
            "DESCRICAO":"TEXTURA TRADICIONAL 25KG AMARELO CANARIO",
            "QT":"40",
            "QTDISP":"0",
            "PESO":"1031.720",
            "CODPRODP":null,
            "QTPROD":"",
            "CODPRODUCAO":null,
            "PREVISAO":"",
            "STATUS":""}*/
        //finalizar e imprimir
        //imprimir dada e hora da impressao no rodape
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,utf8_decode("Impresso em: ".date('d/m/Y H:i:s')), 1, 1, 'L', 1);
       
        $pdf->Output();

    }
}

if(isset($_GET['action'])){
    if($_GET['action'] == 'pdf'){
        $dados = CargasControle::getPendenciasPdf($_GET['numcar']);
        pdf::gerarPdf($dados);

    }
    if($_GET['action'] == 'consolidado'){
        $cargas = $_GET['numcar'];
        $dados = CargasControle::getPendenciasPdfConsolidado($cargas);
        foreach($dados as $key => $value){
            $dados[$key]['NOME'] = 'CONSOLIDADO';
            //tansformando dados no padrão
            $dados[$key]['DTSAIDA'] = null;
            $dados[$key]['QTDISP'] = $value['QTEST'];
            $dados[$key]['PESO'] = $value['PESOPENDENTE'];
            $dados[$key]['TOTAL'] =  $_GET['soma'];
            

        }
        pdf::gerarPdf($dados);
    }

}