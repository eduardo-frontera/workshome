<?php 
	echo HTML::script('js/pages/aportacio.js');
	echo HTML::script('js/pages/comentari.js');
	echo HTML::script('js/pages/missatge.js');
	
	echo HTML::script('js/jquery.autosize.min.js');
	
	echo HTML::script('js/lightbox-2.6.min.js');
	echo HTML::style('css/lightbox.css');

?>

<div data-url-refresh="<?php echo URL::route('esdeveniment.noves', array('id' => $esdeveniment->getID()))?>"></div>

<div class="capcalera">	  
		<h2 style="float: left;"><?php echo $esdeveniment->getNom() ?></h2>
	
		<?php
			
			$moderador = $esdeveniment->autor;
			
			if($moderador->getEmail() == Auth::user()->email_estudiant){
				$slug = $esdeveniment->getSlug();
				?>
				
				<?php
				/*Estil depenent de la longitud del grup*/
					if (strlen($esdeveniment->getNom()) > 25){
						$estil = "margin-top:0px;";
						?> <div style="clear:both"></div> <?php
					}else{
						$estil = "";
					}			
				?>
				
				<div class="moderar modH1" style="<?php echo $estil ?>">
					<a href="<?php echo URL::route('esdeveniment.editar', array('slug' => $esdeveniment->getSlug()))?>">
						<img src="/laravel/public/img/edit3.png" 
						alt="Moderar" 
						data-toggle="tooltip" 
						data-placement="right" 
						data-original-title="Modifica el evento" 
						height="16" 
						width="16">
					</a>			
				</div>
				
				<?php
			}
		?>
</div>

<ul class="nav nav-tabs">
	<li>
		<a href="<?php echo route('esdeveniment.consulta', array('slug' => $esdeveniment->getSlug()))?>">
			Información
		</a>
	</li>
	<li class = "active">		
		<a href="#">
			Mensajes
		</a>
	</li>
</ul>

<br/>

<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger" style="margin-bottom:10px;">
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('Aportación', '<li>:message</li>');
		        echo $errors->first('Fichero', '<li>:message</li>');
				
			?>
		    </ul>   
	    </div>
 	<?php
	}
?>

<?php
	$fitxer = Session::get('fitxer');
	$upload = Session::get('upload');
	$descarrega = Session::get('descarrega');
	
	if($fitxer == 'error'){
		Session::forget('fitxer');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			El tipo de fichero que desea subir no está permitido
		</div>
	<?php
	}	
	
	if($upload == 'error'){
		Session::forget('upload');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			El tipo de fichero que desea subir no está permitido
		</div>
	<?php
	}	
	
	if($descarrega == 'error'){
		Session::forget('descarrega');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			No es posible descargar el fichero en este momento
		</div>
	<?php
	}
?>

<div>
	
<?php 
		echo Form::open(array(
			'files' => true, 
			'class' => 'form-signin', 
			'id' => 'form-comentar',
			'autocomplete' => 'off',
			'url' => route('aportacio.crear.esdeveniment', array('id' => $esdeveniment->getID()))
		));
			
		 echo Form::textarea('aportacio', null,array('placeholder' => 'Compartir en '.$esdeveniment->getNom(). ' . . .', 
														'class' => 'form-control',  
														'id' => 'boxText',
														'onfocus' => 'sizeBox()',
														'required',
													   	'maxlength' => '500',
														'max-width' => '583 px',														
														'resize' => 'none'
														));?>
	</div>
	
	<div style="overflow: hidden">
		<label class="filebutton" style="float: left;">
			<span> <?php echo Form::file('file', array('class' => 'file_style', 
											'id' => 'fileInput',
											'onsubmit' => "return validate()")); ?> 
			</span>
		</label>	
		<div id="nomFile" class="txtFitxer">			
			<small>No se ha seleccionado ningún archivo (opcional)</small>
		</div>	
	</div>	
	
	
	<div style="margin-top:10px">
		<?php echo Form::submit('Enviar',array('class' => 'btn btn-primary btn-sm', 
												'id' => 'botoAportacio', 'style' => 'float:right'));?>		
		<?php echo Form::close();?>
	</div>
	
	<div style="clear: both"></div>
	
	<div id="aportacions">   
	   <?php
		foreach($aportacions as $aportacio){
			echo View::make('esdeveniments/aportacio', array('aportacio' => $aportacio));
		}
		if ($aportacions->getCurrentPage() < $aportacions->getTotal()-1){
			$ruta = route('esdeveniment.missatges',
			  		array('slug' => $esdeveniment->getSlug()))
			
			?>
			<nav id="page-nav">
			  <a href="<?php echo $ruta.'?page='.($aportacions->getCurrentPage()+1) ?>">
			  	
			  </a>
			</nav>
			<?php
		}
		?>
	</div>
	
<!-- Modal -->
<div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Aviso</h4>
			</div>
			<div class="modal-body">
				¿De verdad quieres borrar esta aportación? 			    
			</div>
			<div class="modal-footer">
				<a data-proceed class="btn btn-primary">Sí</a>  
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-eliminar-com" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Aviso</h4>
			</div>
			<div class="modal-body">
				¿De verdad quieres borrar este comentario? 			    
			</div>
			<div class="modal-footer">
				<a data-proceedCom class="btn btn-primary">Sí</a>  
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div id="inputEdicioAportacio" style="overflow:hidden; display:none;">		
	<?php		
	echo Form::open(array(
		'class' => 'form-signin', 
		'autocomplete' => 'off'
	));
		
	 echo Form::textarea('text', null,array( 
											'class' => 'form-control',
											'id' => 'boxText2',
											'required',
										   	'maxlength' => '500',
										   	'style' => 'width:528px; max-width:528px; height:100px; min-height:100px;'
											));?>
											
	<div style="margin-top:10px; float:right;">
		<?php echo Form::submit('Editar',array('class' => 'btn btn-primary btn-sm', 
												'id' => 'botoAportacio'));?>		
		<a href="#" class="btn btn-danger btn-sm" data-cancel>Cancelar</a>
		<?php echo Form::close();?>
	</div>
</div>

<div id="inputEdicioComentari" style="overflow:hidden; display:none;">		
	<?php		
	echo Form::open(array(
		'class' => 'form-signin', 
		'autocomplete' => 'off'
	));
		
	 echo Form::text('text', null,array( 
										'class' => 'form-control',											
										'required',
									   	'maxlength' => '255',
									   	'style' => 'width:450px;'
										));?>
											
	<div style="margin-top:10px; float:right; margin-right:50px;">
		<?php echo Form::submit('Editar',array('class' => 'btn btn-primary btn-sm', 
												'id' => 'botoComentari'));?>		
		<a href="#" class="btn btn-danger btn-sm" data-cancel>Cancelar</a>
		<?php echo Form::close();?>
	</div>
</div>
