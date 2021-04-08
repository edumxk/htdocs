<?php
//As duas linhas abaixo permitem que o usuário recarregue uma pagina com POST sem a
//a pergunta de se quer reenviar o formulário.
// header('Cache-Control: no cache'); //no cache
// session_cache_limiter('private_no_expire'); // works

session_start();


class Logout{
   public static function checkLogin(){

      if(!isset($_SESSION['nome'])){
         header("location: ../../index.php");
      }
      
      $timeout = 1200; // Number of seconds until it times out.
      
      // Check if the timeout field exists.
      if(isset($_SESSION['timeout'])) {
         $duration = time() - (int)$_SESSION['timeout'];
         if($duration > $timeout) {
            session_destroy();
            header("location: ../../index.php");
         }
      }
      
      // Update the timout field with the current time.
      $_SESSION['timeout'] = time();
      
   }

}

?>
