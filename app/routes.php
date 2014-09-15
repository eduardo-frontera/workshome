<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


/*
 *------------------------- INDEX ROUTES -------------------------------------------------
 */

/*Pàgina d'inici*/
Route::any('/', array('as' => 'index','uses' => 'EstudiantController@index'));

/*
 *------------------------- ESTUDIANT ROUTES -------------------------------------------------
 */

/*Dona d'alta un estudiant*/
Route::any('/new', array('as' => 'estudiant.nou','uses' => 'EstudiantController@nouEstudiant'));

/*Tanca la sessió d'un estudiant*/
Route::any('/logout', array(
	'as' => 'estudiant.sortir',
	'uses' => 'LogginController@sortir',
	'before' => 'auth'
));

/*Edició de la informació d'un estudiant*/
Route::any('/user/edit', array(
	'as' => 'estudiant.editar',
	'uses' => 'EstudiantController@editar',
	'before' => 'auth'
));

/*Edició de la imatge de perfil d'un estudiant*/
Route::any('/user/photo', array(
	'as' => 'estudiant.foto',
	'uses' => 'EstudiantController@editarImatgePerfil',
	'before' => 'auth'
));

/*Mostra el perfil d'un estudiant*/
Route::any('/user/{slug}', array(
	'as' => 'estudiant.consultar',
	'uses' => 'EstudiantController@mostrarEstudiant',
	'before' => 'auth'
));

/*Dona de baixa un estudiant*/
Route::any('/unsubscribe', array(
	'as' => 'estudiant.eliminar',
	'uses' => 'EstudiantController@baixaEstudiant',
	'before' => 'auth'
));

/*Matricula un estudiant a un grup*/
Route::any('/user/enrol/{slug}/{grup}', array(
	'as' => 'estudiant.matricular',
	'uses' => 'EstudiantController@matricularEstudiant',
	'before' => 'auth'
));

/*Desmatricula un estudiant d'un grup*/
Route::any('/user/unenrol/{slug}/{grup}', array(
	'as' => 'estudiant.desmatricular',
	'uses' => 'EstudiantController@desmatricularEstudiant',
	'before' => 'auth'
));

/*Acceptació de la solicitud d'un estudiant per entrar a un grup*/
Route::any('/user/enrol-answer/{slug}/{grup}', array(
	'as' => 'estudiant.solmatricula',
	'uses' => 'EstudiantController@solMatriculaEstudiant',
	'before' => 'auth'
));

/*Rebuig de la solicitud d'un estudiant per entrar a un grup*/
Route::any('/user/cancel-enrol-answer/{slug}/{grup}', array(
	'as' => 'estudiant.cancelsolmatricula',
	'uses' => 'EstudiantController@cansolMatriculaEstudiant',
	'before' => 'auth'
));


/*Canvi de la contrasenya d'un estudiant*/
Route::any('/changePassword', array(
	'as' => 'canviar.contrasenya',
	'uses' => 'EstudiantController@canviarContrasenya',
	'before' => 'auth'
));

/*Pestanya matriculacions d'un estudiant*/
Route::any('/user/roles/{slug}', array(
	'as' => 'estudiant.matricules',
	'uses' => 'EstudiantController@matricules',
	'before' => 'auth'
));

/*Informació adicional d'un estudiant*/
Route::any('/info', array(
	'as' => 'estudiant.info',
	'uses' => 'EstudiantController@mesInformacio',
	'before' => 'auth'
));

/*Actualitzar informació adicional d'un estudiant*/
Route::any('/refresh', array(
	'as' => 'estudiant.actualitzar',
	'uses' => 'EstudiantController@actualitzarMesInfo',
	'before' => 'auth'
));

/*
 *------------------------- CENTRE ROUTES -------------------------------------------------
 */

/*Resposta json dels tipus de centres*/
Route::any('/centers/types', array(
	'as' => 'centres.tipus.json',
	'uses' => 'CentreController@getTipusCentre',
	'before' => 'auth'
));

/*Resposta json dels centres d'un tipus donat*/
Route::any('/centers', array(
	'as' => 'centres.json',
	'uses' => 'CentreController@getCentres',
	'before' => 'auth'
));
 
/*
 *------------------------- ASSIGNATURA ROUTES -------------------------------------------------
 */

/*Pàgina principal d'una assignatura*/
Route::any('/subject/{slug}', array(
	'as' => 'assignatura.info',
	'uses' => 'AssignaturaController@mostrarAssignatura',
	'before' => 'auth'
));

/*Retorna les noves aportacions des de la darrera actualització*/
Route::any('/subject/latest/{idAssignatura}', array(
	'as' => 'assignatura.noves',
	'uses' => 'AssignaturaController@novesAportacions',
	'before' => 'auth'
));

/*Afegeix assignatures a un grup*/
Route::any('/subjects/new/{slug}', array(
	'as' => 'assignatures.nou',
	'uses' => 'AssignaturaController@afegirAssignatures',
	'before' => 'auth'
));

/*Edició de la informació d'una assignatura*/
Route::any('/subject/edit/{slug}', array(
	'as' => 'assignatura.editar',
	'uses' => 'AssignaturaController@editarAssignatura',
	'before' => 'auth'
));

/*Eliminació directa d'una assignatura*/
Route::any('/subject/delete/{slug}', array(
	'as' => 'assignatura.eliminar',
	'uses' => 'AssignaturaController@eliminarAssignatura',
	'before' => 'auth'
));

/*Crea una nova aportació a una assignatura*/
Route::any('/subject/{id}/post/create', array(
	'as' => 'assignatura.aportacio.create',
	'uses' => 'AssignaturaController@crearAportacio',
	'before' => 'auth'
));

/*Descarrega un fitxer associat a una aportació d'una assignatura*/
Route::any('/download/{id}', array
	('as' => 'download',
	'uses' => 'AssignaturaController@download',
	'before' => 'auth'
));

/*Elimina una aportació realitzada a una assignatura*/
Route::any('/post/delete/{id}', array
	('as' => 'aportacio.eliminar',
	'uses' => 'AssignaturaController@eliminarAportacio',
	'before' => 'auth'
));

/*Editar una aportació realitzada a una assignatura*/
Route::any('/post/edit/{id}', array
	('as' => 'aportacio.editar',
	'uses' => 'AssignaturaController@editarAportacio',
	'before' => 'auth'
));

/*
 *------------------------- GRUP ROUTES -------------------------------------------------
 */
 
/*Crea un nou grup*/
Route::any('/group/new', array(
	'as' => 'grup.nou',
	'uses' => 'GrupController@nouGrup',
	'before' => 'auth'
));

/*Mostra la pàgina d'un grup*/
Route::any('/group/view/{slug}', array(
	'as' => 'grup.consulta', 
	'uses' => 'GrupController@consultaGrup',
	'before' => 'auth'
));

/*Edita la informació d'un grup*/
Route::any('/group/edit/{slug}', array(
	'as' => 'grup.editar', 
	'uses' => 'GrupController@editarGrup',
	'before' => 'auth'
));

/*Mostra tots els grups*/
Route::get('/groups', array(
	'as' => 'grups.meus', 
	'uses' => 'GrupController@mostrarGrupsMeus',
	'before' => 'auth'
));

/*Dona de baixa un grup*/
Route::any('/group/delete/{id}', array
	('as' => 'grup.eliminar',
	'uses' => 'GrupController@baixaGrup',
	'before' => 'auth'
));

/*Crea un solicitud per a entrar a un grup*/
Route::any('/group/request/{id}', array
	('as' => 'grup.solicitud',
	'uses' => 'GrupController@novaSolicitud',
	'before' => 'auth'
));

/*Cancel·la la solicitud per a entrar a un grup*/
Route::any('/group/canrequest/{id}', array
	('as' => 'grup.cansolicitud',
	'uses' => 'GrupController@canSolicitud',
	'before' => 'auth'
));

/*Permet abandonar un grup*/
Route::any('/group/leave/{id}', array
	('as' => 'grup.sortir',
	'uses' => 'GrupController@sortirGrup',
	'before' => 'auth'
));

/*Llista d'estudiants que volen entrar a un grup*/
Route::any('/groups/requests/{slug}', array(
	'as' => 'estudiant.solicituds',
	'uses' => 'GrupController@solicituds',
	'before' => 'auth'
));
 
/*
 *------------------------- ESDEVENIMENT ROUTES -------------------------------------------------
 */

/*Crea un nou esdeveniment*/
Route::any('/events/new', array
	('as' => 'esdeveniment.nou',
	'uses' => 'EsdevenimentController@nouEsdeveniment',
	'before' => 'auth'
));

/*Edita un esdeveniment*/
Route::any('/events/edit/{slug}', array
	('as' => 'esdeveniment.editar',
	'uses' => 'EsdevenimentController@editarEsdeveniment',
	'before' => 'auth'
));

/*Mostra tots els esdeveniments vigents*/
Route::any('/events', array
	('as' => 'esdeveniments.consulta',
	'uses' => 'EsdevenimentController@consultaEsdeveniments',
	'before' => 'auth'
));

/*Mostra un esdeveniment*/
Route::any('/event/{slug}', array
	('as' => 'esdeveniment.consulta',
	'uses' => 'EsdevenimentController@consultarEsdeveniment',
	'before' => 'auth'
));

/*Elimina un esdeveniment*/
Route::any('/events/delete/{id}', array
	('as' => 'esdeveniment.eliminar',
	'uses' => 'EsdevenimentController@eliminarEsdeveniment',
	'before' => 'auth'
));

/*Crea una aportació a un esdeveniment*/
Route::any('/events/post/create/{id}', array
	('as' => 'aportacio.crear.esdeveniment',
	'uses' => 'EsdevenimentController@crearAportacioEsdeveniment',
	'before' => 'auth'
));

/*Elimina una aportació a un esdeveniment*/
Route::any('/events/post/delete/{id}', array
	('as' => 'aportacio.eliminar.esdeveniment',
	'uses' => 'EsdevenimentController@eliminarAportacioEsdeveniment',
	'before' => 'auth'
));

/*Mostra pestanya missatges d'un esdeveniment*/
Route::any('/events/messages/{slug}', array
	('as' => 'esdeveniment.missatges',
	'uses' => 'EsdevenimentController@mostrarMissatges',
	'before' => 'auth'
));

/*Mostra següent pàgina d'aportacions d'un esdeveniment*/
Route::any('/events/new/messages/{id}', array
	('as' => 'esdeveniment.noves',
	'uses' => 'EsdevenimentController@novesAportacionsEsdeveniment',
	'before' => 'auth'
));

/*Descarrega un fitxer d'una aportació d'un esdeveniments*/
Route::any('/events/download/{id}', array
	('as' => 'esdeveniment.download',
	'uses' => 'EsdevenimentController@descarregarFitxer',
	'before' => 'auth'
));

/*Editar una aportació a un esdeveniment*/
Route::any('/events/post/edit/{id}', array
	('as' => 'aportacio.editar.esdeveniment',
	'uses' => 'EsdevenimentController@editarAportacioEsdeveniment',
	'before' => 'auth'
));


/*
 *------------------------- CERCA ROUTES -------------------------------------------------
 */

/*Cerca estudiants per nom i cognoms*/
Route::any('/search/users/{$text?}', array
	('as' => 'cerca.estudiants',
	'uses' => 'CercaController@cercaEstudiants',
	'before' => 'auth'
));

/*Cerca grups per nom i descripció*/
Route::any('/search/groups/{$text?}', array
	('as' => 'cerca.grups',
	'uses' => 'CercaController@cercaGrups',
	'before' => 'auth'
));


/*
 *------------------------- COMENTARIS ROUTES -------------------------------------------------
 */

/*Crea un nou comentari a una aportació d'una assignatura*/
Route::any('/subject/{id}/comment/create', array(
	'as' => 'comentari.create',
	'uses' => 'ComentariController@crearComentari',
	'before' => 'auth'
));

/*Cerca comentaris anteriors un donat*/
Route::any('/comment/next/{id}/{last}', array
	('as' => 'seguents.comentaris',
	'uses' => 'ComentariController@mesComentaris',
	'before' => 'auth'
));

/*Elimina un comentari d'una aportació d'una assignatura*/
Route::any('/comment/delete/{id}', array
	('as' => 'comentari.eliminar',
	'uses' => 'ComentariController@eliminarComentari',
	'before' => 'auth'
));


/*Crea un nou comentari a una aportació d'un esdeveniment*/
Route::any('/events/{id}/comment/create', array(
	'as' => 'comentari.esdeveniment.create',
	'uses' => 'ComentariController@crearComentariEsdeveniment',
	'before' => 'auth'
));

/*Elimina un comentari d'una aportació d'un esdeveniment*/
Route::any('/events/comment/delete/{id}', array
	('as' => 'comentari.eliminar.esdeveniment',
	'uses' => 'ComentariController@eliminarComentariEsdeveniment',
	'before' => 'auth'
));

/*Editar un comentari d'una aportació d'una assignatura*/
Route::any('/comment/edit/{id}', array
	('as' => 'comentari.editar',
	'uses' => 'ComentariController@editarComentari',
	'before' => 'auth'
));

/*Editar un comentari d'una aportació d'un esdeveniment*/
Route::any('/events/comment/edit/{id}', array
	('as' => 'comentari.editar.esdeveniment',
	'uses' => 'ComentariController@editarComentariEsdeveniment',
	'before' => 'auth'
));

/**
 *-------------------------ContacteController -------------------------------------------------
 */

/*Pàgina de contacte*/
Route::any('/contact', array('as' => 'contacte.email','uses' => 'ContacteController@formulariContacte'));

/*Enviar email*/
Route::any('/email', array('as' => 'enviar.email','uses' => 'ContacteController@enviarEmailContacte'));
 
/*
 *------------------------- PoliticaController -------------------------------------------------
 */ 
 
 /*Politica de privacitat*/
Route::any('/privacy', array('as' => 'politica.privacitat','uses' => 'PoliticaController@mostrarPolitica'));
 
/*
 *------------------------- FuncionamentController -------------------------------------------------
 */ 
 
 /*Funcionament de la pàgina*/
Route::any('/works', array('as' => 'funcionament','uses' => 'FuncionamentController@mostrarFuncionament')); 
 
/*
 *------------------------- PÀGINES D'ERROR -------------------------------------------------
 */

/*Vista de pàgina no trobada*/
App::missing(function($exception)
{
   return Response::view('errors.missing', array(), 404);
}); 
 
/**
 *------------------------- REMINDER PASSWORD -------------------------------------------------
 * */

Route::any('/asdasdasd', array
	('as' => 'email',
	'uses' => 'PruebaController@prueba2'
));

//Introduir email per recuperar el compte
Route::get('password/reset', array(
  'uses' => 'PasswordController@remind',
  'as' => 'password.remind'
));

//Envia missatge amb enllaç per recuperar compte
Route::post('password/reset', array(
  'uses' => 'PasswordController@request',
  'as' => 'password.request'
));

//Mostra la vista per introduir la nova contrasenya
Route::get('password/reset/{token}', array(
  'uses' => 'PasswordController@reset',
  'as' => 'password.reset'
));

//Canvia la contrasenya del compte
Route::post('password/reset/{token}', array(
  'uses' => 'PasswordController@update',
  'as' => 'password.update'
));

/*
 *------------------------- SEO -------------------------------------------------
 */

//Mostra el siteMap de la pàgina
Route::any('/sitemap', array(
  'uses' => 'SEO@siteMap',
  'as' => 'sitemap'
));

/*
 *------------------------- PLANTILLES -------------------------------------------------
 */

  /*Plantilla utilitzada a les pàgines on no és necessari iniciar sessió*/
Route::any('/pruebas/plantilla1', array
	('as' => 'plantilla1',
	'uses' => 'PruebaController@mostrarPlantilla1'
));
 
 
 /*Plantilla utilitzada a totes les pàgines on s'ha d'iniciar sessió*/
Route::any('/pruebas/plantilla2', array
	('as' => 'plantilla2',
	'uses' => 'PruebaController@mostrarPlantilla2'
));