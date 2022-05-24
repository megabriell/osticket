<?php
	require_once dirname(__file__).'/config/conexion.php';
	$_session = Conexion::session();

	$url = 'http://' . $_SERVER['HTTP_HOST'];
	$url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');//PHP_SELF: retorna el nombre y ruta del archivo de script ejecutándose actualmente

	if(  !$_session && !isset($_session['Token']) )echo '<script type="text/javascript">window.location.href = "'.$url.'/views/login/";</script>';//Si no se detecta un inicio de sesion se redirecciona a la pagina de login
	

	$infoUser = json_decode( encode('decrypt',$_COOKIE["_data0U"]) ,true);//Cookie de informacion empresa
	$infoCompany = json_decode( encode('decrypt',$_COOKIE["_data1C"]) ,true);//Cookie de informacion usuario
	
	include dirname(__file__).'/views/template/header.php';

		echo '<div id="contentBody">';
		echo '</div>';

		echo '<script type="text/javascript">
			$.ajaxSetup({ headers: {"CSRF": "'.$_session['Token'].'"} });
			$.post("./controllers/view_controller",{vw:"home"}).done(function(data){
				$("#contentBody").html(data)});
		</script>';//Set toket of ajax. GET $_POST['csrf_token']
	include dirname(__file__).'/views/template/footer.php';