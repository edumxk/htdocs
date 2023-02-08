<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Model/SqlOracle.php');

$dados = new SqlOra();
$json = $dados->select("SELECT codcli, 
case when fantasia like '%*%'
then cliente else
c.fantasia end as name, d.nomecidade city, d.uf state,
c.endercob||' '|| c.complementocob ||', '||c.cepcob address, c.telcob phone, c.emailnfe email 
from kokar.pcclient c
inner join kokar.pccidade d on d.codcidade = c.codcidade
where c.dtexclusao is null and (c.dtultcomp > '01/01/2022')
and c.consumidorfinal = 'N'");


$ret = ['stores' => []];
foreach($json as $j){
    $ret['stores'][] = [
    'name' => utf8_encode($j['NAME']),
    'city' => utf8_encode($j['CITY']),
    'state' => $j['STATE'],
    'pin' => [
        'address' => utf8_encode($j['ADDRESS']),
        'phone' => utf8_encode($j['PHONE']),
        'email' => utf8_encode($j['EMAIL'])]
    ];
}
$json = json_encode($ret);
echo($json);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BI Relatórios</title>
</head>
<body>

</body>
</html>


