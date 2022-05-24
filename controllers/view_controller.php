<?php
/**
 * 
 * Controllador para cargas las vistas
 *  
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
 *
**/

	include_once dirname(__file__,2).'/config/conexion.php';
	$sessionUser = Conexion::session();
	//avoid Cross-site request forgery
	if( empty($sessionUser) || $_SERVER['HTTP_CSRF'] !== $sessionUser['Token'])
	{
		header('Content-Type: application/javascript');
		$url = 'http://' . $_SERVER['HTTP_HOST'];
        $url .= rtrim(dirname($_SERVER['PHP_SELF'],2), '/\\');
		echo "$.alert({
			title: 'Error Interno',
			content: '<strong>Error [1007]</strong>: No se han procesado los datos correctamente. Notifique el error si se vuelve a presentar',
			type: 'red',
			typeAnimated: true,
			buttons: {
				Ok: function () {
					window.location.href = '".$url."/views/login/';
				}
			}
		});";
		return false;
	}

	$typeMessage = '';//type of menssage: red|green|orange|blue|purple|dark
	
	if (isset($_POST['vw'])) {
		$home = dirname(__file__,2).'/views/home/home.php';
		if ($_POST['vw'] == 'home' ) {
			require_once $home;//Pagina inicio por defecto
		}elseif (!empty($_POST['vw']) ) {
				$path = strpos($_POST['vw'], '/');//busca un caracter "/" dentro del string
				if ($path !== false) {//si contien el caracter "/"
					$pathView = dirname(__file__,2).'/views/'.$_POST['vw'].'.php';
					if (file_exists($pathView)) {//si el archivo existe
						require_once $pathView;
					}else{
						$contentMessage = "La ruta del archivo no existe: ".$pathView;
						require_once $home;//Pagina inicio por defecto
					}
				}else{
					$pathView = dirname(__file__,2).'/views/'.$_POST['vw'].'/'.$_POST['vw'].'.php';
					if (file_exists($pathView)) {//si el archivo existe
						require_once $pathView;
					}else{
						$contentMessage = "La ruta del archivo no existe: ".$pathView;
						require_once $home;//Pagina inicio por defecto
					}
				}
		}else{
			$contentMessage = 'Opcion de menu incorrecto';
			require_once $home;//Pagina inicio por defecto
		}
		if (!empty($contentMessage)) {
			echo "<script>$.alert('".$contentMessage."');</script>";
		}
	}
	?>
	