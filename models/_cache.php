<?php
/**
 *
 * Clase para almacenar informacion en cache(Archivo temporal en el servidor)
 *
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
**/

class Cache{

	private $cachedir; // directorio de cache
	private $cachetime; // duración del cache segundos
	
	function __construct()
	{
		$this->cachedir = dirname(__file__,2)."/config/cache/";
		$this->cachetime = 1800;
	}

	public function readCache($filename,$timeExpire=null){
		$data = "";
		$filename = $this->cachedir.$filename.".tmp";

		$this->cachetime = ( !empty($timeExpire) ) ? $timeExpire : $this->cachetime ;
		// Calculamos el tiempo de cache
		if ( file_exists($filename) ) {
			$cachelast = filemtime($filename);
		} else {
			$cachelast = 0;
		}
		clearstatcache();

		// Mostramos el archivo si aun no vence
		if (time() - $this->cachetime < $cachelast) {
			$data = json_decode(file_get_contents($filename),true);
		}else{
			if (file_exists($filename)) {
				unlink($filename);
			}
			return false;
		}
		return $data;
	}

	public function addCache($filename,$content)
	{
		// Generamos el nuevo archivo cache
		$fp = fopen($this->cachedir.$filename.".tmp", 'w');
		// guardamos el contenido
		fwrite($fp, json_encode($content) );
		fclose($fp);
		return true;
	}

	public function clear($filename){
		$filename = $this->cachedir.$filename.".tmp";
		if (file_exists($filename)) {
			unlink($filename);
			return true;
		}else{
			return false;
		}
	}
}

?>