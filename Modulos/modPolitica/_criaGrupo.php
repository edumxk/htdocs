<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once ('model/produto.php');

$aux = file_get_contents("tabela.json");

$conteudo = json_decode($aux, true);
$linhas = [];
foreach($conteudo as $c){

    for($e=1; $e<=5; $e++){
        if(!$c["EMB$e"]==""){
            $p = new Produto;
            $p->novoProduto($c, $e); 
            array_push($linhas, $p); 
        }
    }
}
echo json_encode($linhas);

// // Descomente essas linha para salvar no banco os grupos definidos.
// foreach($linhas as $l){
//     Produto::criaGrupos($l);
// }




?>