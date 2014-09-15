<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class CentreController extends BaseController {
	
	/**
	 * Retorna tots els tipus de centres
	 */
	public function getTipusCentres(){
		$tipus = TipusCentre::get();
		return Response::json($tipus);
	}
	
	/**
	 * Retorna els centres d'un tipus
	 */
	public function getCentres(){
		try{
			$tipus = TipusCentre::find(Input::get('tipus'));
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		return Response::json($tipus->centres);
	}
	
}
