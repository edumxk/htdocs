<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas Comerciais</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header class="head">
        <div class="head__logo">
            <img src="/Recursos/src/Logo-Kokar5.png" alt="Logo da Kokar Tintas">
        </div>
        <div class="head__navegacao">
            <ul class="head__navegaca__lista">
                <li class="head__navegaca__lista-itens"><a href="#">Home</a></li>
                <li class="head__navegaca__lista-itens"><a href="#">Politicas</a></li>
                <li class="head__navegaca__lista-itens"><a href="#">Clientes</a></li>
            </ul>
        </div>
        <div class="head__usuario">
            <img src="/Recursos/src/cliente.png" alt="Foto do usuário">
            <ul class="head__navegaca__lista">
                <li class="head__navegaca__lista-itens">Eduardo Patrick</li>
                <li class="head__navegaca__lista-itens">Desenvolvimento</li>
            </ul>
            <button>Sair</button>
        </div>
    </header>
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
                <a href="index.php">
                    <i class='bx bx-home'></i>
                    <span class="link-nome">Página Inicial</span>
                </a>
                <span class="tooltip">Página Inicial</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-message-rounded-edit'></i>
                    <span class="link-nome">Revalidar Lotes</span>
                </a>
                <span class="tooltip">Revalidar Lotes</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-copy-alt'></i>
                    <span class="link-nome">Copiar Métodos</span>
                </a>
                <span class="tooltip">Copiar Métodos</span>
            </li>
            <li>
                <a href="index.php">
                    <i class='bx bx-merge'></i>
                    <span class="link-nome">Alterar Fórmulas</span>
                </a>
                <span class="tooltip">Alterar Fórmulas</span>
            </li>
        </ul>
        <div class="perfil-conteudo">
            <div class="perfil">
                <div class="perfil-detalhes">
                    <img src="./src/img/curr.jpg" alt="imagem de perfil">
                    <div class="nome-setor">
                        <div class="nome">Eduardo Patrick</div>
                        <div class="cargo">Desenvolvedor</div>
                        <div class="setor">T.I.</div>
                    </div>
                </div>
                <a href="/homelab.php"><i class='bx bxs-log-out' id="sair"></i></a>
            </div>
        </div>
    </section>
    
</body>
</html>