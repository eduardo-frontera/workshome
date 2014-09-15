<div data-aportacio data-date="<?php echo $aportacio->getData()?>" style="margin-top:20px;" class="aportacio">
	<div class="imgPerfil">
		<?php 
			$autor = $aportacio->autor; 
			$urlImatge = $autor->getFoto();
			
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
	
	<div class="capaAportacio">	
		<div>		
			<a href="<?php echo URL::route('estudiant.consultar', 
				array('slug' => $autor->getSlug()))?>">
					<?php echo $autor->getNom().' '.$autor->getCognoms(); ?> 
			</a>
		</div>	
		
		<div data-aportacio-texto>	
			<?php 
				$text = $aportacio->getInfo();
				
				/*Mostrar les pàgines webs com a links externs*/
				$aport= preg_replace("/((www)[^\s]+)/", 
						'<a target="_blank" href="//$1">$0</a>', 
						$text);
						
				/*Conservar salts de linea introduits per l'usuari*/
				$aport = str_replace("\n", "<br>", $aport);
				echo $aport 
			?>	
		</div>
	</div>		
	
	<?php
	
	/*Comprovació de un possible fitxer adjunt i en cas afirmatiu mostrar-ho*/
	
	$fitxer = $aportacio->getFitxer();
	
	if($fitxer!=null){
		
		$valors = getimagesize($fitxer);
		$width = $valors[0]; //width
		$height = $valors[1]; //height
		
		if($width > 0 && $height > 0){
			
			if($width >= 370){
				$rel = $height / $width;
				$width_foto = 370;
				$height_foto = ceil($width_foto * $rel);
			} else if($height >= 438){
				$rel = $height / $width;
				$height_foto = 438;
				$width_foto = ceil($height_foto * $rel);
			} else if($width < 64 || $height < 64){
				$width_foto = 64;
				$height_foto = 64;
			} else{
				$width_foto = $width;
				$height_foto = $height;
			}
		
		?>
		<div class="capaImatge">
			<a href="<?php echo '/htdocs/laravel/public/'.$fitxer ?>" data-lightbox="image-1">
				<img class="imgAportacio" 
					src="<?php echo '/htdocs/laravel/public/'.$fitxer ?>" 
					alt="Imagen aportación" 
					width="<?php echo $width_foto ?> "
					height = "<?php echo $height_foto ?> " >
			</a>
		</div>

		<?php
		}
		?>
		
		<div class="capaAportacio">
			<?php			
			$id_aportacio = $aportacio->getID();			
			?>
			<img src="/laravel/public/img/attach.png" 
					alt="Moderar" 
					height="16" 
					width="16">
			<a href="<?php echo URL::route('esdeveniment.download', array('id' => $id_aportacio))?>" target="_blank">
				Descargar <?php echo basename($aportacio->getFitxer()) ?>
			</a>
		</div>
	<?php
	}		
	?> 
	
	<br/>
	
	<div class="capaAportacio">
		<div>
			<?php 
				if($aportacio->estudiant_email == Auth::user()->email_estudiant){
					?>
					<div style="font-size:13px; float:left; display:inline-block">
						<?php 
						$data_apor = $aportacio->getData();
					   	$date = new DateTime($data_apor);
						echo 'Enviado día '.$date->format('d-m-Y').' a las '.$date->format('H:i');
						?>
					</div>
				<?php
				}else{
					?>
					<div style="font-size:13px;">
						<?php 
						$data_apor = $aportacio->getData();
					   	$date = new DateTime($data_apor);
						echo 'Enviado día '.$date->format('d-m-Y').' a las '.$date->format('H:i');
						?>
					</div>
				<?php
				}
			?>
			
			<?php 
				if($aportacio->estudiant_email == Auth::user()->email_estudiant){
					?>
					<div style="margin-left:3px; display:inline-block; margin-bottom:3px;">			
						<div class="btn-group">
							<button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li>
									<a data-editar-aportacio="<?php echo $aportacio->getID()?>"
							href="<?php echo route('aportacio.editar.esdeveniment', array('id' => $aportacio->getID())) ?>">
										Editar aportación
									</a>
								</li>
								<li>
									<a data-elimina-aportacio="<?php echo $aportacio->getID()?>"
							href="<?php echo route('aportacio.eliminar.esdeveniment', array('id' => $aportacio->getID())) ?>">
										Eliminar aportación
									</a>
								</li>
							</ul>
						</div>
					</div>
					<?php
				}
			?>		
		</div>
	
		<?php
		$comentaris = $aportacio->getLastComments();
		?>
		<div id="comentaris_<?php echo $aportacio->getID()?>">
			<?php
			$comentari = null;
			for($i = $comentaris->count()-1; $i >= 0; $i--){
				$comentari = $comentaris[$i];
				echo View::make('esdeveniments/comentari', array('comentari' => $comentari));
			}		
			
			if($aportacio->num_comentaris() > 2){
				$first = $comentaris[$comentaris->count()-1];
				?>
			
				<a href ="" 
				id="<?php echo $aportacio->getID() ?>"
				data-aportacio="<?php echo $aportacio->getID() ?>" 
				data-seg-com="<?php echo route('seguents.comentaris', array('id'=>$aportacio->getID(),'last' => $first->getID()))?>">
					Ver más comentarios
				</a>
				<?php
			}
			?>
		</div>
	
		<div class="resposta">
			<?php
	
			echo Form::open(array('class' => 'form-signin', 
								'data-form-respuesta' => $aportacio->getID(),
								'autocomplete' => 'off',
								'url' => route('comentari.esdeveniment.create', array('id' => $aportacio->getID()))
								));?>	
		   
		    <div class="inputResposta">				
			<?php echo Form::text('comentari', null, array('placeholder' => 'Responde a tu compañero...', 
																'class' => 'form-control',
																'style' => 'height:45px'																
																)
																);?>
			</div>
			
			<div class="botoResposta">
				<?php echo Form::submit('Enviar',
										array('class' => 'btn btn-primary btn-sm', 'data-boto'));?>		
			</div>
			
			<?php echo Form::close();?>

		</div>
	</div>
</div>

<?php
?>