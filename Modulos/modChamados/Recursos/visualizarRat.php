<?php
require_once('../../../model/pdf/fpdf.php');
require_once('../model/rat.php');
require_once('../model/formulario.php');
header('Content-Type: text/html; charset=utf-8');


$chamado = new Chamado();
$chamado = $chamado->getRat($_GET['numrat']);
$formulario = Formulario::getFormularioRat(Rat::getFormularioConsulta($_GET['numrat']));

//var_dump(   $formulario);



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
$pdf->SetFont('Arial','',8);    $pdf->Cell(50,6,mb_strtoupper($chamado->solicitante),1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,$chamado->telSolicitante,1);


$pdf->ln(6);

$pdf->SetFont('Arial','B',8);   $pdf->Cell(20,6,utf8_decode('PINTOR:'),1,0,'',1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(40,6,mb_strtoupper($chamado->pintor),1);
$pdf->SetFont('Arial','',8);    $pdf->Cell(25,6,$chamado->telPintor,1);
if(sizeof($formulario)>0){
$pdf->Cell(30,6,(str_split(utf8_decode($formulario[0]->nome),16)[0]),1);
$pdf->Cell(50,6,mb_strtoupper($formulario[0]->email),1);
$pdf->Cell(0,6,mb_strtoupper($formulario[0]->telefone),1);


$pdf->ln(6);


    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("Formulário de Atendimento"),1,1,'C',true);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetFillColor(200,200,200);
    foreach($formulario as $f)
    {
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(80,6,utf8_decode(mb_strtoupper($f->textoPrincipal)),1,0,'',1); //string upper case utf8_decode(strtoupper($f->textoPrincipal))
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,6,utf8_decode(mb_strtoupper($f->textoOpcao)),1,1);
    }
    
}else{
    $pdf->Cell(0,6,'',1);
    $pdf->ln(6);
}

//$pdf->ln(6);
if($chamado->ALab != null){
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("Análise do Chamado"),1,1,'C',true);

    $pdf->SetFillColor(200,200,200);

    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('DESC. PROBLEMA:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($chamado->problema),1,1,'L');


    $pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('PATOLOGIA:'),1,0,'',1);
    $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($chamado->ALab->patologia),1,1,'L');

    $pdf->SetFont('Arial','B',8);   $pdf->Cell(0,6,utf8_decode('PARECER DO LABORATÓRIO:'),1,1,'L',1);


    //se a string for maior que 88 caracteres na primeira linha, validar para quebra de linha em espaços em branco
    if(strlen($chamado->ALab->parecer) > 88){
        $pdf->SetFont('Arial','',8);    $pdf->MultiCell(0,6,utf8_decode($chamado->ALab->parecer),1,'L');
    }else{
        $pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode($chamado->ALab->parecer),1,1,'L');
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
    
    //$pdf->SetFont('Arial','B',8);   $pdf->Cell(35,6,utf8_decode('CUSTO TOTAL:'),1,0,'',1);
    //$pdf->SetFont('Arial','',8);    $pdf->Cell(0,6,utf8_decode("R$ ".number_format($chamado->getCorretivaTotal(),2,",",".")),1,1);


}





$pdf->ln(0);

/*Tabela de Produtos*/
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(0,6,utf8_decode("Produtos com Problema"),1,1,'C',true);
$pdf->SetFont('Arial','B',8);   
$pdf->SetFillColor(200,200,200);
if(sizeof($chamado->produtos)>0){
    $pdf->SetFont('Arial','B',8);   
    $pdf->SetFillColor(200,200,200);

    $pdf->Cell(10,6,utf8_decode('COD'),1,0,'C',1); 
    $pdf->Cell(100,6,utf8_decode('PRODUTO'),1,0,'',1);
    $pdf->Cell(20,6,utf8_decode('LOTE'),1,0,'C',1); 
    $pdf->Cell(15,6,utf8_decode('QT'),1,0,'C',1); 
    $pdf->Cell(0,6,utf8_decode('VALIDADE'),1,0,'C',1); 
    //$pdf->Cell(25,6,utf8_decode('VALOR'),1,0,'C',1); 

    $pdf->ln(6);

    $pdf->SetFont('Arial','',8);  
    $vltotal = 0;

    foreach($chamado->produtos as $p){
        $pdf->Cell(10,6,utf8_decode($p->codprod),1,0,'C'); 
        $pdf->Cell(100,6,utf8_decode($p->produto),1);
        $pdf->Cell(20,6,utf8_decode($p->numlote),1,0,'C'); 
        $pdf->Cell(15,6,utf8_decode($p->qt),1,0,'C'); 
        $pdf->Cell(0,6,utf8_decode($p->dtValidade),1,1,'C'); 
        
        //$pdf->Cell(25,6,number_format($p->getTotal(), 2, ',', '.'),1,1,'C'); 
        //$vltotal +=$p->getTotal();
    }

    
    //$pdf->Cell(140,6); 
    //$pdf->SetFont('Arial','B',8);   
    //$pdf->Cell(25,6,utf8_decode('TOTAL:'),1,0,'C',1); 
    //$pdf->Cell(25,6,number_format($vltotal, 2, ',', '.'),1,0,'C'); 
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
    $pdf->Cell(0,6,utf8_decode("Ação Corretiva - Solução"),1,1,'C',true);
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

    $pdf->Cell(140,6); 
    $pdf->SetFont('Arial','B',8);   
    $pdf->Cell(25,6,utf8_decode('TOTAL:'),1,0,'C',1); 
    $pdf->Cell(25,6,number_format($vltotal, 2, ',', '.'),1,1,'C'); 

    $pdf->ln(2);


}



    // Go to 1.5 cm from bottom
    //$pdf->SetY(-55);



    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(0,6,utf8_decode("Departamento Técnico"),1,1,'C',true);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetFont('Arial','',12);    $pdf->Cell(0,6,'mail:departamento.tecnico@kokar.com.br',1,0,'C',0);









/*$filename="pdfGerados/rat".$chamado->numRat.".pdf";
$pdf->Output($filename,'F');*/

$pdf->Output();
?>
