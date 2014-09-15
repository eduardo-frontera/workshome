<?php
foreach($aportacions as $aportacio){
	echo View::make('assignatura/aportacio', array('aportacio' => $aportacio));
}
?>