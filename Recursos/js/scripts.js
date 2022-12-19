function sair(){
    $.ajax({
        type: 'POST',
        url: '/controle/controle.php', //Migrar para Controle Novo
        data: {
            'action': 'sair'
        },
        success: function(response){
            console.log(response);
            if(response=='1')
                location.reload();
        }
    })
}