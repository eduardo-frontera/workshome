<?php
class PasswordController extends BaseController {
	
	/**
	 * Permet introduir l'email del compte oblidat.
	 */
	public function remind()
	{
		$this->layout = View::make('layouts.master2');
		$this->layout->title = 'Workshome - Recordar contraseña';
		$this->layout->description = 'Recuperar tu cuenta de Workshome';
		$this->layout->content = View::make('password/email');	
	}
	
	/**
	 * Envia un missatge amb un enllaç per recuperar el compte
	 */
	public function request()
	{
	  $credentials = array('email_estudiant' => Input::get('email'));
	 
	  return Password::remind($credentials, function ($message, $user){
	  		$message->subject('Workshome - Recuperación de cuenta');
	  });
	}
	
	/**
	 * Mostra la vista on introducir la nova contrasenya
	 * @param string [$token] numero de recuperació de contrasenya
	 */
	public function reset($token)
	{
	  $this->layout = View::make('layouts.master2');
	  $this->layout->title = 'Workshome - Recordar contraseña';
	  $this->layout->description = 'Recuperar tu cuenta de Workshome';
	  $this->layout->content = View::make('password/noves_dades')->with('token', $token);
	}	
	
	/**
	 * Canvia la contraseya i finalment es pot recuperar el compte
	 */
	public function update()
	{
		
	$token = Input::get('token');
	$password = Input::get('password');
	$password_confirmation = Input::get('password_confirmation');
	
	$password = strip_tags($password);
	$password = trim($password);
	
	$password_confirmation = strip_tags($password_confirmation);
	$password_confirmation = trim($password_confirmation);
	
	$numpass = strlen($password);
	
	if($numpass == 0 || $numpass < 6 || $numpass > 45){
		return Redirect::route('password.reset', array('token' => $token))->with('password', 'error');
	}
	
	if($password != $password_confirmation){
  		return Redirect::route('password.reset', array('token' => $token))->with('password', 'different');
  	}		  
	  $credentials = array('email_estudiant' => Input::get('email'));	  
	 
	  return Password::reset($credentials, function($user, $password)
	  {		
	    $user->contrasenya_estudiant = Hash::make($password);
	 
	    $user->save();
	 
	    return Redirect::route('index');
	  });
	}
}
?>