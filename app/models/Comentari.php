<?php

/**
 * @author Eduardo
 */
class Comentari extends Eloquent {
	
	protected $table = 'comentari';	
	protected $primaryKey = 'id_comentari';	
	public $timestamps = false;
	
	public function getID(){
		return $this->id_comentari;
	}
	
	public function getText(){
		return $this->text_comentari;
	}
	
	public function getData(){
		return $this->data_comentari;
	}
	
	/**
	 * Relació d'un comentari amb el seu autor
	 */
	public function autor(){
 		return $this->belongsTo('Estudiant','estudiant_email');
	}
	
	/**
	 * Relació d'un comentari amb la seva aportació
	 */
	public function aportacio(){
		return $this->belongsTo('Aportacio','aportacio_id_aportacio');
	}
	
}
