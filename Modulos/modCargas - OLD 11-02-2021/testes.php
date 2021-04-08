<?php
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Model/carga.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Model/pedido.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Model/produto.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modCargas/Controle/cargasControle.php');
    

    $cargas = [372,373];
    var_dump(Carga::getSaldoCarga($cargas));