<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
    });
</script>

<div class="capcalera">	  
		<h2 style="float: left;"><?php echo $esdeveniment->getNom() ?></h2>
	
		<?php
			
			$moderador = $esdeveniment->autor;
			
			if($moderador->getEmail() == Auth::user()->email_estudiant){
				$slug = $esdeveniment->getSlug();
				?>
				
				<?php
				/*Estil depenent de la longitud del grup*/
					if (strlen($esdeveniment->getNom()) > 25){
						$estil = "margin-top:0px;";
						?> <div style="clear:both"></div> <?php
					}else{
						$estil = "";
					}			
				?>
				
				<div class="moderar modH1" style="<?php echo $estil ?>">
					<a href="<?php echo URL::route('esdeveniment.editar', array('slug' => $esdeveniment->getSlug()))?>">
						<img src="/laravel/public/img/edit3.png" 
						alt="Moderar" 
						data-toggle="tooltip" 
						data-placement="right" 
						data-original-title="Modifica el evento" 
						height="16" 
						width="16">
					</a>			
				</div>
				
				<?php
			}
		?>
</div>

<ul class="nav nav-tabs">
	<li class = "active">
		<a href="#">
			Información
		</a>
	</li>
	<li>		
		<a href="<?php echo route('esdeveniment.missatges', array('slug' => $esdeveniment->getSlug()))?>">
			Mensajes
		</a>
	</li>
</ul>

<br/>

<table class="table table-striped table-bordered table-hover">
	<tr class="success"> 
		<td>Fecha</td>
		<td>Moderador</td>
		<td>Grupo</td>
	</tr>
	<tr > 
		<?php
			$data_esd = $esdeveniment->getData();
			$data_format = new DateTime($data_esd);
		?>
		<td><?php echo 'Día '.$data_format->format('d-m-Y').' a las '.$data_format->format('H:i'); ?></td>
		<?php
			$moderador = $esdeveniment->autor;
		?>
		<td style="max-width:180px; overflow:hidden;"><?php echo $moderador->getNom().' '.$moderador->getCognoms(); ?></td>
		<?php
			$grup = $esdeveniment->grup;
		?>
		<td style="max-width:180px; overflow:hidden;"><?php echo $grup->getNom(); ?></td>
	</tr>
</table>

<?php

$descripcio = $esdeveniment->getDescripcio();
if($descripcio != ''){
	?>
	<h4>Descripción</h4>

	<?php
	$descripcio = str_replace("\n", "<br>", $descripcio);
	echo $descripcio;
}





