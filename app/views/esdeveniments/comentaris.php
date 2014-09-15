<?php
$comentari = null;
for($i = $comentaris->count()-1; $i >= 0; $i--){
	$comentari = $comentaris[$i];
	echo View::make('esdeveniments/comentari', array('comentari' => $comentari));
}
?>