<h2 class="form-signin-heading" style="margin-top:85px";>Recordar contraseña</h2>

<div style="margin-top:5px; margin-bottom:10px;">
	Introduce el email con el que te registraste en WORKSHOME y empezará el proceso para
	recuperar tu cuenta.
</div>

<div>
	
	<?php
	    echo Form::open(array('class' => 'form-signin',
										'route' => 'password.request'));
		echo Form::email('email', null,array('placeholder' => 'Email', 
												'class' => 'form-control', 'autofocus',
												'style' => 'width:350px; 								 
															display:inline-block;',
												'autofocus'												
												));
		echo Form::submit('Enviar', array('class' => 'btn btn-primary btn-sm',
											'style' => 'margin-left:5px;'		
											));
		echo Form::close();
	?>
	
	<?php
		if(Session::has('success')){
			?>
			<div class="alert alert-info" style="margin-top:10px; width:350px">
				Te hemos enviado un email, revisa tu correo.
			</div>
			<?php
		}
	
	?>
	
	<?php
		if(Session::has('error')){
			?>
			<div class="alert alert-danger" style="margin-top:10px; width:350px">
				No existe ninguna cuenta con ese email.
			</div>
		<?php
		}
	
	?>
</div>