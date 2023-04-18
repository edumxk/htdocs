<?php
require_once('../../../model/pdf/fpdf.php');
require_once('../model/rat.php');
require_once('../model/formulario.php');
header('Content-Type: text/html; charset=utf-8');


$chamado = new Chamado();
$chamado = $chamado->getRat($_GET['numrat']);
$formulario = Formulario::getFormularioRat(Rat::getFormularioConsulta($_GET['numrat']));
$despesas = $chamado->getDespesas();
$ctd = $chamado->getCtd();

$pdf = new FPDF();

$pdf->AddPage();
/*Pagina tem 190 de largura*/
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(120,120,120);
$pdf->Cell(40,20,'',1,0,'', 1);
$pdf->Image('../../../recursos/src/Logo-kokar.png',20,11,20); 
$pdf->SetFillColor(200,220,255);
$pdf->MultiCell(0,10,utf8_decode("RAT - REGISTRO DE ATENDIMENTO TÉCNICO \n RAT: ".$chamado->numRat), 1,'C', 1);


$pdf->SetFillColor(200,200,200);
$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('ABERTURA:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(20,6,$chamado->dtAbertura,1);
$pdf->SetFont('Arial','B',8);    $pdf->Cell(100,6,utf8_decode('STATUS: '.$chamado->getStatus()),1,0,'C',1);          
$pdf->SetFont('Arial','B',8);   $pdf->Cell(30,6,utf8_decode('ENCERRAMENTO:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(20,6,$chamado->dtEncerramento,1);

$pdf->ln(6);

$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('RAZÃO:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(120,6,$chamado->cliente,1);
$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('COD:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(30,6,$chamado->codCli,1,1);



$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('FANTASIA:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(120,6,$chamado->fantasia,1);

$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('TEL:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(30,6,$chamado->telCliente,1,1);


$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('CNPJ:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(40,6,$chamado->cnpj,1);

$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('CIDADE:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(60,6,$chamado->cidade,1);

$pdf->SetFont('Arial','B',8);    $pdf->Cell(20,6,utf8_decode('UF:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(30,6,$chamado->uf,1,1);



$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('RCA:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(40,6,$chamado->rca,1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(25,6,$chamado->telRca,1);

$pdf->SetFont('Arial','B',8);   $pdf->Cell(30,6,utf8_decode('SOLICITANTE:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(50,6,$chamado->solicitante,1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,$chamado->telSolicitante,1);


$pdf->ln(6);

$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('PINTOR:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(40,6,$chamado->pintor,1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(25,6,$chamado->telPintor,1);
if(sizeof($formulario)>0){
    $pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('NOME:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(30,6,utf8_decode($formulario[0]->nome),1);
    $pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('TELEFONE:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($formulario[0]->telefone),1);
    $pdf->ln(6);
    $pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('EMAIL:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,6,utf8_decode($formulario[0]->email), 1, 0, 'C', 0);

    $pdf->ln(6);
    
}else{
    $pdf->Cell(0,6,'',1);
    $pdf->ln(6);
}

//$pdf->ln(6);
if($chamado->ALab != null){
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("ANÁLISE DE CHAMADO"),1,1,'C',true);

    $pdf->SetFillColor(200,200,200);

    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('DESC. PROBLEMA:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($chamado->problema),1,1,'L');


    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('PATOLOGIA:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($chamado->ALab->patologia),1,1,'L');

    if( strlen($chamado->ALab->parecer) < 90 ){
        $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('PARECER LAB.:'),1,0,'L',1);
        $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($chamado->ALab->parecer),1,1,'L');

    }else{
        $pdf->SetFont('Arial','B',8);   $pdf->Cell(0,6,utf8_decode('PARECER DO LABORATÓRIO:'),1,1,'L',1);
        $pdf->SetFont('Arial','',8);    $pdf->MultiCell(0,6,utf8_decode($chamado->ALab->parecer),1,'L');
    }


    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('RESULTADO:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    //$pdf->Cell(55,6,utf8_decode($chamado->ALab->conforme),1,'L');

    
    if($chamado->ALab->conforme == 'S'){
        $pdf->Cell(55,6,utf8_decode("PRODUTO DENTRO DOS PADRÕES"),1,'L');
    }elseif($chamado->ALab->conforme == 'N'){
        $pdf->Cell(55,6,utf8_decode("PRODUTO FORA DOS PADRÕES"),1,'L');
    }else{
        $pdf->Cell(55,6,utf8_decode("PENDENTE"),1,'L');
    }


    $pdf->SetFont('Arial','B',8);   $pdf->Cell(15,6,utf8_decode('RESP.:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(35,6,utf8_decode($chamado->ALab->nome),1,'L');
    $pdf->SetFont('Arial','B',8);   $pdf->Cell(15,6,utf8_decode('Data:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(35,6,utf8_decode($chamado->ALab->data),1,1,'C');

    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('PAR. TÉCNICO:'),1,0,'',1);
    $pdf->SetFont('Arial','',8); 

    if($chamado->ATec == 'P'){
        $pdf->Cell(0,6,utf8_decode("PENDENTE"),1,1,'L');
    }elseif($chamado->ATec == 'S'){
        $pdf->Cell(0,6,utf8_decode("RECLAMAÇÃO PROCEDENTE"),1,1,'L');
    }elseif($chamado->ATec == 'N'){
        $pdf->Cell(0,6,utf8_decode("RECLAMAÇÃO NÃO PROCEDENTE"),1,1,'L');
    }
    
    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('CUSTO TOTAL:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode("R$ ".number_format($chamado->getCorretivaTotal(),2,",",".")),1,1);


}





$pdf->ln(0);

/*Tabela de Produtos*/
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(0,6,utf8_decode("PRODUTOS POR PROBLEMAS"),1,1,'C',true);
$pdf->SetFont('Arial','B',8);   
$pdf->SetFillColor(200,200,200);
if(sizeof($chamado->produtos)>0){
    $pdf->SetFont('Arial','B',8);   
    $pdf->SetFillColor(200,200,200);

    $pdf->Cell(10,6,utf8_decode('COD'),1,0,'C',1); 
    $pdf->Cell(80,6,utf8_decode('PRODUTO'),1,0,'',1);
    $pdf->Cell(15,6,utf8_decode('LOTE'),1,0,'C',1); 
    $pdf->Cell(10,6,utf8_decode('QT'),1,0,'C',1); 
    $pdf->Cell(25,6,utf8_decode('FABRICAÇÃO'),1,0,'C',1); 
    $pdf->Cell(25,6,utf8_decode('VALIDADE'),1,0,'C',1); 
    $pdf->Cell(25,6,utf8_decode('VALOR'),1,0,'C',1); 

    $pdf->ln(6);

    $pdf->SetFont('Arial','',8);  
    $vltotal = 0;

    foreach($chamado->produtos as $p){
        $pdf->Cell(10,6,utf8_decode($p->codprod),1,0,'C'); 
        $pdf->Cell(80,6,utf8_decode($p->produto),1);
        $pdf->Cell(15,6,utf8_decode($p->numlote),1,0,'C'); 
        $pdf->Cell(10,6,utf8_decode($p->qt),1,0,'C'); 
        $pdf->Cell(25,6,utf8_decode($p->dtFabricacao),1,0,'C'); 
        $pdf->Cell(25,6,utf8_decode($p->dtValidade),1,0,'C'); 
        
        $pdf->Cell(25,6,number_format($p->getTotal(), 2, ',', '.'),1,1,'C'); 
        $vltotal +=$p->getTotal();
    }

    
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(140,6,'',1,0,'C',0); 
    $pdf->Cell(25,6,utf8_decode('TOTAL:'),1,0,'C',1); 
    $pdf->Cell(25,6,number_format($vltotal, 2, ',', '.'),1,0,'C'); 
}


// if($chamado->ATec != null){
//     $pdf->SetFont('Arial','B',12);
//     $pdf->SetFillColor(200,220,255);
//     $pdf->Cell(0,6,utf8_decode("Análise Técnica"),1,1,'C',true);

//     $pdf->SetFillColor(200,200,200);
//     $pdf->SetFont('Arial','B',8);    $pdf->Cell(35,6,utf8_decode('TESTES REALIZADOS:'),1,0,'',1);
//     $pdf->SetFont('Arial','',8);    $pdf->MultiCell(0,6,utf8_decode($chamado->ATec->testes),1,'L');
//     $pdf->SetFont('Arial','B',8);    $pdf->Cell(35,6,utf8_decode('PARECER TÉCNICO:'),1,0,'',1);
//     $pdf->SetFont('Arial','',8);    $pdf->MultiCell(0,6,utf8_decode($chamado->ATec->parecer),1,'L');

//     $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('RESULTADO:'),1,0,'',1);
//     $pdf->SetFont('Arial','',8);    $pdf->Cell(55,6,utf8_decode($chamado->ATec->getSituacao()),1,'L');
//     $pdf->SetFont('Arial','B',8);   $pdf->Cell(15,6,utf8_decode('RESP.:'),1,0,'',1);
//     $pdf->SetFont('Arial','',8);    $pdf->Cell(35,6,utf8_decode($chamado->ATec->nome),1,'L');
//     $pdf->SetFont('Arial','B',8);   $pdf->Cell(15,6,utf8_decode('DATA:'),1,0,'',1);
//     $pdf->SetFont('Arial','',8);    $pdf->Cell(35,6,utf8_decode($chamado->ATec->data),1,1,'C');
// }
//$pdf->ln(6);

$pdf->ln(6);

/*Tabela de Ações*/
if(sizeof($chamado->corretiva)>0){
    
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("AÇÃO CORRETIVA - SOLUÇÃO"),1,1,'C',true);
    $pdf->SetFont('Arial','B',8);   
    $pdf->SetFillColor(200,200,200);

    $pdf->Cell(30,6,utf8_decode('TIPO'),1,0,'C',1); 
    $pdf->Cell(10,6,utf8_decode('COD'),1,0,'',1);
    $pdf->Cell(90,6,('DESPESA'),1,0,'C',1); 
    $pdf->Cell(25,6,utf8_decode('VL UNIT'),1,0,'C',1); 
    $pdf->Cell(10,6,utf8_decode('QT'),1,0,'C',1); 
    $pdf->Cell(25,6,utf8_decode('VL TOTAL'),1,0,'C',1); 

    $pdf->ln(6);

    $pdf->SetFont('Arial','',8);  
    $vltotal = 0;

    foreach($chamado->corretiva as $c){
        $pdf->Cell(30,6,utf8_decode($c->tipo),1,0,'C'); 
        $pdf->Cell(10,6,utf8_decode($c->codprod),1,0,'C');
        $pdf->Cell(90,6,($c->despesa),1,0); 
        $pdf->Cell(25,6,number_format($c->valor, 2, ',', '.'),1,0,'C'); 
        $pdf->Cell(10,6,utf8_decode($c->qt),1,0,'C'); 
        $pdf->Cell(25,6,number_format($c->valor*$c->qt, 2, ',', '.'),1,1,'C'); 

        $vltotal +=$c->valor*$c->qt;
    }

    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(140,6,'',1,0,'C',0); 
    $pdf->Cell(25,6,utf8_decode('TOTAL:'),1,0,'C',1); 
    $pdf->Cell(0,6,number_format($vltotal, 2, ',', '.'),1,1,'C'); 
}

   
    $pdf->ln(3);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("CTD"),1,1,'C',true);


    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("MOTIVO DA RECLAMAÇÃO"),1,1,'C',true);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(7,6,utf8_decode(''),1,0,'C',0);
    $pdf->Cell(30,6,utf8_decode('LOGISTICA'),1,0,'C',1); 
    $pdf->Cell(7,6,utf8_decode(''),1,0,'C',0);
    $pdf->Cell(30,6,utf8_decode('COMERCIAL'),1,0,'C',1); 
    $pdf->Cell(7,6,utf8_decode('X'),1,0,'C',0);
    $pdf->Cell(30,6,utf8_decode('PRODUTO'),1,0,'C',1); 
    $pdf->Cell(35,6,utf8_decode('NFE VENDA'),1,0,'C',1); 
    $pdf->SetFont('Arial','',8);   
    $pdf->Cell(0,6,utf8_decode($ctd->nfe),1,0,'C',0); 
    $pdf->ln(6);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("INFORMAÇÕES DE VENDA"),1,1,'C',true);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetFont('Arial','B',8); 
    $pdf->Cell(20,6,utf8_decode('CARGA'),1,0,'',1); 
    $pdf->SetFont('Arial','',8);   
    $pdf->Cell(20,6,utf8_decode($ctd->numcar),1,0,'C',0); 
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(30,6,utf8_decode('DATA DA VENDA'),1,0,'',1);
    $pdf->SetFont('Arial','',8);   
    $pdf->Cell(25,6,utf8_decode($ctd->dataemissao),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(20,6,utf8_decode('VEICULO'),1,0,'',1);
    $pdf->SetFont('Arial','',8);   
    $pdf->Cell(0,6,utf8_decode($ctd->veiculo),1,0,'C',0);
    $pdf->ln(6);
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(20,6,utf8_decode('PLACA'),1,0,'',1);
    $pdf->SetFont('Arial','',8);   
    $pdf->Cell(20,6,utf8_decode($ctd->placa),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(30,6,utf8_decode('MOTORISTA'),1,0,'',1);
    $pdf->SetFont('Arial','',8);   
    $pdf->Cell(45,6,utf8_decode($ctd->motorista),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(20,6,utf8_decode('DESTINO'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,6,utf8_decode($ctd->destino),1,1,'C',0);


    if($ctd->nfedev != ''){
    $pdf->SetFillColor(200,220,255);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,6,utf8_decode("INFORMAÇÕES DA DEVOLUÇÃO"),1,1,'C',true);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('Nº DEVOLUÇÃO'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,6,utf8_decode($ctd->nfedev),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('DATA EMISSÃO'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(25,6,utf8_decode($ctd->dataemissaodev),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('DATA ENTRADA'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,6,utf8_decode($ctd->dataentradadev),1,1,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('VALOR ST'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,6,utf8_decode($ctd->vlst),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('VALOR IPI'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(25,6,utf8_decode($ctd->vlipi),1,0,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('VALOR TOTAL'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,6,utf8_decode($ctd->vltotal),1,1,'C',0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(30,6,utf8_decode('OBS'),1,0,'',1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,6,utf8_decode($ctd->obs),1,1,'C',0);

    
    }
    $pdf->ln(3);
    $pdf->SetFillColor(200,220,255);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,6,utf8_decode("AÇÃO CORRETIVA"),1,1,'C',true);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetFont('Arial','',8);
    if(strlen($ctd->acaocorretiva) > 110){
        foreach(str_split($ctd->acaocorretiva,110) as $linha){
            $pdf->Cell(0,6,utf8_decode($linha),1,3,'C', 0);
        }
    }
    else{
        $pdf->Cell(0,6,utf8_decode($ctd->acaocorretiva),1,3,'C', 0);
    }

    $pdf->ln(3);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("VISTORIA"),1,1,'C',true);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(15,6,utf8_decode('AVARIA:'),1,0,'C',1); $pdf->Cell(35,6,utf8_decode(''),1,0,'C',0);
    $pdf->Cell(15,6,utf8_decode('ESTOQUE:'),1,0,'C',1); $pdf->Cell(35,6,utf8_decode(''),1,0,'C',0);
    $pdf->Cell(35,6,utf8_decode('TROCA DE EMBALAGEM:'),1,0,'C',1); $pdf->Cell(0,6,utf8_decode(''),1,1,'C',0);
    
    if(count($despesas) > 0){
        $pdf->ln(3);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,utf8_decode("DESPESAS"),1,1,'C',true);
        $pdf->SetFillColor(200,200,200);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(50,6,utf8_decode('TIPO'),1,0,'C',1);
        $pdf->Cell(100,6,utf8_decode('DESCRIÇÃO'),1,0,'C',1);
        $pdf->Cell(0,6,utf8_decode('VALOR'),1,1,'C',1);
        $valorTotalDesp = 0;
        $pdf->SetFont('Arial','',8);
        foreach($despesas as $desp){
            $pdf->Cell(50,6,utf8_decode($desp->tipo),1,0,'C',0);
            $pdf->Cell(100,6,utf8_decode($desp->descricao),1,0,'C',0);
            $pdf->Cell(0,6,utf8_decode($desp->valor),1,1,'C',0);
            $valorTotalDesp += $desp->valorCalc;
        }

        //total
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(120,6,utf8_decode(''),1,0,'C',0);
        $pdf->Cell(30,6,utf8_decode('TOTAL'),1,0,'C',1);
        $pdf->Cell(0,6,'R$ ' . number_format($valorTotalDesp, 2, ',', '.'),1,1,'C',1);
    }

/*$filename="pdfGerados/rat".$chamado->numRat.".pdf";
$pdf->Output($filename,'F');*/

$pdf->Output();
?>
