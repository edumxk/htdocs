
getVeiculos();
document.getElementById('date').addEventListener('focusout', function(){
    getLancamentos(this.value);
});
getLancamentos(null);
//on focus out data chamar getLancamentos(null)
    

function getVeiculos() {
    
   
    $.ajax({
        url: "../../controller/lancamentoController.php",
        type: "POST",
        data: {action: "getVeiculos"},
        success: function(response){
            let veiculos = JSON.parse(response);
            let select = document.getElementById('veiculos');
            let option = document.createElement('option');

            select.innerHTML = "";
            option.value = 0;
            option.text = "Selecione um veículo";
            select.appendChild(option);
            for(let i = 0; i < veiculos.length; i++){
                let option = document.createElement('option');
                option.value = veiculos[i].CODVEICULO;
                option.text = veiculos[i].DESCRICAO;
                select.appendChild(option);
            }
        }
    });

}

function save(){
    let data = document.getElementById('date');
    let veiculos = document.getElementById('veiculos');
    let valor = document.getElementById('valor');
    let sucesso = document.getElementById('save-info');

    let lancamento = {
        veiculos: veiculos.value,
        valor: valor.value,
        data: data.value,
    }

    if(!validation(lancamento, data, veiculos, valor))
        return;

    if(confirm("Deseja salvar o lançamento?")){
        $.ajax({
            url: "../../controller/lancamentoController.php",
            type: "POST",
            data: {action: "saveKm", lancamento: lancamento},
            success: function(response){
                console.log(response);
                if(response.match("ok")){
                    sucesso.classList.remove("opacity-0");
                    //alterna a transparencia do alerta de sucesso em 1s com transição lenta
                    setTimeout(function(){
                        sucesso.classList.add("opacity-0");
                    }, 5000);
                    valor.value = "";
                    setTimeout(function(){
                        getLancamentos(lancamento.data);
                    }, 300);
                }else{
                    alert("Não foi possível salvar o lançamento, este veiculo já possui um lançamento para esta data.");
                    console.log(response);
                }
            }
        });
    }else{
        sucesso.innerHTML = "Preencha todos os campos!";
        sucesso.style.color = "red";
        sucesso.style.display = "block";
        setTimeout(function(){
            sucesso.style.display = "none";
        }, 3000);
    }
}

function getLancamentos(data){
    if(data == null || data == undefined || data == ""){
        data = document.getElementById('date').value;
    }
    let table = document.getElementById('lancamentos');
    
    $.ajax({
        url: "../../controller/lancamentoController.php",
        type: "POST",
        data: {action: "getLancamentosKm", data: data},
        success: function(response){
            let lancamentos = JSON.parse(response);
            table.innerHTML = "";
            for(let i = 0; i < lancamentos.length; i++){
                let row = table.insertRow(i);
                let cellData = row.insertCell(0);
                let cellVeiculo = row.insertCell(1);
                let cellPlaca = row.insertCell(2);
                let cellKm = row.insertCell(3);
                let cellAction = row.insertCell(4);
                let btn = document.createElement('button');
                btn.classList.add("btn", "btn-danger");
                btn.innerHTML = "Excluir";
                btn.setAttribute("onclick", "deleteLancamento("+lancamentos[i].ID+")");
                cellData.innerHTML = lancamentos[i].COMPETENCIA;
                cellVeiculo.innerHTML = lancamentos[i].DESCRICAO;
                cellPlaca.innerHTML = lancamentos[i].PLACA;
                cellKm.innerHTML = lancamentos[i].VALOR;
                cellAction.appendChild(btn);
            }
        }
    });
}

function deleteLancamento(id){
    if(confirm("Deseja excluir o lançamento?")){
        $.ajax({
            url: "../../controller/lancamentoController.php",
            type: "POST",
            data: {action: "deleteLancamentoKm", id: id},
            success: function(response){
                if(response.match("ok")){
                    alert("Lançamento excluído com sucesso!");
                    getLancamentos(null);
                }else{
                    alert("Não foi possível excluir o lançamento.");
                    console.log(response);
                }
            }
        });
    }
}


function validation(lancamento, data, motorista, valor){
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
    
    if(lancamento.veiculos == 0 ){
       
        motorista.classList.add("is-invalid");
        motorista.focus();
        motorista = document.getElementById('veiculos-error');
        motorista.removeAttribute("hidden");
        return false;
    }else{
       
        motorista.classList.remove("is-invalid");
        motorista.classList.add("is-valid");
        motorista = document.getElementById('veiculos-error');
        motorista.setAttribute("hidden", "true");
    }

    if(lancamento.valor <= 0 || lancamento.valor > 9999999 || lancamento.valor == null || lancamento.valor == undefined || lancamento.valor == ""){
        
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