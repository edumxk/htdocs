let sidebar = document.querySelector(".menu");
let home = document.querySelector(".home-conteudo");
let btn = document.querySelector("#btn");
let sair = document.querySelector("#sair");

btn.onclick = function(){
    sidebar.classList.toggle("active");
    home.classList.toggle("active");
    //alert("Menu");
}

sair.onclick = function(){
    sidebar.classList.toggle("active");
    home.classList.toggle("active");
    //alert("Sair");
}
