<!doctype html>
<html lang="es">
	<head>
		<!-- SEO -->
		<title>WORKSHOME - ERROR</title>
		
		<meta name="description" content="WORKSHOME es una red social académica donde podrás 
		crear grupos de asignaturas e intercambiar opiniones con tus compañeros de clase">
		
        <meta name="keywords" content="WORKSHOME, red, social, repaso, estudios, libro, compartir, 
        evento, grupo, asignatura, amigo, compañero, mensaje, aportación, perfil">    
            
        <!--Favicon-->
        <link rel="shortcut icon" href="/laravel/public/img/favicon.png">
		
		<?php
		//CSS
		echo HTML::style('css/estils.css');
		echo HTML::style('css/bootstrapflatly.css');
		
		//JavaScript
		?>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<?php
		echo HTML::script('js/bootstrap.min.js');
		
		?>
		
		<!--Per utilitzar HTML5 a versions anteriors a IE 9-->
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->		
	</head>
	
	<body>
		<header>
			<div class="innerHeader">
				<a href="<?php echo URL::route('index')?>" class="logo">WORKSHOME</a>
			</div>
		</header>
		<div class="principal">
			<h2 class="form-signin-heading" style="margin-top:85px";>Página no encontrada</h2>
						
			<div class="alert" style="margin-top:10px; margin-bottom:0px">
				Parece que esta página no existe o está en construcción. 
				<strong class="text-danger">					
						Disculpa las molestias.					
				</strong> 
			</div>
			
			<h4><a href="<?php echo URL::route('index')?>"> Volver a la página de inicio </a></h4>
		</div>		
	</body>
</html>


