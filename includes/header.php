<?php 
if(!isset($_SESSION['coduser'])){
    header("Location: /index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
    <link rel="stylesheet" href="/Recursos/css/reset.css">
    <link rel="stylesheet" href="/Recursos/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <title>Kokar | <?php if(isset($titulo)) echo $titulo; else "Kokar | Intranet";?></title>
</head>
<header>
    <div class="caixa d-flex justify-content-between">
        <img src="/Recursos/src/Logo-Kokar5.png">
        <nav class="mx-3 pt-5">
            <ul>
                <li><a href="/modulos/modFrete/home.php">Home</a></li>
                <li><a href="/modulos/modFrete/">DRE frete</a></li>
                <li><a href="/modulos/modFrete/lancamentos/<?php if($_SESSION['coduser']==15) echo 'logistica/'?>">Lançamentos</a></li>
                <li><a href="/home.php">Sair</a></li>
            </ul>
        </nav>
    </div>    
</header>