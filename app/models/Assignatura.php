<?php

/**
 * @author Eduardo
 */
class Assignatura extends Eloquent {
	
	protected $table = 'assignatura';	
	protected $primaryKey = 'id_assignatura';	
	public $timestamps = false;
	
	public function getID(){
		return $this->id_assignatura;
	}
	
	public function getNom(){
		return $this->nom_assignatura;
	}
	
	public function getSlug(){
		return $this->slug_assignatura;
	}
	
	public function getNomProf(){
		return $this->nom_professor;
	}
	
	public function getCognomsProf(){
		return $this->cognoms_professor;
	}
	
	/**
	 * Relació d'una assignatura amb el grup al qual pertany
	 */
	public function grup(){
		return $this->belongsTo('Grup', 'grup_id_grup');
	}
	
	/**
	 * Relació d'una assignatura amb les seves aportacions
	 */
	public function aportacions()
	{
		return $this->hasMany('Aportacio','id_assignatura');
	}
	
	/**
	 * Pagina les aportacions d'una assignatura
	 */
	public function getAportacions(){
		return $this->aportacions()->orderBy('data_aportacio','DESC')->paginate(5);
	}
	
	/**
	 * Retorna aportacions més recents a una data donada
	 * @param Date [$sinceDate] data d'una aportació
	 */
	public function getUltimesAportacions($sinceDate){
		return $this->aportacions()->where('data_aportacio','>',$sinceDate)->where('estudiant_email','<>',Auth::user()->getEmail())->orderBy('data_aportacio','DESC')->get();
	}
	
	/**
	 * Numero d'aportacions d'una assignatura
	 */
	public function num_aportacions(){
		return $this->aportacions()->count();
	}
	
	public static function bySlug($slug){
		return self::where('slug_assignatura','=',$slug)->firstOrFail();
	}
}

