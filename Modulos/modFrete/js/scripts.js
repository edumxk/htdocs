// on document load, ler valores da tabela body despesas e somar os valores
// e colocar no campo total
$(document).ready(function() {
    console.log('scripts.js loaded');
    var total = 0;
    $('#despesas tbody tr').each(function() {
        let dados = parseFloat($(this).find('td').eq(1).text());
        console.log(dados);
        total += dados;
    });
    $('#total').text(total.toFixed(2));
});
