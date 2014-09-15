<?php

/**
 * @author Eduardo
 */
class SEO extends BaseController {
	
	/**
	 * Mostra el XML Sitemap
	 */
	public function sitemap(){
		$file = "/laravel/public/sitemap.xml";
		if (file_exists($file)) {	    	
	    	$content = file_get_contents($file);
	    	return Response::make($content, 200, array('content-type'=>'application/xml'));
		}
	}
}