<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Model/SqlOracle.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Controle/formatador.php');

function formatPhoneNumber($phoneNumber) {
    // Remover espaços em branco e traços desnecessários
    $charactersToRemove = [' ', '(', ')', '-'];
    $phoneNumber = str_replace($charactersToRemove, '', $phoneNumber);

    if($phoneNumber == null)
        return "";
        
    if(strlen($phoneNumber) <= 9){
        $ddd = "63";
        $number = $phoneNumber;
    } else{
        $ddd = substr($phoneNumber, 0, 2);
        $number = substr($phoneNumber, 2);
    }

    // Verificar se é um número de celular
    if (strlen($number) == 8 && $number[0] <= 6) {
        return "($ddd) ". substr($number, 0, 4) . '-' . substr($number, 4);

    } elseif (strlen($number) == 8 && $number[0] > 6) {
        $formattedNumber = "($ddd) 9 " . substr($number, 0, 4) . '-' . substr($number, 4);
        return $formattedNumber;

    } elseif(strlen($number) == 9 && $number[0] > 6) {
        $formattedNumber = "($ddd) 9 " . substr($number, 1, 4) . '-' . substr($number, 5);
        return $formattedNumber;

    } else  
    return "9999-9999";
    
    return $phoneNumber;
}


$dados = new SqlOra();
$json = $dados->select("SELECT codcli, 
case when fantasia like '%*%'
then cliente else
c.fantasia end as name, d.nomecidade city, d.uf state,
c.endercob||' '|| c.complementocob ||', '||c.cepcob address, 
case when c.telcom is null then c.telent
  else telcom end as phone, c.emailnfe email 
from kokar.pcclient c
inner join kokar.pccidade d on d.codcidade = c.codcidade
where c.dtexclusao is null and (c.dtultcomp >= '01/01/2022')
and c.consumidorfinal = 'N'
order by codcli desc");

$rcas = $dados->select("SELECT distinct u.codusur, d.nomecidade cidade,
d.uf, u.email, u.telefone1, u.telefone2,
u.nome 
from kokar.pcclient c
inner join kokar.pccidade d on d.codcidade = c.codcidade
inner join kokar.pcusuari u on u.codusur = c.codusur1
where u.codusur not in ( 1, 3, 44, 45)
and c.dtexclusao is null
order by codusur, uf, cidade");


    // Variável para armazenar os representantes
    $representantes = [];
    $cidades = [];
    $dadosret = [];
    
    // Loop pelos resultados obtidos na consulta
    foreach ($rcas as $key => $rca) {
        if(
            ($rca['CODUSUR'] == 29 && $rca['UF'] != 'TO') or
            ($rca['CODUSUR'] == 29 && $rca['CIDADE'] == 'PORTO NACIONAL') or
            ($rca['CODUSUR'] == 15 && $rca['UF'] != 'TO') or
            ($rca['CODUSUR'] == 30 && $rca['UF'] != 'PA') or
            ($rca['CODUSUR'] == 46 && $rca['UF'] != 'PA') or
            ($rca['CODUSUR'] == 40 && $rca['UF'] != 'GO') or
            ($rca['CODUSUR'] == 8 && $rca['UF'] != 'MA') 
        
            ){
            continue;
        }else{

            $cidades[$rca['CODUSUR']][] = Formatador::br_encode($rca['CIDADE']);
            
            //checa se a key já existe no array
            if(!array_key_exists($rca['CODUSUR'], $representantes)){
                
                $representantes[$rca['CODUSUR']] = 
                [
                    'state' => $rca['UF'],
                    'name' => mb_strtoupper($rca['NOME']),
                    'email' => mb_strtoupper($rca['EMAIL']),
                    'phone1' => formatPhoneNumber($rca['TELEFONE1']),
                    'phone2' => formatPhoneNumber($rca['TELEFONE2'])
                ];
            }
        }
    }
    
    foreach ($representantes as $key => $rca) {
        $representantes[$key]['city'] = $cidades[$key];
    }
    foreach($representantes as $rca){
        $dadosret[] = $rca;
    }


    //var_dump($representantes);
    // Converte o array de representantes em formato JSON
$dadosrca = json_encode($dadosret);
$jsonData = $dadosrca;
$filePath = 'datarcas.json';
if (file_put_contents($filePath, $jsonData) !== false) {
    echo "Dados salvos com sucesso no arquivo JSON rcas.";
} else {
    echo "Ocorreu um erro ao salvar os dados no arquivo JSON.";
}



$ret = ['stores' => []];
foreach($json as $j){
    $ret['stores'][] = array(
    'name' => mb_strtoupper($j['NAME']),
    'city' => mb_strtoupper($j['CITY']),
    'state' => mb_strtoupper($j['STATE']),
    'pin' => array(
        array(
            'address' => mb_strtoupper($j['ADDRESS']),
            'phone' => formatPhoneNumber($j['PHONE']),
            'email' => mb_strtolower($j['EMAIL'])
            )
        )
    );
}
$jsonData = json_encode($ret);
$filePath = 'data.json';
if (file_put_contents($filePath, $jsonData) !== false) {
    echo "Dados salvos com sucesso no arquivo JSON.";
} else {
    echo "Ocorreu um erro ao salvar os dados no arquivo JSON.";
}
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


