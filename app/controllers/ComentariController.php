<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class ComentariController extends BaseController {

	/**
	 * Editar un comentari d'una aportació d'una assignatura
	 * @param int [$id] identificador del comentari
	 */
	public function editarComentari($id){
		try{
			try{
				$comentari = Comentari::findOrFail($id);
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'El comentario ha sido eliminado.'));
			}
			
			$text = Input::get('text');	
			$text = strip_tags($text);
			$text = trim($text);
				
			$validator = Validator::make(
				array('comentari' => $text),
				array('comentari' => array('required','max:255'))
			);
				
			if ($validator->fails()){
				throw new ModelException("Debes escribir un comentario (máx. 255 caracteres)");
			}
			
			$autor = $comentari->autor;
			$aportacio = $comentari->aportacio;
			$assignatura = $aportacio->assignatura;
			$grup = $assignatura->grup;
			$grups = Auth::user()->grups()->lists('id_grup');
			
			/*Comprovació de que l'estudiant estigui matriculat, sigui l'autor del comentari i el grup està actiu*/
			if(in_array($grup->getID(), $grups) && $autor->getEmail() == Auth::user()->getEmail()){
				if($grup->getActiu()){
					$comentari->text_comentari = $text;
					$comentari->save();
				}else{
					return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
				}
			}else{
				return Response::json(array('error' => 'El comentario no se puede editar.'));
			}
			
			return Response::json(array('status' => 'ok'));
		}catch(ModelException $exception){			
			return Response::json(array('error' => $exception->getMessage()));			
		}
	}

	/**
	 * Elimina un comentari d'una aportació d'una assignatura
	 * @param int [$id] identificador del comentari
	 */
	public function eliminarComentari($id){
		try{
			$comentari = Comentari::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Response::json(array('error' => 'El comentario ha sido eliminado.'));
		}
		
		$autor = $comentari->autor;
		$aportacio = $comentari->aportacio;
		$assignatura = $aportacio->assignatura;
		$grup = $assignatura->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		/*Comprovació de que l'estudiant estigui matriculat, sigui l'autor del comentari i el grup està actiu*/
		if(in_array($grup->getID(), $grups) && $autor->getEmail() == Auth::user()->getEmail()){
			if($grup->getActiu()){
				$comentari->delete();
			}else{
				return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
			}			
		}else{
			return Response::json(array('error' => 'El comentario no se puede eliminar.'));
		}
		
		return Response::json(array('status' => 'ok'));
	}

	/**
	 * Crea un nou comentari a una aportació d'una assignatura
	 * @param integer [$id] identificador de l'aportació
	 */
	public function crearComentari($id){		
		try{
			$text = Input::get('comentari');	
			$text = strip_tags($text);
			$text = trim($text);
			
			$validator = Validator::make(
				array('comentari' => $text),
				array('comentari' => array('required','max:255'))
			);
				
			if ($validator->fails()){
				throw new ModelException("Debes escribir un comentario (máx. 255 caracteres)");
			}
			
			try{
				$aportacio = Aportacio::findOrFail($id); 	
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'Esta aportación ha sido eliminada por el autor.'));
			}	
			
			$assignatura = $aportacio->assignatura;
			$grup = $assignatura->grup;
			
			$grups = Auth::user()->grups()->lists('id_grup');
			
			/*Comprovació de que l'estudiant estigui matriculat
			 al grup d'aquesta assignatura i el grup està actiu*/
			if (!in_array($grup->getID(), $grups)){				
				return Response::json(array('error' => 'No estás matriculado en el grupo de esta asignatura.'));
			}
			
			if(!$grup->getActiu()){
				return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
			}
			
			$comentari = new Comentari();
			$comentari->text_comentari = $text;
			
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			$comentari->data_comentari = $ara;
			
			$comentari->aportacio_id_aportacio = $id;
			$comentari->estudiant_email = Auth::user()->email_estudiant;
			
			$comentari->save();	
			
			if (Request::ajax()){
				return View::make('assignatura/comentari',array('comentari' => $comentari));
			}
		}catch(ModelException $exception){
			return Response::json(array('error' => $exception->getMessage()));
		}
	}	
	
	/************************ ESDEVENIMENTS **********************************************/
	
	/**
	 * Elimina un comentari d'una aportació d'un esdeveniment
	 * @param int [$id] identificador del comentari
	 */
	public function eliminarComentariEsdeveniment($id){
		try{
			$comentari = Comentari::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Response::json(array('error' => 'El comentario ha sido eliminado.'));
		}
						
		$autor = $comentari->autor;
		$aportacio = $comentari->aportacio;
		$esdeveniment = $aportacio->esdeveniment;
		$grup = $esdeveniment->grup;
		$grups = Auth::user()->grups()->lists('id_grup');
		
		date_default_timezone_set("Europe/Madrid");
		$ara = date("Y-m-d H:i:s");
		$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
		
		/*Comprovació de que l'estudiant sigui l'autor del comentari i el grup està actiu*/
		if(in_array($grup->getID(), $grups)){
			if($autor->getEmail() == Auth::user()->getEmail()){
				if($grup->getActiu()){
					if($esdeveniment_actiu){
						$comentari->delete();
					}else{
						return Response::json(array('error' => 'El evento ha dejado de estar activo.'));
					}
				}else{
					return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
				}		
			}else{
				return Response::json(array('error' => 'No eres el autor del comentario.'));
			}
		}else{
			return Response::json(array('error' => 'No estás matriculado en el grupo de esta asignatura.'));
		}
		return Response::json(array('status' => 'ok'));
	}

	/**
	 * Crea un nou comentari a una aportació d'un esdeveniment
	 * @param integer [$id] identificador de l'aportació
	 */
	public function crearComentariEsdeveniment($id){		
		try{
			$text = Input::get('comentari');	
			$text = strip_tags($text);
			$text = trim ($text);
			
			$validator = Validator::make(
				array('comentari' => $text),
				array('comentari' => array('required','max:255'))
			);
				
			if ($validator->fails()){
				throw new ModelException("Debes escribir un comentario (máx. 255 caracteres)");
			}
			
			try{
				$aportacio = Aportacio::findOrFail($id); 	
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'Esta aportación ha sido eliminada por el autor.'));
			}	
			
			$esdeveniment = $aportacio->esdeveniment;
			$grup = $esdeveniment->grup;			
			
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
			
			$grups = Auth::user()->grups()->lists('id_grup');
			
			/*Comprovació de que l'estudiant estigui matriculat
			 al grup d'aquesta assignatura i el grup està actiu*/			
			if(!in_array($grup->getID(), $grups)){
				return Response::json(array('error' => 'No estás matriculado en el grupo de esta asignatura.'));
			}			
			if(!$grup->getActiu()){
				return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
			}
			if(!$esdeveniment_actiu){
				return Response::json(array('error' => 'El evento ha dejado de estar activo.'));
			}	
				
			$comentari = new Comentari();
			$comentari->text_comentari = $text;
			
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			$comentari->data_comentari = $ara;
			
			$comentari->aportacio_id_aportacio = $id;
			$comentari->estudiant_email = Auth::user()->email_estudiant;
			
			$comentari->save();	
			
			if (Request::ajax()){
				return View::make('esdeveniments/comentari',array('comentari' => $comentari));
			}
		}catch(ModelException $exception){
			return Response::json(array('error' => $exception->getMessage()));
		}
	}	
	
	/**
	 * Editar un comentari d'una aportació d'un esdeveniment
	 * @param int [$id] identificador del comentari
	 */
	public function editarComentariEsdeveniment($id){
		try{
			try{
				$comentari = Comentari::findOrFail($id);
			}catch(ModelNotFoundException $e){
				return Response::json(array('error' => 'El comentario ha sido eliminado.'));
			}
			
			$text = Input::get('text');	
			$text = strip_tags($text);
			$text = trim ($text);
				
			$validator = Validator::make(
				array('comentari' => $text),
				array('comentari' => array('required','max:255'))
			);
				
			if ($validator->fails()){
				throw new ModelException("Debes escribir un comentario (máx. 255 caracteres)");
			}
			
			$autor = $comentari->autor;
			$aportacio = $comentari->aportacio;
			$esdeveniment = $aportacio->esdeveniment;
			$grup = $esdeveniment->grup;
			$grups = Auth::user()->grups()->lists('id_grup');
			
			date_default_timezone_set("Europe/Madrid");
			$ara = date("Y-m-d H:i:s");
			$esdeveniment_actiu = $esdeveniment->data_esdeveniment > $ara;
			
			/*Comprovació de que l'estudiant sigui l'autor del comentari i el grup està actiu*/
			if(in_array($grup->getID(), $grups)){
				if($autor->getEmail() == Auth::user()->getEmail()){
					if($grup->getActiu()){
						if($esdeveniment_actiu){
							$comentari->text_comentari = $text;
							$comentari->save();
						}else{
							return Response::json(array('error' => 'El evento ha dejado de estar activo.'));
						}
					}else{
						return Response::json(array('error' => 'El grupo ha sido eliminado por su moderador.'));
					}		
				}else{
					return Response::json(array('error' => 'No eres el autor del comentario.'));
				}
			}else{
				return Response::json(array('error' => 'No estás matriculado en el grupo de esta asignatura.'));
			}
			
			return Response::json(array('status' => 'ok'));
		}catch(ModelException $exception){			
			return Response::json(array('error' => $exception->getMessage()));			
		}
	}

	/**
	 * Cerca comentaris anteriors a un donat
	 * @param integer [$id] identificador de l'aportació
	 * @param integer [$last] identificador del darrer comentari mostrat
	 */
	public function mesComentaris($id,$last){
		try{
			$aportacio = Aportacio::findOrFail($id);
		}catch(ModelNotFoundException $e){
			return Response::json(array('error' => 'Esta aportación ha sido eliminada.'));
		}
		
		$comentaris = $aportacio->getComentarisSince($last);
		return View::make('assignatura/comentaris', 
							array('comentaris' => $comentaris));	
	}
}

