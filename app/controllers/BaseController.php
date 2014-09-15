<?php

class BaseController extends Controller {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.master';

	public function __construct(){
		$this->afterFilter('auth',array('uses' => 'BaseController@loadGrups'));
	}
	
	protected function loadGrups(){
		if (Auth::check()){
			$estudiant = Auth::user(); 			
			$this->layout->grups = $estudiant->grups;
			$this->layout->estudiant = $estudiant;
		}
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
			$this->loadGrups();
		}
	}

}