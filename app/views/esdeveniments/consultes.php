
	<?php
		$num_grups = Auth::user()->num_grups();
		
		/*Si no està matriculat a cap grup no crear esdeveniments*/
		if($num_grups>0){
   	?> 
	

    
    <?php
    	$num_esdeveniments = Auth::user()->num_esdeveniments();

		if($num_esdeveniments>0){
   	?> 
    <div style="margin-bottom:15px;">
	    <h1 style="float:left;">Mis eventos</h1> 
		<div>
			<a href ="<?php echo URL::route('esdeveniment.nou')?>" class="btn btn-success btn-xs"
			style = "margin-top:35px; margin-left:5px;">
				Nuevo evento
			</a>
		</div>
    </div>
    
    <table class="table table-striped table-bordered table-hover">
    	<tr class="success">
    		<td>Nombre</td>
    		<td>Fecha</td>
    		<td>Grupo</td>
    	</tr>
    	
    	<?php 
			foreach($esdeveniments as $esdeveniment){
				?>
		    	<tr> 
		    		<td style="max-width: 150px; overflow:hidden;">
			    		<?php	
						$emaillog = Auth::user()->email_estudiant;						
						$creador = $esdeveniment->estudiant_email;			
						
						?>
						<a href="<?php echo URL::route('esdeveniment.consulta',
								array('slug'=> $esdeveniment->getSlug()));?>">
							<?php echo $esdeveniment->getNom();?>
						</a>	
		    		</td>  
		    		
		    		<td> 
		    			<?php 			    				
				    		$data_esdeveniment = $esdeveniment->getData();
				    		$data_esd = new DateTime($data_esdeveniment);
							echo $data_esd->format('d-m-Y');
							echo ' a las ';
							echo $data_esd->format('H:i').' h';
		    			?>							  
					</td>
					
		    		<td style="overflow:hidden;"> 
		    			<?php 
						 $grup = $esdeveniment->grup;
						 echo $grup->getNom();
						 ?> 
	    			</td>
	    			
		    	</tr>
		    	<?php 
				} 						
		    	?> 
    </table>
    <?php 
    }  else{
    	?>
		<div style="margin-bottom:15px;">
		    <h1 style="float:left;">Mis eventos</h1> 
			<div>
				<a href ="<?php echo URL::route('esdeveniment.nou')?>" class="btn btn-success btn-xs"
				style = "margin-top:35px; margin-left:5px;">
					Nuevo evento
				</a>
			</div>
    	</div>
    	<div style="clear:both">No tienes ningún evento</div>
    	<?php
    }
    }else{
    	?>
		<div style="margin-bottom:15px;">
		    <h1>Mis eventos</h1> 
    	</div>
    	
    	<div style="margin-top: 15px;">
		<div class="alert alert-warning">
        	Para poder crear un evento debes estar matriculado en un grupo.
      	</div>
	</div>
	<?php
    }
	?>