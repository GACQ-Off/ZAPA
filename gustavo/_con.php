<?php $host = 'localhost';
	$user = 'root';
	$password = '';
	$db = 'sistema_trapiche_obras';
	$conexion = @mysqli_connect($host,$user,$password,$db);
	if(!$conexion){error_log('Error en la conexión');} ?>