<?php

/**
 * @author Eduardo
 */
class FuncionamentController extends BaseController {
	
	/**
	 * Pàgina on s'especifica la politica de privacitat
	 */
    public function mostrarFuncionament(){
    	$this->layout = View::make('layouts.master2');
		$this->layout->title = 'Workshome - Funcionamiento';
		$this->layout->description = 'Como usar Workshome, tu red social académica';
		$this->layout->content = View::make('inici/funcionament');
	}
}
