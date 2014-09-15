<div class="subdiv">
	<div class="info">
				
		<img src= "/laravel/public/img/portada.jpg" alt="Alumnos en clase" width = "688" height = "290">
	</div>
	<div class="login">
		<?php echo Form::open(array('class' => 'form-signin', 'route' => 'index'));?>
		<h2 class="form-signin-heading">Iniciar sesión</h2>		
		<?php echo Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control', 
														'autofocus',
														'required',
														'maxlength' => '60'
													));?>
		<br/>
		<?php echo Form::password('contrasenya', array('placeholder' => 'Contraseña', 
																	'class' => 'form-control',
																	'pattern' => '.{6,20}'
																	));?>	
		
		<div class="checkbox" style="display:inline-block; float:left; /*border:1px black solid;*/">
			<label>
				<?php echo Form::checkbox('sessio', null, array('class' => 'checkbox')); ?>
				No cerrar sesión
			</label>		
		</div>
		
		<div class="checkbox" style="display:inline-block; float:right; /*border:1px black solid;*/ margin-top:10px">
			<label>
				<a href="<?php echo route("password.remind")?>">¿Contraseña olvidada?</a>
			</label>		
		</div>
		
		<?php echo Form::submit('Entrar', array('class' => 'botoGran botoBlau'));?>		
		<?php echo Form::close();?>
		
		
		<?php
		$auth = Session::get('loggin');
		if($auth == 'error'){
			Session::forget('loggin');
			?>		
			<div class="alert alert-danger" style="margin-top:10px">
				Email o contraseña incorrectos
			</div>
		<?php
		}
		?>
		
		<?php
		$auth = Session::get('flash');
		if($auth == 'ok'){
			Session::forget('flash');
			?>		
			<div class="alert alert-danger" style="margin-top:10px">
				Correcto.
			</div>
			<?php
		}
		?>
		
		<?php
		if($errors->count()>0 ){	
			?>
			<div class="alert alert-danger" style="margin-bottom:10px;">
	    		<p>Han ocurrido los siguientes errores:</p>	
			    <ul>
			    	<?php
			        echo $errors->first('Email', '<li>:message</li>');
			        echo $errors->first('Contraseña', '<li>:message</li>');
				?>
			    </ul>   
		    </div>
	 	<?php
		}
		?>
		
	</div>
</div>

<div class="subdiv">
	<div class="row">
      <div class="midaTile">
        <div class="tile">
          <img class="tile-image big-illustration" alt="Crea grupos" src="<?php echo '/laravel/public/img/group.png' ?>">
          <h3 class="tile-title">Crea grupos</h3>
          <p>Crea grupos de asignaturas e invita a tus compañeros a formar parte de ellos.</p>
        </div>
      </div>
      <div class="midaTile">
        <div class="tile">
          <img class="tile-image big-illustration" alt="Comparte archivos" src="/laravel/public/img/share.png" width="128" height="128">
          <h3 class="tile-title">Comparte archivos</h3>
          <p>Envía a tus compañeros toda clase de archivos.</p>
        </div>
      </div>
      <div class="midaTile">
        <div class="tile">
          <img class="tile-image big-illustration" alt="Define eventos" src="/laravel/public/img/calendar.png" width="128" height="128">
          <h3 class="tile-title">Define eventos</h3>
          <p>Crea hitos en el calendario para no olvidar ninguna fecha importante.</p>
        </div>
      </div>
      <div class="midaTile">
        <div class="tile">
          <img class="tile-image big-illustration" alt="Ordena tus asignaturas" src="/laravel/public/img/tree.png" width="128" height="128">
          <h3 class="tile-title">Ordena tus asignaturas</h3>
          <p>Ten una vista global de tus grupos y asignaturas en tu panel izquierdo</p>
        </div>
      </div>
    </div>
</div>