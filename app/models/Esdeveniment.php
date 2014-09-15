<?php

/**
 * @author Eduardo
 */
class Esdeveniment extends Eloquent {
	
	protected $table = 'esdeveniment';	
	protected $primaryKey = 'id_esdeveniment';	
	public $timestamps = false;
	
	public function getID(){
		return $this->id_esdeveniment;
	}
	
	public function getNom(){
		return $this->nom_esdeveniment;
	}
	
	public function getDescripcio(){
		return $this->descripcio_esdeveniment;
	}
	
	public function getData(){
		return $this->data_esdeveniment;
	}
	
	public function getSlug(){
		return $this->slug_esdeveniment;
	}
	
	public static function bySlug($slug){
		return self::where('slug_esdeveniment','=',$slug)->firstOrFail();
	}
	
	/**
	 * Relació d'un esdeveniment amb el seu autor
	 */
	public function autor(){
		return $this->belongsTo('Estudiant','estudiant_email');
	}
	
	/**
	 * Relació d'un esdeveniment amb el grup al qual pertany
	 */
	public function grup(){
		return $this->belongsTo('Grup','grup_id_grup');
	}
	
	/**
	 * Cerca dels esdeveniments d'un estudiant ordenats per data de proximitat
	 * @param string [$email] email de l'estudiant
	 */
	public static function byEstudiant($email){
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		return Esdeveniment::join('grup', 'esdeveniment.grup_id_grup','=','grup.id_grup')
					->join('estudiant_grup', 'grup.id_grup','=','estudiant_grup.grup_id_grup')
					->join('estudiant', 'estudiant.email_estudiant', '=', 'estudiant_grup.estudiant_email')
					->select('esdeveniment.*')
					->where('estudiant.email_estudiant','=',$email)
					->where('data_esdeveniment', '>', $ara)
					->where('grup.actiu_grup','=',true)
					->orderBy('esdeveniment.data_esdeveniment','ASC')
					->get();	 
	}
	
	/**
	 * Retorna el nombre d'esdeveniments totals d'un estudiant (suma de tots els grups)
	 * @param string [$email] email de l'estudiant
	 */
	public static function num_byEstudiant($email){
		return Esdeveniment::byEstudiant($email)->count();
	}
		
	/**
	 * Relació d'una esdeveniment amb les seves aportacions
	 */
	public function aportacions()
	{
		return $this->hasMany('Aportacio','id_esdeveniment');
	}
	
	/**
	 * Pagina les aportacions d'un esdeveniment
	 */
	public function getAportacions(){
		return $this->aportacions()->orderBy('data_aportacio','DESC')->paginate(5);
	}
	
	/**
	 * Retorna aportacions d'un esdeveniment més recents a una data donada
	 * @param Date [$sinceDate] data d'una aportació
	 */
	public function getUltimesAportacions($sinceDate){
		return $this->aportacions()->where('data_aportacio','>',$sinceDate)->where('estudiant_email','<>',Auth::user()->getEmail())->orderBy('data_aportacio','DESC')->get();
	}
	
	/**
	 * Numero d'aportacions d'un esdeveniment
	 */
	public function num_aportacions(){
		return $this->aportacions()->count();
	}
}
