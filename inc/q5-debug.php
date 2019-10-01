<?php
define ('NEWLINE', "\n\r");

function q5_debug($comment)
{
	$trace = debug_backtrace();
	$fp = fopen( 'd:/wamp64/www/quintic/debug.txt', 'a');
	fwrite ($fp, $trace[1]['function'] . ': ' .$comment . NEWLINE);
	fclose($fp);
}

?>
