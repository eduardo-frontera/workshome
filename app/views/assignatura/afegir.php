<?php 
	echo HTML::script('js/pages/assignatura.js');
?>
   
<h1>Añadir asignaturas</h1>
   
	<?php echo Form::open(array('class' => 'form-signin')); ?>
    <table id="assignatures">  
    <tbody>  
        <tr>
            <td width="150">Nombre asignatura</td>  
            <td width="150">Nombre profesor</td>  
            <td width="150">Apellidos profesor</td>  
        </tr>   
        <tr>
        	<td><input type='text' maxlength = '45' class = 'form-control input-sm' size='16' name='assignatura[" + 1 + "]'></td>   
    		<td><input type='text' maxlength = '45' class = 'form-control input-sm' size='16' name='nom[" + 1 + "]'></td>  
    		<td><input type='text' maxlength = '150' class = 'form-control input-sm' size='16' name='cognoms[" + 1 + "]'></td>
    		
    		<td><input type='button' value='Eliminar' class="btn btn-danger btn-xs" onclick='eliminarAssignatura(this)'></td>
        
        </tr>
    </tbody>  
	</table>
	
	<br/>
	
	<input onclick="afegirAssignatura()" type="button" class="btn btn-primary btn-sm" value="Nueva asignatura">
	<?php echo Form::submit('Crear',array('class' => 'btn btn-primary btn-sm')); ?>
	<?php echo Form::close(); ?>


<?php   
?>