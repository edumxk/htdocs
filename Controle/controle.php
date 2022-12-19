<?php

if(isset($_POST['action'])){
    
    if($_POST['action']=='sair'){
        session_start();
        echo session_destroy();
    }
}