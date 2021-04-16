<?php
	/* ----------------------------------------------------------------------------------- */
	/* Argyros - Funciones de usuarios */
	/* ----------------------------------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	define( "PFXCARTFILE", "savedusercart-id_" );

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
		
		if( isset( $_SESSION["login"] ) ){
			if( $param != "" && $param != "catalogo" ) 
				echo "<script> window.location = 'categories.php'</script>";
		}else{
			if( $param == "" && $param != "registro" && $param != "verificacion" )
				echo "<script> window.location = 'index.php'</script>";	
			if( $param == "catalogo" )
				echo "<script> window.location = 'categories.php'</script>";	
		}
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
	function obtenerValoresGrupoUsuarioDefecto( $dbh ){
		//Devuelve los multiplicadores asociados a los precios del perfil de usuario por defecto
		$q = "select id, name, description, variable_a, variable_b, variable_c, variable_d, material 
		from client_group where name = 'Defecto'";
		
		$data_user = mysqli_fetch_array( mysqli_query( $dbh, $q ) );
		return $data_user;
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerValoresGrupoUsuario( $dbh, $grupo ){
		//Devuelve los multiplicadores asociados a los precios de acuerdo al perfil de usuario
		$q = "select id, name, description, variable_a, variable_b, variable_c, variable_d, material 
		from client_group where id = $grupo";
		
		$data_user = mysqli_fetch_array( mysqli_query( $dbh, $q ) );
		return $data_user;
	}
	/* ----------------------------------------------------------------------------------- */
	function variablesGrupoUsuario( $dbh ){
		//Devuelve los valores de las variables según el perfil de la cuenta en sesión o sin sesión
		
		if( isset( $_SESSION["user"]["client_group_id"] ) ){
			$usuario = obtenerUsuarioSesion( $dbh );
			$grupo_u = $usuario["client_group_id"];
			$var_gr_usuario = obtenerValoresGrupoUsuario( $dbh, $grupo_u );
		}
		else
			$var_gr_usuario = obtenerValoresGrupoUsuarioDefecto( $dbh );

		return $var_gr_usuario;
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioPorId( $idu, $dbh ){
		//Devuelve los datos de un usuario dado su id
		$sql = "select * from clients where id = $idu";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $sql ) );
		return $data_user;					
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioPorEmail( $email, $dbh ){
		//Devuelve los datos de un usuario dado su email
		$sql = "select * from clients where email = '$email'";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $sql ) );
		return $data_user;					
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerEmailNotificacionPedidos( $dbh ){
		//Devuelve el email configurado para recibir notificaciones sobre pedidos
		$sql = "select orders_email from admin_configs";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $sql ) );

		return $data_user["orders_email"];
	}
	/* ----------------------------------------------------------------------------------- */
	function registrarNuevoUsuario( $dbh, $usuario ){
		//Registro de nuevo usuario (cliente)
		//user_group_id (1) : Defecto -> Tipo de usuario por defecto

		$q = "insert into usuario ( nombre, apellido, email, password, token, creado ) 
		values ( '$usuario[name]', '$usuario[lastname]', '$usuario[email]', '$usuario[password]', 
		'$usuario[token]', NOW() )";
		
		$Rs = mysqli_query( $dbh, $q );
		return mysqli_insert_id( $dbh );	
	}
	/* ----------------------------------------------------------------------------------- */
	function registrarRolUsuario( $dbh, $idu, $idr, $nombre_rol ){
		//Asociación rol a un usuario
		$q = "insert into role_user ( user_id, role_id ) values ( $idu, $idr )";
		
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
	function userLogin( $data_user ){
		session_start();
		
		$_SESSION["login"] = 1;
		$_SESSION["user"] = $data_user;
		$_SESSION["cart"] = array();
		
		$login = true; 

		return $login;
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
	function modificarDatosUsuario( $usuario, $dbh ){
		//Actualiza los datos de cuenta de usuario
		$actualizado = 1;
		$cmpy = 0; if( $usuario[tcliente] != "Particular" ) $cmpy = 1;
		$q = "update clients set first_name = '$usuario[name]', last_name = '$usuario[lastname]', 
		phone = '$usuario[phone]', company_type = '$usuario[tcliente]', company = $cmpy,  
		company_name = '$usuario[nempresa]', country_id = '$usuario[pais]', 
		city = '$usuario[ciudad]' where id = $usuario[idusuario]";
		
		mysqli_query ( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;
	}
	/* ----------------------------------------------------------------------------------- */
	function modificarPasswordCuenta( $usuario, $dbh ){
		//Actualiza el valor de contraseña de usuario
		$actualizado = 1;
		$q = "update clients set password = '$usuario[password1]' where id = $usuario[idusuario]";
		
		mysqli_query ( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;
	}
	/* ----------------------------------------------------------------------------------- */
	function modificarEmailCuenta( $usuario, $dbh ){
		//Actualiza el valor del email de usuario
		$actualizado = 1;
		$q = "update clients set email = '$usuario[email]' where id = $usuario[idusuario]";
		
		mysqli_query ( $dbh, $q );
		if( mysqli_affected_rows( $dbh ) == -1 ) $actualizado = 0;
		
		return $actualizado;
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerTokenUsuarioNuevo( $usuario ){
		//Genera un código provisional enviado por email para confirmar y verificar cuenta
		$fecha = date_create();
		$date = date_timestamp_get( $fecha );
		return sha1( md5( $date.$usuario["passw1"] ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function generarTokenRecuperarPassword( $usuario ){
		//Genera un código provisional enviado por email para solicitar restablecimiento de contraseña
		$fecha = date_create();
		$date = date_timestamp_get( $fecha );
		return sha1( md5( $date.$usuario["email"] ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerUsuarioTokenNuevoPassword( $dbh, $token ){
		//Devuelve los datos de un usuario asociado a un token de restablecimiento de contraseña
		$sql = "select * from clients where token_password_recovery = '$token'";
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
		$q = "update clients set token_password_recovery = '$token' where id = $idu";
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
	function obtenerTokenRecuperacionPassword( $dbh, $usuario ){
		//Genera, asigna y devuelve el token de solicitud para restablecimiento de contraseña
		$token_password = generarTokenRecuperarPassword( $usuario );
		asignarTokenRecuperacionPassword( $dbh, $usuario["id"], $token_password );

		return $token_password;
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarPasswordUsuario( $dbh, $password, $idu ){
		//Asigna la nueva contraseña de usuario
		$actualizado = 1;
		$q = "update clients set password = '$password' where id = $idu";
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
	function cargarContenidoCarritoArchivo(){
		//Obtiene el contenido del carrito previamente guardado y lo carga en la variable de sesión

		$filename 			= PFXCARTFILE.$_SESSION["user"]["id"];

		$filecart 			= file_get_contents( "../fn/ckfiles/".$filename.".json" );
		$_SESSION["cart"] 	= json_decode( $filecart, true );
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
	//Inicio de sesión (asinc)
	if( isset( $_POST["usr_login"] ) ){
		include( "bd.php" );
		//include( "../fn/fn-cart.php" );

		parse_str( $_POST["usr_login"], $usuario );

		$data_user = obtenerUsuarioLogin( $dbh, $usuario["email"], $usuario["password"] );
		
		if( $data_user ){
			
			if( $data_user["verified"] != 1 ){
				$res["exito"] = -1;
				$res["mje"] = "<p>Su cuenta no ha sido activada aún. Chequee su buzón de correo y siga las instrucciones para activar su cuenta.".
				"<br>Si no ha recibido el mensaje, haga clic en el siguiente enlace</p>".
				"<p><button id='btn_login' class='btn'>Reenviar mensaje de activación</button></p>";
			}
			if( $data_user["blocked"] == 1 ){
				$res["exito"] = -2;
				$res["mje"] = "<p>Cuenta deshabilitada para iniciar sesión.".
				"<br>Póngase en contacto con el administrador de la página</p>";
			}
			if( ( $data_user["verified"] == 1 ) && ( $data_user["blocked"] != 1 ) ){
				userLogin( $data_user, $dbh );
				//actualizarFechaUltimoInicioSesion( $dbh, $data_user["id"] );
				cargarContenidoCarritoArchivo();
				registrarInicioSesion( $dbh, $data_user["id"] );
				$res["exito"] = 1;
				$res["mje"] = "Inicio de sesión exitoso";
			}

		}else{
			$res["exito"] = 0;
			$res["mje"] = "Usuario o contraseña incorrecta, chequee sus credenciales";
		}
		
		echo json_encode( $res );
	}
	/* ----------------------------------------------------------------------------------- */
	//Cierre de sesión
	if( isset( $_GET["logout"] ) ){
		//include( "bd.php" );
		unset( $_SESSION["login"] );
		unset( $_SESSION["user"] );
		unset( $_SESSION["cart"] );
		echo "<script> window.location = 'index.php'</script>";		
	}	
	/* ----------------------------------------------------------------------------------- */
	//Registro de nueva cuenta de usuario (cliente)
	if( isset( $_POST["form_nu"] ) ){
		include( "bd.php" );
		//include( "../fn/fn-mailing.php" );

		parse_str( $_POST["form_nu"], $usuario );

		if( usuarioYaRegistrado( $dbh, $usuario["email"] ) ){
			$res["exito"] 		= -1;
			$res["mje"] 		= "Dirección de email ya registrada, intente usar una dirección de correo diferente";
		}else{
			
			$usuario["token"] 	= obtenerTokenUsuarioNuevo( $usuario );
			$idu 				= registrarNuevoUsuario( $dbh, $usuario );
			
			if( $idu > 0 ){
				
				//$remail = enviarMensajeEmail( "usuario_nuevo", $usuario, $usuario["email"] );

				if( $remail["exito"] == 1 ){
					$res["exito"] = 1;
					$res["url"] = "registered_account.php?user_t=$usuario[token]";
				}
				else{
					$res["exito"] = -1;
					$res["mje"] = "Error al enviar mensaje: $remail[msg]";
				}

			}else{
				$res["exito"] = -2;
				$res["mje"] = "Error al registrar cuenta de usuario";
			}			
		}

		echo json_encode( $res );

	}
	/* ----------------------------------------------------------------------------------- */
	//Modificar datos de usuario. Bloque: datos personales
	if( isset( $_POST["form_act_dp"] ) ){
		include( "bd.php" );
		
		parse_str( $_POST["form_act_dp"], $usuario );
		
		$res["exito"] = modificarDatosUsuario( $usuario, $dbh );
		
		if( $res["exito"] == 1 )
			$res["mje"] = "Datos de usuario modificados con éxito";
		else
			$res["mje"] = "Error al modificar datos de usuario";
		
		echo json_encode( $res );
	}
	/* ----------------------------------------------------------------------------------- */
	//Modificar datos de usuario. Bloque: Email de la cuenta
	if( isset( $_POST["form_act_cta"] ) ){
		include( "bd.php" );
		
		$dato = $_POST["data"];
		parse_str( $_POST["form_act_cta"], $usuario );

		if( $dato == "email" )
			$res["exito"] = modificarEmailCuenta( $usuario, $dbh );
		if( $dato == "password" )
			$res["exito"] = modificarPasswordCuenta( $usuario, $dbh );
		
		if( $res["exito"] == 1 )
			$res["mje"] = "Datos de usuario modificados con éxito";
		else
			$res["mje"] = "Error al modificar datos de usuario";
		
		echo json_encode( $res );
	}
	
	/* ----------------------------------------------------------------------------------- */
	
	//Recuperación de contraseña
	if( isset( $_POST["passw_recovery"] ) ){
		ini_set( 'display_errors', 1 );
		include("bd.php");
		include( "../fn/fn-mailing.php" );
		$email_noregistrado = false;

		parse_str( $_POST["passw_recovery"], $data );

		if( usuarioYaRegistrado( $dbh, $data["email"] ) ){
			$usuario = obtenerUsuarioPorEmail( $data["email"], $dbh );
			$data = obtenerTokenRecuperacionPassword( $dbh, $usuario );
			$res = enviarMensajeEmail( "recuperar_password", $data, $usuario["email"] );

			if( $res["exito"] == 1 )
				$res["mje"] = "Se ha enviado un mensaje a su buzón de correo para restablecer su contraseña";
			else
				$res["mje"] = "Error al enviar mensaje: $res[msg]";
			}
		else
			$email_noregistrado = true;	
		
		if( $email_noregistrado )
			$res["mje"] = "Esta dirección de correo no se encuentra registrada";
		
		echo json_encode( $res );	
	}
	
	/* ----------------------------------------------------------------------------------- */
	
	//Restablecimiento de contraseña
	if( isset( $_POST["new_passw"] ) ){
		include("bd.php");

		parse_str( $_POST["new_passw"], $data );

		$res["exito"] = actualizarPasswordUsuario( $dbh, $data["password1"], $data["idusuario"] );
		
		if( $res["exito"] == 1 ){
			asignarTokenRecuperacionPassword( $dbh, $data["idusuario"], "" );
			$res["mje"] = "Contraseña restablecida con éxito <a href='login.php'>Iniciar sesión</a>";
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
?>