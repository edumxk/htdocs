let sidebar = document.querySelector(".menu");
let home = document.querySelector(".principal");
let btn = document.querySelector("#btn");
let modalContent = document.querySelector(".modal-content");


btn.onclick = function(){
    sidebar.classList.toggle("active");
    home.classList.toggle("active");
    modalContent.classList.toggle("active");
    //alert("Menu");
}

