/**
 * @author Eduardo
 */

$(document).ready(function(){
	$(".input-group.date").datepicker({ 
		format: "dd/mm/yyyy",
		autoclose: true, 
		todayHighlight: true,
		weekStart: 1,
		startDate: "today",
		language: "es"
	});
});