/**
 * @author Eduardo
 */


function navigate(link) {
   var obj = document.getElementById(link);
   var visible=(obj.style.display != "none");

   if(visible){
	  obj.style.display = "none";
   }else{
	  obj.style.display = "block";
	   }
}
			
function validate() {
    if (document.getElementById("box").value == "") {
         return false;
    } else {
        return true;
    }
}