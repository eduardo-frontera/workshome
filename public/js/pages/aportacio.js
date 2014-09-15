/**
 * @author Eduardo
 */

AportacioPage = function(){
	
};

AportacioPage.prototype.editando = false;

AportacioPage.prototype.init = function(){
	this.initRefresh();
	this.initCompartir();
	this.initEdicio();
	this.initInfiniteScroll();
	this.initEliminarAportacio();
};

/**
 * Edita una aportació
 */
AportacioPage.prototype.initEdicio = function(){
	$('body').on('click', 'a[data-editar-aportacio]', function(e){
		e.preventDefault();
		
		if(!AportacioPage.editando){
			AportacioPage.editando = true;
		}else{
			return;
		}
		
		var $this = $(this);
		var $form = $('#inputEdicioAportacio').clone();
		$form.show();
		var $text = $this.parents('[data-aportacio]').find('[data-aportacio-texto]').hide();
		$form.find('[name="text"]').val($text.text().trim().replace(/<br>/mg,"\n"));
		$form = $form.insertBefore($text);
		$form.find('form').submit(function(e){				
			e.preventDefault();
			AportacioPage.editando = false;
			var href = $this.attr('href');
			$.post(href, $(this).serialize(),function(response){				
				if (response.error != undefined){
					var $modal = $('#myModal');
					$modal.find('.modal-body').html(response.error);
					$modal.modal('show');
				}else{
					$form.fadeOut(function(){
						$text.text($form.find('[name="text"]').val().replace(/\n/g,"<br>"));
						$text.html($text.text());
						$text.show();
						$form.remove();
					});
				}
			});
		});
		
		$form.find('[data-cancel]').click(function(e){
			e.preventDefault();
			AportacioPage.editando = false;
			$form.fadeOut(function(){
				$text.show();
				$form.remove();
			});
		});
	});
}

/**
 * Carrega les següents aportacions en arribar al final de la pàgina.
 */
AportacioPage.prototype.initInfiniteScroll = function(){
	$('#aportacions').infinitescroll({
      navSelector  : '#page-nav',    
      nextSelector : '#page-nav a',  
      itemSelector : '.aportacio',     
   	  loadingText  : "Cargando aportaciones...",
   	  donetext     : "" ,      
      loading: {
          finishedMsg: '',
          img: '/laravel/public/img/ajax-loader.gif'
        }
      }
    );
}

/**
 * Afegeix noves aportacions realitzades quan l'usuari es troba 
 * al principi de la pàgina.
 */
AportacioPage.prototype.initRefresh = function(){
	var refresh = function(){
		//agafa la url on fer la petició
		var url = $('[data-url-refresh]').data('urlRefresh');
		//agafa la primera aportació (la més recent).
		var $latest = $('#aportacions').children(':first');
		//agafa la data de l'aportació més recent
		var sinceDate = $latest.data('date');		
		if ($latest.length == 0 || $(document).scrollTop() < $latest.scrollTop() + $latest.height() 
				|| $(document).height() < $(window).height()){
					
			//realitza la petició
			$.ajax({
				url : url,
				data : {since_date :sinceDate},
				
			}).done(function(response){
					//afegeix les aportacions noves
					var $res = $(response);
					//associa a la aportació el tooltip d'eliminació
					$res.find('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
					$res.hide().prependTo($('#aportacions')).fadeIn();

					setTimeout(refresh, 5000);				
			})
		} else {
			setTimeout(refresh, 5000);
		}
	};	
	setTimeout(refresh, 5000);
};

/**
 * Insereix una nova aportació a la pàgina
 */
AportacioPage.prototype.initCompartir = function(){
	$('#form-comentar').submit(function(e){
		var $form = $(this);	
		
		//si té un fitxer per pujar tornam (no ho feim per AJAX)		
		if ($('[name="file"]').val() != ''){
			return true;			
		}
		
		//Deshabilita el boto per evitar tornar a enviar el formulari
		$('#botoAportacio').attr('disabled','disabled');
		
		e.preventDefault();
		
		//realitza la petició.
		$.ajax({
			url : $(this).attr('action'),
			data : $(this).serialize(),
			method : $(this).attr('method')
		}).done(function(response){
			//si hi ha algun error mostra un modal explicant el problema.
			if (response.error != undefined){
				var $modal = $('#myModal');
				$modal.find('.modal-body').html(response.error);
				$modal.modal('show');
			} else {
				//afegeix la nova aportació
				$aportacio = $(response);
				$aportacio.hide();				
				$('#aportacions').prepend($aportacio);
				$aportacio.fadeIn();
				//associa a l'aportació el tooltip d'eliminació
				$aportacio.find('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
				//elimina el text de l'aportació de l'input
				$form[0].reset();
				//Resize del textArea
				$('#boxText').css('min-height','100px');
				$('#boxText').css('height','100px');
			}
			//activa el botó.
			$('#botoAportacio').removeAttr('disabled');
		});
	});
};

/**
 * Elimina una aportació
 */
AportacioPage.prototype.initEliminarAportacio = function(){
	$('body').on('click', 'a[data-elimina-aportacio]', function(e){
		e.preventDefault();
		//agafa la url
		var url = $(this).attr('href');
		//agafa el botó d'eliminar.
		var $triggerButton = $(this);
		//agafa el modal de confirmacio
		var $modal = $('#modal-eliminar');
		//agafa el boto de confirmacio.
		var $proceedButton = $modal.find('[data-proceed]');
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
					//en cas contrari agafa l'aportació i l'elimina
					var $container = $triggerButton.parents('[data-aportacio]');
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
	var aportacioPage = new AportacioPage();
	aportacioPage.init(); 
});
