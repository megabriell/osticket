<?php
//$config['money']['decNumber']
$config['money'] = [
	'symbol'=>'Q',
	'thousand'=>',',
	'decimal'=>'.',
	'decNumber'=>2
];

$config['date'] = [
	'format'=>'d/m/Y',
];

$config['mail'] = [
	'host'=>'smtp.office365.com',
	'user'=>'mgabriel@grupodelta.com.gt',
	'pass'=>'ajajjaja',
	'port'=>587
];

$config['rol'] = [
	1=>'Administrador',
	2=>'Usuario',
];

$config['status'] = [
	1=>'Sin Asignar',
	2=>'Abierto',
	3=>'Cerrado'
];
return $config;