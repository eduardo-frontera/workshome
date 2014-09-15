<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class EstudiantController extends BaseController{
		
	/**
	 * Funció que retorna vertader si l'any és bixest i fals en cas contrari
	 * @param string [$any] any que volem saber si és bixest
	 * @return boolean vertader si l'any és bixest i fals en cas contrari
	 */	
	public function esBixest($any){
		if(((($any % 4) == 0) && (($any % 100) != 0)) 
			|| (($any % 400) == 0)){
				return true;
		}
		return false;
	}	
		
	/**
	 * Afegeix informació adicional de l'estudiant al seu perfil
	 */		
	public function actualitzarMesInfo(){
		$emaillog = Auth::user()->email_estudiant;
		try{
	    	$estudiant = Estudiant::findOrFail($emaillog); 
		}catch(ModelNotFoundException $e2){			
			return Redirect::route('grups.meus');
		}
								
		$descripcio = Input::get('descripcio');
		$descripcio = strip_tags($descripcio);
		$descripcio = trim($descripcio);
	
		$aficions = Input::get('aficions');
		$aficions = strip_tags($aficions);
		$aficions = trim($aficions);
		
		$validator = Validator::make(
			array('Descripción' => $descripcio, 
				  'Aficiones' => $aficions),
				  
			array('Descripción' => array('max:500'), 
				  'Aficiones' => array('max:500')
				  )				 
		);
			
		if ($validator->fails()){
			return Redirect::route('estudiant.info')->withErrors($validator);
		}
		
		$estudiant->descripcio_estudiant = $descripcio;
		$estudiant->aficions_estudiant = $aficions;
		$estudiant->save();
		
		return Redirect::route('estudiant.info')->with('actualitzat', 'ok');			
	}
	
	/**
	 * Permet afegir informació adicional a un estudiant
	 * dels seus gusts i aficions
	 */	 
	public function mesInformacio(){		
		$emaillog = Auth::user()->email_estudiant;
		
		try{
	    	$estudiant = Estudiant::findOrFail($emaillog); 
		}catch(ModelNotFoundException $e2){			
			return Redirect::route('grups.meus');
		}
		
		$this->layout->title = 'Workshome - Información adicional';
		$this->layout->description = 'Información adicional de tu perfil';
		$this->layout->content = View::make('estudiants/mesInformacio', array('estudiant' => $estudiant));
	}
	
	/**
	 * Mostra el perfil d'un estudiant
	 * @param string [$slug] slug de l'estudiant
	 */
	public function mostrarEstudiant($slug){
		try{			
			$estudiant = Estudiant::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$grups_usu = $estudiant->grups;
		
		$this->layout->title = 'Workshome - '.$estudiant->getNom().' '.$estudiant->getCognoms();
		$this->layout->description = 'Información del perfil de un estudiante';
		$this->layout->content = View::make('estudiants/consulta', array('estudiant' => $estudiant, 'grups_usu' => $grups_usu));
	}
	
	/**
	 * Matricula un estudiant a un grup
	 * @param string [$slug] slug de l'estudiant
	 * @param integer [$grup] identificador del grup
	 */
	public function matricularEstudiant($slug, $grup){
		try{
			$est = Estudiant::bySlug($slug);
		}catch(ModelNotFoundException $e1){
			return Redirect::route('grups.meus');
		}
		
		try{
			$gr = Grup::findOrFail($grup);
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
		$est->matricularGrup($gr);
		return Redirect::route('estudiant.matricules', array('slug' => $slug));
	}
	
	/**
	 * Desmatricula un estudiant d'un grup
	 * @param string [$slug] slug de l'estudiant
	 * @param integer [$grup] identificador del grup
	 */
	public function desmatricularEstudiant($slug, $grup){
		try{
			$est = Estudiant::bySlug($slug);
		}catch(ModelNotFoundException $e1){
			return Redirect::route('grups.meus');
		}
		
		try{
			$gr = Grup::findOrFail($grup);
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
		$est->desmatricularGrup($gr);
		return Redirect::route('estudiant.matricules', array('slug' => $slug));
	}
	
	/**
	 * Permet matricular o desmatricular un estudiant dels grups que ell modera
	 * @param string [$slug] slug de l'estudiant
	 */
	public function matricules($slug){
		try{
			$estudiant = Estudiant::bySlug($slug);
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		$grups_usu = $estudiant->grups;
		
		$this->layout->title = 'Workshome - Matriculaciones de '.$estudiant->getNom().' '.$estudiant->getCognoms();
		$this->layout->description = 'Cambiar matriculaciones de un estudiante';
		$this->layout->content = View::make('estudiants/matriculacions', array('estudiant' => $estudiant, 'grups_usu' => $grups_usu));
	}
	
	/**
	 * Solicitud d'un estudiant per formar part d'un grup
	 * @param string [$slug] slug de l'estudiant
	 * @param integer [$grup] identificador del grup
	 */
	public function solMatriculaEstudiant($slug, $grup){
		try{
			$est = Estudiant::bySlug($slug);
		}catch(ModelNotFoundException $e1){
			return Redirect::route('grups.meus');
		}
		
		try{
			$gr = Grup::findOrFail($grup);
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
		
		$est->matricularGrup($gr);
		$est->cancelSolicitudGrup($gr);	
			
		return Redirect::route('estudiant.solicituds', array('slug' => $gr->getSlug()));	
	}
	
	/**
	 * Cancelació d'una solicitud de matriculació a un grup
	 * @param string [$slug] slug de l'estudiant
	 * @param integer [$grup] identificador del grup
	 */
	public function cansolMatriculaEstudiant($slug, $grup){
		try{
			$est = Estudiant::bySlug($slug);
		}catch(ModelNotFoundException $e1){
			return Redirect::route('grups.meus');
		}
		
		try{
			$gr = Grup::find($grup);
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
		
		$est->cancelSolicitudGrup($gr);	
			
		return Redirect::route('estudiant.solicituds', array('slug' => $gr->getSlug()));
	}
		
	/**
	 * Edita la informació de l'estudiant
	 */
	public function editar(){
		$emaillog = Auth::user()->email_estudiant;
		
		try{
	    	$estudiant = Estudiant::findOrFail($emaillog); 
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
			
		if (Input::has('nom'))
		{
		   $nom = Input::get('nom');
		   $nom = strip_tags($nom);
		   $nom = trim($nom);
		   
		   $cognoms = Input::get('cognoms');
		   $cognoms = strip_tags($cognoms);
		   $cognoms = trim($cognoms);
		   
		   $any = Input::get('any');
		   $any = strip_tags($any);
		   $any = trim($any);
		  
		   $mes = Input::get('mes');
		   $mes = strip_tags($mes);
		   $mes = trim($mes);
		  
		   $dia = Input::get('dia');
		   $dia = strip_tags($dia);
		   $dia = trim($dia);
		   
		   $validator = Validator::make(
				array('nombre' => $nom, 
					  'apellidos' => $cognoms,
					  'día' => $dia,
					  'mes' => $mes,
					  'año' => $any),
					  
				array('nombre' => array('required','max:45'), 
					  'apellidos' => array('required','max:150'),
					  'día' => array('required', 'numeric', 'between:1,31'),
					  'mes' => array('required', 'numeric', 'between:1,12'),
					  'año' => array('required', 'numeric', 'between:1910,2000')
					  )				 
			);
				
			if ($validator->fails()){
				return Redirect::route('estudiant.editar')->withErrors($validator);
			}
			
			//Comprovació de combinacions de dia-mes-any
			
			if(($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) && $dia == 31){
				return Redirect::route('estudiant.editar')->with('combIncorrecte', 'error');
			}
			
			if($mes == 2){				
				if($this->esBixest($any)){
					if($dia > 29){
						return Redirect::route('estudiant.editar')->with('combIncorrecte', 'error');
					}
				}else{
					if($dia > 28){
						return Redirect::route('estudiant.editar')->with('combIncorrecte', 'error');
					}
				}				
			}
		   		   
		   $estudiant->nom_estudiant = $nom;			  
		   $estudiant->cognoms_estudiant = $cognoms;
		   $data = $any.'-'.$mes.'-'.$dia;
		   $estudiant->data_naixement_estudiant = $data;
		   $estudiant->save();	
		   		   
		   return Redirect::route('estudiant.editar')->with('actualitzat', 'ok');
		} else {
		   $this->layout->title = 'Workshome - Editar '.$estudiant->getNom().' '.$estudiant->getCognoms();
		   $this->layout->description = 'Editar la información de perfil';
		   $this->layout->content = View::make('estudiants/editar', array('estudiant' => $estudiant));
		}
	}

	/**
	 * Permet editar la imatge de perfil d'un estudiant
	 */
	public function editarImatgePerfil(){
		$emaillog = Auth::user()->email_estudiant;
		
		try{
	    	$estudiant = Estudiant::findOrFail($emaillog); 
		}catch(ModelNotFoundException $e2){
			return Redirect::route('grups.meus');
		}
		
		//si fitxer pujat correctament
		if (Input::hasFile('imatge'))
		{									
    		$file = Input::file('imatge'); 	
			
			$validator2 = Validator::make(
			array('Imagen Perfil' => $file),
			//Mida màxima del fitxer (kilobytes = 25 MB)
			//Només accepta imatges de les extensions: jpg, jpeg, png i gif.
			array('Imagen Perfil' => array('max:25600','mimes:jpeg,jpg,png,gif'))
			);
			
			if ($validator2->fails()){
				return Redirect::route('estudiant.editar')->withErrors($validator2);
			}
			
			//extensió del fitxer	
			$extensio = $file->getClientOriginalExtension(); 
			
			//No es permeten pujar fitxers amb extensio exe
			if($extensio == 'exe'){
				return Redirect::route('estudiant.editar')->with('fitxer', 'error');
			}
			
			$aleatori = str_random(8);
			//Afegim la seva imatge de perfil a una 
			//carpeta amb nom = l'slug de l'estudiant				
			$destinationPath = 'uploads/profiles/'.$estudiant->getSlug();				
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
			
									
			$uploadSuccess = Input::file('imatge')->move($destinationPath, $filename);
					
			if($uploadSuccess) {
				$estudiant->foto_estudiant = $destinationPath.'/'.$filename;
				$estudiant->save();
				return Redirect::route('estudiant.editar');
			}
			else{
				return Redirect::route('estudiant.editar')->with('upload', 'error');
			}
		}
	}
	
	/**
	 * Dona d'alta un nou estudiant
	 */
	public function nouEstudiant(){
		$this->layout = View::make('layouts.master2');
		
		if (Input::has('nom'))
		{
		   $estudiant = new Estudiant();
		   
		   $nom = Input::get('nom');
		   $nom = strip_tags($nom);
		   $nom = trim($nom);
			
		   $cognoms = Input::get('cognoms');
		   $cognoms = strip_tags($cognoms);
		   $cognoms = trim($cognoms);
		   
		   $email = Input::get('email');
		   $email = strip_tags($email);
		   $email = trim($email);
		   
		   $emailrep = Input::get('emailrep');
		   $emailrep = strip_tags($emailrep);
		   $emailrep = trim($emailrep);
		   
		   $contrasenya = Input::get('contrasenya');
		   $contrasenya = strip_tags($contrasenya);
		   $contrasenya = trim($contrasenya);
		  
		   $any = Input::get('any');
		   $any = strip_tags($any);
		   $any = trim($any);
		  
		   $mes = Input::get('mes');
		   $mes = strip_tags($mes);
		   $mes = trim($mes);
		  
		   $dia = Input::get('dia');
		   $dia = strip_tags($dia);
		   $dia = trim($dia);
		   
		  $validator = Validator::make(
		    array('email' => $email,
		 		  'email confirmación' => $emailrep,
		    	  'nombre' => $nom,
		    	  'apellidos' => $cognoms,
		    	  'contraseña' => $contrasenya,
		    	  'día' => $dia,
				  'mes' => $mes,
				  'año' => $any
				 ),
		    array('email' => array('required', 'email', 'max:60'),
		 		  'email confirmación' => array('required', 'email', 'max:60'),
				  'nombre' => array('required', 'max:45'),
				  'apellidos' => array('required', 'max:150'),
				  //El maxim real del camp contrasenya és 60 però es posa 20 perquè
				  //s'ha d'aplicar l'algorisme d'encriptat
				  'contraseña' => array('required', 'min:6', 'max:20'),
				  'día' => array('required', 'numeric', 'between:1,31'),
				  'mes' => array('required', 'numeric', 'between:1,12'),
				  'año' => array('required', 'numeric', 'between:1910,2000')
				)
		   );
		   
		   if ($validator->fails())
			{
			   return Redirect::route('estudiant.nou')->withErrors($validator);;	
			}
			
			$validator2 = Validator::make(
			    array('email' => $email),
					 
			    array('email' => array('unique:estudiant,email_estudiant'))
		   );
		   
		   if ($validator2->fails())
			{
			   return Redirect::route('estudiant.nou')->with('emailExistent', 'error');	
			}
		   
		    /*Si no coincideixen els dos camps email, mostram l'error*/		   
			if (strcmp($email, $emailrep) != 0){		   		
		   		return Redirect::route('estudiant.nou')->with('emails', 'error');
		   }		
			
			if(($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) && $dia == 31){
				return Redirect::route('estudiant.nou')->with('combIncorrecte', 'error');
			}
			
			if($mes == 2){				
				if($this->esBixest($any)){
					if($dia > 29){
						return Redirect::route('estudiant.nou')->with('combIncorrecte', 'error');
					}
				}else{
					if($dia > 28){
						return Redirect::route('estudiant.nou')->with('combIncorrecte', 'error');
					}
				}				
			}
			
		   $estudiant->nom_estudiant = $nom;
		   $estudiant->cognoms_estudiant = $cognoms;
		   $estudiant->email_estudiant = $email;
		   $estudiant->slug_estudiant = $nom.uniqid();
		  
		   date_default_timezone_set("Europe/Madrid");
		   $ara = date("Y-m-d H:i:s");
		   $estudiant->data_ingres_estudiant = $ara;
		   
		   $password = Hash::make($contrasenya);			  
		   $estudiant ->contrasenya_estudiant = $password;
		   		   
		   $data_naixement = $any.'-'.$mes.'-'.$dia;
		   $estudiant->data_naixement_estudiant = $data_naixement;
		   
		   $estudiant->save();
		   
		   /**************************Email de benvinguda******************************************/
		   
		   //A qui s'envia el correu
			$user = array(
			    'email'=> $email,
			    'name'=> $nom
			);
		 
			//Informació que s'envia a la vista
			$data = array(
			    'detail'=>'',
			    'name'  => $user['name']
			);
		 
			Mail::send('emails.benvinguda', $data, function($message) use ($user)
			{
			  $message->to($user['email'], $user['name'])->subject('¡Bienvenido!');
			});	
		   
		   /********************************************************************/
		   
		   if(Auth::attempt(array('email_estudiant' => $email, 'password' => $contrasenya))){
		   		return Redirect::route('grups.meus');
		   }
		} else {
			$this->layout->title = 'Workshome - Nuevo registro';
			$this->layout->description = 'Registro en Workshome';					
			$this->layout->content = View::make('estudiants/nou');
		}
	}
		
	/**
	 * Dona de baixa l'estudiant, però no l'elimina de la BBDD
	 */
	public function baixaEstudiant(){
		$emaillog = Auth::user()->email_estudiant;
		
		try{
			$estudiant = Estudiant::findOrFail($emaillog); 
		}catch(ModelNotFoundException $e){
			return Redirect::route('grups.meus');
		}
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		$estudiant->data_baixa_estudiant = $ara;
		$estudiant->actiu_estudiant = 0;
		$estudiant->save();
		
		Auth::logout();
		return Redirect::route('index');
	} 
	
	/**
	 * Permet a l'estudiant canviar la seva contrasenya
	 */
	public function canviarContrasenya(){
		if (Input::has('antigaContrasenya'))
		{
			$contAntiga = Input::get('antigaContrasenya');
			$contAntiga = strip_tags($contAntiga);
			$contAntiga = trim($contAntiga);
				
			$contNova = Input::get('novaContrasenya');
			$contNova = strip_tags($contNova);
			$contNova = trim($contNova);
			
			$email = Auth::user()->email_estudiant;
				
			$validator = Validator::make(
				array('Contraseña actual' => $contAntiga, 
					  'Contraseña nueva' => $contNova),
					  
				array('Contraseña actual' => array('required','min:6','max:20'), 
					  'Contraseña nueva' => array('required','min:6','max:20')
					  )				 
			);
				
			if ($validator->fails()){
				return Redirect::route('canviar.contrasenya')->withErrors($validator);
			}
			
			if (Auth::validate(array('email_estudiant' => $email, 
												'password' => $contAntiga, 
												'actiu_estudiant' => 1))){
					
				try{							   					
					$estudiant = Estudiant::findOrFail($email);
				}catch(ModelNotFoundException $e){
					return Redirect::route('grups.meus');
				}
				
				$estudiant->contrasenya_estudiant = Hash::make($contNova);
				$estudiant->save();
				
				Auth::logout();
				Auth::attempt(array('email_estudiant' => $email, 'password' => $contNova));
				
				return Redirect::route('grups.meus');
				
			}else{
				return Redirect::route('canviar.contrasenya')->with('canvi', 'error');
			}
		}else{
			$this->layout->title = 'Workshome - Cambiar contraseña';
		    $this->layout->description = 'Cambiar contraseña actual';
			$this->layout->content = View::make('estudiants/canviarContrasenya');
		}
	}
	
	/**
	 * Pàgina principal on l'usuari pot iniciar sessió
	 */
 	public function index(){
 		$this->layout = View::make('layouts.master2');
		$email = Input::get('email');
		$email = strip_tags($email);
		$email = trim($email);
			
		$contrasenya = Input::get('contrasenya');
		$contrasenya = strip_tags($contrasenya);
		$contrasenya = trim($contrasenya);
									
		if (Input::has('email'))
		{
			$email = Input::get('email');
		    $password = Input::get('contrasenya');
			
			//sessio indica si es desitja matenir la sessio oberta
			$sessio = Input::get('sessio');
			
			$validator = Validator::make(
				array('Email' => $email, 
					  'Contraseña' => $password),
					  
				array('Email' => array('required', 'max:60'), 
					  'Contraseña' => array('required', 'min:6', 'max:20')
					  )				 
			);
				
			if ($validator->fails()){
				return Redirect::route('index')->withErrors($validator);
			}
							
			if($sessio){
				if (Auth::attempt(array('email_estudiant' => $email, 'password' => $password, 'actiu_estudiant' => 1), true)){   					
					return Redirect::route('grups.meus');
				}
				else{
					return Redirect::route('index')->with('loggin', 'error');
				}
			}
			else{
				if (Auth::attempt(array('email_estudiant' => $email, 'password' => $password, 'actiu_estudiant' => 1), false)){	
					return Redirect::route('grups.meus');
				}
				else{
					return Redirect::route('index')->with('loggin', 'error');
				}
			}				
		} else{
			if (Auth::check())
			{
			    return Redirect::route('grups.meus');
			}			
			
			$this->layout->title = 'Workshome - Tu red social académica';
			$this->layout->description = 'WORKSHOME es una red social académica donde podrás 
			crear grupos de asignaturas e intercambiar opiniones con tus compañeros de clase';
			$this->layout->content = View::make('inici/index');				
		}			
	}			
}
?>