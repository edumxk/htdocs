window.onload = function() {
    $("#tabelas-busca").tablesorter();
    $(".loader").toggle();
    //$(".container").toggle();
    $("#produto").on("keyup", function() {
        const value = $(this).val().toUpperCase().split(' ');

        $(".produtos").each(function() {
            const busca = $(this).text().toUpperCase();
            let referencia = $(this);
            let flag2= 0;
            value.filter(function(element) {                            
                if (busca.indexOf(element) !== -1) {   // se for encontrado um valor nos dois arrays
                    flag2  ++;
                }else{
                    flag2 = 0;
                }
                if(value.length === flag2){
                    referencia.show()
                }else{
                    referencia.hide()
                }
                });
            }); 
        });
    };

function marcar(source) {
    let checkboxes = document.getElementsByName('produto');
    let visibilidade = document.getElementsByClassName('produtos');

    //console.log($(visibilidade[0]).css('display'));

    for(let i=0, n=checkboxes.length;i<n;i++) {
        if($(visibilidade[i]).css('display') != 'none'){ 
        checkboxes[i].checked = source.checked;
        }
    }
   // console.log($(visibilidade[i]).css('display'));
}

function novaData(){
    var today = new Date();
        var date = today.toJSON().slice(0, 10);
        var nDate = date.slice(8, 10) + '/' 
                    + date.slice(5, 7) + '/' 
                    + date.slice(0, 4);
        return nDate;
}

