<?php

/**
 * @author Eduardo
 */
class Curs extends Eloquent {
	
	protected $table = 'curs';	
	protected $primaryKey = 'nom_curs';	
	public $timestamps = false;
	
	public function getNom(){
		return $this->nom_curs;
	}
	
	/**
	 * Retorna tots els cursos de la BBDD
	 */
	public function showCurs(){
		$cursos = Curs::all();
	}
}
