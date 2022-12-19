$(document).ready(function () {
    var tabela = null;

    $('#btn-buscar').on('click', function () {
        var lote = $('#lote').val();
        if(tabela != null){
            location.reload(true);
        }
        $.ajax({
            type: 'POST',
            url: './control.php',
            data: {
                'action': 'buscar',
                'query': lote
            }
        }).done(function (msg) {
           
            obj = JSON.parse(msg);

            var dataSet = [];
            var numItens = 0
           
            for (let i = 0; i < obj.length; i++) {
                dataSet[i] = [obj[i][0]['DESCRICAOPADRAO'], obj[i][1]['VALORPADRAO'], obj[i][2]['RESULTADOANALISE'], obj[i][4]['ROWID']];
                numItens = i;
            }
           
            tabela = $('#tabela').DataTable({
                responsive: true,
                paging: false,
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                },
                data: dataSet,
                columnDefs: [ //defini manualmente o tamanho da coluna
                    { "width": "40%", "targets": 0 }, //força a primeira coluna a usar 20%. 
                    { "width": "20%", "targets": 1 },
                    { "width": "20%", "targets": 2 },
                    { "width": "20%", "targets": 3 },
                ],
                columns: [
                    { title: "DESCRICAOPADRAO" },
                    { title: "VALORPADRAO" },
                    { title: "RESULTADOANALISE" },
                    { title: "ROWID" }
                ],
                dom: 'Bfrtip',        // element order: NEEDS BUTTON CONTAINER (B) ****
                select: 'single',     // enable single row selection
                altEditor: true,
                buttons: [{
                    extend: 'selected', // Bind to Selected row
                    text: 'Editar',
                    action: function () {
                        $.map(tabela.rows('.selected').data(), function (item) {

                            var resposta = prompt("Insira o Valor Correto da Análise:", item[2]);

                            tabela.rows({ selected: true }).every(function (rowIdx, tableLoop, rowLoop) {
                                tabela.cell(rowIdx, 2).data(resposta);
                            }).draw();

                        });
                    }
                },
                {
                    text: 'Salvar',
                    action: function () {
                        d = tabela.rows().data();
                        dados = []
                       for (let i = 0; i < numItens+1; i++) {
                        dados[i] = d[i];
                       }
                       
                        $.ajax({
                            type: 'POST',
                            url: './control.php',
                            data: {
                                'action': 'salvar',
                                'query': dados
                            },
                            success: function(response) {
                                console.log(response);
                                if(numItens+1 == parseInt(response))
                                    alert("Laudo Salvo!")
                                else
                                    alert("Algo deu errado, não tente novamente! Procure o TI e informe o erro!  COD ERRO: " + response)
                            }
                        })
                    }
                },
                ],
            });
        })
    });



});