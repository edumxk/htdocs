<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uniformes</title>
    <link href="../../recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../recursos/css/style.css" rel="stylesheet">
    <link href="../../recursos/css/fawsome/css/all.css" rel="stylesheet">

    <script src="../../recursos/js/jquery.min.js"></script>
    <script src="../../recursos/js/bootstrap.min.js"></script>
    <script src="../../recursos/js/scripts.js"></script>
    <script src="../../recursos/js/typeahead.jquery.js"></script>
    <script src="../../recursos/js/jquery.redirect.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</head>

<body style="background-color: teal;">
    <div class="header">
        <div class="row">
            <div class="col-md-10" style="left: 100px; top:2px; display: inline-block; vertical-align: middle;">
                <img src="../../recursos/src/Logo-kokar.png" alt="Logo Kokar">
            </div>
            <div class="float-md-right">
                <div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
                    <!-- Usuário: <?php echo $_SESSION['nome'] ?> -->
                </div>
                <div class="col-sm-12" style="top: 15px; font-weight: 700; color: white">
                    <!-- Setor: <?php echo $_SESSION['setor']?> -->
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="background-color: white; top: 20px; border-radius: 10px">
        <div class="row">
            <div class="col">
                <h3>Controle de Uniformes</h3>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <style>
                    table {
                        width: 100%
                    }

                    table,
                    tr,
                    th,
                    td {
                        border: 1px solid black;
                        font-size: 1rem;
                        table-layout: fixed
                    }

                    tr {
                        text-align: center;
                    }

                    th {
                        text-align: center;
                        padding-left: 5px;
                        background-color: lightgray;
                    }

                </style>
                <table>
                    <tbody>
                        <tr>
                            <th></th>
                            <th>P</th>
                            <th>M</th>
                            <th>G</th>
                            <th>GG</th>
                            <th>Total</th>
                        </tr>
                        <tr>
                            <th>Branco</th>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Amarelo</th>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Azul</th>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Verde</th>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Vermelho</th>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th>0</th>
                            <th>0</th>
                            <th>0</th>
                            <th>0</th>
                            <th>0</th>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="padding-top:15px">
            <div class="col">
                
            </div>
            <div class="col-2">
                <button class="btn btn-success" style="width:100%" onclick="modalEntrada()"> Entrada</button>
            </div>
            <div class="col-2">
                <button class="btn btn-danger" style="width:100%" onclick="modalSaida()"> Saida</button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>Movimentações</h4>
            </div>
            <div class="col-12" style="padding-bottom:20px">
                <table style="table-layout: auto">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 100px">Data</th>
                            <th style="width: 100px">Cor</th>
                            <th style="width: 100px">Tamanho</th>
                            <th style="width: 100px">Destino</th>
                            <th style="width: 100px">Destino</th>
                            <th style="width: 100px">Entrada</th>
                            <th style="width: 100px">Saida</th>
                            <th style="width:300px">Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>1</th>
                            <td>01/01/2020</td>
                            <td>Branco</td>
                            <td>M</td>
                            <td>RCA</td>
                            <td>Joildo</td>
                            <td>15</td>
                            <td>15</td>
                            <td>adsfasfasdfds</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- MODAL SAIDA; -->
    <div class="modal" id="modalSaida" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Saida de Uniforme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <style>
                        label {
                            font-weight: 700;
                        }
                    </style>
                    <form action="">
                        <!-- se for retirada de Funcionario: -->
                        <div class="form-group" id="retirada" style="width:50%">
                            <label for="selectRetirada">Destino:</label>
                            <select class="form-control" id="selectRetirada" onchange="mudaRetira(this)">
                                <option value="0">Selecione</option>
                                <option value="1">Funcionário</option>
                                <option value="2">Cliente</option>
                                <option value="3">Rca</option>
                            </select>
                        </div>

                        <div class="row" id="retirada-func" style="display: none;">
                            <div class="col">
                                <div class="form-group">
                                    <label for="selectCorFunc">Cor:</label>
                                    <select class="form-control" id="selectCorFunc">
                                        <option value="0">Selecione</option>
                                        <option value="1">Branco</option>
                                        <option value="2">Amarelo</option>
                                        <option value="3">Azul</option>
                                        <option value="4">Verde</option>
                                        <option value="5">Vermelho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="selectTamFunc">Tamanho:</label>
                                    <select class="form-control" id="selectTamFunc">
                                        <option value="0">Selecione</option>
                                        <option value="1">P</option>
                                        <option value="2">M</option>
                                        <option value="3">G</option>
                                        <option value="4">GG</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="qtFunc">Quant:</label>
                                    <input type="text" class="form-control" id="qtFunc">
                                </div>
                            </div>
                            
                        </div>



                        <!-- se for retirada de cliente: -->
                        <div class="row" id="retirada-cli" style="display: none;">
                            <div class="col-2" style="padding-bottom:10px">
                                <div class="form-group" style="display:inline">
                                    <label for="codCli">Cod.:</label>
                                    <input type="text" class="form-control" id="codCli" style="width:100%">

                                </div>
                            </div>
                            <div class="col-10" style="padding-bottom:10px">
                                <label for="codCli">Cliente:</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-4" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="selectCorFunc">Cor:</label>
                                    <select class="form-control" id="selectCorFunc" style="width:100%">
                                        <option value="0">Selecione</option>
                                        <option value="1">Branco</option>
                                        <option value="2">Amarelo</option>
                                        <option value="3">Azul</option>
                                        <option value="4">Verde</option>
                                        <option value="5">Vermelho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-4" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="selectTamFunc">Tamanho:</label>
                                    <select class="form-control" id="selectTamFunc" style="width:100%">
                                        <option value="0">Selecione</option>
                                        <option value="1">P</option>
                                        <option value="2">M</option>
                                        <option value="3">G</option>
                                        <option value="4">GG</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-4" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="qtFunc">Quantidade:</label>
                                    <input type="text" class="form-control" id="qtFunc" style="width:100%">
                                </div>
                            </div>

                        </div>


                        <!-- se for retirada de RCA: -->
                        <div class="row" id="retirada-rca" style="display: none;">
                            <div class="col-3" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="selectCorRca">RCA:</label>
                                    <select class="form-control" id="selectCorRca" style="width:100%">
                                        <option value="0">Selecione</option>
                                        <option value="1">Kokar</option>
                                        <option value="2">Wanderlei</option>
                                        <option value="3">Joildo</option>
                                        <option value="4">Domingos</option>
                                        <option value="5">Mauro</option>
                                        <option value="6">Franklin</option>
                                        <option value="7">Radamés</option>
                                        <option value="8">Alexander</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="selectCorFunc">Cor:</label>
                                    <select class="form-control" id="selectCorFunc" style="width:100%">
                                        <option value="0">Selecione</option>
                                        <option value="1">Branco</option>
                                        <option value="2">Amarelo</option>
                                        <option value="3">Azul</option>
                                        <option value="4">Verde</option>
                                        <option value="5">Vermelho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="selectTamFunc">Tamanho:</label>
                                    <select class="form-control" id="selectTamFunc" style="width:100%">
                                        <option value="0">Selecione</option>
                                        <option value="1">P</option>
                                        <option value="2">M</option>
                                        <option value="3">G</option>
                                        <option value="4">GG</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3" style="padding-bottom:10px">
                                <div class="form-group">
                                    <label for="qtFunc">Quantidade:</label>
                                    <input type="text" class="form-control" id="qtFunc" style="width:100%">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="obsEnt">Observação</label>
                                    <textarea class="form-control" id="obsEnt" rows="3"></textarea>
                                  </div>
                            </div>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>



    <!-- MODAL ENTRADA; -->
    <div class="modal" id="modalEntrada" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Entrada de Uniforme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <style>
                        label {
                            font-weight: 700;
                        }
                    </style>
                    <form action="">
                        <div class="row" id="entrada">
                            <div class="col">
                                <div class="form-group">
                                    <label for="selectCorEntrada">Cor:</label>
                                    <select class="form-control" id="selectCorEntrada">
                                        <option value="0">Selecione</option>
                                        <option value="1">Branco</option>
                                        <option value="2">Amarelo</option>
                                        <option value="3">Azul</option>
                                        <option value="4">Verde</option>
                                        <option value="5">Vermelho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="selectTamEntrada">Tamanho:</label>
                                    <select class="form-control" id="selectTamEntrada">
                                        <option value="0">Selecione</option>
                                        <option value="1">P</option>
                                        <option value="2">M</option>
                                        <option value="3">G</option>
                                        <option value="4">GG</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="qtFunc">Quant:</label>
                                    <input type="text" class="form-control" id="qtFunc">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="obsEnt">Observação</label>
                                    <textarea class="form-control" id="obsEnt" rows="3"></textarea>
                                    </div>
                            </div>
                        </div>
    
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

</body>


</body>

<script>
    function modalSaida() {

        $("#modalSaida").modal('toggle');
    }
    function modalEntrada() {

        $("#modalEntrada").modal('toggle');
    }


    function mudaAcao(elm) {
        var acao = $(elm).val();
        var retirada = $('#retirada');
        var retiradaFunc = $('#retirada-func');
        var retiradaCli = $('#retirada-cli');
        var retiradaRca = $('#retirada-rca');

        if (acao == 0) {
            retirada.hide();
            retiradaFunc.hide();
            retiradaCli.hide();
            retiradaRca.hide();
        }
        if (acao == 1) {
            retirada.show();
            retiradaFunc.hide();
            retiradaCli.hide();
            retiradaRca.hide();
        }




        if (acao == 2) {
            retirada.hide();
            retiradaFunc.hide();
            retiradaCli.hide();
            retiradaRca.hide();
        }

    }

    function mudaRetira(elm) {
        var acao = $(elm).val();
        console.log(acao)
        var retiradaFunc = $('#retirada-func');
        var retiradaCli = $('#retirada-cli');
        var retiradaRca = $('#retirada-rca');

        if (acao == 0) {
            retiradaFunc.hide();
            retiradaCli.hide();
            retiradaRca.hide();
        }
        if (acao == 1) {
            retiradaFunc.show();
            retiradaCli.hide();
            retiradaRca.hide();
        }
        if (acao == 2) {
            retiradaFunc.hide();
            retiradaCli.show();
            retiradaRca.hide();
        }
        if (acao == 3) {
            retiradaFunc.hide();
            retiradaCli.hide();
            retiradaRca.show();
        }
    }
</script>

</html>