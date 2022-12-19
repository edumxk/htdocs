<?php

if(isset($_GET['msg'])){
	$msg = $_GET['msg'];
    echo "<script>alert('Login ou Senha incorretos: $msg')</script>";
}
?>

<!DOCTYPE html>
<html lang="br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Kokar Intranet</title>
	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">
	<link rel="shortcut icon" type="image/x-icon" href="/Recursos/img/favicon.ico"> 
	<link href="recursos/css/bootstrap.min.css" rel="stylesheet">
	<link href="recursos/css/style.css" rel="stylesheet">
	<style>.cookieConsentContainer{z-index:999;width:350px;min-height:20px;box-sizing:border-box;padding:30px 30px 30px 30px;background:#232323;overflow:hidden;position:fixed;bottom:30px;right:30px;display:none}.cookieConsentContainer .cookieTitle a{font-family:OpenSans,arial,sans-serif;color:#fff;font-size:22px;line-height:20px;display:block}.cookieConsentContainer .cookieDesc p{margin:0;padding:0;font-family:OpenSans,arial,sans-serif;color:#fff;font-size:13px;line-height:20px;display:block;margin-top:10px}.cookieConsentContainer .cookieDesc a{font-family:OpenSans,arial,sans-serif;color:#fff;text-decoration:underline}.cookieConsentContainer .cookieButton a{display:inline-block;font-family:OpenSans,arial,sans-serif;color:#fff;font-size:14px;font-weight:700;margin-top:14px;background:#000;box-sizing:border-box;padding:15px 24px;text-align:center;transition:background .3s}.cookieConsentContainer .cookieButton a:hover{cursor:pointer;background:#3e9b67}@media (max-width:980px){.cookieConsentContainer{bottom:0!important;left:0!important;width:100%!important}}</style>
	</div>
</head>
<body style="background-color: #002695;">
	<div class="container" style="background-color: antiquewhite; border-style: solid; border-width: 1px; width: 300px; height: 400px; top:20%">
		<div class="row">
			<div class="col-md-12" style="border-bottom: solid; border-width: 1px; height: 70px; background-color: #002695; padding-bottom: 20px">
				<div style="padding-left:30px">
					<img src="../../recursos/src/Logo-kokar5.png" alt="Logo Kokar" style="width: 196px; height:69px">
				</div>
			</div>
			<div class="com-md-12" style="display:inline-block; width: 80%; padding: 10px; margin-left: auto; margin-right: auto; padding-top:50px">
				<form action="home.php" method="POST" autocomplete="off"> 
					<div class="form-group">
						<label for="login">Usuário</label>
						<input type="text" class="form-control" name="login">
					</div>
					<div class="form-group">
						<label for="senha">Senha</label>
						<input type="password" class="form-control" name="senha">
					</div>
					<button type="submit" class="btn btn-primary" name="submit">Entrar</button>
				</form>
			</div>
		</div>
	</div>
	<script src="recursos/js/jquery.min.js"></script>
	<script src="recursos/js/bootstrap.min.js"></script>
	<script src="recursos/js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
	<script>var purecookieTitle="Cookies Kokar",purecookieDesc="Este site utiliza cookies para te proporcionar uma melhor experiência. Ao continuar navegando, você aceita nossa ",purecookieLink='<a href="http://kokar.com.br/politica" target="_blank">Política de Privacidade.</a>',purecookieButton="Eu aceito!";function pureFadeIn(e,o){var i=document.getElementById(e);i.style.opacity=0,i.style.display=o||"block",function e(){var o=parseFloat(i.style.opacity);(o+=.02)>1||(i.style.opacity=o,requestAnimationFrame(e))}()}function pureFadeOut(e){var o=document.getElementById(e);o.style.opacity=1,function e(){(o.style.opacity-=.02)<0?o.style.display="none":requestAnimationFrame(e)}()}function setCookie(e,o,i){var t="";if(i){var n=new Date;n.setTime(n.getTime()+24*i*60*60*1e3),t="; expires="+n.toUTCString()}document.cookie=e+"="+(o||"")+t+"; path=/"}function getCookie(e){for(var o=e+"=",i=document.cookie.split(";"),t=0;t<i.length;t++){for(var n=i[t];" "==n.charAt(0);)n=n.substring(1,n.length);if(0==n.indexOf(o))return n.substring(o.length,n.length)}return null}function eraseCookie(e){document.cookie=e+"=; Max-Age=-99999999;"}function cookieConsent(){getCookie("purecookieDismiss")||(document.body.innerHTML+='<div class="cookieConsentContainer" id="cookieConsentContainer"><div class="cookieTitle"><a>'+purecookieTitle+'</a></div><div class="cookieDesc"><p>'+purecookieDesc+" "+purecookieLink+'</p></div><div class="cookieButton"><a onClick="purecookieDismiss();">'+purecookieButton+"</a></div></div>",pureFadeIn("cookieConsentContainer"))}function purecookieDismiss(){setCookie("purecookieDismiss","1",7),pureFadeOut("cookieConsentContainer")}window.onload=function(){cookieConsent()};</script>
</body>
</html>