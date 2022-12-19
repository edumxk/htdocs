var idItem = 0;

function preencher(id) {
        $.ajax({
            type : 'POST',
            url : "control/controle.php",
            data : {'action': 'preencher', 'id': id},
            success: function (response) {
                arr = JSON.parse(response);
                body = "";
                $('#saldoCarga').text("");
                pesoTotalPen =0;
                arr.forEach(function(t){
                    body += '<tr>'
                            +'<td>'+t[0]+'</td>'
                            +'<td>'+t[1]+'</td>'
                            +'<td><button onclick="verFormula('+t[0]+',1)" >'+t[2]+'</td>'
                            +'</tr>'
                })
                $('#dados').empty();
                $('#dados').append(body);
            }
        });       
    }

    function checarSenha(){
        return $('#modal-senha').val()
    }

    function itemDeletar(id){
        idItem = id;
    }

    function fechar(){
        $('#modal-cancelar').modal('toggle');
        
    }
    function fechar2(){
       $('#modal__formula').modal('toggle');
        
    }

    function cancelar() {
        let id = idItem;
        let senha = checarSenha()

        if(confirm('Cancelando a movimentação id: '+ id)){
            $.ajax({
                type : 'POST',
                url : "control/controle.php",
                data : {'action': 'deletar', 'id': id, 'senha': senha},
                success: function (response) {
                    if(response=='Senha Incorreta! Procure o T.I.')
                    alert(response);
                    else{
                    alert("Cancelado com sucesso!")
                    window.location.reload();
                    //alert(response);
                    }
                }
            }); 
            return;
        }
        console.log("Negou");
    }
    function verFormula(codprod, metodo) {
        $.ajax({
            type: 'POST',
            url: "control/controle.php",
            data: {
                'action': 'verFormula',
                'codprod': codprod,
                'metodo': metodo
            },
            success: function(response) {
                arr = JSON.parse(response);
                body = "";
                pesoTotalPen =0;
                arr.forEach(function(t){
                    body += '<tr>'
                            +'<td>'+t['CODPROD']+'</td>'
                            +'<td class="tabela__formulas__dados-descricao">'+t['DESCRICAO']+'</td>'
                            +'<td class="tabela__formulas__dados-perc">'+parseFloat(t['PERCENTUAL']).toFixed(3)+'</td>'
                            +'<td>'+t['NUMSEQ']+'</td>'
                            +'<td>'+t['FRACAOUMIDA']+'</td>'
                            +'</tr>'
                })
                $('#modal-dados').empty();
                $('#modal-dados').append(body);
                $('#modal__formula').modal();
            }
            }); 
            
        }