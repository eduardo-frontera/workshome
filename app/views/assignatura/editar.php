<div>
	<div style="float:left">
		<h1><?php echo $assignatura->getNom() ?></h1>
	</div>
	
	<?php
	/*Estil depenent de la longitud del grup*/
		if (strlen($assignatura->getNom()) > 18){
			$estil = "margin-top:3px; margin-bottom:10px";
			?> <div style="clear:both"></div> <?php
		}else{
			$estil = "margin-top:35px; margin-left:5px;";
		}			
	?>
	
	<div>
		<a href ="#" data-toggle="modal" data-target="#avisModal" class="btn btn-danger btn-xs"
		style = "<?php echo $estil ?>">
			Eliminar
		</a>
	</div>
</div>

<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger" style="margin-top:10px; margin-bottom:10px;">
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('nombre asignatura', '<li>:message</li>');
		        echo $errors->first('nombre profesor', '<li>:message</li>');
		        echo $errors->first('apellidos profesor', '<li>:message</li>');
				?>
		    </ul>   
	    </div>
 	<?php
	}
?>

<div class="centrarForm" style="margin-top:15px;">
	<br/>
	<?php
	echo Form::open(array()); ?>
	<?php echo Form::text('nom_ass', 
							$assignatura->getNom(),
							array('class' => 'form-control',
							'placeholder' => 'Nombre asignatura',
							'required',
							'maxlength' => '45'
							)); ?>
	<br/> 
	<?php echo Form::text('nom_prof', 
							$assignatura->getNomProf(),
							array('class' => 'form-control',
							'placeholder' => 'Nombre profesor',
							'maxlength' => '45'
							)); ?>
	<br/> 			
	<?php echo Form::text('cognoms_prof', 
							$assignatura->getCognomsProf(),
							array('class' => 'form-control',
							'placeholder' => 'Apellidos profesor',
							'maxlength' => '150'
							)); ?>
	<br/> 
			
	<?php
	
	echo Form::submit('Guardar cambios',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));
	echo Form::close();
	?>
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
				¿Estás seguro de que quieres eliminar la asignatura? <br/>
				¡Perderás toda la información!
			</div>
			<div class="modal-footer">
				<a href="<?php echo URL::route('assignatura.eliminar',
								array('slug' => $assignatura->getSlug()))?>" class="btn btn-primary">Sí
				</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>