<?php
// Definitions
define('ROOT_URL', 'http://ilo.ve.it/');

// Admin Username And Password:
$adminuser ='admin';
$adminpass ='admin';

// Random string function, edit as you wish:
function generateTag($url) {
	return substr(md5($url.uniqid(mt_rand(), true)), 0, 5);
}

// DB Connection
$db = new Mysqli('localhost', 'username', 'password', 'iloveit');
if ($db->connect_error)
    die('Connect Error ('.$db->connect_errno.') '. $db->connect_error);