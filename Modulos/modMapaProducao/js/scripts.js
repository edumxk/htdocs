//console.log('inicio da carga automatica');
  var _segundo = 1000;
  var _minuto = _segundo * 60;
  var _hora = _minuto * 60;
  var _dia = _hora * 24;
  callTimer()
  //setInterval(function () {callTimer()},1000); 
  //setInterval(function () {callUpdate()},60000); 

function callTimer(){
  e =  $(".contador").children() //chama a classe a ser manipulada pela função
  
  $.each( e, function( i, el ) { 
    let temp = ($(el).val().split('-'));
    attTimer(formatarData(temp[1]),temp[0])
    setInterval(function () { //chama as funções de atualização de tempo
    attTimer(formatarData(temp[1]),temp[0])
  },1000);
  })
}

function attTimer(dataInicio, codproducao){
  var tempo = [];
  var tempoStatus = new Date() - dataInicio; 
  tempo = attRelogio(tempoStatus);
  var elem = $(`#${codproducao}`)
  Array.from(elem).forEach((el) => {
    el.innerHTML =  tempo[0] + ' dia(s) ' +    padTo2Digits(tempo[1]) + ':' + padTo2Digits(tempo[2]) + ':' + padTo2Digits(tempo[3]);
  });
  
}

function attRelogio(tempoStatus){ //retorna o calculo do tempo, adicionei abs para calcular tempo negativo tambem, como contagem regressiva.
  var tempo = [];
  tempo.push(Math.floor(tempoStatus / _dia));
  if( (tempoStatus % _dia) / _hora < 0 )
    tempo.push(Math.abs(Math.floor((tempoStatus % _dia) / _hora))-1);
  else
    tempo.push(Math.abs(Math.floor((tempoStatus % _dia) / _hora)));
  tempo.push(Math.abs(Math.floor((tempoStatus % _hora) / _minuto)));
  tempo.push(Math.abs(Math.floor((tempoStatus % _minuto) / _segundo)));
  return tempo;
}

function formatarData(data){
  data = data.split('\/')
  temp = data[2].split(' ')
  data = temp[0]+'-'+data[1]+"-"+data[0]+"T"+temp[1];
  return (new Date(data));
}

function padTo2Digits(num) {
  return num.toString().padStart(2, '0');
}

function callUpdate(){
  if(confirm('OP xxxxx foi apontada, lote xxxxx, deseja atualizar a pagina?')){
    refresh();
  }
}