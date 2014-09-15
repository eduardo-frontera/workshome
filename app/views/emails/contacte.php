<div class="centrarForm" style="overflow:hidden">
	<h2 class="form-signin-heading">Contacto</h2>
	<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger">				
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('Email', '<li>:message</li>');
				echo $errors->first('Mensaje', '<li>:message</li>');
		        echo $errors->first('Asunto', '<li>:message</li>');
			?>
		    </ul>   
	    </div>
 	<?php
	}
	
	
	$auth = Session::get('enviat');
	if($auth == 'ok'){
		Session::forget('enviat');
		?>
		<div class="alert alert-info" style="margin-top:10px">
			Mensaje enviado correctamente.
			<br/>
			Le atenderemos lo antes posible.
		</div>
	<?php
	}
	
	
	$auth = Session::get('enviat');
	if($auth == 'ko'){
		Session::forget('enviat');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			Se ha producido un error durante el envío.
		</div>
	<?php
	}
	?>
	
	<?php echo Form::open(array('class' => 'form-signin','url' => route('enviar.email')))?>
				
	<?php echo Form::text('nom', null,array('placeholder' => 'Nombre', 
											'class' => 'form-control',
											'autofocus',
											'required',
											'maxlength' => '250'
											));?>				
	<br/>
	
	<?php echo Form::email('from', null,array('placeholder' => 'Email', 
												'class' => 'form-control',
												'required',
												'maxlength' => '80'
												));?>									
	<br/>
	
	<?php echo Form::label('txtSubject', 
								'Asunto:', 
								array('style' => 'margin-top:10px; margin-bottom:0px;')
							);?>
	
	<?php echo Form::select('subject', 
								array(
									'Nuevo centro' => 'Añadir nuevo centro',
									'Peticion' => 'Petición',
									'Duda' => 'Duda',
									'Consejo' => 'Consejo',									
									'Publicidad' => 'Publicidad',
									'Otro' => 'Otro'
								),
								null,
								array('
									placeholder' => 'Asunto', 
									'class' => 'form-control',
									'style' => 'display:inline-block; float:right; width:350px',
									'required'									
								)
							);?>
	<br/>	
	<br/>	
	<br/>		
	
	<?php echo Form::textarea('message', null,array('placeholder' => 'Escribe tu mensaje y te atenderemos lo antes posible.', 
											'class' => 'form-control txtArea',
											'style' => 'height:180px; max-width:426px',
											'required',
											'maxlength' => '500'
												));?>

 
		<br/>		
	
	<?php echo Form::submit('Enviar',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));?>		
	<?php echo Form::close();?>
	</div>
</div>
<?php
?>