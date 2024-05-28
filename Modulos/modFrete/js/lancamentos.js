//on focusout do campo data chamar a função getLancamentos com a data selecionada
document.getElementById('date').addEventListener('focusout', function(){
    getLancamentos(this.value);
});


getLancamentos(null);

$(document).ready(function(){
    $.ajax({
        url: "../controller/lancamentoController.php",
        type: "POST",
        data: {action: "getMotoristas"},
        success: function(response){
            let motoristas = JSON.parse(response);
            let select = document.getElementById('motorista');
            let option = document.createElement('option');

            select.innerHTML = "";
            option.value = 0;
            option.text = "Selecione um motorista";
            select.appendChild(option);
            for(let i = 0; i < motoristas.length; i++){
                let option = document.createElement('option');
                option.value = motoristas[i].MATRICULA;
                option.text = motoristas[i].NOME;
                select.appendChild(option);
            }
        }
    });
    //preenche o select de tipos de lançamento
    $.ajax({
        url: "../controller/lancamentoController.php",
        type: "POST",
        data: {action: "getTipoLancamentos"},
        success: function(response){
            
            let tipos = JSON.parse(response);
            let select = document.getElementById('tipolanc');
            let option = document.createElement('option');

            select.innerHTML = "";
            option.value = 0;
            option.text = "Selecione um Lançamento";
            select.appendChild(option);
            for(let i = 0; i < tipos.length; i++){
                let option = document.createElement('option');
                option.value = tipos[i].ID;
                option.text = tipos[i].DESCRICAO;
                select.appendChild(option);
            }
        }
    });  
});


function save(){
    let data = document.getElementById('date');
    let motorista = document.getElementById('motorista');
    let tipo = document.getElementById('tipolanc');
    let valor = document.getElementById('valor');
    let sucesso = document.getElementById('save-info');

    let lancamento = {
        motorista: motorista.value,
        valor: valor.value,
        data: data.value,
        tipo: tipo.value
    }

    if(!validation(lancamento, data, motorista, tipo, valor))
        return;

    console.log(JSON.stringify(lancamento));
    if(confirm("Deseja salvar o lançamento?"))
    {
        $.ajax({
            url: "../controller/lancamentoController.php",
            type: "POST",
            data: {
                data: lancamento,
                action: "save"},
            success: function(response){
                if(response.match("ok")){
                    sucesso.classList.remove("opacity-0");
                    //alterna a transparencia do alerta de sucesso em 1s com transição lenta
                    setTimeout(function(){
                        sucesso.classList.add("opacity-0");
                    }, 5000);
                    tipo.value = 0;
                    valor.value = "";
                    setTimeout(function(){
                        getLancamentos(lancamento.data);
                    }, 300);
                }else{
                    alert("Não foi possível salvar o lançamento, este motorista já possui um lançamento para esta data.");
                    console.log(response);
                }
            }

        }); 
        
    }

}

function getLancamentos(data){
    if(data == undefined || data == null || data == ""){
        data = document.getElementById('date').value;
    }
    

    $.ajax({
        url: "../controller/lancamentoController.php",
        type: "POST",
        data: {
            data: data,
            action: "getLancamentos"},
        success: function(response){
            console.log(response);
            let lancamentos = JSON.parse(response);
            let table = document.getElementById('lancamentos');
            table.innerHTML = "";
            //se não houver lançamentos para a data selecionada, exibe uma mensagem com colspan 5
            if(lancamentos.length == 0){
                let row = table.insertRow(0);
                let cell = row.insertCell(0);
                cell.innerHTML = "Não há lançamentos para esta data.";
                cell.setAttribute("colspan", "5");
                return;
            }

            for(let i = 0; i < lancamentos.length; i++){
                let row = table.insertRow(i);
                let cellData = row.insertCell(0);
                let cellMotorista = row.insertCell(1);
                let cellTipo = row.insertCell(2);
                let cellValor = row.insertCell(3);
                let cellDelete = row.insertCell(4);
                
                cellData.innerHTML = lancamentos[i].COMPETENCIA;
                cellMotorista.innerHTML = lancamentos[i].NOME;
                cellTipo.innerHTML = lancamentos[i].DESCRICAO;
                cellValor.innerHTML = parseFloat(lancamentos[i].VALOR).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                cellDelete.innerHTML = "<button class='btn btn-danger btn-sm' onclick='deleteLancamento("+lancamentos[i].ID+")'>Excluir</button>";
            }
        }
    });
}

function deleteLancamento(id){
    if(confirm("Deseja excluir o lançamento?")){
        $.ajax({
            url: "../controller/lancamentoController.php",
            type: "POST",
            data: {
                id: id,
                action: "delete"},
            success: function(response){
                if(response.match("ok")){
                    alert("Lançamento excluído com sucesso.");
                    getLancamentos(null);
                }else{
                    alert("Não foi possível excluir o lançamento.");
                    console.log(response);
                }
            }
        });
    }
}

function validation(lancamento, data, motorista, tipo, valor){
    console.log(lancamento);
    if(lancamento.data == null || lancamento.data == undefined || lancamento.data == ""){
        data.classList.add("is-invalid");
        data.focus();
        data = document.getElementById('date-error');
        data.removeAttribute("hidden");
        return false;
    }else{
        
        data.classList.remove("is-invalid");
        data.classList.add("is-valid");
        data = document.getElementById('date-error');
        data.setAttribute("hidden", "true");
    }
    
    if(lancamento.motorista == 0 ){
       
        motorista.classList.add("is-invalid");
        motorista.focus();
        motorista = document.getElementById('motorista-error');
        motorista.removeAttribute("hidden");
        return false;
    }else{
       
        motorista.classList.remove("is-invalid");
        motorista.classList.add("is-valid");
        motorista = document.getElementById('motorista-error');
        motorista.setAttribute("hidden", "true");
    }

    if(lancamento.tipo == 0 ){
       
        tipo.classList.add("is-invalid");
        tipo.focus();
        tipo = document.getElementById('tipolanc-error');
        tipo.removeAttribute("hidden");
        return false;
    }else{
       
        tipo.classList.remove("is-invalid");
        tipo.classList.add("is-valid");
        tipo = document.getElementById('tipolanc-error');
        tipo.setAttribute("hidden", "true");
    }

    if(lancamento.valor <= 0 || lancamento.valor > 999999 || lancamento.valor == null || lancamento.valor == undefined || lancamento.valor == ""){
        
        valor.classList.add("is-invalid");
        valor.focus();
        valor = document.getElementById('valor-error');
        valor.removeAttribute("hidden");
        return  false;
    }else{
        valor.classList.remove("is-invalid");
        valor.classList.add("is-valid");
        valor = document.getElementById('valor-error');
        valor.setAttribute("hidden", "true");
    }
    return true;
}
