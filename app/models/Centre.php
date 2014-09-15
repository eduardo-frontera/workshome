<?php

/**
 * @author Eduardo
 */
class Centre extends Eloquent {
	
	protected $table = 'centre';	
	protected $primaryKey = 'nom_centre';	
	public $timestamps = false;
	
	public function getNom(){
		return $this->nom_centre;
	}
	
	/**
	 * Retorna tots els centres de la BBDD
	 */
	public static function showCentres(){
		return Centre::all();
	}
	
	/**
	 * Relació d'un centre amb el seu tipus
	 */
	public function tipus()
	{		
		return $this->belongsTo('TipusCentre', 'tipus_centre_nom_tipus_centre');
	}
}
