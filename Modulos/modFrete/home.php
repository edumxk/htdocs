<?php
session_start();
$titulo = "Frete";
if(isset($_GET['date']))
    $date = $_GET['date'];
else   
    $date = date('Y-m-t', strtotime('last month'));

include($_SERVER["DOCUMENT_ROOT"] . '/includes/header.php');
if($_SESSION['codsetor'] > 1 && $_SESSION['coduser'] != 39 && $_SESSION['coduser'] != 15){
    header("Location: /home.php");
}

?>
<head>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css" />
</head>
<body>
    <section class="container-fluid">
        <div class="row">
            <div class="col text-center m-3">
                <div class="row row-cols-1 row-cols-lg-3 mt-4 g-4">
                    <div class="col">
                        <div class="card h-100 m-auto" style="width: 300px; background-color: #0000ff;">
                            <a href="/modulos/modFrete/">
                                <div class="card-body">
                                    <h5 class="card-title text-light">Demonstrativo de Resultado</h5>
                                    <p class="card-text text-light">DRE</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col" <?php if($_SESSION['codsetor'] > 1 && $_SESSION['codsetor'] != 14 ) echo 'hidden';?>>
                        <div class="card h-100 m-auto" style="width: 300px; background-color: #0000ff;">
                        <a href="/modulos/modFrete/lancamentos">
                            
                                <div class="card-body">
                                    <h5 class="card-title  text-light">Lançamentos RH</h5>
                                    <p class="card-text  text-light">Lançamentos de Folha de Pagamento.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col" <?php if($_SESSION['codsetor'] > 1 && $_SESSION['coduser'] != 15 ) echo 'hidden';?>>
                        <div class="card h-100 m-auto" style="width: 300px; background-color: #0000ff;">
                            <a href="/modulos/modFrete/lancamentos/logistica/">
                                
                                <div class="card-body">
                                    <h5 class="card-title text-light">Lançamentos Logistica</h5>
                                    <p class="card-text text-light">Registrar a quilometragem final de cada mês de um veículo.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
    <script src="js/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
    </body>
</html>