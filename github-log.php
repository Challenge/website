<?php

$file = 'git.log';

if (file_exists($file)) {
	$log = file($file);
echo '
<table>
<tr>
<th>Line</th><th>Content</th>
</tr>
';
foreach($log as $line_num => $line) {
	echo '
	<tr>
	  <td>'.$line_num.'</td>
	  <td>'.$line.'</td>
	</tr>';
}
echo '</table>';
} else {
	echo 'No log file found';
}
