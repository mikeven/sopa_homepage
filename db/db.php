<?php
	/* --------------------------------------------------------- */
	/* Cupfsa Coins - Conexión a base de datos */
	/* --------------------------------------------------------- */
	/* --------------------------------------------------------- */
	ini_set( 'display_errors', 1 );

	$cfg = obtenerConfigBD();

	$server 	= $cfg["server"];
	$dbuser 	= $cfg["username"];
	$dbpass 	= $cfg["password"];
	$database 	= $cfg["database"];
	
	$dbh = mysqli_connect ( $server, $dbuser, $dbpass ) 
	or die('No se puede conectar a '.$server.": ". mysql_error());
	mysqli_select_db ( $dbh, $database );
	mysqli_query( $dbh, "SET NAMES 'utf8'" );

	/* --------------------------------------------------------- */
	/* --------------------------------------------------------- */
	function obtenerConfigBD(){
		//
		$config = array();
		$file = fopen( __DIR__."/database.ini", "r" ) or exit( "Error al leer archivo" );
		
		fgets( $file );
		while( !feof( $file ) )	{
			list( $key, $val ) = explode('=', fgets( $file ) );
			$config[$key] = trim( $val );
		}

		fclose( $file );

		return $config;
	}
	/* --------------------------------------------------------- */
	function cambiaf_a_mysql( $fecha ){
		//Obtiene una fecha del formato dd/mm/YYYY al formato YYYY-mm-dd
		list( $dia, $mes, $ano ) = explode( "/", $fecha );
    	$lafecha = "$ano-$mes-$dia";
		return $lafecha; 
	}
	/* --------------------------------------------------------- */
	function obtenerListaRegistros( $data ){
		//Devuelve un arreglo con los resultados de un resultset de BD
		$lista_c = array();
		if( $data ){
			while( $c = mysqli_fetch_array( $data ) ){
				$lista_c[] = $c;	
			}
		}
		return $lista_c;
	}
	/* --------------------------------------------------------- */
	function obtenerFechaConFormato($lnk, $fecha, $formato){
		$q = "Select DATE_FORMAT('$fecha','$formato') as fecha";
		echo $q;
		$fdata = mysql_fetch_array( mysql_query ( $q, $lnk ) );
		return $fdata["fecha"];		
	}
	/* --------------------------------------------------------- */
	function obtenerFechaActual(){
		//Obtiene la fecha actual de acuerdo a la zona horaria especificada, retornándola en varios formatos
		date_default_timezone_set( "America/Caracas" ); 
		
		$hora_actual_f1 = date("g:i a");		// Formato mm:ss am/pm
		$fecha_actual_f1 = date("d/m/Y");		// formato d.m.a
		$hora_actual_f2 = date("G:i:s");		// Formato mm:ss ( 24 horas )
		$fecha_actual_f2 = date("Y-m-d");		// Formato aaaa-mm-dd
		
		$fecha_f1['fecha'] = $fecha_actual_f1;
		$fecha_f1['hora'] = $hora_actual_f1;
		$fecha_f2['fecha'] = $fecha_actual_f2;
		$fecha_f2['hora'] = $hora_actual_f2;
		$fecha["f1"] = $fecha_f1;
		$fecha["f2"] = $fecha_f2;
		
		return $fecha;
	}
	/* --------------------------------------------------------- */
	function escaparTexto( $dbh, $texto ){
		// Devuelve un texto escapado
		if( is_string( $texto ) )
			return mysqli_real_escape_string( $dbh, $texto );
	}
	/* --------------------------------------------------------- */
	function escaparCampos( $dbh, $registro ){
		// Devuelve un arreglo con los valores escapados de sus  
		// campos de tipo string
		foreach ( $registro as $campo => $valor ) {
			$registro[$campo] = $valor;
			if( is_string( $valor ) )
				$registro[$campo] = mysqli_real_escape_string( $dbh, $valor );
		}
		return $registro;
	}
	/* --------------------------------------------------------- */
	function registroAsociadoTabla( $dbh, $tabla, $campo, $valor ){
		//Determina si existe un registro asociado a una tabla
		$asociado = false;
		$q = "select * from $tabla where $campo = $valor";
		
		$nrows = mysqli_num_rows( mysqli_query ( $dbh, $q ) );
		
		if( $nrows > 0 ) $asociado = true;

		return $asociado;
	}
	/* --------------------------------------------------------- */
?>	