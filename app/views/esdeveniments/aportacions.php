<?php
foreach($aportacions as $aportacio){
	echo View::make('esdeveniments/aportacio', array('aportacio' => $aportacio));
}
?>