<?php
session_start();
require_once('Controle/notificacoes.php' );
$dados = Model::getOperadores();
$checkRequest = microtime();
$_SESSION['request']=$checkRequest; 

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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/cadastro.css">    
</head>
<body> 
    <div class="title" style="margin: 30px 0 10px 0 ;">
        <h1>Cadastro de Operador</h1>
    </div>
    <section class="mapa">
        <div class="mapa_linhas">
            <div class="mapa_linha_card">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>SETOR</th>
                            <th>DT CADASTRO</th>
                            <th>AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dados as $d): ?>
                        <tr>
                            <td><?= $d->id?></td>
                            <td><?= $d->nome?></td>
                            <td><?= $d->setor?></td>
                            <td><?= $d->cadastro?></td>
                            <td><a class="btn btn-warning">Editar</a><a class="btn btn-danger">Excluir</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
        <div class="modal-body">
            <div>
                <label for="lote" class="lote form-group">Lote da Producao:</label>
                <span name="lote" id="modal-lote" class="form-group"></span>
            </div>
            <div>
                <label for="operador" class="operador form-group">Operador</label>
                <select name="operador" id="operador" class="form-group">
                    <option value="0" class="operadores">Washington</option>
                    <option value="1" class="operadores">Lucas</option>
                </select>
            </div>
            <div>
                <label for="tanque" class="tanque form-group">Tanque</label>
                <select name="tanque" id="tanque" class="form-group">
                    <option value="0" class="tanques">1</option>
                    <option value="1" class="tanques">2</option>
                    <option value="2" class="tanques">3</option>
                    <option value="3" class="tanques">4</option>
                </select>
            </div>
            <div class="col-md-12 modal-radio">
                <label for="optradio" class="form-check" id="modal-status">Status da Produção</label>
                <div class="form-check-inline">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="0" name="optradio">Aguardando
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="1" name="optradio">Em Produção
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="2" name="optradio">Finalizado
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


<script>
    function salvar(elemento){
        console.log(elemento);
    }
</script>