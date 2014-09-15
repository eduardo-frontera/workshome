<?php 
	echo HTML::script('js/pages/selector_centre.js');
?>

<script>
$(document).ready(function(){
  $('#conf-eliminacio').click(function(){   
  });
});
</script>

<h2 style="float:left;"><?php echo $grup->getNom();?></h2>      
	<?php
	/*Estil depenent de la longitud del grup*/
	if (strlen($grup->getNom()) > 25){
		$estil = "margin-top:3px; margin-bottom:10px";
		?> <div style="clear:both"></div> <?php
	}else{
		$estil = "margin-left:5px; margin-top:28px;";
	}
	
	?>

<div class="btn-group" style="<?php echo $estil ?>">
	<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
		Más opciones 
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li>
			<a href="<?php echo URL::route('assignatures.nou',	array('slug' => $grup->getSlug()))?>">
				Añadir asignaturas
			</a>
		</li>
		
		<li class="divider"></li>
		
		<li>
			<a href ="#" data-toggle="modal" data-target="#avisModal">
				Eliminar grupo
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
				¿Estás seguro de que quieres eliminar el grupo? <br/>
				¡Perderás toda la información!
			</div>
			<div class="modal-footer">
				<a href="<?php echo URL::route('grup.eliminar',	
										array('id' => $grup->getID()))?>" class="btn btn-primary">Sí
				</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
 
<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger" style="margin-top:10px;">
		    	<?php
		        echo $errors->first('nombre');
				echo $errors->first('descripción');
				echo $errors->first('aula');
				echo $errors->first('centro');
				?>		      
	    </div>
 	<?php
	}
?>
    		  
<div>
	<?php
	
	echo Form::open(array('class' => 'form-signin'));
	?> 
		<br/> 
	<?php
	echo Form::text('nom', $grup->getNom(), array('placeholder' => 'Nombre grupo', 
													'class' => 'form-control',
													'required',
													'maxlength' => '60'
													));
	?> 
		<br/> 
	<?php
	
	echo Form::textarea('descripcio', $grup->getDescripcio(), array('placeholder' => 'Descripción grupo', 
																		'class' => 'form-control txtArea',
																		'rows' => 3,
																		'maxlength' => '255'
																	 ));
	?> 
		<br/> 
	<?php
	
	echo Form::text('aula', $grup->getAula(), array('placeholder' => 'Aula clases', 
																'class' => 'form-control',
																'maxlength' => '25'
																));
	
	?> 
		<br/> 
	<?php
	
	echo Form::label('Info', 'Centro donde se imparten las clases:');

	?> <br/> <?php
	$opcions = array();
	$tcentres = TipusCentre::all();
	foreach ($tcentres as $tcentre){
		$opcions[$tcentre->getNom()] = $tcentre->getNom();
	}
	
	$centre = $grup->centre;
	$tipus_centre = $centre->tipus;	
	
	
	echo Form::select('tipus_centre', $opcions, $tipus_centre->getNom(), array('class' => 'form-control', 
								'data-select-tipus-centre' => URL::route('centres.json'), 
								'style' => 'width:200px; float:left; margin-right:20px;'
								
								));
								
	echo Form::select('centre', array(), $centre, array('data-select-centre', 
													'class' => 'form-control',
													'style' => 'width:300px',
													'data-centre' => $centre->getNom()
													));
	
	
	?>
	<br>
	<?php
		
	echo Form::submit('Editar grupo',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));
	
	echo Form::close();
		
	?>
</div>	
	
