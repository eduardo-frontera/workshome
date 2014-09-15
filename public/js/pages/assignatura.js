/**
 * @author Eduardo
 */

var numCamp = 2;  	  
	
function afegirAssignatura() {   		  
    nuevaFila = document.getElementById("assignatures").insertRow(-1);  		  
    nuevaFila.id = numCamp;  		  
    nuevaCelda = nuevaFila.insertCell(-1);  		  
    nuevaCelda.innerHTML = "<td><input type='text' maxlength = '45' class = 'form-control input-sm' size='16' name='assignatura[" + numCamp + "]'></td>";		    
    nuevaCelda = nuevaFila.insertCell(-1);  
    nuevaCelda.innerHTML = "<td><input type='text' maxlength = '45' class = 'form-control input-sm' size='16' name='nom[" + numCamp + "]'></td>";  
    nuevaCelda = nuevaFila.insertCell(-1);  
    nuevaCelda.innerHTML = "<td><input type='text' maxlength = '150' class = 'form-control input-sm' size='16' name='cognoms[" + numCamp + "]'></td>";  
    nuevaCelda = nuevaFila.insertCell(-1);  
    nuevaCelda.innerHTML = "<td><input type='button' value='Eliminar' class='btn btn-danger btn-xs' onclick='eliminarAssignatura(this)'></td>";  
    numCamp++;  		  
}  
	  
function eliminarAssignatura(obj) {  		  
    var oTr = obj;  		  
    while(oTr.nodeName.toLowerCase() != 'tr') {  		  
        oTr=oTr.parentNode;		  
    } 		  
    var root = oTr.parentNode;  		  
    root.removeChild(oTr);  		  
}    