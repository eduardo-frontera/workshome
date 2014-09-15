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
		echo HTML::script('js/jquery.infinitescroll.js');
		echo HTML::script('js/scroll_top.js');
		echo HTML::script('js/pages/plantillaSessio.js');
		?>
		
		<!--Per utilitzar HTML5 a versions anteriors a IE 9-->
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
	</head>
	
	<body>		
		<header>
			<div class="innerHeader">
				<a href="#" onclick='MGJS.subir();return false;' class="logo">WORKSHOME</a>
				<a href="<?php echo URL::route('grups.meus')?>" class="botoHeader">Mis grupos</a>
				<a href="<?php echo URL::route('esdeveniments.consulta')?>" class="botoHeader">Mis eventos</a>
				<a href="<?php echo URL::route('estudiant.consultar',
								array('slug' => Auth::user()->getSlug()));?>" 
								class="botoHeader">
					Mi perfil
				</a>
				<a href="<?php echo URL::route('estudiant.sortir')?>" class="botoHeader">Salir</a>
				
				<div class="btn-group" id="responsiveDesp">
					<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
						Menú
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						
						<li>
							<a href="<?php echo URL::route('grups.meus')?>">
								Mis grupos
							</a>
						</li>
						
						<li>
							<a href="<?php echo URL::route('esdeveniments.consulta')?>">
								Mis eventos
							</a>
						</li>
						
						<li>
							<a href="<?php echo URL::route('estudiant.consultar',
								array('slug' => Auth::user()->getSlug()));?>">
								Mi perfil
							</a>
						</li>
						
						<li class="divider"></li>
						
						<li>
							<a href="<?php echo URL::route('estudiant.sortir')?>">
								Salir
							</a>	
						</li>
					</ul>
				</div>
				
				<div class="cercador">
					<?php echo Form::open(array('route' => 'cerca.estudiants', 
												'class' => 'navbar-form',
												'method' => 'get',
												'autocomplete' => 'off',
												'onsubmit' => "return validate()",
												'style' => 'margin-left:0px; padding:0px;'
												));?>
					<div class="capaCercador">
					<?php echo Form::text('text', null, array('placeholder' => 'Buscar personas o grupos...', 
														'class' => 'form-control col-lg-8',
														'style' => 'float:left;',
														'id' => 'box'
					             						));?>
					</div>
					<?php echo Form::close(); ?>
	           </div>
         </div> 
		</header>
		
		<div class="principal">
			<aside>
				<div class="grups">
					<i class="icon-list-alt"></i>
			    	<div class="titol"><h5>GRUPOS</h5></div>
					<?php
						
						if(Auth::user()->num_grups() > 0){
							foreach ($grups as $grup) {
							?>	
							<a href="javascript:navigate('<?php echo $grup->getID()?>');" class="nounderline grup"><?php echo $grup->getNom()?></a>   
							<div id="<?php echo $grup->getID()?>" style="display:block; margin-left:20px">         
							    <?php 	
							    
								$assignatures = $grup->assignatures;     
								
								foreach($assignatures as $asig){
									$nom = $asig->getNom(); ?> 
									<a href="<?php echo URL::route('assignatura.info',array('slug'=> $asig->getSlug()));?>">
										<?php echo $asig->getNom();?>
									</a>
									<br/>
									<?php 
								} ?>
							</div>	<br/>
							<?php    	
							}
						}else{
							?>
							<div>
								No estás matriculado
							</div>
							<?php
						}
					?>	
				</div>
			</aside>
			<section>
				<?=$content?>
			</section>
			<div class="esdeveniments">
				<div class="titol">
					<h5>EVENTOS</h5>					
				</div>
								
			  		<?php 
						if(Auth::user()->num_esdeveniments() > 0){
						
							$esdeveniments = Auth::user()->getEsdeveniments();
							
							foreach($esdeveniments as $esdeveniment)
							{							
								$data_es = $esdeveniment->getData();		
								
								$avui = date("Y-m-d H:i:s");
								$segons = strtotime($data_es) - strtotime($avui);
								$diferencia_dies = intval($segons/60/60/24);
								
								/*Si falten mes de 45 dies per l'esdeveniments no es mostra*/
								if ($diferencia_dies < 45){
									$menysdundia = false;
								
									if ($diferencia_dies > 0){
										$t = $diferencia_dies;
										if($diferencia_dies == 1){
											$u = ' día';
										}else{
											$u = ' días';
										}						
									}else{
										
										$menysdundia = true;
										
										$diferencia_hores = intval($segons/60/60);
										if($diferencia_hores > 0){
											$t = $diferencia_hores;
											$u = ' horas';
										}
										else{
											$diferencia_minuts = intval($segons/60);
											$t = $diferencia_minuts;
											$u = ' minutos';
										}
									}					
								
								?>
								<div class="esdeveniment">
									<?php
									if($menysdundia){
										?>
										<div class="nom">
											<b>
												<p class="text-danger" style="margin-bottom:0px;">
													<?php echo $esdeveniment->getNom(); ?>
												</p>
											</b>										
										</div>
									
										<div class="temps">
												<b>
													<p class="text-danger" style="margin-bottom:0px;">
														<?php  echo '('.$t.' '.$u.')' ?>
													</p>
												</b>	
										</div>
									<?php
									}else{
										?>
										<div class="nom">
											<?php echo $esdeveniment->getNom(); ?>
										</div>
									
										<div class="temps">									
											<?php echo '('.$t.' '.$u.')'; ?>																
										</div>									
									<?php
									}
									?>		
								</div>
													
						    <?php
								}
							}
						}else{
							?>
							<div>
								No hay eventos programados
							</div>
							<?php
						}							
					?>				
			</div>
		
		</div>
		
		<!-- Modal d'error auxiliar-->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">ERROR</h4>
		      </div>
		      <div class="modal-body">
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-success" data-dismiss="modal">Entendido</button>
		      </div>
		    </div>
		  </div>
		</div>
	
	</body>
</html>