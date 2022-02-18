let sidebar = document.querySelector(".menu");
let home = document.querySelector(".principal");
let btn = document.querySelector("#btn");


btn.onclick = function(){
    sidebar.classList.toggle("active");
    home.classList.toggle("active");
    //alert("Menu");
}

