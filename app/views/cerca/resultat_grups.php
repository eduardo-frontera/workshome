<h1>Resultados</h1>

<ul class="nav nav-tabs">
	<li>
		<a href="<?php echo URL::route('cerca.estudiants', 
								array('text' => $text))?>">
			Estudiantes
		</a>
	</li>
	<li class = "active">
		<a href="#">
			Grupos
		</a>
	</li>
</ul>

<div class="list-group"> 				
	<?php
	if($num_gr > 0){
		?>
		<h4>Grupos</h4>
		<?php
		foreach($grups as $grup){
		?> 
			<div>
				<a class="list-group-item" 
					href="<?php echo URL::route('grup.consulta',
					array('slug'=> $grup->getSlug()));?>">
					<div>
						<strong> <?php echo $grup->getNom(); ?> </strong> 
					</div>
					<div>
						<?php echo $grup->getDescripcio() ?>
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
	echo $grups->links();	
?>