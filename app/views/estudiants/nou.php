<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({trigger: 'clic','placement': 'right'});
    });
</script>

<div class="centrarForm"> 
	<h2 class="form-signin-heading">Registro</h2>		
	<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger">				
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('email', '<li>:message</li>');
				echo $errors->first('email confirmación', '<li>:message</li>');
		        echo $errors->first('nombre', '<li>:message</li>');
		        echo $errors->first('apellidos', '<li>:message</li>');
				echo $errors->first('contraseña', '<li>:message</li>');
				echo $errors->first('día', '<li>:message</li>');
				echo $errors->first('mes', '<li>:message</li>');
				echo $errors->first('año', '<li>:message</li>');
			?>
		    </ul>   
	    </div>
 	<?php
	}
	?>
	
	<?php
	$auth = Session::get('emailExistent');
	if($auth == 'error'){
		Session::forget('emailExistent');
		?>		
		<div class="alert alert-danger" style="margin-top:10px">
			Ooops! Ya existe una cuenta con ese email!
		</div>
		<?php
	}
	?>
	
	<?php
	$auth = Session::get('emails');
	if($auth == 'error'){
		Session::forget('emails');
		?>		
		<div class="alert alert-danger" style="margin-top:10px">
			Los emails no coinciden
		</div>
		<?php
	}
	?>
	
	<?php
	$auth = Session::get('combIncorrecte');
	if($auth == 'error'){
		Session::forget('combIncorrecte');
		?>		
		<div class="alert alert-danger" style="margin-top:10px">
			Combinación de fecha incorrecta
		</div>
		<?php
	}
	?>
	
	           	
	<?php echo Form::open(array('class' => 'form-signin'));?>
				
	<?php echo Form::text('nom', null,array('placeholder' => 'Nombre', 
											'class' => 'form-control',
											'style' => 'float:left; width:200px; margin-right:5px;',
											'autofocus',
											'required',
											'maxlength' => '45'
											));?>		
	
	<?php echo Form::text('cognoms', null,array('placeholder' => 'Apellidos', 
												'class' => 'form-control',
												'style' => 'width:220px;',
												'required',
												'maxlength' => '150'
												));?>
	<br/>		
	<?php echo Form::email('email', null,array('placeholder' => 'Email', 
												'class' => 'form-control',
												'required',
												'maxlength' => '60'
												));?>	
												
	<br/>		
	<?php echo Form::email('emailrep', null,array('placeholder' => 'Repetir Email', 
												'class' => 'form-control',													
												'required',
												'maxlength' => '60'
												));?>	
	<br/>
	<?php echo Form::password('contrasenya', array('placeholder' => 'Contraseña', 
													'class' => 'form-control',
													'data-toggle' => 'popover', 
													'data-placement' => 'right', 
													'data-content' => 'Debe tener mínimo 6 carácteres',
													'required',
													'pattern' => '.{6,20}'
												));?>	
	<br/>	
	<?php 
	
	echo Form::label('fecha', 'Fecha de nacimiento: ');
	
	?> <br/> <?php
	echo Form::selectRange('dia', 1, 31, null,array('class' => 'form-control', 'style' => 'width:85px; float:left; margin-right:45px; margin-left:35px;'));	
	
	echo Form::selectRange('mes', 1, 12, null,array('class' => 'form-control', 'style' => 'width:85px; float:left; margin-right:45px;'));
	
	echo Form::selectRange('any', 2000, 1910, null,array('class' => 'form-control', 'style' => 'width:100px; float:left;'));
	
	?>
	
	<br/>
	<br/>
	<br/>
	
	<small>Al crear una cuenta aceptas nuestra 
		<a href="<?php echo route('politica.privacitat') ?>">
			política de privacidad
		</a>
	</small>
	
	<br/>
	<br/>
	
	<?php echo Form::submit('Crear cuenta',array('class' => 'botoGran botoBlau'));?>		
	<?php echo Form::close();?>

</div>