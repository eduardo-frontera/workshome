/**
 * @author Eduardo
 */


MissatgePage = function(){
};

MissatgePage.prototype.init = function(){
	this.fileInput();
	this.labelButton();
	this.resizeText();
	this.resizeBox();
};

MissatgePage.prototype.labelButton = function(){
	$(".label.filebutton").change(function(){
		$(this).parent().find(".file").html($(this).val());
    })
}

MissatgePage.prototype.fileInput = function(){	
	$('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
	$('#fileInput').bind('change', function() {
	  //Mida màxima del fitxer (bytes = 25 MB).
	  if(this.files[0].size > 26214400){
	  		$('#fileInput').val("");
	  		var $modal = $('#myModal');
			$modal.find('.modal-body').html("El tamaño del fichero debe ser menor a 25 MB");
			$modal.modal('show');
			$('#nomFile').html("<small>No se ha seleccionado ningún archivo (opcional)</small>");
	  }else{
			$('#nomFile').html('<small>Fichero: ' + $('#fileInput').val().replace(/C:\\fakepath\\/i, '') + '</small>');
	  }
	});
};

/**
 * Resize del text area a mesura que l'usuari escriu. 
 */
MissatgePage.prototype.resizeText = function(){	 	
	$('#boxText').autosize();
}

/**
 * Efecte resize al fer focus al textarea. 
 */
MissatgePage.prototype.resizeBox = function(){
	$("#boxText").one('focus', function(){  
         $(this).css('height', '100px');
		 $(this).css('min-height', '100px');
    });
}

$(document).ready(function(){
	var missatgePage = new MissatgePage();
	missatgePage.init(); 
});