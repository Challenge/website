<?php
/**
 * Creates a random string encoded in base 62 with the given length.
 * @param $length The length of the random generated string.
 * @return The encoded string with the given length.
 */
function getRandomString($length) {
	$validCharacters = "123456789abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ";
	$validCharNumber = strlen($validCharacters);
	$result = "";
	
	for ($i = 0; $i < $length; $i++) {
		$index = mt_rand(0, $validCharNumber - 1);
		$result .= $validCharacters[$index];
	}
	
	return $result;
}
?>

<?php
/**
 * Check if a string is longer than $stringLength, if it is then split the string.
 * @param $str The string to check.
 * @param $stringLength The length the string should be.
 * @return The input string at max $stringLength long.
 */
function stringLengthSplit($str, $stringLength = 50) {
	$returnString = '';
	if($stringLength < 5) return '...';
	
	if(strlen($str) > $stringLength) {
		$returnString = substr($str, 0, $stringLength - 3) . '...';
	} else {
		$returnString = $str;
	}
	
	return $returnString;
}
?>

<?php
/**
 * Insert linebreaks at given intervals
 * @param $str The string to insert linebreaks into.
 * @param $lineLength The interval to insert linebreaks.
 * @return The input string with linebreaks inserted.
 */
function lineBreaks($str, $lineLength = 80) {
	$lineBreak = '\n';
	$tempString = $str;
	$returnString = '';
	
	while(strlen($tempString) > 0) {
		$buildString = substr($tempString, 0, $lineLength);
		$buildString .= $lineBreak;
		$returnString .= $buildString;
		
		$tempString = substr($tempString, $lineLength);
	}
	
	return $returnString;
}
?>



