<?php
/**
 *
 * Implementacion de funciones a utilizar en todo el sistema
 *
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
**/
	$app = include_once dirname(__file__,2).'/config/confApp.php';//file of configuration/format/parameters

	function fDateDefault($date)//Format Date by defautl
	{
		return formatDateTime($date, $GLOBALS['app']['date']['format']);
	}

	function fMoneyDefault($value)//Format Money by defautl
	{
		return $GLOBALS['app']['money']['symbol'].' '.number_format(
				$value,
				intval($GLOBALS['app']['money']['decNumber']),
				$GLOBALS['app']['money']['decimal'],
				$GLOBALS['app']['money']['thousand']
			);
	}

	function fMoney($value)//Format Money by defautl
	{
		return number_format($value, intval($GLOBALS['app']['money']['decNumber']), '.', ',' );
	}

	function isString($string):bool//is string not null
	{
		if(empty($string)){
			return false;
		}else{
			if( !is_string($string) ){
				return false;
			}else{
				return true;
			}
		}
	}

	function isIntN($int):bool//is int natural
	{
		if( empty($int) ){ 
			return false; 
		}else{
			if( !is_numeric($int) || $int <= 0 ){ 
				return false;
			}else{
				return true;
			}
		}
	}

	//DataTime formata dinamyc 
	function formatDateTime($dataTime, $format):String
	{
		$result = '';
		if ( !empty($dataTime) && !empty($format) ) {
			$date = new DateTime($dataTime);
	    	$result = $date->format($format);
		}
		return $result;
	}

	//Get the difference of days between two dates
	function dDifference($start,$end){
		$dStart = new DateTime($start);
		$dEnd = new DateTime($end);
		$dDiff  = $dEnd->diff($dStart);
		return $dDiff;
	}

	//Get the year|month|day of day
	function lblDateFull($val,$get){
		//options of var get: m=>month d=>day y=>year  dmy|mdy|ydm...
		$label = '';
		$opt = str_split($get);
		if (in_array("y", $opt) || in_array("Y", $opt)) {
			if ( $val->y > 0 ) {
				if ($val->y == 1) {
					$label .= $val->y.' año, ';
				}else{
					$label .= $val->y.' años, ';
				}
			}
		}
		if ( in_array("m", $opt) || in_array("M", $opt) ){
			if (!empty($label) || $val->m > 0) {
				if ($val->m == 1) {
					$label .= $val->m.' mes, ';
				}else{
					$label .= $val->m.' meses, ';
				}
			}
		}
		if (in_array("d", $opt) || in_array("D", $opt)) {
			if ($val->d == 1) {
				$label .= $val->d.' dia';
			}else{
				$label .= $val->d.' dias';
			}
		}
		return $label;
	}

	//-- Formato de texto --
	function lowerCase($value)//Texto en minusculas (hola mundo)
	{
		return mb_strtolower($value);
	}

	function upperCase($value)//Texto en mayuscula (HOLA MUNDO)
	{
		return strtoupper($value);
	}

	function camelCase($value)//Primera letra de cada palabra en mayuscula (Hola Mundo)
	{
		return ucwords(mb_strtolower($value), "./- \t\r\n\f\v");
	}

	function sentenceCase($value)//Primera letra en mayuscula (Hola mundo)
	{
		return ucfirst(mb_strtolower($value));
	}

	//-- Funcion de encriptacion de texto
	function encode($action, $string):?string
	{
		//Funcion que cifra y decifra el valor dado
		$encrypt = new class($string) {//New Class anonymous
			private $encode;
			private $secret_key = 'me91H0v5YS38Txyk';
			private $secret_iv = 'U0l7jUCKeTKUO80s';
			private $method = "AES-256-CBC";
			private $key;// hash
			private $iv;// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			public function __construct($text)
			{
			    $this->encode = $text;
			}
			function encrypt():string
			{
				$this->key = hash('sha256', $this->secret_key);
				$this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);
				$prepare = openssl_encrypt($this->encode, $this->method, $this->key, 0, $this->iv);
				$output = base64_encode($prepare);
			    return $output;
			}
			function decrypt():string
			{
				$this->key = hash('sha256', $this->secret_key);
				$this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);
				$output = openssl_decrypt(base64_decode($this->encode), $this->method, $this->key, 0,$this->iv);
			    return $output;
			}
		};

	    if ( $action == 'encrypt' ) {
	    	return $encrypt->encrypt();
	    } else if( $action == 'decrypt' ) {
	    	return $encrypt->decrypt();
	    }
	}