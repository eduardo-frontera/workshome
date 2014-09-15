<?php
class PasswordController extends BaseController {
	
	public function remind()
	{
		$this->layout = View::make('layouts.master2');
		$this->layout->content = View::make('password/email');	
	}
	
	public function request()
	{
	  $credentials = array('email_estudiant' => Input::get('email'));
	 
	  return Password::remind($credentials, function ($message, $user){
	  		$message->subject('Workshome - Recuperación de cuenta');
	  });
	}
	
	public function reset($token)
	{
	  $this->layout = View::make('layouts.master2');
	  $this->layout->content = View::make('password/noves_dades')->with('token', $token);
	}	
	
	public function update()
	{
		
	$token = Input::get('token');
	$password = Input::get('password');
	$password_confirmation = Input::get('password_confirmation');
	
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