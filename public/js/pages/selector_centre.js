/**
 * @author Eduardo
 */

$(document).ready(function(){
	
	$('[data-select-tipus-centre]').on('change', function(){
		var $this = $(this);
		$.ajax({
			url : $this.data('selectTipusCentre'),
			data : {tipus:$this.val()}
		}).done(function(response){
			//agafa el selector de centres
			var $centre = $('[data-select-centre]');
			//el buida.
			$centre.empty();
			//afegeix les noves opcions.
			for (var i = 0; i < response.length; i++){
				$option = $('<option></option>');
				$option.val(response[i].nom_centre).text(response[i].nom_centre);
				$centre.append($option);
			}
			
			var $default = $('[data-centre]').data('centre');
			
			if($default != 'none'){
				$('select[name="centre"] option[value="' + $default +'"]').prop("selected", true);
			}						
		});
	});
	
	$('[data-select-tipus-centre]').change();
	
});
