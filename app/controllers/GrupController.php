<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class GrupController extends BaseController {
		
	/**
	 * Mostra la informació d'un grup
	 * @param string [$slug] slug del grup
	 */
	public function consultaGrup($slug){
		try{
			$grup = Grup::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$this->layout->title = 'Workshome - '.$grup->getNom();
		$this->layout->description = 'Ver información de un grupo';
		$this->layout->content = View::make('grups/consulta', 
											array('grup' => $grup)
											);
	}
	
	/**
	 * Mostra les solicituds que té un estudiant per entrar en un grup que modera
	 * @param string [$slug] slug del grup
	 */
	public function solicituds($slug){
		try{
			$grup_sol = Grup::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$emaillog = Auth::user()->email_estudiant;
			
		if($emaillog != $grup_sol->getEmail() || !$grup_sol->getActiu()){
			return Redirect::route('grups.meus');
		}
		
		$estudiants_sol = $grup_sol->solEstudiants;
		$num_sol = $grup_sol->num_solicituds();
		
		$this->layout->title = 'Workshome - Solicitudes '.$grup_sol->getNom();
		$this->layout->description = 'Ver solicitudes de un grupo';
		$this->layout->content = View::make('grups/solicituds', array('estudiants_sol' => $estudiants_sol,
																	  'grup_sol' => $grup_sol,
																	  'num_sol' => $num_sol));
 	}
	
	/**
	 * Cancel·la la solicitud d'un estudiant per entrar a un grup
	 * @param integer [$id] identificador del grup
	 */
	public function canSolicitud($id){
		$emaillog = Auth::user()->email_estudiant;
		
		try{
			$estudiant = Estudiant::findOrFail($emaillog);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		try{
			$grup = Grup::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$estudiant->cancelSolicitudGrup($grup);
		$slug = $grup->getSlug();
		
		return Redirect::route('grup.consulta', array('slug' => $slug));
	}
	
	/**
	 * Edita la informació d'un grup
	 * @param string [$slug] slug del grup
	 */
	public function editarGrup($slug){
		
		try{		
			$grup = Grup::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$emaillog = Auth::user()->email_estudiant;
			
		if($emaillog == $grup->getEmail() && $grup->getActiu()){	
			$centres = Centre::all();
			
			if (Input::has('nom'))		   
			{		   
			   $nom = Input::get('nom');			   
			   $nom = strip_tags($nom);
			   $nom = trim($nom);
			   
			   $descripcio = Input::get('descripcio');
			   $descripcio = strip_tags($descripcio);
			   $descripcio = trim($descripcio);
			   
			   $aula = Input::get('aula');
			   $aula = strip_tags($aula);
			   $aula = trim($aula);
			   
			   $centre = Input::get('centre');
			  
			   $validator = Validator::make(
					array('nombre' => $nom,
					  'descripción' => $descripcio,
					  'aula' => $aula,
					  'centro' => $centre),
					array('nombre' => array('required','max:60'),
					  'descripción' => array('max:255'),
					  'aula' => array('max:25'),
					  'centro' => array('max:150'),
					  )
				);
			
			   if ($validator->fails()){
			   		return Redirect::route('grup.editar', 
							array('slug' => $grup->getSlug()))->withErrors($validator);
			   }

			   $grup->nom_grup = $nom;
			   $grup->descripcio_grup = $descripcio;
			   $grup->aula = $aula;
			   $grup->centre_nom_centre = $centre;
	
			   $grup->save();
			
				return Redirect::route('grups.meus');				
			}
			
			$this->layout->title = 'Workshome - Editar '.$grup->getNom();
		    $this->layout->description = 'Editar información de un grupo';
			$this->layout->content = View::make('grups/editar', 
												array('centres' => $centres, 
												'grup' => $grup));
		}else{
			return Redirect::route('grups.meus');	
		}
		
	}
	
	/**
	 * Desmatricula un alumne d'un grup
	 * @param integer [$id] identificador del grup
	 */
	public function sortirGrup($id){
		try{
			$grup = Grup::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$emaillog = Auth::user()->email_estudiant;
		
		try{
			$est = Estudiant::findOrFail($emaillog);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$est->desmatricularGrup($grup);
		return Redirect::route('grups.meus');	
	}
	
	/**
	 * Mostra els grups d'un estudiant
	 */
	public function mostrarGrupsMeus(){
		$emaillog = Auth::user()->email_estudiant;
		
		try{
			$estudiant = Estudiant::findOrFail($emaillog); 
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$grups = $estudiant->getGrups();	
	
		$this->layout->title = 'Workshome - Mis grupos';
		$this->layout->description = 'Ver la información de mis grupos';
		$this->layout->content = View::make('grups/consultes', array('grups' => $grups));
	}
	
	/**
	 * Crea un nou grup
	 */
	public function nouGrup(){
		
		if (Input::has('nom'))
		{			
		   $grup = new Grup();		   
			
		   $nom = Input::get('nom');
		   $nom = strip_tags($nom);
		   $nom = trim($nom);
		   
		   $descripcio = Input::get('descripcio');
		   $descripcio = strip_tags($descripcio);
		   $descripcio = trim($descripcio);
		   
		   $aula = Input::get('aula');
		   $aula = strip_tags($aula);
		   $aula = trim($aula);
		   
		   $centre = Input::get('centre');
		   
		   $validator = Validator::make(
				array('nombre' => $nom,
					  'descripción' => $descripcio,
					  'aula' => $aula,
					  'centro' => $centre),
				array('nombre' => array('required','max:60'),
					  'descripción' => array('max:255'),
					  'aula' => array('max:25'),
					  'centro' => array('max:150'),
					  )
			);
			
			if ($validator->fails()){
				return Redirect::route('grup.nou')->withErrors($validator);
			}
		   
		   $grup->nom_grup = $nom;
		   $grup->descripcio_grup = $descripcio;
		   $grup->aula = $aula;
		   $grup->centre_nom_centre = $centre;
		   $grup->curs_nom_curs = '2013-2014';
		   
		   $slug = $nom.uniqid();
		   $grup->slug_grup = $slug;
		   
		   $emaillog = Auth::user()->email_estudiant;
			 
		   $grup->estudiant_email_estudiant = $emaillog;
		   
		   $grup->save();
		   
		   try{
		   		$estudiant = Estudiant::findOrFail($emaillog); 
		   }catch(ModelNotFoundException $e){
				return Redirect::route('grups.meus');
		   }
		   
		   $estudiant->matricularEstudiant($slug);	
		   
		   return Redirect::route('assignatures.nou',array('slug' => $slug));
		} else {		
			$centres = Centre::all();
			
			$this->layout->title = 'Workshome - Nuevo grupo';
		    $this->layout->description = 'Crear nuevo grupo';
		    $this->layout->content = View::make('grups/nou', array('centres' => $centres));
		}
	}

	/**
	 * Dona de baixa un grup (no l'elimina de la BBDD)
	 * @param integer [$id] identificador del grup
	 */
	public function baixaGrup($id){
		
		try{
			$grup = Grup::findOrFail($id);
		}catch(ModelNotFoundException $e1){
			return Redirect::route('grups.meus');
		}
		
		$emaillog = Auth::user()->email_estudiant;
			
		if($emaillog == $grup->getEmail() && $grup->getActiu()){	
			$grup->actiu_grup = 0;
			date_default_timezone_set("Europe/Madrid");
		    $ara = date("Y-m-d H:i:s");
			$grup->data_eliminacio_grup = $ara;

			$grup->save();
		}
		
		return Redirect::route('grups.meus');
	}
	
	/**
	 * Crea una nova solicitud per a entrar a un grup 
	 * @param integer [$id] identificador del grup
	 */
	public function novaSolicitud($id){
			
		$emaillog = Auth::user()->email_estudiant;
		
		try{
			$estudiant = Estudiant::findOrFail($emaillog);
		}catch(ModelNotFoundException $e1){
			return Redirect::route('grups.meus');
		}
		
		try{
			$grup = Grup::findOrFail($id);
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
		
		$estudiant->solicitudGrup($grup);
		$slug = $grup->getSlug();
		
		return Redirect::route('grup.consulta', array('slug' => $slug));
	}
}
