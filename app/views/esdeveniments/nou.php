<?php
    echo HTML::style('css/calendar/datepicker.css');
	echo HTML::script('js/calendar/bootstrap-datepicker.js');
	echo HTML::script('js/calendar/bootstrap-datepicker.es.js');
	echo HTML::script('js/pages/calendari.js');
?>

<h1>Nuevo evento</h1>

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
echo Form::text('nom', null,array('placeholder' => 'Nombre', 
									'class' => 'form-control',									
									'required',
									'maxlength' => '60',
									'autofocus'
									));
?> 

<br/> 

<?php

echo Form::textarea('descripcio', null,array('placeholder' => 'Descripción', 
												'class' => 'form-control txtArea',
												'rows' => 3,
												'maxlength' => '255'
												));

?> 
	<br/> 
<?php
	
echo Form::label('data', 'Fecha del evento: ', array('style' => 'margin-top:9px;'));
	
	?> <div style="display: inline-block; float: right; margin-right: 35px;"><?php
	
	date_default_timezone_set("Europe/Madrid");
	$avui = date("d/m/Y");
		
	?>

	<div style="float:left;" class="input-group date" id="dp3" data-date="12-02-2012" data-date-format="mm-dd-yyyy">
		<input value="<?php echo $avui ?>" name="data" class="form-control" type="text" readonly="" style="width:150px;">
		<span class="input-group-addon" style="width:0%; cursor:pointer">
			<img src="/laravel/public/img/calendar_bootstrap.png" 
					alt="Moderar"  
					height="16" 
					width="16">
		</span>
	</div>
	
	<?php
		$hora = Date("H");
		
		if($hora < 22){
			$hora += 1;
			$minut = '00';
		}else{
			$minut = '55';
		}
	?>
	
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
	
	echo Form::select('grup', 
						$nom_grups,
						null,
						array('class' => 'form-control', 
						'style' => 'display:inline-block; 
									width: 300px; 
									margin-left:10px;'
					));
 ?>
		
 <br/> 
 <br/> 
 
 <?php

echo Form::submit('Crear evento',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));

echo Form::close();	

?>

<div id="sandbox-container"></div>

<script type="text/javascript">
	$('#sandbox-container div').datepicker({
		format: "dd/mm/yyyy",
		weekStart: 1,
		startDate: "today",
		language: "es",
		autoclose: true,
		todayHighlight: true
	});
</script>
	
<?php	