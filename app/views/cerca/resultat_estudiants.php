<h1>Resultados</h1>

<ul class="nav nav-tabs">
	<li class = "active">
		<a href="#">
			Estudiantes
		</a>
	</li>
	<li>
		<a href="<?php echo URL::route('cerca.grups', 
								array('text' => $text))?>">
			Grupos
		</a>
	</li>
</ul>

<div class="list-group"> 				
	<?php
	if($num_est > 0){
		?>
		<h4>Estudiantes</h4>
		<?php
	
		foreach($estudiants as $est){
		?> 
			<div>
				<a class="list-group-item" 
					href="<?php echo URL::route('estudiant.consultar',
					array('slug'=> $est->getSlug()));?>">
					
					<div class="imgPerfil">
						<?php 
							
							$urlImatge = $est->getFoto();
							
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
					
					
					<div style="margin-left:60px;">
						<div>
							<strong> <?php echo $est->getNom().' '.$est->getCognoms();?> </strong> 
						</div>
						<div>
							<?php echo 'En '.$est->num_grups().' grupos' ?>
						</div>
					</div>
				</a>
			</div>
			<br/>
		<?php 
		}	
	}else{
		echo '<br>No se han encontrado resultados con esa búsqueda';
	}
	?>
</div>

<?php
	echo $estudiants->links();	
?>