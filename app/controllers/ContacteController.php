<?php

/**
 * @author Eduardo
 */
class ContacteController extends BaseController {
	
	/**
	 * Envia un email de contacte
	 */
	public function enviarEmailContacte(){
		
	   $from = Input::get('from');
	   $from = strip_tags($from);
	   $from = trim($from);
	   
	   $message = Input::get('message');
	   $message = strip_tags($message);
	   $message = trim($message);
	   
	   $subject = Input::get('subject');
	   $subject = strip_tags($subject);
	   $subject = trim($subject);		
	
	   $validator = Validator::make(
		array('Email' => $from, 
		  'Mensaje' => $message,
		  'Asunto' => $subject),
			  
		array('Email' => array('required', 'max:80'), 
		  'Mensaje' => array('required', 'max:500'),
		  'Asunto' => array('required', 'max:60')		  
	  		)				 
	   );
	
		if ($validator->fails()){
			return Redirect::route('contacte.email')->withErrors($validator);
		}
	
	   $to = 'mail@gmail.com';
	   $headers = "From:" . $from;
	   
	   $enviat = mail($to,$subject,$message,$headers);
	   
	   if($enviat){
			return Redirect::route('contacte.email')->with('enviat', 'ok');
	   }else{
	   		return Redirect::route('contacte.email')->with('enviat', 'ko');
	   }  
	}
	
	/**
	 * Mostra un formulari de contacte
	 */
	public function formulariContacte(){		
		
		//A qui s'envia el correu
		$user = array(
		    'email'=>'mail@gmail.com',
		    'name'=>'Eduardo Frontera'
		);
	 
		//Informació que s'envia a la vista
		$data = array(
		    'detail'=>'',
		    'name'  => $user['name']
		);
		
		$this->layout = View::make('layouts.master2');
		$this->layout->title = 'Workshome - Formulario de contacto';
		$this->layout->description = 'Enviar correo al administrador de la página para cualquier duda, sugerencia o petición';
		$this->layout->content = View::make('emails/contacte');
	}
}



	