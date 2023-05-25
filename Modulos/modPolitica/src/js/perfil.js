$(".loader").toggle();
$(".pagina__loader").css('height', '20px');
$(".principal__conteudo").toggle();

    

function getPerfis(){
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "getPerfis"
        },
        success: function(data){
            //console.log(data);
            dados = JSON.parse(data);
             //limpar perfil-dados
             $("#perfil-dados").html("");
            //preencher perfil-dados
            dados.forEach(element => {
                $("#perfil-dados").append(`
                    <tr>
                        <th scope="row" class="check-politica">
                        <input class="form-check-input codperfil_radio" type="radio" name="codPerfil-radio" id="per${element.codPerfil}"></th>
                        <td><span class="">${element.codPerfil}</span></td>
                        <td><span class="">${element.rca}</span></td>
                        <td><span class="">${element.descricao}</span></td>
                        <td><a onclick="getPoliticaPerfil(${element.codPerfil})" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verPerfil">Ver</a></td>
                    </tr>
                `);
            });
        }
    })
}

getPerfis();
getRca();


function getPoliticaPerfil(codPerfil){
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "getPoliticaPerfil",
            codPerfil: codPerfil
        },
        success: function(data){
            dados = JSON.parse(data);
            //console.log(dados);
            //limpar modal-ver-dados
            $("#modal-ver-dados").html("");
            //preencher modal-ver-dados
            dados.forEach(element => {
                $("#modal-ver-dados").append(`
                    <tr>
                        <th scope="row">${element.codGrupo}</th>
                        <td><span class="">${element.descricao}</span></td>
                        <td><span class="d-block text-center">${element.desconto}</span></td>
                    </tr>
                `);
            });


        }
    })
}

function criarPerfil(){
    //coletar valores do modal cadastro-perfil
    let descricao = $("#cadastro-descricao").val();
    let rca = $("#cadastro-rca").val();
    let codcli = $("#cadastro-cliente-id").val();
    let obs = $("#cadastro-obs").val();


    //verificar se os campos foram preenchidos
    if(descricao == "" || rca == "0"){
        alert("Preencha todos os campos");
        return;
    }
    //verifica se a descricao tem pelo menos 6 caracteres
    if(descricao.length < 6){
        alert("A descrição deve ter pelo menos 6 caracteres");
        return;
    }   

    dados = {
        descricao: descricao,
        rca: rca,
        codcli: codcli,
        obs: obs
    }

   // console.log(dados);

    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "criarPerfil",
            dados: dados
        },
        success: function(data){
            //console.log(data);
            getPerfis();
            limparPerfil();
        }
    });
}

function buscarCliente(){
    let descricao = $("#cadastro-cliente-busca").val();
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "buscarCliente",
            descricao: descricao
        },
        success: function(data){
            try{
                dados = JSON.parse(data);
            }catch{
                alert("Erro ao buscar cliente, tente buscar novamente detalhando a busca.")
                return;
            }
            
            if((dados.erro)){
                alert(dados.erro);
                return;
            }
            if(dados.length == 0){
                $("#busca-clientes-lista").html("");
                $("#busca-clientes-lista").append(`
                    <tr class="table-primary" >
                        <th scope="row" class="check-politica text-center" style="color: red" colspan="4">NENHUM CLIENTE ENCONTRADO</th>
                    </tr>
                `);
                return;
            }
            //limpar busca-clientes-lista
            $("#busca-clientes-lista").html("");
            //preencher busca-clientes-lista
            dados.forEach(element => {
                $("#busca-clientes-lista").append(`
                    <tr class="table-primary">
                        <th scope="row" class="check-politica"> 
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="codcli-radio" id="${element.codcli}">
                            </div>
                        </th>
                        <td>${element.codcli}</td>
                        <td>${element.razao}</td>
                        <td>${element.cidadeUf}</td>
                    </tr>
                `);
            });
        }
    })
}

var campoBusca = document.getElementById("cadastro-cliente-busca");

function confirmarBusca(){

    //verificar se algum cliente foi selecionado
    if($("input[name='codcli-radio']:checked").length == 0){
        alert("Selecione um cliente");
        return;
    }

    //pegar o id do cliente selecionado
    let codcli = $("input[name='codcli-radio']:checked").attr("id");
    //pegar o nome do cliente selecionado
    let cliente = $("input[name='codcli-radio']:checked").parent().parent().parent().find("td:nth-child(3)").text();
    
    //inserir o id no campo codcli hidden

    $("#cadastro-cliente-id").val(codcli);

    //inserir o nome no campo cadastro-cliente-descricao
    $("#cadastro-cliente-descricao").val(cliente);

    //remover o hidden do botao cadastro-cliente-limpar
    $("#cadastro-cliente-limpar").removeAttr("hidden");

    //altera o foco para o modal de cadastro de perfil
    $('#busca-cliente').modal('hide');
    $('#cadastro-perfil').modal('show');

    //remover o hidden da descricao
    $("#cadastro-cliente-descricao").removeAttr("hidden");
    
    //limpar busca-clientes-lista
    $("#busca-clientes-lista").html("");

    //limpar campo busca
    $("#cadastro-cliente-busca").val("");


}

// limpar cliente selecionado
function limparCliente(){
    //limpar cadastro-cliente-descricao e cadastro-cliente-id
    $("#cadastro-cliente-descricao").val("");
    $("#cadastro-cliente-id").val("");
    //adicionar hidden na descricao
    $("#cadastro-cliente-descricao").attr("hidden", true);
    //adicinar hidden no botao limpar
    $("#cadastro-cliente-limpar").attr("hidden", true);
}


campoBusca.addEventListener("keyup", function(event) {
        // Verifica se a tecla pressionada foi "Enter"
        if (event.keyCode === 13) {
            // Se a tecla "Enter" foi pressionada, chama a função "buscar"
        buscarCliente();
    }

    //foca ao abrir modal busca-cliente no id cadastro-cliente-busca
    $('#busca-cliente').on('shown.bs.modal', function () {
        $('#cadastro-cliente-busca').focus();
    })

});

function getRca(){
    //preencher cadastro-rca com os rca do banco
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "getRca"
        },
        success: function(data){
           // console.log(data);
            dados = JSON.parse(data);
            //console.log(dados);
            //limpar cadastro-rca
            $("#cadastro-rca").html("");
            $("#cadastro-rca").append(`
                    <option value="0">Selecione um Representante</option>
                `);
            //preencher cadastro-rca
            dados.forEach(element => {
                $("#cadastro-rca").append(`
                    <option value="${element.codrca}">${element.nome}</option>
                `);
            });
        }
    })
}

function limparPolitica(){
    $("#modal-ver-dados").html("");
}

function limparPerfil(){
    $("#cadastro-descricao").val("");
    $("#cadastro-rca").val("0");
    //limpar cliente
    limparCliente();
    $("#cadastro-obs").val("");
    //limpar busca;
    $("#cadastro-cliente-busca").val("");
    //limpar busca-clientes-lista
    $("#busca-clientes-lista").html("");
    //fechar modal
    $('#cadastro-perfil').modal('hide');
}

var politica = {};
function editarPerfil(){

    //verificar se algum perfil foi selecionado
    if($("input[name='codPerfil-radio']:checked").length == 0){
        alert("Selecione um perfil");
        return;
    }
    //buscar politica e preencher tabela para edição do desconto
    let codPerfil = $("input[name='codPerfil-radio']:checked").attr("id");
    //limpar 3 primeiros caracteres de codperfil
    codPerfil = codPerfil.substring(3);
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "getPoliticaPerfil",
            codPerfil: codPerfil
        },
        success: function(data){
            
            dados = JSON.parse(data);
            //atualiza politica
            politica = dados;
            //limpar modal-ver-dados
            $("#editar-perfil-table").html("");
            //preencher modal-ver-dados
            dados.forEach(element => {
                $("#editar-perfil-table").append(`
                    <tr>
                        <th scope="row">${element.codGrupo}</th>
                        <td><span class="">${element.descricao}</span></td>
                        <td><input type="number" class="form-control d-block text-center" value="${element.desconto}"></td>
                    </tr>
                `);
            });
            $('#modal-editar-perfil').modal('show');
        }
    })

    
}

function limparSelecaoPerfil(){
    //remover o checked do radio selecionado do codperfil_radio
    $("input[name='codPerfil-radio']:checked").prop("checked", false);
}

function confirmarEditarPerfil(){
    //pegar o codperfil
    let codPerfil = $("#editar-codperfil").val();
    //pegar os valores dos inputs de desconto
    let descontos = $("#editar-perfil-table").find("input");
    //verifica quais valores foram alterados e salva o grupo e o desconto
    let politicaEditada = [];
    for(let i = 0; i < descontos.length; i++){
        if(descontos[i].value != politica[i].desconto){
            politicaEditada.push({
                codGrupo: politica[i].codGrupo,
                desconto: descontos[i].value,
                codPerfil: politica[i].codPerfil
            });
        }
    }
    //imprime politicaEditada
    console.log(politicaEditada);
    
    //salvar politicaEditada no banco
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "editarPoliticaPerfil",
            dados: politicaEditada
        },
        success: function(data){
            data = JSON.parse(data);
            //checa se data é array
            if(data[0] == 1){
                alert("Perfil editado com sucesso");
                $('#modal-editar-perfil').modal('hide');
            }else{
                alert(data);
            }
        }
    })

}

function excluirPerfil(){
    //verificar se algum perfil foi selecionado
    if($("input[name='codPerfil-radio']:checked").length == 0){
        alert("Selecione um perfil");
        return;
    }
    //pegar o codperfil
    let codPerfil = $("input[name='codPerfil-radio']:checked").attr("id");
    //limpar 3 primeiros caracteres de codperfil
    codPerfil = codPerfil.substring(3);
    //pegar senha em um confirm para excluir perfil, tipo password
    let senha = prompt("Digite sua senha para confirmar a exclusão do perfil");
    //limpa espaco no final de senha
    senha = senha.trim();
    //excluir perfil
    $.ajax({
        url: "control/perfilController.php",
        type: "POST",
        data: {
            action: "excluirPoliticaPerfil",
            codPerfil: codPerfil,
            senha: senha
        },
        success: function(data){
            console.log(data);
            //checa se data é array
            if(data == 1){
                alert("Perfil excluido com sucesso!!!");
                //renderiza tabela de perfis
                getPerfis();
            }else{
                console.log(data);
                alert('Senha incorreta');
                getPerfis();
            }
        }
    })
}

function copiarPerfil(){
    //open modal modal-distribuicao
    $('#modal-distribuicao').modal('show');

}
