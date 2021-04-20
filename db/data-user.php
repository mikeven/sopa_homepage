<?php
	/* ----------------------------------------------------------------------------------- */
	/* SOPA Life Planner - Funciones de usuarios */
	/* ----------------------------------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	function ultimaActualizacion( $dbh, $idu ){
		//Retorna la fecha donde se realizó la última actualización de documentos de usuario
		$q = "select date_format(ultima_act_doc,'%Y-%m-%d') as fecha from clients where id = $idu";
		$data = mysql_fetch_array( mysql_query ( $q, $dbh ) );
		
		return $data["fecha"];
	}
	/* ----------------------------------------------------------------------------------- */
	function chequearActualizacion( $dbh, $hoy, $idu ){
		//Chequea el estado de actualización de documentos e invoca a su revisión
		include( "bd/data-documento.php" );
		$fult_act_docs = ultimaActualizacion( $dbh, $idu );
		
		if( $fult_act_docs < $hoy ){
			revisarEstadoDocumentos( $dbh, $idu, $hoy );
		}		
	}
	/* ----------------------------------------------------------------------------------- */
	function checkSession( $param ){
		
		
	}
	/* ----------------------------------------------------------------------------------- */
	function usuarioValido( $usuario, $dbh ){
		$valido = true;

		$q = "select usuario from usuario where usuario = '$usuario'";
		$data_user = mysql_fetch_array( mysql_query ( $q, $dbh ) );
		if( $usuario == $data_user["usuario"] ) $valido = false;

		return $valido;
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioSesion( $dbh ){
		//Devuelve los datos del usuario con sesión iniciada
		
		$idu = $_SESSION["user"]["id"];
		$q = "select * from clients where id = $idu";

		$data_user = mysqli_fetch_array( mysqli_query( $dbh, $q ) );
		return $data_user;					
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioPorId( $idu, $dbh ){
		//Devuelve los datos de un usuario dado su id
		$sql = "select * from clients where id = $idu";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $sql ) );
		return $data_user;					
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioPorToken( $dbh, $token ){
		//Devuelve los datos de un usuario dado su token
		$q = "select * from usuario where token = '$token'";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
		return $data_user;					
	}
	/* ----------------------------------------------------------------------------------- */
	function registrarNuevoUsuario( $dbh, $usuario ){
		//Registro de nuevo usuario

		$q = "insert into usuario ( nombre, apellido, email, password, token, creado ) 
		values ( '$usuario[name]', '$usuario[lastname]', '$usuario[email]', '$usuario[password]', 
		'$usuario[token]', NOW() )";
		
		$Rs = mysqli_query( $dbh, $q );
		return mysqli_insert_id( $dbh );	
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioLogin( $lnk, $email, $pass ){
		//Obtiene los datos de un usuario por email y contraseña
		$sql = "select * from clients where email = '$email' and password='$pass'";
		
		$data = mysqli_query ( $lnk, $sql );		
		$data_user = mysqli_fetch_array( $data );

		return $data_user;
	}
	/* ----------------------------------------------------------------------------------- */
	function sesionUsuarioBloqueado( $dbh ){
		//Devuelve verdadero si el usuario en sesión activa está bloqueado
		$bloqueado = false;

		$usuario = obtenerUsuarioSesion( $dbh );
		if( $usuario && $usuario["blocked"] == 1 ) 
			$bloqueado = true;
		
		return $bloqueado;
	}
	/* ----------------------------------------------------------------------------------- */
	function checkUsuarioBloqueado( $dbh ){
		if( sesionUsuarioBloqueado( $dbh ) ){
			echo "<script> window.location = 'index.php?logout'</script>";
		}
	}
	/* ----------------------------------------------------------------------------------- */
	function usuarioYaRegistrado( $dbh, $email ){
		//Determina si ya existe un usuario registrado dado su email
		$existe = false;
		$q = "select * from usuario where email = '$email'";
		$nrows = mysqli_num_rows( mysqli_query ( $dbh, $q ) );
		
		if( $nrows > 0 ) $existe = true;
		
		return $existe;
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarFechaUltimoInicioSesion( $dbh, $id ){
		// Actualiza la fecha de último inicio de sesión de clientes.
		$actualizado = 1;
		$q = "update clients set last_login = NOW() where id = $id";
		mysqli_query ( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;
	}
	/* ----------------------------------------------------------------------------------- */
	function registrarInicioSesion( $dbh, $idc ){
		// Registra la fecha y hora en la cual un usuario inicia sesión
		$q 	= "insert into client_logins ( date, fk_client ) values ( NOW(), $idc )";
		
		$Rs = mysqli_query( $dbh, $q );
		return mysqli_insert_id( $dbh );	
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerTokenUsuarioNuevo( $usuario ){
		//Genera un código provisional enviado por email para confirmar y verificar cuenta
		$fecha 	= date_create();
		$date 	= date_timestamp_get( $fecha );
		return sha1( md5( $date.$usuario["password"] ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function asignarNuevoTokenUsuario( $dbh, $token_actual, $token_nuevo ){
		// Actualiza el token de usuario dado su token actual
		$q = "update usuario set token = '$token_nuevo' where token = '$token_actual'";
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioTokenNuevoPassword( $dbh, $token ){
		//Devuelve los datos de un usuario asociado a un token de restablecimiento de contraseña
		$sql = "select * from clients where token = '$token'";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $sql ) );
		return $data_user;
	}
	/* ----------------------------------------------------------------------------------- */
	function verificarCuenta( $dbh, $id_usuario ){
		//Verifica cuenta de usuario después de su registro validado
		$actualizado = 1;
		$q = "update clients set verified = 1 where id = $id_usuario";
		$r = mysqli_query( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;	
	}
	/* ----------------------------------------------------------------------------------- */
	function asignarTokenRecuperacionPassword( $dbh, $idu, $token ){
		//Guarda el token de solicitud para restablecer contraseña
		$actualizado = 1;
		$q = "update clients set token = '$token' where id = $idu";
		$r = mysqli_query( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;	
	}
	/* ----------------------------------------------------------------------------------- */
	function chequearTokenCuenta( $dbh, $ta ){
		//Chequea si existe un token de usuario registrado para verificar cuenta
		$verificada = false;
		$q = "select id, token, verified from clients where token = '$ta'";
		$data = mysqli_query ( $dbh, $q );
		$data_user = mysqli_fetch_array( $data );
		$nrows = mysqli_num_rows( $data );
		
		if( $nrows > 0 ){
			verificarCuenta( $dbh, $data_user["id"] );
			$verificada = true;
		}
		return $verificada;
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarPasswordUsuario( $dbh, $password, $token ){
		//Asigna la nueva contraseña de usuario
		$actualizado = 1;
		$q = "update usuario set password = '$password' where token = '$token'";

		$r = mysqli_query( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerOrdenesNoLeidas( $dbh, $usuario ){
		//Devuelve el número de pedidos no leídos por el usuario
		$q = "select count(id) as noleidos from orders 
		where order_read = 'no-leido' and user_id = $usuario[id]";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $q ) );

		return $data_user["noleidos"];
	}
	/* ----------------------------------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	/* Solicitudes asíncronas al servidor para procesar información de usuarios */
	/* ----------------------------------------------------------------------------------- */
	//Detección de sesión
	if( isset( $_SESSION["login"] ) ){
		$idu = $_SESSION["user"]["id"];
	}else $idu = NULL;
	
	/* ----------------------------------------------------------------------------------- */
	//Registro de nueva cuenta de usuario (cliente)
	if( isset( $_POST["form_nu"] ) ){
		include( "db.php" );
		//include( "../fn/fn-mailing.php" );

		parse_str( $_POST["form_nu"], $usuario );

		if( usuarioYaRegistrado( $dbh, $usuario["email"] ) ){
			$res["exito"] 			= -1;
			$res["mje"] 			= "Dirección de email ya registrada, por favor use una diferente";
		}else{
			
			$usuario["token"] 		= obtenerTokenUsuarioNuevo( $usuario );
			$idu 					= registrarNuevoUsuario( $dbh, $usuario );
			
			if( $idu > 0 ){
				
				//$remail = enviarMensajeEmail( "usuario_nuevo", $usuario, $usuario["email"] );

				if( $remail["exito"] == 1 ){
					$res["exito"] 	= 1;
					$res["url"] 	= "registered_account.php?user_t=$usuario[token]";
				}
				else{
					$res["exito"] 	= -1;
					$res["mje"] 	= "Error al enviar mensaje: $remail[msg]";
				}

			}else{
				$res["exito"] 		= -2;
				$res["mje"] 		= "Error al registrar cuenta de usuario";
			}			
		}

		echo json_encode( $res );

	}
	
	/* ----------------------------------------------------------------------------------- */
	
	//Restablecimiento de contraseña
	if( isset( $_POST["new_passw"] ) ){
		include( "db.php" );
		
		parse_str( $_POST["new_passw"], $data );

		$res["exito"] = actualizarPasswordUsuario( $dbh, $data["password"], $data["token"] );
		
		if( $res["exito"] == 1 ){
			$nuevo_token = obtenerTokenUsuarioNuevo( $data );
			asignarNuevoTokenUsuario( $dbh, $data["token"], $nuevo_token );
			$res["mje"] = "Contraseña restablecida con éxito <a href='sopa/'>Iniciar sesión</a>";
		}
		else
			$res["mje"] = "Error al restablecer contraseña";
		
		echo json_encode( $res );	
	}
	
	/* ----------------------------------------------------------------------------------- */
	
	//Enviar datos de formulario de contacto
	if( isset( $_POST["form_ctc"] ) ){

		include( "bd.php" );
		include( "data-system.php" );
		include( "../fn/fn-mailing.php" );
		
		parse_str( $_POST["form_ctc"], $data );
		$demail = obtenerEmailNotificacionContacto( $dbh );
		$res = enviarMensajeEmail( "datos_contacto", $data, $demail["contact_email"] );

		if( $res["exito"] == 1 ){
			$res["mje"] = "Mensaje enviado con éxito";
		}
		else
			$res["mje"] = "Error al enviar mensaje";
		
		echo json_encode( $res );	
	}
	/* ----------------------------------------------------------------------------------- */
	//Enviar datos de formulario de contacto
	if( isset( $_GET["token"] ) ){

		$usuario = obtenerUsuarioPorToken( $dbh, $_GET["token"] );

	}
?>