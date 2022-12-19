<?php


date_default_timezone_set('America/Sao_Paulo');
header('Content-type: text/html; charset=ISO-8859-1');
require_once 'mailer.php';
require_once ($_SERVER["DOCUMENT_ROOT"]. '/Modulos/modChamados/Controle/ratControl.php');


//Mailer::sendMail(1470);



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENVIAR RAT EMAIL</title>
</head>
<body> 
    <div>
        <label for="numrat">N° RAT</label>
        <input type="text" id="numrat">
        <button id='btn-enviar' onclick="this.disabled=true;enviar();">Enviar E-mail</button>
    </div>
    
</body>
</html>
<script>
   

    function enviar(){
        numrat = document.getElementById('numrat').value;
        
      
        $.ajax({
			type: 'POST',
			url: '../../controle/ratControl.php',
			data: {
				'action': 'enviar',
				'query': numrat
			},
			success: function(response) {
				if (response.match("ok")) {
                    alert("Email Enviado!")
					location.reload()
				}
			}
		});

    }
</script>

<script src="/recursos/js/jquery.min.js"></script>