<?php
// This is the post-receive hook that git forwards to us.

$file = 'git.log';
if (isset($_POST['payload'])) {
	$payload = json_decode($_POST['payload']);
	file_put_contents($file, json_encode($payload)."\n", FILE_APPEND);
	shell_exec('git pull');
}

