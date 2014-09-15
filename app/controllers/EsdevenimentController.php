<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class EsdevenimentController extends BaseController {
	
	/**
	 * Editar l'aportació d'un esdeveniment.
	 * @param int [$id] identificador de l'aportació
	 */
	public function editarAportacioEsdeveniment($id){
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
			$esdeveniment = $aportacio->esdeveniment;
			$grup = $esdeveniment->grup;
			$grups = Auth::user()->grups()->lists('id_grup');
			
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
			
			if($autor->getEmail() == Auth::user()->getEmail()){
				if(in_array($grup->getID(), $grups)){				
					if($grup->getActiu()){
						if($esdeveniment_actiu){
							$aportacio->info_aportacio = $text;				
							$aportacio->save();
						}else{
							return Response::json(array('error' => 'El evento no está activo.'));
						}
					}else{
						return Response::json(array('error' => 'El grupo ha sido eliminado por el moderador.'));
					}			
				}else{
					return Response::json(array('error' => 'No estás matriculado en este grupo.'));
				}
			}else{
				return Response::json(array('error' => 'No eres el autor de esta aportación.'));
			}	
			
			return Response::json(array('status' => 'ok'));
		
		}catch(ModelException $exception){			
			return Response::json(array('error' => $exception->getMessage()));			
		}
	}
	
	/**
	 * Descarrega el fitxer d'una aportació d'un esdeveniment
	 * @param integer [$id] identificador de l'aportació
	 */
	public function descarregarFitxer($id){
		try{
			$aportacio = Aportacio::find($id);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$esdeveniment = $aportacio->esdeveniment;
		$grup = $esdeveniment->grup;	
		$grups = Auth::user()->grups()->lists('id_grup');
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
		
		/*Comprovació de que l'estudiant estigui matriculat
		 al grup d'aquesta assignatura i el grup estigui actiu*/
		if (in_array($grup->getID(), $grups) && $grup->getActiu() && $esdeveniment_actiu){
			
			$file = $aportacio->getFitxer();
			
			if (file_exists($file)){								
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
				return Redirect::route('esdeveniment.missatges',
										array('slug' => $esdeveniment->getSlug()))->with('descarrega', 'error');
			}
	 	}else{
			return Redirect::route('grups.meus');	
		}		
	}
	
	/**
	 * Retorna les següents aportacions a mostrar.
	 * @param integer [$id] identificador de l'esdeveniment
	 */
	public function novesAportacionsEsdeveniment($id){
		try{
			$esdeveniment = Esdeveniment::findOrFail($id);
		}catch(ModelNotFoundException $e){
			//Si l'esdeveniment ja no existeix no mostram les següents aportacions.
			die();
		}
		$sinceDate = Input::get('since_date');
		$aportacions = $esdeveniment->getUltimesAportacions($sinceDate);
		return View::make('esdeveniments/aportacions', 
												array('aportacions' => $aportacions,
												'esdeveniment' => $esdeveniment)
												);	
	}	
	
	/**
	 * Mostra els missatges d'un esdeveniment.
	 * @param string [$slug] slug de l'esdeveniment
	 */
	public function mostrarMissatges ($slug){
		try{
			$esdeveniment = Esdeveniment::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('esdeveniments.consulta');
		}
		
		$grup = $esdeveniment->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
		
		/*Comprovació de que l'estudiant estigui matriculat
		 al grup d'aquesta assignatura i el grup estigui actiu*/
		if (in_array($grup->getID(), $grups) && $grup->getActiu() && $esdeveniment_actiu){
		
			$aportacions = $esdeveniment->getAportacions();

			$this->layout->title = 'Workshome - Mensajes '.$esdeveniment->getNom();
		    $this->layout->description = 'Ver aportaciones y comentarios de un evento';
			$this->layout->content = View::make('esdeveniments/missatges', 
												array('aportacions' => $aportacions,
												'esdeveniment' => $esdeveniment)
												);	
		}else{
			return Redirect::route('esdeveniments.consulta');	
		}
	}	
    
	/**
	 * Elimina l'aportació d'un esdeveniment.
	 * @param int [$id] identificador de l'aportació
	 */
	public function eliminarAportacioEsdeveniment($id){
		try{
			$aportacio = Aportacio::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Response::json(array('error' => 'La aportación ya no existe o ha sido eliminada.'));
		}
		
		$autor = $aportacio->autor;
		$esdeveniment = $aportacio->esdeveniment;
		$grup = $esdeveniment->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
		
		if($autor->getEmail() == Auth::user()->getEmail()){
			if(in_array($grup->getID(), $grups)){				
				if($grup->getActiu()){
					if($esdeveniment_actiu){
						if($aportacio->num_comentaris() > 0){
							$comentaris = $aportacio->comentaris;
					
							foreach($comentaris as $comentari){
								$comentari->delete();								
							}
						}					
						$aportacio->delete();
					}else{
						return Response::json(array('error' => 'El evento no está activo.'));
					}
				}else{
					return Response::json(array('error' => 'El grupo ha sido eliminado por el moderador.'));
				}			
			}else{
				return Response::json(array('error' => 'No estás matriculado en este grupo.'));
			}
		}else{
			return Response::json(array('error' => 'No eres el autor de esta aportación.'));
		}	
		
		return Response::json(array('status' => 'ok'));
	}	
	
	/**
	 * Crea una aportació a un esdeveniment.
	 * @param int [$id] identificador de l'esdeveniment
	 */
	public function crearAportacioEsdeveniment($id){
		try{			
			try{	
				$esdeveniment = Esdeveniment::findOrFail($id);
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'Este evento ha sido eliminado por su creador'));
			}
			
			$grup = $esdeveniment->grup;
			$grups = Auth::user()->grups()->lists('id_grup');
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
			
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
			 
			 if(!$esdeveniment_actiu){
			 	if (Request::ajax()){
					return Response::json(array('error' => 'El evento no está activo.'));
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
			$aportacio->id_esdeveniment = $esdeveniment->getID();
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
					return Redirect::route('esdeveniment.missatges',array('slug' => $esdeveniment->getSlug()))->withErrors($validator2);
				}
				
				//extensió del fitxer	
				$extension = $file->getClientOriginalExtension(); 
				
				//No es permeten pujar fitxers amb extensio exe
				if($extension == 'exe'){
					return Redirect::route('esdeveniment.missatges',array('slug' => $esdeveniment->getSlug()))->with('fitxer', 'error');
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
				
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
				
				if($uploadSuccess) {
					$aportacio->fitxer_aportacio = $destinationPath.'/'.$filename;
				}
				else{
					return Redirect::route('esdeveniment.missatges',array('slug' => $esdeveniment->getSlug()))->with('upload', 'error');
				}
			}	
						
			$aportacio->save();
			
			if (Request::ajax()){
				return View::make('esdeveniments/aportacio', array('aportacio' => $aportacio));
			} else{
				return Redirect::route('esdeveniment.missatges', array('slug' => $esdeveniment->getSlug()));
			}
		}catch(ModelException $exception){
			if (Request::ajax()){
				return Response::json(array('error' => $exception->getMessage()));
			}else{
				return Redirect::route('esdeveniment.missatges', 
					array('slug' => $esdeveniment->getSlug()))->withErrors($validator);
			}
		}
	}	
		
	/**
	 * Crea un nou esdeveniment
	 */
	public function nouEsdeveniment(){		
		$num_grups = Auth::user()->num_grups();
		
		if($num_grups > 0){
			$emaillog = Auth::user()->email_estudiant;
			$estudiant = Estudiant::findOrFail($emaillog); 				
                    
			if (Input::has('nom'))
			{
		   		$nom = Input::get('nom');
				$nom = strip_tags($nom);
				$nom = trim($nom);
				
			    $descripcio = Input::get('descripcio');
				$descripcio = strip_tags($descripcio);
				$descripcio = trim($descripcio);
				
				$data = Input::get('data');
				
				$hora = Input::get('hora');
				$minut = Input::get('minut');
				
				$id_grup = Input::get('grup');
				$id_grup = strip_tags($id_grup);
				
				$validator = Validator::make(
					array('nombre' => $nom, 
						  'fecha' => $data,
						  'hora' => $hora,
						  'minuto' => $minut,
						  'descripción' => $descripcio,
						  'grupo' => $id_grup),
						  
						  
					array('nombre' => array('required','max:60'), 
						  'fecha' => array('required','date_format:"d/m/Y"'),
						  'hora' => array('required', 'numeric', 'between:0,23'),
						  'minuto' => array('required', 'numeric', 'between:0,55'),
						  'descripción' => array('max:255'),
						  'grupo' => array('required')
						  )				 
				);
				
				if ($validator->fails()){
					return Redirect::route('esdeveniment.nou')->withErrors($validator);
				}
				
				$dia = substr($data,0,2);
				$mes = substr($data,3,2);
				$any = substr($data,6,8);
				
				/*Afegim un zero davant per	complir amb 
				  l'especificaió del tipus DATE*/
				if($hora<10){
					$hora2 = '0'.$hora;
				}else{
					$hora2 = $hora;
				}

				$data_final=$any.'-'.$mes.'-'.$dia.' '.$hora2.':'.$minut.':'.'00';
				
				$esdeveniment = new Esdeveniment();
				$esdeveniment->nom_esdeveniment = $nom;
				$esdeveniment->descripcio_esdeveniment = $descripcio;
				$esdeveniment->estudiant_email = $emaillog;
				$esdeveniment->grup_id_grup = $id_grup;
				$esdeveniment->slug_esdeveniment = $nom.uniqid();
				$esdeveniment->data_esdeveniment = $data_final;
				
				try{
					$grup = Grup::findOrFail($id_grup); 
				}catch(ModelNotFoundException $e){
					return Redirect::route('esdeveniments.consulta');
				}	
				
				$grups = Auth::user()->grups()->lists('id_grup');
		
				/*Comprovació de que l'estudiant estigui matriculat
				 al grup i estigui actiu*/
				if (in_array($grup->getID(), $grups) && $grup->getActiu()){
					$esdeveniment->save();
				}else{
					return Redirect::route('esdeveniments.consulta');
				}
		  
		   	return Redirect::route('esdeveniments.consulta');
		 
			}else {
				$grups = $estudiant->grups;	
				
				$this->layout->title = 'Workshome - Nuevo evento';
		        $this->layout->description = 'Crear un nuevo evento';
				$this->layout->content = View::make('esdeveniments/nou', array('grups' => $grups));
			}
		}else{
			return Redirect::route('esdeveniments.consulta');
		}
	}
	
	/**
	 * Edita un esdeveniment donat
	 * @param string [$slug] slug de l'esdeveniment
	 */
	public function editarEsdeveniment($slug){
		$emaillog = Auth::user()->email_estudiant;
		$estudiant = Estudiant::find($emaillog); 
		$grups = $estudiant->grups;	
		
		try{
			$esdeveniment = Esdeveniment::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('esdeveniments.consulta');
		}
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		
		if($esdeveniment->getData() < $ara){
			return Redirect::route('grups.meus');
		}
		
		$autor_esd = $esdeveniment->autor;			
		$grup = $esdeveniment->grup;
		
		if($emaillog == $autor_esd->getEmail() && $grup->getActiu()){						
			if (Input::has('nom'))
			{						
			   		$nom = Input::get('nom');
					$nom = strip_tags($nom);
					$nom = trim($nom);
					
				    $descripcio = Input::get('descripcio');
					$descripcio = strip_tags($descripcio);
					$descripcio = trim($descripcio);
					
					$data = Input::get('data');	
					
					$hora = Input::get('hora');
					$minut = Input::get('minut');
					
					$id_grup = Input::get('grup');
					$id_grup = strip_tags($id_grup);
					
					$validator = Validator::make(
					array('nombre' => $nom, 
						  'fecha' => $data,
						  'hora' => $hora,
						  'minuto' => $minut,
						  'descripción' => $descripcio,
						  'grupo' => $id_grup),
						  
						  
					array('nombre' => array('required','max:60'), 
						  'fecha' => array('required','date_format:"d/m/Y"'),
						  'hora' => array('required', 'numeric', 'between:0,23'),
						  'minuto' => array('required', 'numeric', 'between:0,55'),
						  'descripción' => array('max:255'),
						  'grupo' => array('required')
						  )					 
					);
					
					if ($validator->fails()){
						return Redirect::route('esdeveniment.editar',
												array('slug' => $slug))->withErrors($validator);
					}
					
					$dia = substr($data,0,2);
					$mes = substr($data,3,2);
					$any = substr($data,6,8);						
				
					/*Afegim un zero davant per	complir amb 
					  l'especificaió del tipus DATE*/
					if($hora<10){
						$hora2 = '0'.$hora;
					}else{
						$hora2 = $hora;
					}
				
					$data_final=$any.'-'.$mes.'-'.$dia.' '.$hora2.':'.$minut.':'.'00';
					$esdeveniment->nom_esdeveniment = $nom;
					$esdeveniment->descripcio_esdeveniment = $descripcio;
					$esdeveniment->estudiant_email = $emaillog;
					$esdeveniment->grup_id_grup = $id_grup;
					
					$esdeveniment->data_esdeveniment = $data_final;
					
					try{
						$grup = Grup::findOrFail($id_grup); 
					}catch(ModelNotFoundException $e){
						return Redirect::route('esdeveniments.consulta');
					}	
					
					$grups = Auth::user()->grups()->lists('id_grup');
		
					/*Comprovació de que l'estudiant estigui matriculat
					 al grup i estigui actiu*/
					if (in_array($grup->getID(), $grups) && $grup->getActiu()){
						$esdeveniment->save();
					}else{
						return Redirect::route('esdeveniments.consulta');
					}
					  
			   		return Redirect::route('esdeveniments.consulta');					 
				
			}else {
				$this->layout->title = 'Workshome - Editar '.$esdeveniment->getNom();
		        $this->layout->description = 'Editar la información de un evento';				
				$this->layout->content = View::make('esdeveniments/editar', 
											array('grups' => $grups,
												  'esdeveniment' => $esdeveniment));
				
				}
		}else{
			return Redirect::route('esdeveniments.consulta');
		}
	}
	
	/**
	 * Mostra els esdeveniments d'un estudiant
	 */
	public function consultaEsdeveniments(){			
		$esdeveniments = Auth::user()->getEsdeveniments();
		
		$this->layout->title = 'Workshome - Mis eventos';
		$this->layout->description = 'Ver la información de mis eventos';
		$this->layout->content = View::make('esdeveniments/consultes', 
								array('esdeveniments' => $esdeveniments));
	}
	
	/**
	 * Mostra la informació d'un esdeveniment
	 * @param string [$slug] slug d'un esdeveniment
	 */
	public function consultarEsdeveniment($slug){
		try{
			$esdeveniment = Esdeveniment::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('esdeveniments.consulta');
		}
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		
		if($esdeveniment->getData() < $ara){
			return Redirect::route('grups.meus');
		}
		
		$grup = $esdeveniment->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		/*Comprovació de que l'estudiant estigui matriculat
		 al grup d'aquesta assignatura*/
		if (in_array($grup->getID(), $grups) && $grup->getActiu()){
		
		$this->layout->title = 'Workshome - '.$esdeveniment->getNom();
		$this->layout->description = 'Consultar la información de un evento';
		$this->layout->content = View::make('esdeveniments/consulta', 
								array('esdeveniment' => $esdeveniment));
		}else{
			return Redirect::route('esdeveniments.consulta');
		}
	}
	
	/**
	 * Eliminació d'un esdeveniment 
	 * @param integer [$id] identificador de l'esdeveniment
	 */
	public function eliminarEsdeveniment($id){	
		try{
			$esdeveniment = Esdeveniment::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Redirect::route('esdeveniments.consulta');
		}
		
		$emaillog = Auth::user()->email_estudiant;
		$autor_esd = $esdeveniment->autor;
		$grup = $esdeveniment->grup;
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
		
		if($emaillog == $autor_esd->getEmail() && $grup->getActiu() && $esdeveniment_actiu){
			if($esdeveniment->num_aportacions() > 0){
				$aportacions = $esdeveniment->aportacions;
				
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
			$esdeveniment->delete();
		}
		return Redirect::route('esdeveniments.consulta');
	}
}
?>