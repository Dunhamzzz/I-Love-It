<?php
require_once('config.php');
if(isset($_GET['t'])) {
	$stmt = $db->prepare("SELECT `url` FROM `urls` WHERE `tag` = ?");
	$stmt->bind_param('s', $_GET['t']);
	$stmt->execute();
	$stmt->bind_result($url);
	$stmt->fetch();
	$stmt->close();
	if(!$url) {
		header("Location: /?invalid=".$_GET['t']);
	} else {
		$stmt = $db->prepare("UPDATE `urls` SET `count` = `count`+ 1 WHERE `tag` = ?");
		$stmt->bind_param('s', $_GET['t']);
		$stmt->execute();
		header("Location: ".$url);
	}
} else {
	header("Location: /");
}