<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Eduardo
 */
class CercaController extends BaseController {
		
	/**
	 * Cerca els estudiants actius per nom i cognoms segons el text introduit
	 */
	public function cercaEstudiants(){
		
			$text = Input::get('text');
			$text = strip_tags($text);
			$text = trim($text);
				
			$estudiants = Estudiant::resultat($text);
			$num_est = Estudiant::num_resultat($text);
			
			$this->layout->title = 'Workshome - Búsqueda '.$text;
		    $this->layout->description = 'Búsqueda de estudiantes';
			$this->layout->content = View::make('cerca/resultat_estudiants', 
												array('estudiants' => $estudiants,
													  'num_est' => $num_est,
													  'text' => $text)
												);	
	}
	
	/**
	 * Cerca els grups per nom i descripció segons el text introduit
	 */
	public function cercaGrups(){
		
			$text = Input::get('text');
			$text = strip_tags($text);
			$text = trim($text);
			
			$num_gr = Grup::num_resultat($text);
			$grups = Grup::resultat($text);
			
			$this->layout->title = 'Workshome - Búsqueda '.$text;
		    $this->layout->description = 'Búsqueda de grupos';
			$this->layout->content = View::make('cerca/resultat_grups', 
												array('text' => $text,
													  'num_gr' => $num_gr,
													  'grups' => $grups)
												);	
	}
	
}