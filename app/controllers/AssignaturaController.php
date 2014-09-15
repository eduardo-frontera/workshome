<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class AssignaturaController extends BaseController {
	
	/**
	 * Editar l'aportació d'una assignatura.
	 * @param int [$id] identificador de l'aportació
	 */
	public function editarAportacio($id){
		try{
			try{
				$aportacio = Aportacio::findOrFail($id);
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'La aportación ha sido eliminada.'));
			}
			
			$text = Input::get('text');	
			$text = strip_tags($text);
			$text = trim ($text);
			
			$validator = Validator::make(
				array('comentari' => $text),
				array('comentari' => array('required','max:500'))
			);
			
			if ($validator->fails()){
				throw new ModelException("Debes escribir algo... (máx. 500 caracteres)");
			}
			
			$autor = $aportacio->autor;
			$assignatura = $aportacio->assignatura;
			$grup = $assignatura->grup;
			$grups = Auth::user()->grups()->lists('id_grup');
			
			if(in_array($grup->getID(), $grups)){
				if($autor->getEmail() == Auth::user()->getEmail()){
					if($grup->getActiu()){
						$aportacio->info_aportacio = $text;				
						$aportacio->save();
					}else{
						return Response::json(array('error' => 'El grupo ha sido eliminado por el moderador.'));
					}
				}else{
					return Response::json(array('error' => 'No eres el autor de la aportación.'));
				}
			}else{
				return Response::json(array('error' => 'No estás matriculado en este grupo.'));
			}
			
			return Response::json(array('status' => 'ok'));
			
		}catch(ModelException $exception){			
			return Response::json(array('error' => $exception->getMessage()));			
		}			
	}			
		
	/**
	 * Elimina una aportació feta a una assignatura
	 * @param int [$id] identificador de l'aportació
	 */
	public function eliminarAportacio($id){
		try{
			$aportacio = Aportacio::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Response::json(array('error' => 'La aportación ha sido eliminada.'));
		}
		
		$autor = $aportacio->autor;
		$assignatura = $aportacio->assignatura;
		$grup = $assignatura->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		if(in_array($grup->getID(), $grups)){
			if($autor->getEmail() == Auth::user()->getEmail()){
				if($grup->getActiu()){
					if($aportacio->num_comentaris() > 0){
						$comentaris = $aportacio->comentaris;
				
						foreach($comentaris as $comentari){
							$comentari->delete();								
						}
					}					
					$aportacio->delete();
				}else{
					return Response::json(array('error' => 'El grupo ha sido eliminado por el moderador.'));
				}
			}else{
				return Response::json(array('error' => 'No eres el autor de la aportación.'));
			}
		}else{
			return Response::json(array('error' => 'No estás matriculado en este grupo.'));
		}
		
		return Response::json(array('status' => 'ok'));
	}
	
	/**
	 * Mostra la pàgina d'una assignatura (les aportacions i els comentaris)
	 * @param string [$slug] slug assignatura
	 */
	public function mostrarAssignatura($slug){
		
		try{
			$assignatura = Assignatura::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$grup = $assignatura->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		/*Comprovació de que l'estudiant estigui matriculat
		 al grup d'aquesta assignatura i el grup estigui actiu*/
		if (in_array($grup->getID(), $grups) && $grup->getActiu()){
		
			$aportacions = $assignatura->getAportacions();
			
			$this->layout->title = 'Workshome - '.$assignatura->getNom();
		    $this->layout->description = 'Ver aportaciones y comentarios de una asignatura';
			$this->layout->content = View::make('assignatura/info', 
												array('aportacions' => $aportacions,
												'assignatura' => $assignatura)
												);	
		}else{
			return Redirect::route('grups.meus');	
		}	
	}

	/**
	 * S'encarrega de demanar al model les noves aportacions aparegudes 
	 * des de la darrera actualització
	 * @param integer [$idAssignatura] identificador de l'assignatura
	 */
	public function novesAportacions($idAssignatura){
		try{
			$assignatura = Assignatura::findOrFail($idAssignatura);
		}catch(ModelNotFoundException $e){
			//Si l'esdeveniment ja no existeix no mostram les següents aportacions.
			die();
		}
		$sinceDate = Input::get('since_date');
		$aportacions = $assignatura->getUltimesAportacions($sinceDate);
		return View::make('assignatura/aportacions', 
												array('aportacions' => $aportacions,
												'assignatura' => $assignatura)
												);	
	}

	/**
	 * Afegeix assignatures a un grup ja existent
	 * @param string [$slug] slug assignatura
	*/
	public function afegirAssignatures ($slug){
		try{
			$grup = Grup::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$moderador = $grup->estudiant;
		
		//Comprovar que sigui el moderador de l'assignatura i el grup estigui actiu
		if(Auth::user()->getEmail() == $moderador->getEmail() && $grup->getActiu()){
			
			if (Input::has('assignatura')){			
			$a_assignatures = array(); 
  			$assignatures = Input::get('assignatura');
			
			$a_noms = array();
			$noms = Input::get('nom');
			
			$a_cognoms = array();
			$cognoms = Input::get('cognoms');
  
			foreach($assignatures as $key=>$value){
			    $a_assignatures[]= $value;
			}
			
			foreach($noms as $key=>$value){
			    $a_noms[]= $value;
			}
			
			foreach($cognoms as $key=>$value){
			    $a_cognoms[]= $value;
			}
					
			for($i=0; $i<count($a_noms); $i++)  
			{
			   if($a_assignatures[$i] != "") { 
				   					   
				   $ass = new Assignatura();
				   
				   $nom_ass = $a_assignatures[$i]; 
				   $nom_ass = strip_tags($nom_ass);
				   $nom_ass = trim($nom_ass);
				   $ass->nom_assignatura = $nom_ass;
				   
				   $nom_prof_ass = $a_noms[$i]; 
				   $nom_prof_ass = strip_tags($nom_prof_ass);
				   $nom_prof_ass = trim($nom_prof_ass);
				   $ass->nom_professor = $nom_prof_ass;
				   
				   $cognoms_prof_ass = $a_cognoms[$i]; 
				   $cognoms_prof_ass = strip_tags($cognoms_prof_ass);
				   $cognoms_prof_ass = trim($cognoms_prof_ass);
				   $ass->cognoms_professor = $cognoms_prof_ass; 
				   
				   $ass->grup_id_grup = $grup->getID();
				   $ass->slug_assignatura = uniqid();
				   
				   $ass->save();
			   }
			   
			}
			return Redirect::route('grups.meus');			
			}			
		$this->layout->title = 'Workshome - Añadir asignaturas';
		$this->layout->description = 'Añadir asignaturas a tu grupo';
		$this->layout->content = View::make('assignatura/afegir');  
		}else{
			return Redirect::route('grups.meus');	
		}
	}

	/**
	 * Crea una nova aportació a una assignatura
	 * @param integer [$id] identificador de l'assignatura
	 */	
	public function crearAportacio($id){
		try{			
			try{	
				$assignatura = Assignatura::findOrFail($id);
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'Esta asignatura ha sido eliminada por el moderador del grupo'));
			}
			
			$grup = $assignatura->grup;
			$grups = Auth::user()->grups()->lists('id_grup');
			
			/*Comprovació de que l'estudiant estigui matriculat
			 al grup d'aquesta assignatura i el grup estigui actiu*/			
			
			 if(!in_array($grup->getID(), $grups)){
			 	if (Request::ajax()){
					return Response::json(array('error' => 'No estás matriculado en este grupo.'));
				} else{
					return Redirect::route('grups.meus');
				}
			 }
			 
			 if(!$grup->getActiu()){
			 	if (Request::ajax()){
					return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
				} else{
					return Redirect::route('grups.meus');
				}
			 }			
			
			$aport = Input::get('aportacio');
			$validator = Validator::make(
				array('Aportación' => $aport),
				array('Aportación' => array('required','max:500'))
			);
			
			if ($validator->fails()){
				throw new ModelException("Debes escribir algo... (máx. 500 caracteres)");
			}
			
			$aportacio = new Aportacio();
			
			//Elimina TAGs HTML
			$aport = strip_tags($aport);
			//Elimina els espais en blanc del començament i del final
			$aport = trim($aport);
							
			$aportacio->info_aportacio = $aport;
			
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			
			$aportacio->data_aportacio = $ara;
			$aportacio->id_assignatura = $assignatura->getID();
			$aportacio->estudiant_email = Auth::user()->email_estudiant;
				
			//si fitxer pujat correctament
			if (Input::hasFile('file'))
			{									
	    		$file = Input::file('file'); 	
				
				$validator2 = Validator::make(
					array('Fichero' => $file),
					//Mida màxima del fitxer (kilobytes = 25 MB)
					array('Fichero' => array('max:25600'))
				);
				
				if ($validator2->fails()){
					return Redirect::route('assignatura.info', 
					array('slug' => $assignatura->getSlug()))->withErrors($validator2);
				}
				
				//extensió del fitxer	
				$extension = $file->getClientOriginalExtension(); 
				
				//No es permeten pujar fitxers amb extensio exe
				if($extension == 'exe'){
					return Redirect::route('assignatura.info', array('slug' => $assignatura->getSlug()))->with('fitxer', 'error');
				}
				
				$aleatori = str_random(8);				
				$destinationPath = 'uploads/files/'.$aleatori;		
				
				$filename = $file->getClientOriginalName();
				
				//Lleva tags HTML
				$filename = strip_tags($filename);
				
				//Reemplaça caràcters amb accents per es seus homònims 
				//per evitar problemes demostrats amb rutes amb accents
				
				$filename = str_replace(array('á','à','â','ã','ª','ä'),'a',$filename);
				$filename = str_replace(array('Á','À','Â','Ã', 'Ä'),'A',$filename);
				$filename = str_replace(array('Í','Ì','Î','Ï'),'I',$filename);
				$filename = str_replace(array('í','ì','î','ï'),'i',$filename);
				$filename = str_replace(array('é','è','ê','ë'),'e',$filename);
				$filename = str_replace(array('É','È','Ê','Ë'),'E',$filename);
				$filename = str_replace(array('ó','ò','ô','õ','ö','º'),'o',$filename);
				$filename = str_replace(array('Ó','Ò','Ô','Õ','Ö'),'O',$filename);
				$filename = str_replace(array('ú','ù','û','ü'),'u',$filename);
				$filename = str_replace(array('Ú','Ù','Û','Ü'),'U',$filename);
				$filename = str_replace(array('[','^','´','`','¨','~',']'),'',$filename);
				$filename = str_replace('ç','c',$filename);
				$filename = str_replace('Ç','C',$filename);
				$filename = str_replace('ñ','n',$filename);
				$filename = str_replace('Ñ','N',$filename);
				$filename = str_replace('Ý','Y',$filename);
				$filename = str_replace('ý','y',$filename);
				
				//chmod('/laravel/public/uploads/files', '0777');			
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
				//$destinationPath = '/htdocs/'.$destinationPath;	
				if($uploadSuccess) {
					$aportacio->fitxer_aportacio = $destinationPath.'/'.$filename;
				}
				else{
					return Redirect::route('assignatura.info', array('slug' => $assignatura->getSlug()))->with('upload', 'error');
				}
			}	
						
			$aportacio->save();
			
			if (Request::ajax()){
				return View::make('assignatura/aportacio',array('aportacio' => $aportacio));
			} else{
				return Redirect::route('assignatura.info', array('slug' => $assignatura->getSlug()));
			}
		}catch(ModelException $exception){
			if (Request::ajax()){
				return Response::json(array('error' => $exception->getMessage()));
			}else{
				return Redirect::route('assignatura.info', 
					array('slug' => $assignatura->getSlug()))->withErrors($validator);
			}
		}			
	}

	/**
	 * Descarrega un fitxer associat a una aportació
	 * @param string [$id] numero de l'aportacio on es adjunt el fitxer
	 */
	public function download($id){
				
		try{
			$aportacio = Aportacio::find($id);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$assignatura = $aportacio->assignatura;
		$grup = $assignatura->grup;
		
		$grups = Auth::user()->grups()->lists('id_grup');
		
		/*Comprovació de que l'estudiant estigui matriculat
		 al grup d'aquesta assignatura i el grup estigui actiu*/
		if (in_array($grup->getID(), $grups) && $grup->getActiu()){
		
			$file = $aportacio->getFitxer();
			
			if (file_exists($file)) {
								
			    header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename='.basename($file));
			    header('Content-Transfer-Encoding: binary');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($file));
				
			    ob_clean();
			    flush();
				
			    readfile($file);
			    exit;				
			}else{
				return Redirect::route('assignatura.info',
										array('slug' => $assignatura->getSlug()))->with('descarrega', 
																						'error');
			}
		}else{
			return Redirect::route('grups.meus');	
		}				
	}
	
	/**
	 * Edita la informació d'una assignatura
	 * @param string [$slug] slug d'una assignautra
	 */
	public function editarAssignatura($slug){
		$emaillog = Auth::user()->email_estudiant;
		$estudiant = Estudiant::find($emaillog); 	
			
		$assignatura = Assignatura::bySlug($slug);
		
		$grup = $assignatura->grup;
		
		/*Comprovam que sigui el moderador del grup 
		  al qual pertany l'assignatura i el grup estigui actiu*/
		if($emaillog == $grup->getEmail() && $grup->getActiu()){
		
			if (Input::has('nom_ass')){
				$nom_ass = Input::get('nom_ass');
				$nom_ass = strip_tags($nom_ass);
				$nom_ass = trim($nom_ass);
				
				$nom_prof = Input::get('nom_prof');
				$nom_prof = strip_tags($nom_prof);
				$nom_prof = trim($nom_prof);
				
				$cognoms_prof = Input::get('cognoms_prof');
				$cognoms_prof = strip_tags($cognoms_prof);
				$cognoms_prof = trim($cognoms_prof);
				
			    $validator = Validator::make(
				array('nombre asignatura' => $nom_ass, 
					  'nombre profesor' => $nom_prof,
					  'apellidos profesor' => $cognoms_prof),
					  
				array('nombre asignatura' => array('required','max:45'), 
					  'nombre profesor' => array('max:45'),
					  'apellidos profesor' => array('max:150')
					  )				 
				);
					
				if ($validator->fails()){
					return Redirect::route('assignatura.editar', array('slug' => $slug))->withErrors($validator);
				}
				
				$assignatura->nom_assignatura = $nom_ass;
				$assignatura->nom_professor = $nom_prof;
				$assignatura->cognoms_professor = $cognoms_prof;
				
				$assignatura->save();
				
				return Redirect::route('assignatura.info', array('slug' => $slug));
			}

			$this->layout->title = 'Workshome - Editar '.$assignatura->getNom();
		    $this->layout->description = 'Editar la información de una asignatura';
			$this->layout->content = View::make('assignatura/editar', array('assignatura' => $assignatura));
		}else{
			return Redirect::route('grups.meus');
		}
	}
	
	/**
	 * Elimina una assignatura d'un grup i totes les seves aportacions
	 * i comentaris
	 * @param string [$slug] slug d'una assignatura
	 */
	public function eliminarAssignatura($slug){
		$emaillog = Auth::user()->email_estudiant;
		
		try{
			$estudiant = Estudiant::findOrFail($emaillog); 	
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}

		try{
			$assignatura = Assignatura::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$grup = $assignatura->grup;
		
		/*Comprovam que sigui el moderador del grup 
		  al qual pertany l'assignatura i el grup estigui actiu*/
		if($emaillog == $grup->getEmail() && $grup->getActiu()){
			
			if($assignatura->num_aportacions() > 0){
				$aportacions = $assignatura->aportacions;
				
				foreach($aportacions as $aportacio){
					
					if($aportacio->num_comentaris() > 0){
						$comentaris = $aportacio->comentaris;
						
						foreach($comentaris as $comentari){
							$comentari->delete();								
						}
					}
					$aportacio->delete();
				}
			}
			$assignatura->delete();
		}
		
		return Redirect::route('grups.meus');
	}			
}
?>