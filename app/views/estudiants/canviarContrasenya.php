<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({trigger: 'clic','placement': 'right'});

    });
</script>

<h1>Cambiar contraseña</h1>

<div class="centrarForm">
	<?php
	echo Form::open(array()); ?>
	<?php echo Form::label('actual', 'Contraseña actual: '); ?>
	<?php echo Form::password('antigaContrasenya', array('class' => 'form-control',
														'required',
														'maxlength' => '45'
														)); ?>
	<br/> 
	<?php echo Form::label('nova', 'Contraseña nueva: '); ?>
	<?php echo Form::password('novaContrasenya', array('class' => 'form-control',
								'data-toggle' => 'popover', 
								'data-placement' => 'right', 
								'data-content' => 'Debe tener mínimo 6 carácteres',
								'required',
								'pattern' => '.{6,20}'
	)); ?>
	
	<br/> 			
			
	<?php		
		echo Form::submit('Cambiar',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));
		echo Form::close();
	?>
</div>
<div style="clear:both"></div>

<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger" style="margin-top:10px;">
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('Contraseña actual', '<li>:message</li>');
		        echo $errors->first('Contraseña nueva', '<li>:message</li>');
			?>
		    </ul>   
	    </div>
 	<?php
	}
?>

<?php
	$auth = Session::get('canvi');
	if($auth == 'error'){
		Session::forget('canvi');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			La contraseña actual introducida es incorrecta
		</div>
		<?php
	}
?>

