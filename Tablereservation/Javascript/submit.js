	
/* Adds an error-box to the tag with the given id */
function addError(id,error){

	var temp = document.getElementById(id);
	var errorText = document.createElement('p');
	
	errorText.innerHTML = '* '+error;
	errorText.setAttribute('class','error');
	temp.appendChild(errorText);	
}

/* redirects to the current page, but with submit defined 
 * i.e the user is tryint go make a reservation.
 */
function submit(){
	/* MAKE URL */
	var selectedStr = selected.toString();
	window.location.href = makeQueryString()+"&submit=";
}	

/* resets all the selectiosn the user has done. */
function reset(){
	/* MAKE URL */
	selected.clear();
	window.location.href = makeQueryString();
}
