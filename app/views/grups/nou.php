<?php 
	echo HTML::script('js/pages/selector_centre.js');
?>
<h1>Nuevo grupo</h1>

<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger">
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

<?php 

echo Form::open(array('class' => 'form-signin'));

?> <br/> <?php
echo Form::text('nom', null, array('placeholder' => 'Nombre grupo', 
									'class' => 'form-control',
									'autofocus',
									'required',
									'maxlength' => '60'
									));

?> <br/> <?php
echo Form::textarea('descripcio', null, array('placeholder' => 'Descripción grupo', 
												'class' => 'form-control txtArea',
												'rows' => 3,
												'maxlength' => '255'
												));
?> <br/> <?php

echo Form::text('aula', null, array('placeholder' => 'Aula clases', 
														'class' => 'form-control',
														'maxlength' => '25'
														));

?> <br/> <?php

echo Form::label('Info', '¿En qué centro se imparten las clases?');

?> <br/> <?php

$opcions = array();
$tcentres = TipusCentre::all();
foreach ($tcentres as $tcentre){
	$opcions[$tcentre->getNom()] = $tcentre->getNom();
}

echo Form::select('tipus_centre', $opcions, null, array('class' => 'form-control', 
								'data-select-tipus-centre' => URL::route('centres.json'), 
								'style' => 'width:200px; float:left; margin-right:20px;'								
								));
								
echo Form::select('centre', array(), null, array('data-select-centre', 
													'class' => 'form-control',
													'style' => 'width:300px',
													'data-centre' => 'none'													
													));
?> <br/> <?php

echo Form::submit('Crear grupo',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));

echo Form::close();
?>