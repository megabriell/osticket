<?php
/**
 * 
 * Controllador para la clase login
 *  
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
 *
**/

	include_once dirname(__file__,2).'/models/login.php';
	
	//Funcion que valida usuario registrado y permite el acceso
	if(isset($_POST['login']))
	{
		header("content-type: application/javascript");//retorna una respesta javascript
		$login = new Login();
		if ( $login->validData($_POST) )
		{
			echo "window.location.href = '../../';";//redirecciona al usuario
		}else{
			if (!empty($login->message)) {
				$contentMessage = $login->message;
				$typeMessage = $login->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
			$timeMessage = ($login->time)? "autoClose: 'Ok|10000'," : '';
			echo "$.alert({
				title: false,
				content: '".$contentMessage."',
				".$timeMessage."
				type: '".$typeMessage."',
				typeAnimated: true,
				buttons: {
					Ok: function () {}
					}
			});";
		}
	}
	

	//Funcion cierra session
	if(isset($_GET['logout']))
	{
		Login::doLogout();
	}

	//Funcion que devuelve nombre e imagenes de la empresa(datos registrados)
	if(isset($_POST['startpage']))
	{
		header("content-type: application/json");
		$login = new Login();
		$info = $login->infoCompany();
		$json = [
			'img' => 'default.png',
			'favicon' => 'default.ico',
			'company' => 'MyCompany'
	    ];
	    if($info)
	    {
	    	$json = [
	    		'img' => ($info['lsegundario'])? $info['lsegundario'] : 'default.png',
	    		'favicon' => ($info['favicon'])? $info['favicon'] : 'default.ico',
	    		'company' => ($info['nombre'])? $info['nombre'] : 'MyCompany'
	    	];
	    }
	    echo json_encode($json);
	}
	
?>