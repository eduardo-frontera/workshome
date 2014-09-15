<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		document.getElementById("imageInput").onchange = function(){		
			document.getElementById("formImatge").submit();
		}
	});
</script>

<h1 style="float:left;">Mi perfil</h1>

<!--Dropdown d'opcions-->
<div class="btn-group" style="margin-left:5px; margin-top:28px;">
	<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
		Más opciones 
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		
		<li>
			<a href="<?php echo URL::route('estudiant.info')?>">
				Completar perfil
			</a>
		</li>
		
		<li>
			<a href="<?php echo URL::route('canviar.contrasenya')?>">
				Cambiar contraseña
			</a>
		</li>
		
		<li class="divider"></li>
		
		<li>
			<a href ="#" data-toggle="modal" data-target="#avisModal">
				Dar de baja
			</a>	
		</li>
	</ul>
</div>

<!-- Modal -->
<div class="modal fade" id="avisModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="titolAvis">Aviso</h4>
			</div>
			<div class="modal-body">
				¿Estás seguro de que quieres darte de baja en WORKSHOME? <br/>
			    ¡Perderás toda la información de tu cuenta!
			</div>
			<div class="modal-footer">
				<a href="<?php echo URL::route('estudiant.eliminar')?>" class="btn btn-primary">Sí</a>  
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<br/>
<br/>

<?php

$upload = Session::get('upload');
$fitxer = Session::get('fitxer');
$auth = Session::get('actualitzat');
$format = Session::get('combIncorrecte');

if($upload == 'error'){
		Session::forget('upload');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			Se ha producido un error al subir la imagen
		</div>
	<?php
	}


if($fitxer == 'error'){
		Session::forget('fitxer');
		?>
		<div class="alert alert-danger" style="margin-top:10px">
			El tipo de fichero que desea subir no está permitido
		</div>
	<?php
	}

if($auth == 'ok'){
	Session::forget('actualitzat');
	?>
	<div class="alert alert-info" style="margin-top:10px">
		Información actualizada correctamente
	</div>
	<?php
}

if($format == 'error'){
	Session::forget('combIncorrecte');
	?>		
	<div class="alert alert-danger" style="margin-top:10px">
		Combinación de fecha incorrecta
	</div>
	<?php
}

	if($errors->count()>0 ){	
		?>
		<div class="alert alert-danger" style="margin-bottom:10px;">
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('nombre', '<li>:message</li>');
		        echo $errors->first('apellidos', '<li>:message</li>');
				echo $errors->first('día', '<li>:message</li>');
				echo $errors->first('mes', '<li>:message</li>');
				echo $errors->first('año', '<li>:message</li>');
				echo $errors->first('Imagen Perfil', '<li>:message</li>');				
			?>
		    </ul>   
	    </div>
 	<?php
	}
?>

<div class="imgPerfil">
	<?php
		$urlImatge = $estudiant->getFoto();
		if($urlImatge == ''){
			$urlImatge = '/laravel/public/img/profile.png';
		}else{
			$urlImatge = '/htdocs/laravel/public/'.$urlImatge;
	 	}
	
	echo Form::open(array(
				'files' => true, 
				'class' => 'form-signin', 
				'id' => 'formImatge',
				'url' => route('estudiant.foto', array('slug' => $estudiant->getSlug()))
			));
	?>	
	
	<div style="overflow: hidden">
		<label class="imagebutton" style="float: left; background-size:50px 50px; background-image:url('<?php echo $urlImatge ?>')">
			<span> 
				<?php echo Form::file('imatge', array(
														'accept' => 'image/png,image/jpeg,image/jpg, image/gif',
														'id' => 'imageInput'																						
														)); ?> 
			</span>
		</label>			
	</div>	
		
	<?php echo Form::close();?>
</div>

<div class="formEdicioEst">
	<?php
	echo Form::open(array()); ?>
	<?php echo Form::text('nom', $estudiant->getNom(),
								array('class' => 'form-control',
								'required',
								'maxlength' => '45'
								)); ?>
	<br/> 
	<?php echo Form::text('cognoms', $estudiant->getCognoms(),
								array('class' => 'form-control',
								'required',
								'maxlength' => '150'
								)); ?>
	<br/> 			
	
	<?php echo Form::label('fecha', 'Fecha de nacimiento: '); ?>
	<br/> 
	<?php
	$emaillog = Auth::user()->email_estudiant;
	$estudiant = Estudiant::find($emaillog);
	$mi_nacimiento = $estudiant->getData();
	
	$mi_dia = date ( "d", strtotime ($mi_nacimiento)); 
	$mi_mes = date ( "m", strtotime ($mi_nacimiento)); 
	$mi_any = date ( "Y", strtotime ($mi_nacimiento)); 
	
	echo Form::selectRange('dia', 1, 31, $mi_dia,array('class' => 'form-control', 'style' => 'width:85px; float:left; margin-right:45px; margin-left:35px;'));	
	
	echo Form::selectRange('mes', 1, 12, $mi_mes,array('class' => 'form-control', 'style' => 'width:85px; float:left; margin-right:45px'));
	 
	echo Form::selectRange('any', 2000, 1910, $mi_any,array('class' => 'form-control', 'style' => 'width:100px; float:left; margin-right:10px'));
	
	?>			
	<br/>
	<br/>
	<br/>			
	<?php
	
	echo Form::submit('Actualizar',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));
	echo Form::close();
	?>
</div>

<br/>

<?php
$descripcio = $estudiant->getDescripcio();
$aficions = $estudiant->getAficions();

if($descripcio == '' || $aficions == ''){
	?>
	<div style="clear:both; margin-top:25px;">
		<div class="bs-example">
			<div class="alert alert-dismissable alert-warning">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>¡Tu perfil no está completo!</h4>
				<p>Añade intereses, aficiones y mucho más 
					<a href="<?php echo route('estudiant.info')?>">
						aquí!
					</a>
				</p>
			</div>
		</div>
	</div>
	<?php
}
?>