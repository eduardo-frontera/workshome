<h2 class="form-signin-heading" style="margin-top:85px";>Recordar contraseña</h2>

<div style="margin-top:5px; margin-bottom:10px;">
	Introduce el email con el que te registraste en WORKSHOME y empezará el proceso para
	recuperar tu cuenta.
</div>

<div>
	<?php
	    echo Form::open(array('class' => 'form-signin',
										'route' => 'password.request'));
		echo Form::text('email', null,array('placeholder' => 'Email', 
												'class' => 'form-control', 'autofocus',
												'style' => 'width:350px; 								 
															display:inline-block;',
												'autofocus'												
												));
		echo Form::submit('Submit', array('class' => 'btn btn-primary btn-sm',
											'style' => 'margin-left:5px;'		
											));
		echo Form::close();
	?>
</div>