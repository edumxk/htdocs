// AÇÕES LIGADAS COM CONTROLE
var modal = $("#modalEditar");
var modal2 = $("#modalProdutos");
function editar(op, custo, qtProduzir, rendimento, custoajuste, custoTotal) {
  let dataset = op;
  let custotot = custo * qtProduzir;
  let totqt = 0.0;
  let totcusto = 0.0;

  $.ajax({
    type: "POST",
    url: "control/CentralOp.php",
    data: {
      action: "editar",
      dataset: dataset,
    },
    success: function (response) {
      array = JSON.parse(response);
      produtos = ``;
      for (i = 0; i < array.length; i++) {
        totcusto = parseFloat(array[i].custo) + totcusto;
        totqt = parseFloat(array[i].qt) + totqt;
        let custoPrint = calcularCustoUn(
          parseFloat(array[i].custo),
          custoTotal,
          custoajuste
        ); //calculo de parte de custo total
        produtos =
          produtos +
          `
                        <tr>\n
                            <td>${array[i].codProd}</td>\n
                            <td>${array[i].descricao}</td>\n
                            <td class="numero">${parseFloat(
                              array[i].qt
                            ).toFixed(3)}</td>\n
                            <td class="numero">${parseFloat(
                              array[i].custo
                            ).toFixed(3)}</td>\n
                            <td class="numero">${parseFloat(
                              custoPrint * 100
                            ).toFixed(3)}</td>\n
                        </tr>\n
                        `;
      }
      $("#modal-dados").html(produtos);
      $("#custo_qt").html(totqt.toFixed(3));
      $("#custo_tot").html(totcusto.toFixed(3));

      totcusto = calcularCusto(
        totcusto,
        custotot,
        totqt,
        qtProduzir,
        rendimento,
        custo
      ); //calculo de custo total

      $("#custo_lanc").html((totcusto * 100).toFixed(3));
      modal.modal("show");
    },
  });
}

function editarOP(codprod) {
  modal2.modal("show");
  $("#codprod").focus();
}

function close() {
  modal.modal("toggle");
}

function calcularCusto(custoajuste, custoprod, qtajuste, qttotal, rend, custo) {
  let totcusto =
    (custoajuste + custoprod) / ((qtajuste + qttotal) * (1 + rend));
  return totcusto / custo - 1;
}

function calcularCustoUn(custo, custoTotal, custoAjuste) {
  let totcusto = (custo / custoTotal) * custoAjuste;
  return totcusto;
}

function getProduto(codprod){
    $.ajax({
        type: "POST",
        url: "control/CentralOp.php", 
        data: {
            action: "getProduto",
            codprod: codprod,
        },
        success: function (response) {
          
            if(response.match('Notice')) {
              $('#descricao').val('Produto não encontrado');
              $('#estoque').val('');
              return;
            }
            response = JSON.parse(response);
            
            if(response.DESCRICAO.match('Exceção') || response.DESCRICAO.match('Undefined'))
                $('#descricao').val('Produto não encontrado');
            else
                $('#descricao').val(response.DESCRICAO);
                $('#estoque').val(response.ESTOQUE);
            
        }
    });
}

$('#codprod').on('keyup', function(){
  let codprod = $('#codprod').val()
  if(codprod.length == 0)
    $('#descricao').val('');
  else
    getProduto(codprod);
});

function addItem(){
  let codprod = $('#codprod').val();
  let descricao = $('#descricao').val();
  let qt = $('#qtdProd').val();
  let estoque = $('#estoque').val();

  let dataset = {
    codprod: codprod,
    descricao: descricao,
    qt: qt,
  };

  if(codprod == 0 || qt == 0){
    alert('Preencha todos os campos!');
    return;
  }

  if(parseFloat(estoque) < parseFloat(qt)){
    alert('Quantidade maior que o estoque!');
    return;
  }
  if(0 >= parseFloat(qt)){
    alert('Quantidade maior que o estoque!');
    return;
  }

  $.ajax({
    type: "POST", 
    url: "control/CentralOp.php",
    data: {
      action: "addItem",
      dataset: dataset,
    },
    success: function (response) {
        console.log(response);
    }
  });

      
        
  let item = `
    <tr>
      <td>${codprod}</td>
      <td>${descricao}</td>
      <td>${qt}</td>
      <td>12</td>
      <td>2.56%</td>
    </tr>
  `;

  $('#table-produtos').append(item);
}

