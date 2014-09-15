<h2><?php echo $estudiant->getNom().' '.$estudiant->getCognoms(); ?></h2>

<!--Pestanyes-->
<ul class="nav nav-tabs">
	<li>
		<a href="<?php echo URL::route('estudiant.consultar', 
								array('slug' => $estudiant->getSlug()))?>">
			Perfil
		</a>
	</li>
	<li class = "active">
		<a href="#">
			Matriculaciones
		</a>
	</li>
</ul>

	<br/> 
	
	<?php
	
	$emaillog = Auth::user()->getEmail();
	
	if($emaillog == $estudiant->getEmail()){
		?>
		<div class="alert alert-warning">
        	<strong>¡Eres tú mismo!</strong> 
        	Si quieres abandonar un grupo dirígete a su perfil
      	</div>
		<?php
	}else{
	
		$estSessio = Estudiant::find($emaillog);
		$grups = $estSessio->grups;
		
		$moderador = false;
		foreach ($grups as $grup) {
			if($grup->getEmail() == $emaillog){
				$moderador = true;
				break;
			}
		}
		
		if($moderador == false){
			?>
			<div class="alert alert-warning">
	        	<strong>¡No moderas ningún grupo!</strong> 
	        	<a href="<?php echo route("grup.nou") ?>" class="alert-link">Crea uno nuevo</a>
	      	</div>
			<?php
		}else{
		
			?>
			
			<table class="table table-striped table-bordered table-hover">
				<tr class="success" style="text-align:center">
					<td>Grupos que modero</td>
					<td>Matricular/Desmatricular</td>
				</tr>
			<?php
			
				$current = $estudiant->grups()->lists('id_grup');
				$jo = Estudiant::find($emaillog);
				$meus_grups = $jo->grups;
				
				foreach($meus_grups as $grup){
					?>	
						<tr style="text-align: center">
					<?php
					$moderador = $grup->getEmail();	
					
					if(($moderador == $emaillog) && ($emaillog != $estudiant->getEmail())){
						
						if (in_array($grup->getID(), $current)){
							
							?>	
							<td>
							<?php
								echo "Persona matriculada en: ".$grup->getNom();
							?>
							</td>
							
							<td>
								<a href="<?php echo URL::route('estudiant.desmatricular',
																array('slug'=> $estudiant->getSlug(),
																'grup' => $grup->getID()))?>" class = "btn btn-danger btn-xs">
									Desmatricular
								</a>
						    </td> 
						    
					    <?php
						}
						else{
							?>	
							<td>
								<?php
								echo "Persona no matriculada en: ".$grup->getNom();
								?>
							</td>
							<td style="text-align: center">
								<a href="<?php echo URL::route('estudiant.matricular',
								array('slug'=> $estudiant->getSlug(),
								'grup' => $grup->getID()))?>" class = "btn btn-success btn-xs">Matricular</a>
						    </td>
						    <?php	
						}
					}
				?>	
					</tr>
				<?php
				}
				?>
				</table>
			<?php 	
			}	
		}
		?>