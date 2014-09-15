<!doctype html>
<html lang="es">
	<head>
		<!-- SEO -->
		<title><?=$title?></title>
		<meta name="description" content="<?=$description?>">
	    <meta name="keywords" content="WORKSHOME, red, social, repaso, estudios, libro, compartir, 
	    evento, grupo, asignatura, amigo, compañero, mensaje, aportación, perfil">    
	            
	    <!--Favicon-->
	    <link rel="shortcut icon" href="/laravel/public/img/favicon.png">	   
	    
	    <?php    
	    //CSS
		echo HTML::style('css/estils.css', array('media' => 'all'));
		echo HTML::style('css/estils800.css', array('media' => '(max-width: 800px)'));
		echo HTML::style('css/bootstrapflatly.css');	
		
		//JavaScript
		?>
			<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>		
		<?php
		
		echo HTML::script('js/bootstrap.min.js');
		?>
	</head>
	<body>
		<header>
			<div class="innerHeader">
				<a href="<?php echo URL::route('index')?>" class="logo">WORKSHOME</a>
				<a href="<?php echo URL::route('estudiant.nou')?>" class="botoHeader">Registro</a>
			</div>
			
			<div class="btn-group" id="responsiveDesp">
					<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
						Menú
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						
						<li>
							<a href="<?php echo URL::route('estudiant.nou')?>">
								Registro
							</a>
						</li>
					</ul>
			</div>
		</header>
	
		<div class="principal" style="min-height:448px;">		
				<?=$content?>
		</div> 		
			<footer>
				<div class="inner">
		            <div class="links">
		            	<ul>
		                	<li class="link">
		                    	<a href="<?php echo route('politica.privacitat')?>"> <span class="lletra_foot">Política de privacidad</span></a>
		                    </li>                        
		                    <li class="link">
		                    	 <a href="<?php echo route('contacte.email')?>"> <span class="lletra_foot">Contacto</span></a>
		                    </li>
		                     <li class="link">
		                    	 <a href="<?php echo route('funcionament')?>"> <span class="lletra_foot">Como funciona</span></a>
		                    </li>
		                </ul>
		            </div>			            
		            <div class="rights">  
	            		&copy; 2014 Workshome
		            </div>		          
		        </div>
			</footer>
		</body>
</html>