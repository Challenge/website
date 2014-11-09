<?php
// This is the post-receive hook that git forwards to us.

$file = 'git.log';
if(isset($_POST['payload'])) {
echo var_dump($_POST);
	$payload = json_decode($_POST['payload']);
	file_put_contents($file, json_encode($payload)."\n", FILE_APPEND);
	shell_exec('git pull');
	shell_exec('chown -R www-data:www-data /var/www-testing');
	shell_exec('chmod -R g+w /var/www-testing');
} else {
	echo "Access is denied!";
}

