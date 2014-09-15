<?php
$comentari = null;
for($i = $comentaris->count()-1; $i >= 0; $i--){
	$comentari = $comentaris[$i];
	echo View::make('assignatura/comentari', array('comentari' => $comentari));
}
?>