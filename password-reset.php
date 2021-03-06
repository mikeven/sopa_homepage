<?php
    /*
     * TFE Life Planner - Reestablecer contraseña
     * 
     */
    ini_set( 'display_errors', 1 );
    include( "db/db.php" );
    include( "db/data-user.php" );

?>
<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Miguel Ángel Rangel">
    <meta name="description" content="“S.O.P.A. Lifeplanner” es una útil y práctica herramienta digital, especialmente diseñada para que hagas un mejor uso de tu tiempo">
    <meta name="keywords" content="Sujeto, Objetivo, Proveedor, Actividad, tiempo, organizar, agenda, calendario, planificar, registro">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    
    <title>S.O.P.A. Life Planner - Reestablecer contraseña</title>
    <link rel="canonical" href="https://www.sopalifeplanner.com/index.html"/>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/bootstrap-selector/css/bootstrap-select.min.css">
    <!--icon font css-->
    <link rel="stylesheet" href="vendors/themify-icon/themify-icons.css">
    <link rel="stylesheet" href="vendors/elagent/style.css">
    <link rel="stylesheet" href="vendors/flaticon/flaticon.css">
    <link rel="stylesheet" href="vendors/animation/animate.css">
    <link rel="stylesheet" href="vendors/owl-carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="vendors/magnify-pop/magnific-popup.css">
    <link rel="stylesheet" href="vendors/nice-select/nice-select.css">
    <link rel="stylesheet" href="vendors/scroll/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom_styles.css">
    <link rel="stylesheet" href="css/responsive.css">

    <link rel="stylesheet" href="vendors/bootstrapvalidator/dist/css/bootstrapValidator.min.css" type="text/css">
</head>

<body>
    <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="S" class="letters-loading">
                        S
                    </span>
                    <span data-text-preloader="O" class="letters-loading">
                        O
                    </span>
                    <span data-text-preloader="P" class="letters-loading">
                        P
                    </span>
                    <span data-text-preloader="A" class="letters-loading">
                        A
                    </span>
                </div>
                <p class="text-center">Cargando</p>
            </div>
            <div class="loader">
                <div class="row">
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body_wrapper">
        <header class="header_area">
            <nav class="navbar navbar-expand-lg menu_one menu_five">
                <div class="container">
                    <a class="navbar-brand sticky_logo" href="index.html">
                        <img src="img/sopa-logo.png" srcset="img/sopa-logo.png" alt="logo" width="200">
                        <img src="img/sopa-logo-dark.png" srcset="img/sopa-logo-dark.png" alt="" width="200">
                    </a>
                    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="menu_toggle">
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="hamburger-cross">
                                <span></span>
                                <span></span>
                            </span>
                        </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav menu w_menu pl_120 ml-auto mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link " href="index.html" role="button" > Inicio </a>
                            </li>
                        </ul>
                        <a class="btn_get btn_get_radious menu_cus menu_custfive" href="sopa/">Ingresa</a>
                    </div>
                </div>
            </nav>
        </header>

        <section class="breadcrumb_area" style="background: url(img/backgrounds/sopa_home_background.png);">
            <img class="breadcrumb_shap" src="img/backgrounds/sopa_home_background.png" alt="">
            <div class="container">
                <div class="breadcrumb_content text-center">
                    <h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Reestablecer contraseña</h1>
                </div>
            </div>
        </section>
        
        <section class="sign_in_area bg_color sec_pad">
            <div class="container">
                <div class="sign_info">
                    <?php if ( $usuario ){ ?>
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="login_info">
                                    <h2 class="f_p f_600 f_size_24 t_color3 mb_40">Reestablecer contraseña</h2>
                                    <form id="frm_resetpassword" action="#" class="login-form sign-in-form">
                                        <input type="hidden" id="tokenuser" name="token">
                                        <div class="form-group text_box">
                                            <label class="f_p text_c f_400">Contraseña</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-group text_box">
                                            <label class="f_p text_c f_400">Confirme contraseña</label>
                                            <input type="password" class="form-control" name="cnf_password">
                                        </div>
                                        
                                        <button type="submit" class="btn_three sign_btn_transparent">Reestablecer</button>

                                        <div id="reg-resp"> </div>
                                    
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="sign_info_content">
                                    <h3 class="f_p f_600 f_size_24 t_color3 mb_40">¿Recuerdas tu contraseña?</h3>
                                    
                                    <a href="sopa/" class="btn_three sign_btn_transparent">Inicia sesión</a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <p class="f_p f_100 f_size_12 t_color3 mb_40 frm_error">
                                    Este enlace no es válido. Intente reestablecer su contraseña nuevamente  
                                    <a href="http://sopalifeplanner.com/sopa/es/recuperar-password.php">Aquí</a>
                                </p>
                            </div>
                            <div class="col-lg-3"></div>
                        </div> 
                    <?php } ?>
                    
                </div>
            </div>
        </section>

        <footer class="footer_area footer_area_five pt_0 f_bg">
            <div class="footer_top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="f_widget company_widget">
                                <a href="index.html" class="f-logo"><img src="img/sopa-logo-dark.png" srcset="img/sopa-logo-dark.png 1x" alt="logo"></a>
                                <div class="widget-wrap">
                                    <p class="f_400 f_p f_size_15 mb-0 l_height34"><span>Email:</span> <a href="mailto:saasland@gmail.com" class="f_400">contact@sopalifeplanner.com</a></p>
                                    <p class="f_400 f_p f_size_15 mb-0 l_height34"><span>Phone:</span> <a href="tel:948256347968" class="f_400">+000 000 000 000</a></p>
                                </div>
                                <form action="#" class="f_subscribe mailchimp_two" method="post">
                                    <input type="text" name="EMAIL" class="form-control memail" placeholder="Email">
                                    <button class="btn btn-submit" type="submit"><i class="ti-arrow-right"></i></button>
                                    <p class="mchimp-errmessage" style="display: none;"></p>
                                    <p class="mchimp-sucmessage" style="display: none;"></p>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="f_widget about-widget pl_40">
                                <h3 class="f-title f_600 t_color f_size_18 mb_40">Columna II</h3>
                                <ul class="list-unstyled f_list">
                                    <li><a href="#">Enlace 1</a></li>
                                    <li><a href="#">Enlace 2</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="f_widget about-widget">
                                <h3 class="f-title f_600 t_color f_size_18 mb_40">Columna II</h3>
                                <ul class="list-unstyled f_list">
                                    <li><a href="#">Enlace 1</a></li>
                                    <li><a href="#">Enlace 2</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="f_widget about-widget">
                                <h3 class="f-title f_600 t_color f_size_18 mb_40">Columna III</h3>
                                <ul class="list-unstyled f_list">
                                    <li><a href="#">Enlace 1</a></li>
                                    <li><a href="#">Enlace 2</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer_bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-5 col-sm-6">
                            <p class="mb-0 f_400">S.O.P.A. Life Planner © <span id="year"></span></p>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-6">
                            <div class="f_social_icon_two text-center">
                                <a href="#"><i class="ti-facebook"></i></a>
                                <a href="#"><i class="ti-twitter-alt"></i></a>
                                <a href="#"><i class="ti-vimeo-alt"></i></a>
                                <a href="#"><i class="ti-pinterest"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <ul class="list-unstyled f_menu text-right">
                                <li><a href="#">Términos de Uso</a></li>
                                <li><a href="#">Política de privacidad</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/propper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--<script src="js/validator.min.js"></script>-->
    <script src="vendors/wow/wow.min.js"></script>
    <script src="vendors/sckroller/jquery.parallax-scroll.js"></script>
    <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="vendors/isotope/isotope-min.js"></script>
    <script src="vendors/magnify-pop/jquery.magnific-popup.min.js"></script>
    <script src="vendors/scroll/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    <!-- Validator -->
    <script src="vendors/bootstrapvalidator/dist/js/bootstrapValidator.min.js" type="text/javascript"></script>
    <script src="js/fn-user.js"></script>
    <script type="text/javascript">
        var parsed  = new URL( window.location.href );
        document.getElementById("tokenuser").value = parsed.searchParams.get("token");
    </script>
</body>

</html>