<?php

/**
 * @author Eduardo
 */
class Grup extends Eloquent {
	
	protected $table = 'grup';	
	protected $primaryKey = 'id_grup';	
	public $timestamps = false;
	
	public function getNom(){
		return $this->nom_grup;
	}
	
	public function getID(){
		return $this->id_grup;
	}
	
	public function getDescripcio(){
		return $this->descripcio_grup;
	}
	
	public function getEmail(){
		return $this->estudiant_email_estudiant;
	}
	
	public function getSlug(){
		return $this->slug_grup;
	}
	
	public function getAula(){
		return $this->aula;
	}
	
	public function getActiu(){
		return $this->actiu_grup;
	}
	
	/**
	 * Defineix els estudiants que formen part d'un grup
	 */
	public function estudiants()
	{		
		return $this->belongsToMany('Estudiant', 'estudiant_grup', 'grup_id_grup', 'estudiant_email');
	}
	
	/**
	 * Relaciona un grup amb el seu moderador
	 */
	public function estudiant()
	{		
		return $this->belongsTo('Estudiant', 'estudiant_email_estudiant');
	}
	
	/**
	 * Defineix a quin centre pertany un grup
	 */
	public function centre()
	{		
		return $this->belongsTo('Centre', 'centre_nom_centre');
	}
	
	/**
	 * Defineix durant quin curs acadèmic es va crear el grup
	 */
	public function curs()
	{		
		return $this->belongsTo('Curs', 'curs_nom_curs');
	}
	
	/**
	 * Relaciona el grup amb les assignatures que té
	 */
	public function assignatures()
	{
 		return $this->hasMany('Assignatura', 'grup_id_grup');
	}
	
	/**
	 * Defineix els esdeveniments del grup a partir de la data actual i ordenats per proximitat
	 */
	public function esdeveniments(){
		$hoy = date("Y-m-d H:i:s");
		return $this->hasMany('Esdeveniment','grup_id_grup')
					->where('data_esdeveniment', '>', $hoy)
					->orderBy('data_esdeveniment');
	}
	
	public static function bySlug($slug){
		return self::where('slug_grup','=',$slug)->firstOrFail();
	}
	
	/*
	 * Realitza una consulta per nom i descrició dels grups
	 * @param string [$text] text introduit per l'estudiant	 * 
	 */
	public static function resultat($text){		     																				
	 return Grup::where('actiu_grup','=',1)->where(function($query) use ($text){			     	
		$query->where('nom_grup', 'like', '%'.$text.'%')->orwhere('descripcio_grup', 
																		'like', 
																		'%'.$text.'%');					
																		})->paginate(5);																				
	}

	/*
	 * Retorna el nombre de grups que coincideixen amb la cerca
	 * @param string [$text] text introduit per l'estudiant
	 */
	public static function num_resultat($text){		     
	    return Grup::resultat($text)->count();	
	}
	
	/**
	 * Relaciona el grup amb les sol·licituts per formar part d'ell
	 */
	public function solEstudiants(){
		return $this->belongsToMany('Estudiant', 
							     		'solicitud_estudiant_grup', 
							     		'grup_id_grup',							     									     		
							     		'estudiant_email');
	}
	
	/**
	 * Retorna el nombre de solicituds per entrar a un grup
	 */
	public function num_solicituds(){
		return Grup::solEstudiants()->count();
	}
}
