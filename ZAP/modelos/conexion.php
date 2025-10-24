<?php

class Conexion{

	static public function conectar(){

		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		$link = new PDO("mysql:host=localhost;dbname=zapateriak",
			            "root",
			            "",$options);

		$link->exec("set names utf8");

		return $link;

	}

}