<?php

session_start();

if (!isset($_SESSION['coduser'])) {
	header("location:/index.php?msg=failed");
}
require_once('Controle/notificacoes.php' );

$dados = Notificacao::getDados();

if(isset($_POST['operador'])){
   // var_dump($_POST);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ordem de Produção</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script
        src="https://code.jquery.com/jquery-3.6.2.min.js"
        integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA="
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/status.css">
        <link href="/recursos/css/style.css" rel="stylesheet">
    </head>
    <body> 
        <header>
            <div class="logo">
                <img src="/recursos/src/Logo-kokar5.png" alt="Logo Kokar" width="350">
            </div>
            <div class="usuario">
                <ul>
                    <li>Usuário: <?php echo $_SESSION['nome']?></li>
                    <li>Setor: <?php echo $_SESSION['setor']?></li>
                    <li><a style="color: white" onclick="sair()" href="#">Sair</a></li>
                </ul>
            </div>		
        </header>
        <div class="title">
            <h1>Produção Kokar</h1>
        </div>
        <section class="mapa">
            <div class="mapa_linhas">
                <?php for($i=1; $i<=5; $i++): ?>
                    <div class="mapa_linha_card" id="div<?= $i ?>">
                            <h1 id="<?= $i ?>">Linha <?= $i ?></h1>
                        <div class="mapa_linhas_producao">
                            <?php foreach($dados as $d): 
                            if($d->linha == $i):?>
                                <span class="tbtanques2" onclick="abrirProducao({codProducao: '<?= $d->codProducao ?>', descricao: '<?= $d->descricao ?>', lote: '<?= $d->lote ?>', status: '<?= ($d->status) ?>', andamento: 1})">
                                    <div class="titulo-card">
                                        <p>LOTE <?= $d->lote ?> | <?= $d->pliquido ?> KG</p>
                                        <p class="descricao"><?= $d->descricao ?></p>
                                        <p class="btanque"><?= ($d->status) ?></p>
                                        <p class="contador" id="<?= $d->codProducao ?>"><input type="text" hidden disabled value="<?= $d->codProducao . "-" . $d->abertura?>"></p>
                                    </div>
                                </span>
                            <?php   
                            endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endfor;?> 
            </div>
        </section>
        <!-- Modal Ação Produção -->
        <div class="modal fade" id="editarProducao" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Editar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form action="./status.php" method="POST" class="form-group">
                        <?php 
                            $checkRequest = microtime();
                            $_SESSION['request']=$checkRequest; 
                        ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="modal-lote" class="lote form-group">Lote da Producao:</label>
                                <input type="text" name="lote" id="modal-lote" readonly class="form-group modal-lote"></input>
                                <input type="hidden" value="<?= $checkRequest ?>" name="checkrequest" />
                            </div>
                            <div class="form-group">
                                <label for="operador" class="operador form-group">Operador</label>
                                <select name="operador" id="operador" class="form-group">
                                    <option value="0" class="operadores">Washington</option>
                                    <option value="1" class="operadores">Lucas</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanque" class="tanque">Tanque</label>
                                <select name="tanque" id="tanque">
                                    <option value="0" class="tanques">1</option>
                                    <option value="1" class="tanques">2</option>
                                    <option value="2" class="tanques">3</option>
                                    <option value="3" class="tanques">4</option>
                                </select>
                            </div>
                            <div class="col-md-12 modal-radio form-group">
                                <label for="optradio" class="form-check" id="modal-status">Status da Produção</label>
                                <div class="form-check-inline">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="0" name="andamento">Aguardando
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="1" name="andamento">Em Andamento
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="2" name="andamento">Finalizado
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<script src="./js/scripts.js"></script>
<script src="./js/scriptsstatus.js"></script>
<script src="/recursos/js/scripts.js"></script>
<script>
    setNameOfLinha();
    attCor();
</script>