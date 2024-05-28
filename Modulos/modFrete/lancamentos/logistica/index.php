<?php
session_start();
$titulo = "Lançamento";
if (isset($_GET['date']))
    $date = $_GET['date'];
else
    $date = date('Y-m-t', strtotime('last month'));

include($_SERVER["DOCUMENT_ROOT"] . '/includes/header.php');
?>

<head>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/index.css" />
</head>

<body>
    <section class="container-fluid">
        <div class="row">
            <div class="col text-center mt-3">
                <h3>Lançamentos Logistica</h3>
            </div>
        </div>
        <div class="row">
            <div class="col bg-white mx-auto">
                <div class="container-fluid">
                    <div class="row d-block">
                        <div class="col-lg-5 col-md-6 col-sm-12 col-12 mb-3 mx-auto">
                            <div class="alert-success alert text-center opacity-0 m-0 fw-bold" id="save-info">
                                Lançamento salvo com sucesso!
                            </div>
                            <label class="pb-2 fw-bold" for="date">Competência</label>
                            <input type="date" class="form-control" id="date" placeholder="Data" value="<?= $date ?>" required>
                            <div hidden class="alert-danger alert text-center " id="date-error">
                                Selecione uma data válida
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 mb-3 mx-auto">
                            <label class="pb-2 fw-bold" for="veiculos">Veiculo</label>
                            <select class="form-select" aria-label="veiculos" id="veiculos">
                                <option selected value="0">Selecione</option>
                            </select>
                            <div hidden class="alert-danger alert text-center " id="veiculos-error">
                                Selecione um veiculo!
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 mb-3 mx-auto">
                            <label class="pb-2 fw-bold" for="valor">Quilometragem</label>
                            <input type="number" class="form-control text-left" id="valor" placeholder="Valor" required step="0.01">
                            <div hidden class="alert-danger alert text-center " id="valor-error">
                                Insira um valor válido
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 mb-3 align-self-end text-end offset-0 offset-md-8 mx-auto">
                            <button type="submit" class="btn btn-primary w-100" style="background-color: #0000FF" onclick="save()">Gravar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col" >
                <table class="table table-hover table-dark table-responsive">
                    <thead>
                        <tr class="fw-bold">
                            <th>Competencia</th>
                            <th>Veiculo</th>
                            <th>Placa</th>
                            <th>Quilometragem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="lancamentos">

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

<?php
include($_SERVER["DOCUMENT_ROOT"] . '/includes/footer.php');
?>
<script src="../../js/logistica.js"> </script>
