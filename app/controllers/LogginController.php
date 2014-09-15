<?php

/**
 * @author Eduardo
 */
class LogginController extends BaseController {
	
	/**
	 * Tanca la sessió actual
	 */
    public function sortir(){
		Auth::logout();
		return Redirect::route('index');
	}
}
?>