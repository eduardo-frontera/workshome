<h2 class="form-signin-heading" style="margin-top:85px";>Recordar contraseña</h2>
<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({trigger: 'clic','placement': 'right'});
    });
</script>

<div style="margin-top:5px; margin-bottom:10px;">
	Introduce tu email y tu nueva contraseña para acabar el proceso.
</div>

<div class="formContrasenya">
	
	<?php

	if(Session::has('error')){
		$reason = Session::get('reason');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			El email introducido no es válido.
		</div>
		<?php
	}
	?>
	
	<?php
	$auth = Session::get('password');
	if($auth == 'different'){
		Session::forget('password');
		?>		
		<div class="alert alert-danger" style="margin-top:10px">
			Las contraseñas no coinciden.
		</div>
		<?php
	}
	?>
	
	<?php
	$auth = Session::get('password');
	if($auth == 'error'){
		Session::forget('password');
		?>		
		<div class="alert alert-danger" style="margin-top:10px">
			La contraseña debe tener entre 6 y 45 caracteres.
		</div>
		<?php
	}
	?>	
	
	<?php
	    echo Form::open(array('class' => 'form-signin',
										'route' => array('password.update', $token)));
		echo Form::email('email', null,array('placeholder' => 'Email', 
												'class' => 'form-control'												
												));
		?><br/><?php
		
		echo Form::password('password', array('placeholder' => 'Nueva contraseña',
													'class' => 'form-control',
													'data-toggle' => 'popover', 
													'data-placement' => 'right', 
													'data-content' => 'Debe tener mínimo 6 carácteres'													
													));		
													
		?><br/><?php								
						
		echo Form::password('password_confirmation', array('placeholder' => 'Repite la contraseña',
													'class' => 'form-control'													
													));
					
		?><br/><?php	
		
		echo Form::hidden('token', $token);
												
		echo Form::submit('Enviar', array('class' => 'btn btn-primary btn-sm',
											'style' => 'margin-left:5px; float:right;'		
											));
		echo Form::close();
	?>
</div>