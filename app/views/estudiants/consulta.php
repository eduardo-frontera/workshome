<h2>
	<?php echo $estudiant->getNom().' '.$estudiant->getCognoms(); ?>
</h2>

<?php
	
	$Socjo = Auth::user()->getEmail() == $estudiant->getEmail();
	
	if($Socjo){
		?>
			<div>
				<a href="<?php echo route('estudiant.editar')?>">
					<button type="button" class="btn btn-primary btn-sm">
						Editar perfil
					</button>
				</a>
			</div>
			<br/>
		<?php
	}

?>

<!--Pestanyes-->
<ul class = "nav nav-tabs">
	<li class = "active"><a href="#">Perfil</a></li>
	
	<?php
	
	if($Socjo){
		?>
		<li class="disabled">
			<a href = "#">
					Matriculaciones
			</a>
		</li>
		<?php
	}else{
		?>
		<li>
			<a href = "<?php echo URL::route('estudiant.matricules', 
						array('slug' => $estudiant->getSlug()))?>">
					Matriculaciones
			</a>
		</li>
		<?php
	}
	
	?>
</ul>

<br/>

<div class="list-group">
		<div class="imgPerfilEstudiant">
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
				width="80px"
				height="80px" >
		</div>
		<div class="list-group-item" style="position:static; margin-left: 85px;">
			  <h4 class="list-group-item-heading">
			  	<b>Información básica</b>
		  	  </h4>
			  <p class="list-group-item-text">
			  	<?php 
		
					$data_naixement = $estudiant->getData();
					$data_naix = new DateTime($data_naixement);
					
					$ara = new DateTime();									
					$diff = $ara->diff($data_naix);

					echo 'Nacido día '.$data_naix->format('d-m-Y').' ('.$diff->y.' años)';
		
				?>
			  	<br/>
			  	<?php
			  		$data_ingres = $estudiant->getIngres();
					$data_ing = new DateTime($data_ingres);
			
					echo 'En Workshome desde el '.$data_ing->format('d-m-Y');
			  	?>	
		  	  </p>
		</div>   
</div>

<?php
	$descripcio = $estudiant->getDescripcio();
	$descripcio = str_replace("\n", "<br>", $descripcio);
	if($descripcio!= ''){
?>
		<div class="list-group">
			<div class="list-group-item">
				  <h4 class="list-group-item-heading">
				  	<b>Descripción</b>
			  	  </h4>
				  <p class="list-group-item-text">
				  	<?php echo $descripcio; ?>
			  	  </p>
			</div>   
		</div>

<?php
	}
?>

<?php
	$aficions = $estudiant->getAficions();
	$aficions = str_replace("\n", "<br>", $aficions);
	if($aficions!= ''){
?>

	<div class="list-group">
		<div class="list-group-item">
			  <h4 class="list-group-item-heading">
			  	<b>Aficiones</b>
		  	  </h4>
			  <p class="list-group-item-text">
		  			<?php echo $aficions; ?>
		  	  </p>
		</div>   
	</div>
	
<?php
	}
?>              

<?php
	$num_grups = $estudiant->num_grups();
	if($num_grups > 0){
?>

		<div class="list-group"> 
			<div class="list-group-item">
				 <h4 class="list-group-item-heading">
				 	<b>Grupos en los que está matriculado</b>
			 	 </h4>
		 	</div>				
			<?php
			foreach($grups_usu as $grupo){
			?> 
				<a class="list-group-item" style="color:rgb(44, 62, 80);"
					<?php $slug = $grupo->getSlug();?>
					href="<?php echo URL::route('grup.consulta', 
								array('slug' => $slug))?>">
								<?php echo $grupo->getNom();?>
					
					<?php
					if($estudiant->getEmail() == $grupo->getEmail()){ ?>
						<span class="badge">
							Moderador
						</span>	
		   <?php }
					?>
				</a>
			<?php 
			}	
			?>
		</div>

<?php
	}else{
		?>
		<div class="alert alert-info">
			    <?php $meuperfil = Auth::user()->getEmail() == $estudiant->getEmail();
					  if($meuperfil){
					  	?>
					  	<strong>Aún no perteneces a ningún grupo</strong>
					  	<?php
					  }else{
					  	?>
					  	<strong>Aún no pertenece a ningún grupo</strong>
					  	<?php
					  }
			    ?>
      	</div>
		<?php
	}
?> 
