<?php
/*
	Dependencies
		- *dataDir*\settings.php
		- *phpDir*\reservationFields.php
		- *phpDir*\schema.php
		- *jsDir*\selected.js
		- *jsDir*\submit.js
*/

/* Should the errors be shown?
   note: if submit is set in the query, errors are shown regardless
*/
$showErrors = 0;

/* The destination for a successful submition*/
$succesDest = $root;

/* All the error messages to be shown */
$selectedErrorText1 = "Plads ";
$selectedErrorText2 = " er desværre allerede blevet reserveret";
$noSelectionErrorText = "Der er ikke blevet valgt nogle pladser. </br> Du kan vælge pladser ved at trykke på et eller flere borde nedenfor. ";
$undefinedTicketErrorText = "Indtast venligst et billet ID";
$undefinedNameErrorText = "Indtast venligst et navn";
$invalidTicketIdErrorText = "Det indtastede billet ID er ikke gyldigt";
$eid = 'errorView';		
$alreadyHasReservationErrorText = "Der er allerede blevet reserveret en plads til billet id: ";
$dublicateTicketErrorText = "Gentaget billet ID. </br> * Du må kun reservere en plads pr. billet";
$cancelReservationText1 = "Kilk her for at annullere reservationen på plads: ";
$cancelReservationText2 = ". Til billet id: ";

/* A count that tells the number of errors*/
$hasError = 0;


/* Adds an message to a tag with the given id.*/
function addFieldError($id, $error){
	echo 'addError('.$id.','.$error.');';
}

/* Checks for Ticket errors ie. invalid tickets. 
   Adds an error message for each violation found.
*/
function makeTicketErrors($seat, $name, $ticket){
	global $undefinedTicketErrorText,$undefinedNameErrorText, $invalidTicketIdErrorText;
	
	$id = 'field'.$seat;
	if(isSeatTaken($seat) != 1){
		if($name == 'undefined'){
			addError($id,$undefinedNameErrorText);
		}
	
		if($ticket == 'undefined'){	
				addError($id,$undefinedTicketErrorText);
		} else if(isValidTicket($ticket) != 1){
				addError($id,$invalidTicketIdErrorText);
		}	
	}
	
}

/* returns true if the given seat is taken */
function isSeatTaken($seat){
	global $takenArray;
/*	var_dump($takenArray);
	var_dump(isset($takenArray[$seat]));
	print_r(isset($takenArray[$seat]));
	print 'done';*/
	return isset($takenArray[$seat]);
}

/* returns the seat of the given ticketID, returns false the ticket has not been used*/
function isTicketUsed($ticketId){
	global $takenArray;
	
	foreach($takenArray as $seat => $ticket)
		if($ticket == $ticketId)
			return $seat;		
	
			return false;
}

/* Checks all the textfields for dublicate ticket id's*/
function makeDublicateErrors($seat,$ticket){
	global $takenArray,$eid,$dublicateTicketErrorText,$defaultTextBoxText;
	
	for($i = 0; isset($_REQUEST['selected'.$i]);$i++){	
		$id = explode('_',htmlentities($_REQUEST['selected'.$i]));
	
		if($ticket == $id[2] && $ticket != 'undefined' && $seat != $id[0]){
			addError('field'.$seat,$dublicateTicketErrorText);	
		}
		
	}
}

/* Check to see if all the selected seats are valid. 
 * e.g not taken
*/
function makeSelectedErrors($seat, $ticket){
		global $selectedErrorText1, $selectedErrorText2,$eid;
			
		if(isSeatTaken($seat)){
			addError($eid,$selectedErrorText1.$seat.$selectedErrorText2);	
		}	
}

/* initializes everything except the schema itself (see schema.php) */
function init(){
	global $eid, $showErrors, $selectedErrorText1, $selectedErrorText2,$noSelectionErrorText, $hasError;
	$i = 0;	
	
	/* is "showErrors" defined? if yes use that value*/
	if(isset($_REQUEST['showErrors']))
		$showErrors = htmlentities($_REQUEST['showErrors']);
	
	if($showErrors == 1 || isset($_REQUEST['submit'])){
		/* Only show errors if the user has submitted atleast once */
		$showErrors = 1;
		
		for($i = 0; isset($_REQUEST['selected'.$i]);$i++){	
			$id = explode('_', $_REQUEST['selected'.$i]);
			makeSelectedErrors($id[0],$id[2]);
			makeTicketErrors($id[0],$id[1],$id[2]);
			makeDublicateErrors($id[0],$id[2]);
		}
	
	if($i == 0)
		addError($eid,$noSelectionErrorText);
	} else {
		/* Don't show errors on theme change if the user has not selected atleast one seat*/
		$showErrors = 0;
	}
		
	/* if there was no errors found and submit is defined in the query string */
	if($hasError == 0 && isset($_REQUEST['submit'])){
		/* array containing information on successful or changed reservations */
		$succesArray = array();
		$changeArray = array();

		startTransaction();
		
		/* itterate on all the selections*/
		for($i = 0; isset($_REQUEST['selected'.$i]) ;$i++){
			$selection = explode('_', $_REQUEST['selected'.$i]);
			/* Can the reservation be made? */
			if(makeReservation($selection[0],$selection[1],$selection[2]) != '00000'){
				/* if not, can the reservation be changed? */
				if(changeReservation($selection[0],$selection[1],$selection[2]) != '00000'){
					addError($eid,$selectedErrorText1.$selection[0].$selectedErrorText2);
				} else {
					/* if the reservation was changed add it to the changeArray */
					$changeArray[$i] = htmlentities($_REQUEST['selected'.$i]);
				}
			}else{
					/* if the reservation was successful add it to the succesArray */
					$succesArray[$i] = htmlentities($_REQUEST['selected'.$i]);
			}
		
		}
		/* If theres still no errors, commit the changes and call doSuccess*/
		if($hasError == 0){
			commit();
			doSuccesMessage($succesArray,$changeArray);
		} else {
			rollbackTransaction();	
		}
	}

}


/* add an error to the tag with the given id*/
function addError($id,$error){
global $hasError;
	$hasError++;
	echo 'addError("'.$id.'","'.$error.'");';
}

/* Show a popup box contain information about the reservation,
	when the popup is closed, redirect to $succesDest
	IF POSSIBLE CHANGE THIS TO SOMETHING BETTER!!!!
	*/
function doSuccesMessage($succesArray, $changeArray){
global $succesDest;
	$str = '';
	
	if(sizeof($succesArray) > 0){
	$str = "Følgende pladser er blevet reserveret:\\n";	
		foreach($succesArray as $index => $select){
			$t = explode('_',$select);
			$str.= "Plads $t[0], med billet id : $t[2], til $t[1]\\n";	
		}
	}
	
	if(sizeof($changeArray) > 0){
		$str .= "\\nFølgende reservation er blevet ændret :\\n";
		
		foreach($changeArray as $index => $change){
			$t = explode('_',$change);
			$str.= "Billet id: $t[2]'s plads er blevet ændret til plads: $t[0] og navn: $t[1]\\n";	
		}
	}
	
	if(sizeof($changeArray) == 0 && sizeof($succesArray) == 0)
		return;
	
	if(isset($_REQUEST['style']))
		$sty = htmlentities($_REQUEST['style']);
	 
	
	$str = str_replace(array('æ','ø','å'), array('\u00E6','\u00F8','\u00E5'), $str);
	echo 'alert("'.$str.'");';
	echo "window.location.href = '$succesDest?style=$sty'";
}



?>
