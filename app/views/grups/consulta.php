<div class="capcalera">	
	<h2 style="float:left;">
		<?php echo $grup->getNom();?>
	</h2> 
 
	<?php	

	$emaillog = Auth::user()->email_estudiant;
	$estudiant = Estudiant::find($emaillog);
	$meus_grups = $estudiant->grups;	
	$matriculat = false;
	
	foreach($meus_grups as $gr){
		if ($gr->getID() == $grup->getID()){
			$matriculat = true;
			break;
		}
	}
	
	$mis_sol = $estudiant->grupos_sol()->lists('id_grup');
	if (in_array($grup->getID(), $mis_sol)){
		
		/*Estil depenent de la longitud del grup*/
		if (strlen($grup->getNom()) > 33){
			$estil = "margin-top:3px; margin-bottom:10px";
			?> <div style="clear:both"></div> <?php
		}else{
			$estil = "margin-top:30px";
		}
		
		?>
		<a href="<?php echo URL::route('grup.cansolicitud',
				array('id' => $grup->getID()))?>" 
				class = "btn btn-danger btn-xs" 
				style = "<?php echo $estil ?>; margin-left:5px;">
			Cancelar solicitud
		</a>
		<?php 
	}else{	
		if(!$matriculat){
			
			/*Estil depenent de la longitud del grup*/
			if (strlen($grup->getNom()) > 31){
				$estil = "margin-top:3px; margin-bottom:10px";
				?> <div style="clear:both"></div> <?php
			}else{
				$estil = "margin-top:30px";
			}
			
			?>
			
			<a href="<?php echo URL::route('grup.solicitud',array('id' => $grup->getID()))?>" class="btn btn-warning btn-xs"
				style = "<?php echo $estil ?>; margin-left:5px;">
				Solicitar matriculación
			</a>
			
			<?php 
		} else{
			if($grup->getEmail() == $emaillog){
				
				/*Estil depenent de la longitud del grup*/
				if (strlen($grup->getNom()) > 25){
					$estil = "margin-top:3px; margin-bottom:10px";
					?> <div style="clear:both"></div> <?php
				}else{
					$estil = "margin-top:30px";
				}
				
				?>
				<a href="#" data-toggle="modal" data-target="#avisModal" class="btn btn-danger btn-xs"
					style = "<?php echo $estil ?>; margin-left:5px;">
					Abandonar grupo y eliminar
				</a>
			<?php
			}else{
				
				/*Estil depenent de la longitud del grup*/
				if (strlen($grup->getNom()) > 33){
					$estil = "margin-top:3px; margin-bottom:10px";
					?> <div style="clear:both"></div> <?php
				}else{
					$estil = "margin-top:30px";
				}
				
				?>
				<a href="<?php echo URL::route('grup.sortir',array('id' => $grup->getID()))?>" class="btn btn-warning btn-xs"
					style = "<?php echo $estil ?>; margin-left:5px;">
					Abandonar grupo
				</a>
				<?php
			}
			
		}	
	}
	
	?>
	</div>
	
	<?php
	$descripcio = $grup->getDescripcio();
	if($descripcio != ''){
		?>
		<div style="margin-top:10px">
		<?php 		
		$descripcio = str_replace("\n", "<br>", $descripcio);
		echo $descripcio;
		?>
	</div>	
	<br/>
	<?php
	}
	
	$moderador = $grup->estudiant;
	
    $curs = $grup->curs; 
	
    $centre = $grup->centre;
    $nom_centre = $centre->getNom();
    $tipus_centre = $centre->tipus;
    $nom_tipus_centre = $tipus_centre->getNom();
    $nom_complet_centre = $nom_tipus_centre.' '.$nom_centre;
	?>
	
	<table class="table table-striped table-bordered table-hover">
    	<tr class="success"> 
    		<td>Aula</td>
    		<td>Moderador</td>
    		<td>Curso</td>
    		<td>Centro</td>
		</tr>
		<tr> 
    		<td style="max-width:100px; overflow:hidden;">
    			<?php 
    			$aula = $grup->getAula();
    			if($aula == ''){
    				$aula = '-';
    			}
				
    			echo $aula ?>
    		</td>
    		<td style="max-width:180px; overflow:hidden;">
    			<?php echo $moderador->getNom().' '.$moderador->getCognoms(); ?>
			</td>
    		<td><?php echo $curs->getNom(); ?></td>
    		<td><?php echo $nom_complet_centre?></td>
		</tr>
	</table>
	
	<!--Estudiants-->
	
	<?php         
	$estudiants = $grup->estudiants;
	?> <div class="list-group"> 
			<div class="list-group-item active">Estudiantes del grupo</div>
	<?php
	foreach($estudiants as $est){
		?>
		<a class="list-group-item" 
		href="<?php echo URL::route('estudiant.consultar',array('slug'=> $est->getSlug()));?>">
			<?php echo $est->getNom(); echo' '; echo $est->getCognoms();?>
		</a>
		<?php
	}	?>
	</div>				
		
	<!--Assignatures-->
	
	<?php
	$asignaturas = $grup->assignatures;     
	?> <div class="list-group"> 
	   	<div class="list-group-item active">Asignaturas</div>
	<?php
	
	if($matriculat){
		
		foreach($asignaturas as $asig){					
			$nom = $asig->getNom(); ?> 
		
			<a class="list-group-item" href="<?php echo URL::route('assignatura.info',array('slug'=> $asig->getSlug()));?>">
				<?php echo $asig->getNom();?>
			</a>
			<?php 
		} 
		?>	
	</div>
		<?php
	}else{
		foreach($asignaturas as $asig){					
			$nom = $asig->getNom(); ?> 
		
			<div class="list-group-item">
				<?php echo $asig->getNom();?>
			</div>
			<?php 
		} 
	}
			
	?>
	
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
				<a href="<?php echo URL::route('grup.eliminar',	array('id' => $grup->getID()))?>" class="btn btn-primary">Sí
				</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>