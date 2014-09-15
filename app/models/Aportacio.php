<?php

/**
 * @author Eduardo
 */
 
class Aportacio extends Eloquent {
	
	protected $table = 'aportacio';	
	protected $primaryKey = 'id_aportacio';	
	public $timestamps = false;
	
	public function getID(){
		return $this->id_aportacio;
	}
	
	public function getInfo(){
		return $this->info_aportacio;
	}
	
	public function getData(){
		return $this->data_aportacio;
	}
	
	public function getFitxer(){
		return $this->fitxer_aportacio;
	}
	
	/**
	 * Relació de l'aportació amb l'assignatura a la qual pertany
	 */
	public function assignatura(){
		return $this->belongsTo('Assignatura', 'id_assignatura');
	}
	/**
	 * Relació de l'aportació amb l'esdeveniment al qual pertany
	 */
	public function esdeveniment(){
		return $this->belongsTo('Esdeveniment', 'id_esdeveniment');
	}
	/**
	 * Defineix la relació d'una aportació amb els seus comentaris
	 */
	public function comentaris(){
		return $this->hasMany('Comentari','aportacio_id_aportacio');
	}
	/**
	 * Numero de comentaris d'una aportació
	 */
	public function num_comentaris(){
		return $this->comentaris()->count();
	}
	
	/**
	 * Relació d'una aportació amb el seu autor
	 */
	public function autor(){
 		return $this->belongsTo('Estudiant','estudiant_email');
	}
	
	/**
	 * Retorna els 2 darrers comentaris d'una aportació
	 */
	public function getLastComments(){
		return $this->comentaris()->orderBy('data_comentari','DESC')->limit(2)->get();
	}
	
	/**
	 * Retorna els comentaris anteriors a un donat
	 * @param int [$last] identificador d'un comentari
	 */
	public function getComentarisSince($last){
		return $this->comentaris()->orderBy('id_comentari','DESC')->where('id_comentari','<',$last)->get();
	}
}
