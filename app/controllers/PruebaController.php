<?php
    class PruebaController extends BaseController {
    		
	public function mostrarPlantilla1(){
		$this->layout = View::make('layouts.master2');
		$this->layout->title = 'Workshome - Plantilla';
		$this->layout->description = '';
		$this->layout->content = View::make('pruebas/plantilla1');    	
	}
	
	public function mostrarPlantilla2(){
		$this->layout->title = 'Workshome - Plantilla';
		$this->layout->description = '';
		$this->layout->content = View::make('pruebas/plantilla2');    	
	}		
}
?>