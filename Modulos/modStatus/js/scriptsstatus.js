const state = (() => {

    let notificacao = {}

    return {
        getNotificacao: () => notificacao,
        setNotificacao: newNotificacao => {
            notificacao = newNotificacao
            return notificacao
        }
    }
})()

const notificacaoInterna = state.getNotificacao()



function getDados(){
    $.ajax({
        type: 'POST',
        url: './Controle/notificacoes.php',
        data: { 'action': 'getNotificacoes' },
        success: function (response) {
            console.log(response)
            state.setNotificacao(response);
        }		  
    });

}

function setNameOfLinha(){
   
    for(i=1; i<=5; i++){

        switch (i){
            case 1: 
                document.getElementById(i).innerHTML = 'Tintas 5000';
            break;
            case 2: 
                document.getElementById(i).innerHTML = 'Tintas 2000';
            break;
            case 3: 
                document.getElementById(i).innerHTML = 'Texturas';
            break;
            case 4: 
                document.getElementById(i).innerHTML = 'Massas';
            break;
            case 5: 
                document.getElementById(i).innerHTML = 'Solventes';
            break;
        }
    }

}

function attCor(){
    
    $(".tbtanques2").each(function() {
        let status = $(this).children().children()[2].innerHTML;
        if (status == "ABERTURA/OP") {
            $(this).css("background-color", "#B0ABA6"); //cinza
            $(this).css("border-color", "#B0ABA6"); //cinza
        } else if (status == "PESAGEM") {
            $(this).css("background-color", "#FFA30E"); //laranja
            $(this).css("border-color", "#FFA30E"); //laranja
        } else if (status == "DISPERSÃO / BASE") {
            $(this).css("background-color", "#FFFF00"); //amarelo
            $(this).css("border-color", "#FFFF00"); //amarelo
        } else if (status == "LABORATÓRIO") {
            $(this).css("background-color", "#B89D53"); //marrom
            $(this).css("border-color", "#B89D53"); //marrom
        } else if (status == "COR") {
            $(this).css("background-color", "#3956FA"); //blue
            $(this).css("border-color", "#3956FA"); //blue
        } else if (status == "ENVASE") {
            $(this).css("background-color", "#42FFD0"); //LIGHTblue
            $(this).css("border-color", "#42FFD0"); //LIGHTblue
        } else if (status == "CORREÇÃO") {
            $(this).css("background-color", "#FA3D10"); //VERMELHO
            $(this).css("border-color", "#FA3D10"); //VERMELHO
        } else if (status == "FINALIZADO") {
            $(this).css("background-color", "#08FF0C"); //VERDE
            $(this).css("border-color", "#08FF0C"); //VERDE
        } else if (status == "AGUARDANDO") {
            $(this).css("background-color", "white"); //BRANCO
            $(this).css("border-color", "white"); //BRANCO
        }
    
    });
}

function abrirProducao(elemento){
    let statusHistory = getStatusHistory(elemento.codProducao).then(function(response) {
        console.log(response.andamento)
        if (response.andamento == 0)
            $('.form-check-input')[0].checked = true;
            
        else if(response.andamento == 1)
            $('.form-check-input')[1].checked = true;

        else if(response.andamento == 2)   
            $('.form-check-input')[2].checked = true;
           
      }).catch(function(response) {
        console.log(response)
      });
   
    let modal = $('#editarProducao')
    $('.modal-title').text(elemento.descricao)
    
    modal.modal('toggle');
   $('#modal-status').text(elemento.status);
   $('#modal-lote').val(elemento.lote);
   
    
}

function salvar(element){
    console.log(element)
    
}

function getStatusHistory(element){

    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'POST',
            url: './Controle/notificacoes.php',
            data: { 'action': 'getStatusHistory', 'dados': element},
            success: function (response) {
            let statusHistory = JSON.parse(response);
            resolve(statusHistory);
            },
            error: function(response) {
                reject(response) // Reject the promise and go to catch()
            }	  
        });
      });
   
}

