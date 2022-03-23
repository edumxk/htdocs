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
                            +'<td>'+t[2]+'</td>'
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
        console.log($('#modal-cancelar').modal('toggle'));
        
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
                    if(response=='Senha Incorreta!')
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