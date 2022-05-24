<?php
/**
 * Archivo de conexion a la base de datos, haciendo uso de la libreria ezSQL_pdo 
 * -Author:Justin Vincent - http://justinvincent.com/ezsql
 * Extension de conexion a BD: PDO
 * 
 *	
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2021, Manuel Gabriel
 *
 *
**/

include_once dirname(__file__,2).'/config/ezSQL/ez_sql_core.php';
include_once dirname(__file__,2).'/config/ezSQL/ez_sql_pdo.php';
include_once dirname(__file__,2).'/models/_funtions.php';
//include_once dirname(__file__,2).'/models/_cache.php';

class Conexion extends ezSQL_pdo
{
	private $dsn = 'mysql:host=localhost:3306;dbname=ticketos';
	private $user = 'root';
	private $password = 'sa@manager';
	private $configdb = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\'');

	public $cache;
	Public $device;
	public $messageDB;

	function __construct()
	{
		date_default_timezone_set('America/Guatemala');//Zona horaria
		try {
			//$this->cache = new Cache();
			$this->hide_errors();
			//validacion de parametros minimos, para una conexion a BD
			if ( !empty($this->dsn) && !empty($this->user) && !empty($this->password) && !empty($this->configdb) ) {
				if( $this->connect($this->dsn, $this->user, $this->password, $this->configdb) ){
				}else{
					throw new Exception("Errro interno: se ha detectado un problema con las credenciales ingresadas");
				}
			}else{
				throw new Exception("Errro interno: Uno de los valore de conexion no es valido");
			}
		}catch (Exception $e){
			trigger_error($this->last_error.' -- '.$e->getMessage(),E_USER_WARNING);
			$this->messageDB = $e->getMessage();
		}
	}

	public static function session():?array//Return function array or null "?array" -- Function to validate session of user
	{
		session_start();
		$array = [];
		$array['iduser'] = (isset($_SESSION['idusuario']))? $_SESSION['idusuario'] : '';
		$array['rol'] = (isset($_SESSION['rol']))? $_SESSION['rol'] : '';//contains the user system role
		$array['logged'] = (isset($_SESSION['session']))? $_SESSION['session'] : '';//simple token
		$array['Token'] = (isset($_SESSION['csrf']))? $_SESSION['csrf'] : '';//Token CSRF
		if (empty( $array['logged'] ) || $array['logged'] != (37197314)){
			$array = NULL;
		}
		return $array;//getValue: echo $array['idEmployee']
	}

}//End class Conexion