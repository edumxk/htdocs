
    function numeroParaVisual(numero) {
        //converte 1000.00 para 1.000,00
        var numero = parseFloat(numero)
        numero = numero.toFixed(4).split('.');
        numero[0] = numero[0].split(/(?=(?:...)*$)/).join('.');
        return numero.join(',');
    }

    function visualParaNumero(numero) {
        //converte 1.000,00 para 1000.00
        var numero = numero.replace('.', '');
        numero = numero.split(',').join('.');
        return numero;
    }