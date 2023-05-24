<?php
require_once './control/controle.php';
    session_start();
    if ($_SESSION['nome'] == null) {
        header("location:\..\..\home.php");
    }
    $clientes = Controle::getClientes();
    //$politicas2 = Controle::getPoliticas(271 ,2);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas Comerciais | Perfil</title>
    <!-- CSS only -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/perfil.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <header class="head">
        <div class="head__logo">
            <img src="/Recursos/src/Logo-Kokar5.png" alt="Logo da Kokar Tintas">
        </div>
    </header>
    <section class="menu">
        <div class="logo-conteudo">
            <div class="logo">
                <div class="logo-nome"><p>Políticas Comerciais</p> </div>
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
                <a href="copiarPolitica.php">
                    <i class='bx bx-message-rounded-edit'></i>
                    <span class="link-nome">Copiar Política</span>
                </a>
                <span class="tooltip">Copiar Política</span>
            </li>
            <li>
                <a href="historico.php">
                    <i class='bx bx-copy-alt'></i>
                    <span class="link-nome">Histórico</span>
                </a>
                <span class="tooltip">Histórico</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-merge'></i>
                    <span class="link-nome">Perfil Política</span>
                </a>
                <span class="tooltip">Perfil Política</span>
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
    <section class="principal">
        <div class="pagina__loader">
            <div class="loader"></div>
        </div> 
        <div class="principal__conteudo container-fluid">
            <div class="principal__conteudo-conteudo row">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastro-perfil">Novo Perfil</button>
                </div>
               <div class="col-lg-7 col-md-12">
                    <h1>Perfil</h1> 
                    <table class="table table-striped table-sm table-dark">
                        <thead>
                            <tr>
                                <th scope="col" class="">#</th>
                                <th scope="col">Id</th>
                                <th scope="col">Representante</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="perfil-dados">
                            <tr>
                                <th scope="row" colspan="5" class="check-politica" style="text-align: center;"> 
                                    <h1>Carregando ...</h1>
                                </th>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col" colspan="5">
                                    <button type="button" onclick="limparSelecaoPerfil()" class="btn btn-info">Limpar</button>
                                   
                                    <button type="button" class="btn btn-warning" onclick="editarPerfil()">Editar Perfil</button>

                                    <button type="button" class="btn btn-danger" onclick="excluirPerfil()">Excluir </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
               </div>
               <div class="col-lg-5 col-md-12">
                    <h1>Clientes Sem Politica</h1>
                    <table class="table table-sm table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col"><input class="form-check" type="checkbox" name="" id=""></th>
                                <th scope="col">Rca</th>
                                <th scope="col">Cod</th>
                                <th scope="col">Cliente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><input class="form-check" type="checkbox" name="" id=""></th>
                                <td><span class="">Joildo</span></td>
                                <td><span class="">231</span></td>
                                <td><span class="">Fabiano Parafusos</span></td>
                            </tr>
                            <tr>
                                <th scope="row"><input class="form-check" type="checkbox" name="" id=""></th>
                                <td><span class="">Joildo</span></td>
                                <td><span class="">231</span></td>
                                <td><span class="">Fabiano Parafusos</span></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col" colspan="4">
                                    <button type="button" class="btn btn-success">Cadastrar Perfil</button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
               </div>
            </div>
        </div>
    </section>
    <section class="modais">
        <!-- Modal Body-->
        <div class="modal fade" id="verPerfil" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="modal-title">Ver Perfil</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limparPolitica()" aria-label="Close"></button>
                            </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <table class="table table-sm table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Descrição</th>
                                        <th scope="col">Desconto</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-ver-dados">
                                    <tr>
                                        <th scope="row" colspan="3" class="check-politica" style="text-align: center;"> 
                                            <h1>Carregando ...</h1>
                                        </th>
                                    </tr>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="limparPolitica()" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="cadastro-perfil" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Cadastro de Perfil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="conteiner-fluid">
                            <div class="row">
                                <div class="col-lg-12 my-2">
                                    <label for="cadastro-descricao">Descrição</label>
                                    <input type="text" class="form-control" name="cadastro-descricao" id="cadastro-descricao" aria-describedby="cadastro-descricao" placeholder="">
                                </div>
                                <div class="col-lg-12 my-2">
                                    <label for="cadastro-rca">Representante</label>
                                    <select class="form-select" name="cadastro-rca" id="cadastro-rca" aria-label="Default select example">
                                        <option selected value="0">Selecione um Representante</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 my-2">
                                    <label class="sr-only" for="cadastro-cliente-id">Cliente Base (Opcional)</label>
                                    <input type="text" class="form-control" readonly id="cadastro-cliente-id" placeholder="Razão Social ou Fantasia">
                                    <input hidden type="text" class="form-control" readonly id="cadastro-cliente-descricao" placeholder="Razão Social ou Fantasia">
                                    <!-- Limpar Cliente -->
                                    <button hidden type="button" class="btn btn-danger" onclick="limparCliente()" id="cadastro-cliente-limpar">Limpar Cliente</button>
                                    <button data-bs-toggle="modal" data-bs-target="#busca-cliente" class="btn btn-primary form-control my-2">Buscar</button>
                                </div>
                                <!-- div para cadastro-obs -->
                                <div class="col-lg-12 my-2">
                                    <label for="cadastro-obs">Descrição da Política</label>
                                    <textarea class="form-control" name="cadastro-obs" id="cadastro-obs" rows="3"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="criarPerfil()">Cadastrar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="busca-cliente" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Busca de Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#cadastro-perfil"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="sr-only" for="cadastro-cliente-busca">Pesquisar</label>
                                    <input type="text" class="form-control" id="cadastro-cliente-busca" placeholder="Razão Social ou Fantasia">
                                    <button onclick="buscarCliente()" class="btn btn-primary form-control my-2">Buscar</button>
                                </div>
                                <div class="col-lg-12">
                                   
                                    <div class="table-responsive my-2">
                                        <table class="table table-striped
                                        table-hover	
                                        table-borderless
                                        table-primary
                                        align-middle">
                                            <thead class="table-light">
                                                <caption>Clientes com Políticas Comerciais Ativas</caption>
                                                <tr>
                                                    <th>#</th>
                                                    <th>CÓDIGO</th>
                                                    <th>RAZÃO SOCIAL</th>
                                                    <th>CIDADE - UF</th>
                                                </tr>
                                                </thead>
                                                <tbody class="table-group-divider" id="busca-clientes-lista">
                                                    <tr class="table-primary" >
                                                        <th scope="row" class="check-politica text-center" colspan="4"> AGUARDANDO PESQUISA... </th>
                                                    </tr>
                                                </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" ddata-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#cadastro-perfil">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarBusca()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Perfil -->
        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="modal-editar-perfil" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Editar Perfil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <section class="mb-3">
                            <div class="form-group">
                                <label for="editar-perfil-descricao">Descricao</label>
                                <input type="text" class="form-control" id="editar-perfil-descricao" placeholder="Descrição">

                                <label for="editar-perfil-obs">Obs</label>
                                <textarea class="form-control" name="editar-perfil-obs" id="editar-perfil-obs" rows="3"></textarea>
                            </div>
                        </section>
                        <div id="editar-perfil">
                            <div class="table-responsive-md">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">Código</th>
                                            <th scope="col">Categoria</th>
                                            <th scope="col">Desconto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="editar-perfil-table">
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarEditarPerfil()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>
<script src="./js/sidebar.js"></script>
<script src="./js/scriptsGerais.js"></script>
<script src="./js/perfil.js"></script>
</html>