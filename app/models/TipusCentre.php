<?php

/**
 * @author Eduardo
 */
class TipusCentre extends Eloquent {
	
	protected $table = 'tipus_centre';	
	protected $primaryKey = 'nom_tipus_centre';	
	public $timestamps = false;
	
	
	public function getNom(){
		return $this->nom_tipus_centre;
	}
	
	/**
	 * Relació dels centres que existeixen que són d'un tipus
	 */
	public function centres(){
		return $this->hasMany('Centre', 'tipus_centre_nom_tipus_centre');
	}
}
