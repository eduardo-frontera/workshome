<?php
	if($comentari->estudiant_email == Auth::user()->email_estudiant){
		?>
			<div data-comentari class="comentari propi"> 
		<?php
	}else{
		?>
			<div class="comentari company"> 
		<?php
	}
?>			 
		<div>
			<?php					
			$estudiant_com = $comentari->autor;
			?>
			<a href="<?php echo URL::route('estudiant.consultar', 
			array('slug' => $estudiant_com->getSlug()))?>">
				<?php echo $estudiant_com->getNom().' '.$estudiant_com->getCognoms(); ?> 
			</a>
		</div>
		
		<div data-comentari-text>
			<?php
			$textInCom = $comentari->getText();
		    $textOutCom = preg_replace("/((www)[^\s]+)/", 
				'<a target="_blank" href="//$1">$0</a>', 
				$textInCom);
			echo $textOutCom;
			?>
		</div>
		
		<div>
			<?php 
				if($comentari->estudiant_email == Auth::user()->email_estudiant){
					?>
					<div class="estil_data" style="float:left; display:inline-block">		
						<?php	
							$data_com = $comentari->getData();
						   	$date = new DateTime($data_com);
							echo 'Enviado día '.$date->format('d-m-Y').' a las '.$date->format('H:i');
						?> 	
					</div>
				<?php
				}else{
					?>
					<div class="estil_data">		
						<?php	
							$data_com = $comentari->getData();
						   	$date = new DateTime($data_com);
							echo 'Enviado día '.$date->format('d-m-Y').' a las '.$date->format('H:i');
						?> 	
					</div>
				<?php	
				}
			?>
			
			<div>
				<?php 
					if($comentari->estudiant_email == Auth::user()->email_estudiant){
						?>
					<div style="margin-left:3px; display:inline-block; margin-top:5px;">						
						<div class="btn-group">
							<button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li>
									<a data-editar-comentari="<?php echo $comentari->getID()?>"
										href="<?php echo route('comentari.editar.esdeveniment', array('id' => $comentari->getID())) ?>">
										Editar comentario
									</a>
								</li>
								<li>
									<a data-elimina-comentari="<?php echo $comentari->getID()?>"
							href="<?php echo route('comentari.eliminar.esdeveniment', array('id' => $comentari->getID())) ?>">
										Eliminar comentario
									</a>
								</li>
							</ul>
						</div>						
					</div>
						<?php
					}
				?>
			</div>
		</div>
	</div> 
<?php
?>