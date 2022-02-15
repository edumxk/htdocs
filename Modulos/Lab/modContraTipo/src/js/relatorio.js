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
                        +'</tr>'
            })
            $('#dados').empty();
            $('#dados').append(body);
        }
        }); 
        
    }