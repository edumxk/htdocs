<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controle/formatador.php');
require_once($_SERVER["DOCUMENT_ROOT"]. '/Modulos/lab/modRevalidacao/controller/controler.php');
session_start();
$lista = [];
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Revalidar Lotes</title>

    <meta name="description" content="Lançamento de requisições de abastecimento">
    <meta name="author" content="Eduardo Patrick">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="./revalidacao.css" rel="stylesheet">
    <link href="../../Lab/modContraTipo/src/css/reset.css" rel="stylesheet">
    <link href="../../Lab/modContraTipo/src/css/sidebar.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
</head>
<body>
	<main>
        <section class="menu">
            <div class="logo-conteudo">
                <div class="logo">
                    <img class='kokar_logo' src="/Recursos/src/Logo-kokar.png"></img>
                    <div class="logo-nome">Laboratório</div>
                </div>
                <i class='bx bx-menu' id="btn"></i>
            </div>
            <ul class="navegacao">
                <li>
                    <a href="revalidacao.php">
                        <i class='bx bx-home'></i>
                        <span class="link-nome">Página Inicial</span>
                    </a>
                    <span class="tooltip">Página Inicial</span>
                </li>
                <li>
                    <a href="../modContraTipo/relatorio.php">
                        <i class='bx bx-message-rounded-edit'></i>
                        <span class="link-nome">Histórico</span>
                    </a>
                    <span class="tooltip">Histórico</span>
                </li>
                <li>
                    <a href="revalidacao.php">
                        <i class='bx bx-copy-alt'></i>
                        <span class="link-nome">Revalidar Lotes</span>
                    </a>
                    <span class="tooltip">Revalidar Lotes</span>
                </li>
                <li>
                    <a href="../modMetodo/index.php">
                        <i class='bx bx-merge'></i>
                        <span class="link-nome">Copiar Fórmulas</span>
                    </a>
                    <span class="tooltip">Copiar Fórmulas</span>
                </li>
            </ul>
            <div class="perfil-conteudo">
                <div class="perfil">
                    <div class="perfil-detalhes">
                       
                         <!--<img src="./src/img/curr.jpg" alt="imagem de perfil">-->
                         <div class="nome-setor">
                            <div class="nome"><?=$_SESSION['nome']?></div>
                            <div class="cargo"><?=$_SESSION['cargo']?></div>
                            <div class="setor"><?=$_SESSION['setor']?></div>
                        </div>
                    </div>
                    <a href="/homelab.php"><i class='bx bxs-log-out' id="sair"></i></a>
                </div>
            </div>
        </section>
        <section class="home-conteudo"> 
          
            <div id="abastecimento" class="principal_card">
                <h2>Revalidar de Lote</h2>

                <label for="numlote">Lote</label>
                <input  type="text" id="numlote">
                
                <label for="tempo">Tempo</label>  
                <select id="tempo">
                        <optgroup id="tempo" label="Tempo">
                            <option value="6">+ 6 meses</option>
                            <option value="12">+ 12 meses</option>
                        </optgroup>
                </select>
                <button class="btn btn-sm btn-success" onclick="revalidar()">
                    Revalidar
                </button>
            </div>
            
            
            <div id="abastecimento" class="principal_card-informacoes">
                <div>
                    <div>
                        <spam>Informações</spam>
                    </div>
                </div>
                <div class="principal_card-dados">
                    <div>
                    <p id="vprod" value=""></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
    <script src="../modContraTipo/src/js/sidebar.js"></script>
    <script src="../modContraTipo/js/jquery.min.js"></script>


<script>
    $('#numlote').keyup(()=>{
        buscaLote($('#numlote').val());
    })

    window.onload = function() {
        document.getElementById("numlote").focus();
    };

    function revalidar() {
        
        numlote = document.getElementById('numlote').value;
        temp = document.getElementById("vprod").innerHTML;
        numlote = numlote.toUpperCase();
        if (temp.indexOf('EXISTE')>1){
            alert("Lote inválido!")
        }else {
            var result = confirm("Confirma a revalidação do lote nº "+numlote+"?");
        }if(result){
        tempo = $("#tempo option:selected").val();
        usuario = '<?php echo $_SESSION['nome'] ?>';
        dataset = {
            "numlote": numlote,
            "tempo": tempo,
            "usuario": usuario
        };
        
        console.log(dataset);
        $.ajax({
            type: 'POST',
            url: 'controller/controler.php',
            data: {
                'action': 'revalidar',
                'query': dataset
            },
            success: function(response) {
                console.log(response);
                if (response.match("ok")) {
                    alert("Lote n° " + numlote + " revalidado com sucesso.");

                }
            }
        });
    }
     
    }

    function buscaLote(elm) {
        numlote = elm.toUpperCase();
        $.ajax({
            type: 'POST',
            url: 'controller/controler.php',
            data: {
                'action': 'buscaLote',
                'query': numlote
            },
            success: function(response) {
                if(response.indexOf("Notice")== -1){
                    document.getElementById("vprod").innerHTML = response;
                }
                else{
                    document.getElementById("vprod").innerHTML = "LOTE Nº "+numlote+". NÃO EXISTE!";    
                }
               
            }
        })
    }
</script>

</html>