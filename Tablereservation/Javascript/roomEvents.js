
/* returns the seat-tag with the given number (id = "seat"+num)*/
function getSeat(num){
	return document.getElementById('seat'+num);
}

/* gets the tag with the given id (id = "x,y")*/
function getTile(x,y){
	return document.getElementById(x+','+y);
}

/* this function is called whenever a user clicks a valid seat
 * It selects the seat if the seat has not yet been selected.
 * It deselectes the seat if the seat already has been selected.
*/
function clickSeat(num, isSelected){
	var seat = getSeat(num); 

		if(isSelected == 0){
			seat.setAttribute('class','tile seat-selected clickable');
			seat.setAttribute('onclick','clickSeat('+num+',1)');
			seat.setAttribute('onmouseover','onMouseOver('+num+')');
			seat.setAttribute('onmouseout','onMouseLeaveSelected('+num+')');
			
			selected.add(num);

		} else if (isSelected == 1){	
			seat.setAttribute('class','tile seat clickable');
			seat.setAttribute('onclick','clickSeat('+num+',0)');
			seat.setAttribute('onmouseover','onMouseOver('+num+')');
			seat.setAttribute('onmouseout','onMouseLeave('+num+')');
			selected.remove(num);
		}
}

/* highligts the seat the mouse is currently above*/
function onMouseOver(num){
	var seat = getSeat(num); 
	seat.setAttribute('class','tile seat-mouseover clickable');
}

/* returns the seat to normal */
function onMouseLeave(num){
	var seat = getSeat(num); 
	seat.setAttribute('class','tile seat clickable');
}

/* returns the selected seat to normal */
function onMouseLeaveSelected(num){
	var seat = getSeat(num); 
	seat.setAttribute('class','tile seat-selected  clickable');
}
