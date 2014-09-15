/**
 * @author Eduardo
 */


ComentariPage = function(){
};

ComentariPage.prototype.enviando = false;
ComentariPage.prototype.editant = false;

ComentariPage.prototype.init = function(){
	this.initCompartir();
	this.initSeguents();
	this.initEdicio();
	this.initEliminarComentari();
};


ComentariPage.prototype.initEdicio = function(){
	$('body').on('click', 'a[data-editar-comentari]', function(e){
		e.preventDefault();
		
		if(!ComentariPage.editant){
			ComentariPage.editant = true;
		}else{
			return;
		}
		
		var $this = $(this);
		var $form = $('#inputEdicioComentari').clone();
		$form.show();
		var $text = $this.parents('[data-comentari]').find('[data-comentari-text]').hide();
		$form.find('[name="text"]').val($text.text().trim());
		$form = $form.insertBefore($text);
		$form.find('form').submit(function(e){				
			e.preventDefault();
			ComentariPage.editant = false;
			var href = $this.attr('href');
			$.post(href, $(this).serialize(),function(response){				
				if (response.error != undefined){
					var $modal = $('#myModal');
					$modal.find('.modal-body').html(response.error);
					$modal.modal('show');
				}else{
					$form.fadeOut(function(){
						$text.text($form.find('[name="text"]').val());
						$text.show();
						$form.remove();
					});
				}
			});
		});
		
		$form.find('[data-cancel]').click(function(e){
			e.preventDefault();
			ComentariPage.editant = false;
			$form.fadeOut(function(){
				$text.show();
				$form.remove();
			});
		});
	});
}

/**
 * Insereix un nou comentari a la pàgina
 */
ComentariPage.prototype.initCompartir = function(){
	$('body').on('submit','[data-form-respuesta]',function(e){
		
		//No es permet enviar un formulari si s'està processant un altre
		if(!ComentariPage.enviando){
			ComentariPage.enviando = true;
		}else{
			return false;
		}
		
		var $form = $(this);	
		//atura el submit	
		e.preventDefault(); 
		//agafa l'atribut "action"
		var url = $form.attr('action');
		
		/**************** Desactivar formulari i botó ***************************************/
		$boto = $form.find('[data-boto]');
		$boto.attr('disabled','disabled');		
		
		//fa la petició ajax
		$.ajax({
			url : url,
			data : $form.serialize(),
			method : $form.attr('method')
		}).done(function(response){
			//si hi ha algun error mostra un modal explicant el problema.
			if (response.error != undefined){
				var $modal = $('#myModal');
				$modal.find('.modal-body').html(response.error);
				$modal.modal('show');
			} else {
				//agafa la resposta
				$comentari = $(response);
				//no l'insereix tot d'una per poder afegir un afecte d'aparició suau.
				$comentari.hide();
				//associa al comentari el tooltip d'eliminació
				$comentari.find('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
				$('#comentaris_'+$form.data('formRespuesta')).append($comentari);
				$comentari.fadeIn();
				//elimina el text del comentari de l'input
				$form[0].reset();
			}
			//activa el botó.
			$boto.removeAttr('disabled');
			ComentariPage.enviando = false;
		});
	});
};

/**
 * Mostra els seguents comentaris (els mes antics)
 */
ComentariPage.prototype.initSeguents = function(){
	$('body').on('click','[data-seg-com]', '[data-aportacio]',function(e){
		e.preventDefault();
		var $this = $(this);
		//agafa la url on fer la petició
		var url = $this.data('segCom');
		$.get(url).done(function(response){			
			if (response.error != undefined){
					var $modal = $('#myModal');
					$modal.find('.modal-body').html(response.error);
					$modal.modal('show');
				}else{			
					var $comentaris = $(response);
					//associa als comentaris el tooltip d'eliminació
					$comentaris.find('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
					$comentaris.hide();
					//afegeix els nous comentaris.
					$this.parent().prepend($comentaris);
					$comentaris.fadeIn();
					//agafa el numero d'aportació a la qual pertany el comentari.
					var num_aportacio = $this.data('aportacio');
					//agafa l'aportació i l'amaga.
					var $aportacio = $('#'+num_aportacio);
					$aportacio.hide();
				}
		});
	});
};

/**
 * Elimina un comentari 
 */
ComentariPage.prototype.initEliminarComentari = function(){
	$('body').on('click', 'a[data-elimina-comentari]', function(e){
		e.preventDefault();
		//agafa la url
		var url = $(this).attr('href');
		//agafa el botó d'eliminar.
		var $triggerButton = $(this);
		//agafa el modal de confirmacio
		var $modal = $('#modal-eliminar-com');
		//agafa el boto de confirmacio.
		var $proceedButton = $modal.find('[data-proceedCom]');
		//elimina possibles listeners anteriors.
		$proceedButton.unbind();
		$proceedButton.click(function(){
			$modal.modal('hide');
			//realitza la petició
			$.ajax({
				url : url,
				method : 'get'
			}).done(function(response){
				//si hi ha algun error mostra un modal explicant el problema.
				if (response.error != undefined){
					var $modal = $('#myModal');
					$modal.find('.modal-body').html(response.error);
					$modal.modal('show');
				} else {
					//en cas contrari agafa el comentari i l'elimina
					var $container = $triggerButton.parents('[data-comentari]');
					$container.fadeOut(function(){
						$container.remove();
					});
				}
		
			});
		});
		$modal.modal('show');
	});
}

$(document).ready(function(){
	var comentariPage = new ComentariPage();
	comentariPage.init(); 
});
