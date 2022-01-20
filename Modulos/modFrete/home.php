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
                        <li><a href="../../home.php">Home</a></li>
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
                    <div class="col-md-12" style="text-align: center;">
                        <form action="index.php" method="get">
                            <label for="dataini" class="labeldata">Data Inicial</label>
                            <input type="date" name="dataini" value="<?=date('Y-m-01')?>">
                            <label for="datafin" class="labeldata">Data Final</label>
                            <input type="date" name="datafin" value="<?=date('Y-m-t')?>">
                            <label for="placa" class="labeldata">Placa</label>
                            <input type="text" placeholder="Falta implementar" name="placa">
                            <button type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    </body>
</html>