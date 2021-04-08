<?php

// include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/Recursos/finalRat.php');
// include_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/Recursos/email/mailer.php');
include_once('testesControle.php');
header('Content-Type: text/html; charset=UTF-8');


$ret = TesteControle::getJson();

$cargas = $ret['cargas'];
$pedidos = $ret['pedidos'];

//echo json_encode($cargas);


?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Nova RAT</title>

		<meta name="description" content="Source code generated using layoutit.com">
		<meta name="author" content="LayoutIt!">

		<link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../recursos/css/style.css" rel="stylesheet">
		<link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">
		<link href="recursos/css/table.css" rel="stylesheet">
		</div>
	</head>

	<body>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" id="tabelaCargas">

                </div>
                <div class="col-md-6">
                    <table id="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">COD</th>
                                <th scope="col" style="width: 10%">Peso</th>
                                <th scope="col" style="text-align: center; width: 10%">Carregamento</th>
                                <th scope="col" style="width: 5%;">COD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="numped">1</td>
                                <td class="peso">100</td>
                                <td>
                                    <div class="listaCargas">

                                    </div>
                                </td>
                                <td>
                                <button type="submit" class="btn btn-sm btn-primary" onclick="inserir(this)">
											<i class="far fa-edit"></i>
										</button>
                                </div>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>    
    <script src="../../recursos/js/jquery.min.js"></script>
		<script src="../../recursos/js/jquery.redirect.js"></script>
		<script src="../../recursos/js/bootstrap.min.js"></script>
		<script src="../../recursos/js/scripts.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {
        getCargas();
        getCargasPeso();
    });


    function inserir(el){
        numped = el.parentElement.parentElement.getElementsByClassName("numped")[0].innerHTML;
        peso = el.parentElement.parentElement.getElementsByClassName("peso")[0].innerHTML;
        carga = el.parentElement.parentElement.getElementsByClassName("select")[0];
        
        $.ajax({
            type: 'POST',
            url: 'testesControle.php',
            data: {'action': 'getCargasPeso'},
            success: function(response){
                //console.log(response);
                /*arr = JSON.parse(response);
                console.log(arr);*/
            }
        });
    }


    function getCargas(){

        $.ajax({
            type: 'POST',
            url: 'testesControle.php',
            data: {'action': 'getCargas'},
            success: function(response){
                //console.log(response);
                arr = JSON.parse(response);
                console.log(arr);
                
                listaCargas = '<select class="select" style="width: 100%">'
                            +'<option value="-1">Carga</option>';

                $(".listaCargas").empty();
                

                for (i = 0; i < arr.length; i++) {
                    listaCargas += '<option value="'+arr[i]['carga']+'">'+arr[i]['carga']+'</option>'
                }
                listaCargas += '</select>'
                $(".listaCargas").append(listaCargas);
                
            }
        });
    }

    function getCargasPeso(){

        $.ajax({
            type: 'POST',
            url: 'testesControle.php',
            data: {'action': 'getCargasPeso'},
            success: function(response){
                //console.log(response);
                arr = JSON.parse(response);
                console.log(arr);
                

                $("#tabelaCargas").empty();
                tabelaCargas = '';
                for (i = 0; i < arr.length; i++) {
                    tabelaCargas += '<div class="row" style="border: 1px solid black">'
                            +'<div class="col-sm-8">Total '+arr[i]['carga']+':</div>'
                            +'<div class="col-sm-4">'+arr[i]['peso']+'</div>'
                    +'</div>';
                }
                $("#tabelaCargas").append(tabelaCargas);
                
            }
        });
    }
</script>



