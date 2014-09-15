<?php

/**
 * @author Eduardo
 */
class PoliticaController extends BaseController {
	
	/**
	 * Pàgina on s'especifica la politica de privacitat
	 */
    public function mostrarPolitica(){
    	$this->layout = View::make('layouts.master2');
		$this->layout->title = 'Workshome - Política';
		$this->layout->description = 'Política de privacidad de Workshome';
		$this->layout->content = View::make('inici/politica');
	}
}
