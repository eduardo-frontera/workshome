<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * @author Eduardo
 */
class Estudiant extends Eloquent implements UserInterface, RemindableInterface{
	
	protected $table = 'estudiant';	
	protected $primaryKey = 'email_estudiant';	
	public $timestamps = false;
	
	public function getNom(){
		return $this->nom_estudiant;
	}
	
	public function getCognoms(){
		return $this->cognoms_estudiant; 
	}
	
	public function getData(){
		return $this->data_naixement_estudiant;
	}
	
	public function getEmail(){
		return $this->email_estudiant;
	}
	
	public function getReminderEmail(){
		return $this->email_estudiant;
	}
	
	public function getIngres(){
		return $this->data_ingres_estudiant;
	}
	
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}
	
	public function getSlug(){
		return $this->slug_estudiant;
	}
	
	public function getAuthPassword()
	{
		return $this->contrasenya_estudiant;
	}
	
	public static function bySlug($slug){
		return self::where('slug_estudiant','=',$slug)->firstOrFail();
	}
	
	/**
	 * Definició de la relació d'un usuari amb els seus grups
	 */
	public function grups()
	{
 		return $this->belongsToMany('Grup', 
						     		'estudiant_grup', 
						     		'estudiant_email', 
						     		'grup_id_grup')->where('actiu_grup', 
														     		'=', 
														     		1);
	}
	
	/**
	 * Nombre de grups als quals està matriculat un estudiant
	 */
	public function num_grups()
	{
 		return $this->grups()->count();
	}
	
	/**
	 * Obtenció dels grups de l'usuari paginats
	 */
	public function getGrups(){
		return $this->grups()->paginate(4);
	}
	
	/**
	 * Definició de la relació d'un estudiant amb les seves sol·licituds per a entrar a un grup
	 */
	public function grupos_sol()
	{
 		return $this->belongsToMany('Grup', 
						     		'solicitud_estudiant_grup', 
						     		'estudiant_email', 
						     		'grup_id_grup')->where('actiu_grup', 
							     		'=', 
							     		1);
	}
	
	/**
	 * Matricula un estudiant a un grup
	 * @param Grup [$grup] grup on matricular
	 */
	public function matricularGrup(Grup $grup){
		$this->grups()->attach($grup->getID());
	}
	
	/**
	 * Desmatricula un estudiant d'un grup
	 * @param Grup [$grup] grup on desmatricular
	 */
	public function desmatricularGrup(Grup $grup){
		$this->grups()->detach($grup->getID());
	}
	
	/**
	 * Solicita la matriculació d'un estudiant a un grup
	 * @param Grup [$grup] grup on solicitar la matriculació
	 */
	public function solicitudGrup(Grup $grup){
		$this->grupos_sol()->attach($grup->getID());
	}
	
	/**
	 * Cancela la solicitud de matriculació d'un estudiant a un grup
	 * @param Grup [$grup] grup on cancelar la matriculació
	 */
	public function cancelSolicitudGrup(Grup $grup){
		$this->grupos_sol()->detach($grup->getID());
	}
	
	public function matricularEstudiant($slug){
		$gr = Grup::bySlug($slug);
		$this->matricularGrup($gr);
	}
	
	/**
	 * Realitza una cerca d'estudiants actius per nom i cognoms
	 * @param string [$text] text introduit per l'estudiant
	 */
	public static function resultat($text){		     
	    return Estudiant::where('actiu_estudiant','=',1)->where(function($query) use ($text){
		     	
				$query->where('nom_estudiant', 'like', '%'.$text.'%')->orwhere('cognoms_estudiant', 
				'like', 
				'%'.$text.'%');
				
	    		})->paginate(5);
	}
	
	/**
	 * Retorna el nombre d'estudiants que coincideixen amb la cerca
	 * @param string [$text] text introduit per l'estudiant
	 */
	public static function num_resultat($text){		    
	 return Estudiant::resultat($text)->count();   
	}
	
	/**
	 * Cerca dels esdeveniments d'un estudiant ordenats per data de proximitat (Utilitza Model Esdeveniment)
	 */
	public function getEsdeveniments(){
		return Esdeveniment::byEstudiant($this->getEmail());
	}
	
	/**
	 * Retorna el nombre d'esdeveniments d'un estudiant (Utilitza Model Esdeveniment)
	 */
	public function num_esdeveniments(){
		return Esdeveniment::num_byEstudiant($this->getEmail());
	}
		
	public function getDescripcio(){
		return $this->descripcio_estudiant;
	}
	
	public function getAficions(){
		return $this->aficions_estudiant;
	}
	
	public function getFoto(){
		return $this->foto_estudiant;
	}
}

?>