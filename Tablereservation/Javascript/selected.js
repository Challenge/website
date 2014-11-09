/* 
Dependencies:
	- *phpDir*\reservationFields.php
*/

var selected = {};

/* Contains every selected seat number*/
selected.array = new Array();

/* appends the given seat to list of selected seats
 * and calls makeField to add a field.
*/
selected.add = function(seatNumber){
	selected.array.push(seatNumber);
	makeField(seatNumber);
}

/* Removes the latest added seat from the list
 * aswell as it's field.
 */
selected.removeEnd = function(){

	var seatNumber = selected.array.pop();
	removeReservationField(seatNumber);

	return seatNumber;
}

/* Clears the whole list */
selected.clear = function(){
	while(selected.length() > 0)
		this.removeEnd();
}

/* Removes the given seatNumber from the list*/
selected.remove = function(seatNumber){
	function filter(seatNumber2){
		return seatNumber != seatNumber2;
	}
	
	selected.array = selected.array.filter(filter);
	removeReservationField(seatNumber);
}

/* Returns the length of the list*/
selected.length = function(){
	return selected.array.length;
}

/* Returns the i'th element of the list*/
selected.get =function(index){
	return selected.array[index];
}

/* Returns a query string representing the content of the list and the textfields */
selected.toString = function(){

	var string ='';
	var i;

	for(i = 0; i < selected.length();i++){
		var res = getTextBoxValue(selected.get(i));
		string += '&selected' + i + '=' + selected.get(i) + '_' + res[0] + '_' + res[1];
	}
	return string;	
}
