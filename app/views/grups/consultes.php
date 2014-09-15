<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
    });
</script>

<h1 style="float:left;">Mis grupos</h1> 
<div>
	<a href ="<?php echo URL::route('grup.nou')?>" class="btn btn-success btn-xs"
	style = "margin-top:35px; margin-left:5px;">
		Nuevo grupo
	</a>
</div>

<?php
$emaillog = Auth::user()->email_estudiant;
$num_grups = Auth::user()->num_grups();
	
if($num_grups > 0){
	?>	
	<div>
		<?php
		foreach ($grups as $grup) {
		    ?>   
			<div class="infoGrup">    
		        <div class="capcalera">	  
		    		<h4 style="float: left;">
		    			<?php $slug = $grup->getSlug();?>
		    			<a href="<?php echo URL::route('grup.consulta', array('slug' => $slug))?>">
		    				<?php echo $grup->getNom();?>
						</a>
					</h4>
		    
			    	<?php
			    	$moderador = $grup->getEmail();
					
					if($moderador == $emaillog){
						$slug = $grup->getSlug();
						?>
						<div class="moderar modH4">
							<a href="<?php echo URL::route('grup.editar', array('slug' => $slug))?>">
								<img src="/laravel/public/img/edit3.png" 
								alt="Moderar"  
								data-toggle="tooltip" 
								data-placement="top" 
								data-original-title="Modifica el grupo" 
								height="16" 
								width="16">
							</a>	
						</div>
						
						<?php
						$numPet = 0;
						$ests = $grup->solEstudiants;
						foreach($ests as $est){
							$numPet++;
							break;
							?> <br/> <?php
						}
						if($numPet > 0){
						    $slug_grup = $grup->getSlug();
							?>
							<a href="<?php echo URL::route('estudiant.solicituds',
															array('slug' => $slug_grup))?>" class="btn btn-danger btn-xs">
															Nuevas solicitudes
							</a>	
							<?php	
						}			
					}
			    	
			    	?>
		    	
		    	</div>
		    	
		    	<?php 
		    	$descripcio = $grup->getDescripcio();
				if($descripcio == null){
					?> <em> <?php echo 'Sin descripción'; ?></em>
					<?php
				}
				else{
					echo $descripcio;
				}
		    	?> 
		    		<br/> <br/>		    	
		    	<?php 		        
				/*Estudiants del grup*/
				$estudiants = $grup->estudiants;
				
				?> 
				<div class="list-group"> 
					<div class="list-group-item active">Estudiantes del grupo</div>
				<?php
				foreach($estudiants as $est){
					?>
					<a class="list-group-item" 
						href="<?php echo URL::route('estudiant.consultar',array('slug'=> $est->getSlug()));?>">
		    			<?php echo $est->getNom(); echo' '; echo $est->getCognoms();?>
					</a>
				<?php
				}	
				?>
				</div>					
				
				<?php
				
				/*Assignatures del grup*/
		    	$asignaturas = $grup->assignatures;     
				?> 
				<div class="list-group"> 
			   		<div class="list-group-item active">Asignaturas</div>
						<?php
						foreach($asignaturas as $asig){					
							$nom = $asig->getNom(); ?> 
							
							<a class="list-group-item" href="<?php echo URL::route('assignatura.info',array('slug'=> $asig->getSlug()));?>">
				    			<?php echo $asig->getNom();?>
							</a>
						<?php 
							} 
						?>	
				</div>	
				
			</div>
			
		<?php    	
		}	
		?>
	</div>
	<?php 
}else{
	?>
	<div style="margin-top: 15px;">
		<div class="alert alert-warning">
        	<strong>¡No tienes grupos!</strong> 
        	Crea uno o solicita la matriculación en uno ya existente
      	</div>
	</div>
	<?php
}
?>

<?php
	/*Botons de paginació*/
    echo $grups->links();
?>