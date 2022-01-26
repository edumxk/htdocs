<!DOCTYPE html>

<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Modulos/Lab/modContraTipo/control/controle.php');
session_start();
if($_SESSION['nome']== null){
    header("location:\..\..\home.php");
}
$produtos = ContraTipoControle::getProdutos(1276, 1);
//var_dump($produtos);
header("refresh: 180;");
date_default_timezone_set('America/Araguaina');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Contratipo</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" href="css/contratipo.css">  
    </head>
<body>
    <main>
        <section class="navegacao">
            <nav id="home" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="/homelab.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="index.php">Contratipo</a>
                    </li>
                </ol>
            </nav>
        </section>
        <section class="principal">
            <section class="itens">
                <div class="dropdown">
                    <button class="dropbtn">Tintas</button>
                    <div class="dropdown-content">
                        <div class="menu-itens unselectable"><input id="1" type="checkbox"><label for="1">Fit</label></div>
                        <div class="menu-itens unselectable"><input id="2" type="checkbox"><label for="2">Economica</label></div>
                        <div class="menu-itens unselectable"><input id="3" type="checkbox"><label for="3">Standard</label></div>
                        <div class="menu-itens unselectable"><input id="4" type="checkbox"><label for="4">Premium</label></div>
                        <div class="menu-itens unselectable"><input id="5" type="checkbox"><label for="5">Piso</label></div>
                        <div class="menu-itens unselectable"><input id="15" type="checkbox"><label for="15">Resina</label></div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Revestimentos</button>
                    <div class="dropdown-content">
                        <div class="menu-itens unselectable"><input id="6" type="checkbox"><label for="6">Texturas</label></div>
                        <div class="menu-itens unselectable"><input id="7" type="checkbox"><label for="7">Sublime</label></div>
                        <div class="menu-itens unselectable"><input id="8" type="checkbox"><label for="8">Rev. Marmore</label></div>
                        <div class="menu-itens unselectable"><input id="9" type="checkbox"><label for="9">Rev. Cimento</label></div>
                        <div class="menu-itens unselectable"><input id="10" type="checkbox"><label for="10">Minerais</label></div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Sintéticos</button>
                    <div class="dropdown-content">
                        <div class="menu-itens unselectable"><input id="11" type="checkbox"><label for="11">Esmalte</label></div>
                        <div class="menu-itens unselectable"><input id="12" type="checkbox"><label for="12">Verniz</label></div>
                        <div class="menu-itens unselectable"><input id="13" type="checkbox"><label for="13">Fundo Complemento</label></div>
                        <div class="menu-itens unselectable"><input id="14" type="checkbox"><label for="14">Pasta</label></div>
                        
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Complementos</button>
                    <div class="dropdown-content">
                        <div class="menu-itens unselectable"><input id="16" type="checkbox"><label for="16">Acrílica/Fit</label></div>
                        <div class="menu-itens unselectable"><input id="17" type="checkbox"><label for="17">Corrida/Fit</label></div>
                        <div class="menu-itens unselectable"><input id="18" type="checkbox"><label for="18">Selador/Fit</label></div>
                        <div class="menu-itens unselectable"><input id="19" type="checkbox"><label for="19">Fundo Preparador/Fit</label></div>
                        
                    </div>
                </div>                    
            </section>
            <section class="conteudo">
                
                <div class="conteudo-tabela">
                    <div class="busca-itens">    
                        <label for="produto">Pesquisar Produto</label><input type="text" id="produto"/>
                        <div class="botao">    
                           <button>Concluir</button>
                        </div> 
                    </div> 
                     

                    <table class="conteudo__produtos" id="tabelas-busca">
                        <thead>
                            <tr>
                                <th><input class="checkbox" type="checkbox" id="select-all"/></th>
                                <th>Codprod</th>
                                <th>Descricao</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($produtos as $p):?>
                            <tr class="produtos">
                                <td><input class="checkbox" type="checkbox" id="codprod"/></td>
                                <td><?= $p->codprodmaster?></td>
                                <td class="texto"><?= $p->produto?></td>
                                <td class="texto"><?= $p->categoria?></td>
                            </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="../../../recursos/js/jquery.tablesorter.js"></script>
    
        <script>
            window.onload = function() {
                $("#tabelas-busca").tablesorter();
                $(".loader").toggle();
                //$(".container").toggle();

                $("#produto").on("keyup", function() {
                    var value = $(this).val().toUpperCase();
                    
                    $(".produtos").each(function() {
                        if(!$(this).text().toUpperCase().includes(value)){
                            $(this).hide()
                        }
                        if($(this).text().toUpperCase().includes(value)){	
                            $(this).show()
                        }
                    });
                });
            }
        </script>
    </body>
</html>
