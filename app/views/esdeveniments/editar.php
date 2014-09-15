<?php
    echo HTML::style('css/calendar/datepicker.css');
	echo HTML::script('js/calendar/bootstrap-datepicker.js');
	echo HTML::script('js/calendar/bootstrap-datepicker.es.js');
	echo HTML::script('js/pages/calendari.js');
?>

<div class="capcalera">	
	<h2 style="float:left;">
		<?php echo $esdeveniment->getNom(); ?>
	</h2> 
	
	<?php
	/*Estil depenent de la longitud del grup*/
		if (strlen($esdeveniment->getNom()) > 23){
			$estil = "margin-top:3px; margin-bottom:10px";
			?> <div style="clear:both"></div> <?php
		}else{
			$estil = "margin-top:30px; margin-left:5px;";
		}			
	?>
	
	<a href ="#" data-toggle="modal" data-target="#avisModal" class="btn btn-danger btn-xs"
		style = "<?php echo $estil ?>">
			Eliminar
		</a>
</div>

<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger" style="margin-bottom:10px;">
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('nombre', '<li>:message</li>');
		        echo $errors->first('fecha', '<li>:message</li>');
		        echo $errors->first('hora', '<li>:message</li>');
				echo $errors->first('minuto', '<li>:message</li>');
				echo $errors->first('descripción', '<li>:message</li>');
				echo $errors->first('grupo', '<li>:message</li>');
			?>
		    </ul>   
	    </div>
 	<?php
	}
?>

	<?php
	echo Form::open(array());

	echo Form::text('nom', $esdeveniment->getNom(),array('placeholder' => 'Nombre', 
														 'class' => 'form-control',
														 'required',
														'maxlength' => '60'
														 ));
	
	?> 
	<br/> 
	<?php
	
	echo Form::textarea('descripcio', $esdeveniment->getDescripcio(),array('placeholder' => 'Descripción', 
																			'class' => 'form-control txtArea',
	 																		'rows' => 3,
	 																		'maxlength' => '255'
	 																		));
	?> 
	<br/> 
	<?php
	
	echo Form::label('data', 'Fecha del evento: ', array('style' => 'margin-top:9px;'));
	
	?> <div style="display: inline-block; float: right; margin-right: 35px;"><?php
		
	$data = $esdeveniment->getData();
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$any = substr($data,0,4);	

	$hora = substr($data,11,2);
	$minut = substr($data,14,2);
	
	$data_esdeveniment=$dia.'/'.$mes.'/'.$any;
	?>
		
	<div style="float:left;" class="input-group date" id="dp3" data-date="<?php echo $data_esdeveniment ?>" data-date-format="mm-dd-yyyy">
		<input value="<?php echo $data_esdeveniment ?>" name="data" class="form-control" type="text" readonly="" style="width:150px;">
		<span class="input-group-addon" style="width:0%; cursor:pointer">			
			<img src="/laravel/public/img/calendar_bootstrap.png" 
						alt="Moderar"  
						height="16" 
						width="16">
		</span>
	</div>

	<div style="display:inline-block; overflow: hidden">
		<?php
		echo Form::selectRange('hora', 0, 23, $hora,
			array('class' => 'form-control', 
			'style' => 'width:85px; float:left; margin-right:10px; margin-left:14px;'
		));
		?>
		
		<?php
		echo Form::label('punts', ':', array('style' => 'margin-top:6px;'));
	?>
	</div>
	
	<?php
		$minuts = array();
		$minuts['00'] = '00';
		$minuts['05'] = '05';
		$minuts['10'] = '10';
		$minuts['15'] = '15';
		$minuts['20'] = '20';
		$minuts['25'] = '25';
		$minuts['30'] = '30';
		$minuts['35'] = '35';
		$minuts['40'] = '40';
		$minuts['45'] = '45';
		$minuts['50'] = '50';
		$minuts['55'] = '55';
		
	?>	
	
	<div style="display:inline-block; overflow: hidden">
		<?php
		echo Form::select('minut', $minuts, $minut,
			array('class' => 'form-control', 
			'style' => 'width:85px; float:left; margin-right:10px; margin-left:8px;'
		));
		?>
	</div>
	
	</div>
	
	<div style="clear:both"></div>
	
	<br/>
	
	<?php
	
	$nom_grups = array();
	foreach($grups as $grup){
		$nom_grups[$grup->getID()] = $grup->getNom();	
	}
	
	echo Form::label('grup', 'Grupo del evento: ');
	$id_grup = $esdeveniment->grup->getID();
	echo Form::select('grup', 
						$nom_grups,
						$id_grup,
						array('class' => 'form-control', 
						'style' => 'display:inline-block; 
									width: 300px; 
									margin-left:10px;'
					));
	 ?>
			
	 <br/> 
	 <br/> 
	 
 	<?php	
	echo Form::submit('Editar evento',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));	
	echo Form::close();		
	?>
	
	<div id="sandbox-container"></div>
	
<!-- Modal -->
<div class="modal fade" id="avisModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="titolAvis">Aviso</h4>
			</div>
			<div class="modal-body">
				¿Estás seguro de que quieres eliminar el evento? 
			</div>
			<div class="modal-footer">
				<a href="<?php echo URL::route('esdeveniment.eliminar',array('id' => $esdeveniment->getID()))?>" class="btn btn-primary">Sí
				</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>	