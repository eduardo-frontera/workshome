<h1> <?php echo 'Nuevas Solicitudes'?> </h1>

<h4 style="margin-top:20px;"><?php echo $grup_sol->getNom();?></h4>
<?php
	
if($num_sol > 0){
	?>
	<table class="table table-striped table-bordered table-hover">
	<?php

    foreach($estudiants_sol as $estudiant){
    	?>
    	<tr>    		
	    	<td  
    		style="max-width: 150px; cursor:pointer;cursor:hand"
    		onClick='document.location.href="<?php echo URL::route('estudiant.consultar',
												array('slug'=> $estudiant->getSlug()));?>"'
			> 
	    		
	    		<div class="imgPerfil">
					<?php 
						
						$urlImatge = $estudiant->getFoto();
						
						if($urlImatge == ''){
							$urlImatge = '/laravel/public/img/profile.png';
						}else{
							$urlImatge = '/htdocs/laravel/public/'.$urlImatge;
					 	}
					?>
					<img				
						src="<?php echo $urlImatge ?>" 
						alt="Perfil" 
						width="50px"
						height = "50px" >
				</div>
				<div style="display:inline-block; margin-left:10px; margin-top:10px;">
					<?php echo $estudiant->getNom().' '.$estudiant->getCognoms(); ?>
				</div>
	    	    	
	    	</td>
	    	
	    	<td style = "text-align: center; width:50px;">
	    		<a href="<?php echo URL::route('estudiant.solmatricula', array('slug' => $estudiant->getSlug(), 
	    		'grup' => $grup_sol->getID()))?>" class="btn btn-success btn-sm" style="margin-top:5px;">
	    			Aceptar
	    		</a>
    		</td> 
    		
	    	<td style = "text-align:center; width:50px;">
	    		<a href="<?php echo URL::route('estudiant.cancelsolmatricula', array('slug' => $estudiant->getSlug(), 
	    		'grup' => $grup_sol->getID()))?>" class="btn btn-danger btn-sm" style="margin-top:5px;">
	    			Rechazar
	    		</a>
    		</td> 
		</tr>
	   <?php	
    }
	?>
	</table>
	<?php
	}else{
		?>
		<div style="margin-top: 15px;">
			<div class="alert alert-warning">
	        	<strong>¡No hay más solicitudes!</strong> 
	        	
	      	</div>
		</div>
		<?php
	}
