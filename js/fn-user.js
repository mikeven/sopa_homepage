/*
 * S.O.P.A. Life Planner - Función de usuarios
 *
 */
/* ----------------------------------------------------------------------------------- */
function registrarUsuario(){
	//Envía al servidor la petición de registro de un nuevo usuario
	var form = $("#frm_register");
	var form_usr = form.serialize();
    var loader_gif = "<img src='assets/images/ajax-loader.gif'>";
	
	$.ajax({
        type:"POST",
        url:"db/data-user.php",
        data:{ form_nu: form_usr },
        beforeSend: function(){
            $("#reg-resp").html( loader_gif );
        },
        success: function( response ){
            $("#reg-resp").html( "" );
            res = jQuery.parseJSON( response );
            
            if( res.exito == 1 ){

            } else {

            } 
        }
    });
}
/* ----------------------------------------------------------------------------------- */
function chequearEstadoCarrito(){
    // Evalúa si existe un estado previo del carrito de compra y se carga en variable de sesión del carrito.

    /*if ( typeof $.cookie( "ckcart" ) === 'undefined' )
        console.log( "No existen elementos de carrito guardado" );
    else{ 
        //Cookie registrada:
        console.log( "Obteniendo carrito guardado..." );
        
    }*/
    console.log( "Obteniendo carrito guardado..." );
    cargarCarritoGuardadoSesion();
}
/* ----------------------------------------------------------------------------------- */
function iniciarSesion( form, mode ){
    //Envía al servidor la petición de inicio de sesión
    //mode: full: Página de login. min: ventana emergente del menú navegación
    var form_log = form.serialize();
    
    $.ajax({
        type:"POST",
        url:"database/data-user.php",
        data:{ usr_login: form_log },
        success: function( response ){
            console.log( response );
            res = jQuery.parseJSON( response );
            if( res.exito == 1 ){
                /*chequearEstadoCarrito();*/
                if( mode == "full" ){
                    //Redirigir a pantalla de cuenta de usuario
                    window.location.href = "categories.php"; 
                }else{
                    window.location.href = "categories.php";
                }
            }else{
                if( mode == "full" ){
                    mensajeAlerta( "#alert-msgs", res.mje );
                    activarBoton( "#btn_login" );
                }else{
                    window.location.href = "login.php?err";   
                }

            }
        }
    });
}

/* ----------------------------------------------------------------------------------- */

function enviarDatosContacto( datos ){
    //Envía al servidor los datos del formulario de contacto
    var form_co = $(datos).serialize();
    
    $.ajax({
        type:"POST",
        url:"database/data-user.php",
        data:{ form_ctc: form_co },
        success: function( response ){
            
            res = jQuery.parseJSON( response );
            scroll_To();
            mensajeAlerta( "#alert-msgs", res.mje );
            $("#frm_contacto")[0].reset();
            if( res.exito != 1 ){
                $("#frm_contacto")[0].reset();
                activarBoton( "#btn_contacto", true );  
            }
        }
    });
}

// ================================================================================== //
jQuery.fn.exists = function(){ return ($(this).length > 0); }
// ================================================================================== //

$( document ).ready(function() {	
    
	$( ".select_pdetail" ).first().click();

    $("#btn_login_dd").on( "click", function(){
        iniciarSesion( $("#frm_login_bar"), "min" );
    });
    /* ......................................................................*/
    //Formulario Registro de usuarios: Autocompletar código de área según país seleccionado
    $("#usuario-pais").on( "change", function(){
        var cod_pais = $(this).find(':selected').attr('data-cp');
        var prefijo = "(+" + cod_pais + ") ";
        $("#lbtlf").html( prefijo );
    });

    $("#ntelef").on( "blur", function(){
        var prefijo = $("#lbtlf").html();
        var ntelf = $("#ntelef").val();
        $("#telefono").val( prefijo + ntelf );
    });

    //Formulario Registro de usuarios: Mostrar campo nombre empresa si tipo de cliente es empresa
    $("#t-cliente-r").on( "change", function(){
        if( $(this).val() != "Particular" ){
            $("#r-nempresa").fadeIn(120);
        }else{
            $("#r-nempresa").fadeOut(120);
        }
    });
    /* ......................................................................*/
    if ( $('#frm_register').exists() ) {
        
        $('#frm_register').bootstrapValidator({
            
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar su nombre'
                        }
                    }
                },
                lastname: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar su apellido'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar un email'
                        },
                        emailAddress: {
                            message: 'Debe indicar un email válido'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar contraseña'
                        }
                    }
                },
                cnf_password: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar contraseña'
                        },
                        identical: {
                            field: 'password',
                            message: 'Las contraseñas deben coincidir'
                        },
                    }
                },
                acceptterms: {
                    validators: {
                        notEmpty: {
                            message: 'Debe marcar la casilla de confirmación'
                        }
                    }
                }
            }
        });

        $('#frm_register').bootstrapValidator().on('submit', function (e) {
    	  if (e.isDefaultPrevented()) {
    	   
    	  } else {
    	  	registrarUsuario();
    	  	return false;
    	  }
    	});
    }
    /* ......................................................................*/
    if ( $('#frm_login').exists() ) {
        
        $('#frm_login').bootstrapValidator({
            
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar un email'
                        },
                        emailAddress: {
                            message: 'Debe indicar un email válido'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar contraseña'
                        }
                    }
                }
            }
        });

        $('#frm_login').bootstrapValidator().on('submit', function (e) {
          if (e.isDefaultPrevented()) {
            
          } else {
            iniciarSesion( $("#frm_login"), "full" );
            return false;
          }
        });
    }
    /* ......................................................................*/
    if ( $('#frm_contacto').exists() ) {
        
        $('#frm_contacto').bootstrapValidator({
            
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                nombre: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar su nombre'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar un email'
                        },
                        emailAddress: {
                            message: 'Debe indicar un email válido'
                        }
                    }
                },
                mensaje: {
                    validators: {
                        notEmpty: {
                            message: 'Debe escribir mensaje'
                        }
                    }
                }
            }
        });

        $('#frm_contacto').bootstrapValidator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
            
            } else {
                enviarDatosContacto( $(this) );
                return false;
            }
        });
    }
    /* ......................................................................*/
    /*
    $('#frm_login_bar').bootstrapValidator({
        
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar un email'
                    },
                    emailAddress: {
                        message: 'Debe indicar un email válido'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar contraseña'
                    }
                }
            }
        }
    });

    $('#frm_login_bar').bootstrapValidator().on('submit', function (e) {
      if (e.isDefaultPrevented()) {
        alert("prevent");  
      } else {
        iniciarSesion( $("#frm_login_bar") );
        return false;
      }
    });
    */

});