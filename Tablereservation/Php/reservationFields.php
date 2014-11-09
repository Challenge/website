<?php
//Denne fil indeholder alle javascripts og events der bruges i forbindelse med
//oprettelsen af reservations boksene der popper op når man klikker på ledig plads.
?>

<script type="text/javascript"> 
/* default text for the textboxes in the Fields*/
var defaultTextboxTextTicket = '<?php echo $defaultTextboxTextTicket; ?>';
var defaultTextboxTextName = '<?php echo $defaultTextboxTextName; ?>';

/* sets the value of the textbox for the given seat */
	function setTextBoxValue(seat,name_text,ticket_text){
		var name = document.getElementById('textbox'+seat+'Name');
		var ticket = document.getElementById('textbox'+seat+'Ticket');
		
		name.setAttribute('value',name_text);
		ticket.setAttribute('value',ticket_text);
	}

/* gets the value of the textbox for the given seat */
	function getTextBoxValue(seat){
		var val = new Array()
		var name = document.getElementById("textbox"+seat+'Name').value;
		var ticket = document.getElementById("textbox"+seat+'Ticket').value;

		
		if(name == defaultTextboxTextName || name == '') {
			name = undefined;
		}
		
		if(ticket == defaultTextboxTextTicket || ticket == ''){
			ticket = undefined;
		}
		
		val[0] = name
		val[1] = ticket
	
		return val
	}
/* Called when a textbox with the given id gains focus */
	function onFocusTextBoxName(id){
		var temp = document.getElementById(id);

		if(temp.value == defaultTextboxTextName){
			temp.setAttribute('value','');
		}
	}

/* Called when a textbox with the given id gains focus */
	function onFocusTextBoxTicket(id){
		var temp = document.getElementById(id);

		if(temp.value == defaultTextboxTextTicket){
			temp.setAttribute('value','');
		}
	}
	
/* Called when a textbox with the given id loses focus */	
	function onFocusLostTextBoxName(id){	
		var temp = document.getElementById(id);
		
		if(temp.value == '' || temp.value == null){
			temp.setAttribute('value',defaultTextboxTextName);
		}
	}
	
/* Called when a textbox with the given id loses focus */	
	function onFocusLostTextBoxTicket(id){	
		var temp = document.getElementById(id);

		if(temp.value == '' || temp.value == undefined){
			temp.setAttribute('value',defaultTextboxTextTicket);
		}
	}
	
/* Adds a field for the given seat */
	function makeField(seat){		
		var masterForm = document.createElement('div');
		var fieldset = document.createElement('fieldset');
		var legend = document.createElement('legend');
		var img = document.createElement('img');
		var inputTicket = document.createElement('input');
		var inputName = document.createElement('input');
		var dropdown = document.createElement('select');

		/* the title-label for the field */
		legend.innerHTML = 'Plads: '+seat;
		legend.setAttribute('class','formLegend');

		/* the Help-icon next to the title-label*/
		img.setAttribute('src','Graphics/global/questionMark.png');
		img.setAttribute('class','help');
		img.title = '<?php echo $helpGuest;?>'


		/* the textbox in the field*/
		var textBoxId = 'textbox'+seat+'Name';
		inputName.setAttribute('id',textBoxId );
		inputName.setAttribute('class', 'textbox');		
		inputName.setAttribute('onfocus', 'onFocusTextBoxName("'+textBoxId+'")');
		inputName.setAttribute('onblur', 'onFocusLostTextBoxName("'+textBoxId +'")');
		inputName.setAttribute('value',defaultTextboxTextName);	
		inputName.setAttribute('type','text');
		
		/* the textbox in the field*/
		var textBoxId = 'textbox'+seat+'Ticket';
		inputTicket.setAttribute('id',textBoxId );
		inputTicket.setAttribute('class', 'textbox');		
		inputTicket.setAttribute('onfocus', 'onFocusTextBoxTicket("'+textBoxId+'")');
		inputTicket.setAttribute('onblur', 'onFocusLostTextBoxTicket("'+textBoxId +'")');
		inputTicket.setAttribute('value',defaultTextboxTextTicket);	
		inputTicket.setAttribute('type','text');
		
		
		/* the div containing all the elements*/
		masterForm.setAttribute('id','reservationform'+seat);
		masterForm.setAttribute('class','reservationForm');
		masterForm.appendChild(fieldset);	
	
		/* the box with the title-label as title*/
		fieldset.setAttribute('id', 'field'+seat);
		fieldset.setAttribute('class', 'field');
		fieldset.innerHTML += '<p class="textboxLabel">Navn: </p> </br>';
		fieldset.appendChild(inputName);
		fieldset.innerHTML += '</br>';
		fieldset.innerHTML += '<p class="textboxLabel">Billet ID: </p> </br>';
		fieldset.appendChild(inputTicket);
	
		legend.appendChild(img);
		fieldset.appendChild(legend);
		
		document.getElementById('fieldDiv').appendChild(masterForm);
	
	}


	/* wrapper function for makeField 
	 This function is called whenever a user selects a seat
	 */
	function addReservationField(seat){
		makeField(seat);	
	}

	/* removes a reservationField with the given seat */
	function removeReservationField(seat){
		var temp = document.getElementById('reservationform'+(seat));
		temp.parentNode.removeChild(temp);
	}

</script>