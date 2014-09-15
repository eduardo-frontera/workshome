<script language="javascript" type="text/javascript">
	function sizeBox(obj) {	  
	   obj.style.height = "100px";
	}
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({trigger: 'clic','placement': 'right'});
    });
</script>

<h1>Información adicional</h1>

<?php
	if($errors->count() > 0 ){	
		?>
		<div class="alert alert-danger" style="margin-top:10px;">
    		<p>Han ocurrido los siguientes errores:</p>	
		    <ul>
		    	<?php
		        echo $errors->first('Descripción', '<li>:message</li>');
		        echo $errors->first('Aficiones', '<li>:message</li>');
			?>
		    </ul>   
	    </div>
 	<?php
	}
?>

<?php
	$auth = Session::get('actualitzat');
	if($auth == 'ok'){
		Session::forget('actualitzat');
		?>
		<div class="alert alert-info" style="margin-top:10px">
			Información actualizada correctamente
		</div>
		<?php
	}

$descripcio = $estudiant->getDescripcio();
$aficions = $estudiant->getAficions();

echo Form::open(array('url' => route('estudiant.actualitzar'))); 

echo Form::textarea('descripcio', $descripcio, array('placeholder' => 'Descríbete', 
														'class' => 'form-control',
														'id' => 'boxDescripcio',
														'onfocus' => 'sizeBox(this)',											
														'style' => 'max-width:583px; height:50px;',														
														'maxlength' => '500',
														'resize' => 'none',
														'data-toggle' => 'popover', 
														'data-placement' => 'right', 
														'data-content' => '¿Cómo te describirías?'
														)
													);

?><br/><?php
											
echo Form::textarea('aficions', $aficions, array('placeholder' => 'Tus aficiones', 
														'class' => 'form-control',
														'id' => 'boxAficions',
														'onfocus' => 'sizeBox(this)',														
														'style' => 'max-width:583px; height:50px;',
														'maxlength' => '500',
														'resize' => 'none',
														'data-toggle' => 'popover', 
														'data-placement' => 'right', 
														'data-content' => '¿Cuáles son tus aficiones a parte de estudiar un montón?'
														)
													);
													
?><br/><?php

echo Form::submit('Actualizar',array('class' => 'btn btn-primary btn-sm', 'style' => 'float:right;'));
echo Form::close();

?>

     