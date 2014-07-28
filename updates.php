<?php

require_once('./config.php');	
require_once('./lib/github.php');

session_start();

//Check to see if we're not spamming requests

if (!DEVELOPMENT && isset($_SESSION['lastUpdate']) && $_SESSION['lastUpdate'] + UPDATEINTERVAL > time()) {

	echo json_encode(array("error" => "tooManyRequests"));
	return;

}

if (!isset($_POST['description']) || strlen($_POST['description']) === 0) {

	echo json_encode(array("error" => "descriptionEmpty"));
	return;

}

if (!isset($_POST['location']) || strlen($_POST['location']) === 0) {

	echo json_encode(array("error" => "locationEmpty"));
	return;

}

if (!isset($_POST['icon']) || strlen($_POST['icon']) === 0) {

	echo json_encode(array("error" => "iconEmpty"));
	return;

}

if (!isset($_POST['website']) || strlen($_POST['website']) === 0) {

	echo json_encode(array("error" => "websiteEmpty"));
	return;

}

if (!isset($_POST['name']) || strlen($_POST['name']) === 0) {

	echo json_encode(array("error" => "nameEmpty"));
	return;

}

if (!isset($_POST['coins']) || count($_POST['coins']) === 0) {

	echo json_encode(array("error" => "noCoins"));
	return;

}

if (!isset($_POST['countries']) || count($_POST['countries']) === 0) {

	echo json_encode(array("error" => "noCountry"));
	return;

}

//Make a key without special characters for the YAML file.
function makeKey($s) {

	$s = strtolower($s);
	$s = str_replace(' ', '', $s);
	$s = preg_replace('/[^A-Za-z0-9]/', '', $s);

	return $s;

}

//Make a YAMLfied string based on an array.
function makeYamlArray($a) {

	$s = '[';
	$c = count($a);
	$i = 1;

	foreach ($a as $value) {

		$s .= $value;

		if ($i < $c) {
			$s .= ',';
		}

		$i++;
	}

	$s .= ']';
	return $s;

}

function textToHTML($s) {

	$s = preg_replace('/(\r?\n){2,}/', '</p><p>', $s);
	$s = preg_replace('/(\r?\n)+/', '<br>', $s);
	return '<p>' . $s . '</p>';

}

$key       = makeKey($_POST['name']);
$icon      = $_POST['icon'];
$label     = $_POST['name'];
$countries = makeYamlArray($_POST['countries']);
$location  = sprintf('[%s]', $_POST['location']);
$content   = textToHTML($_POST['description']);
$url       = $_POST['website'];
$coins     = makeYamlArray($_POST['coins']);

$file    = './data/services.yaml';
$current = file_get_contents($file);

$yaml    = "
$key:
  label: $label
  icon: $icon
  countries: $countries
  location: $location
  url: $url
  content: |
    $content
  coins: $coins
";



try {

	$branchname = 'update/' . time() . '_' . $key;
	$git        = new Git();

	$git->createNewBranch($branchname);
	$fileInfo = $git->getFileInformation('master');

	$sha      = $fileInfo->sha;
	$newFile  = base64_encode($current . PHP_EOL . $yaml);

	$git->updateFile($branchname, $sha, $newFile);
	$git->sendPR($branchname);

} catch (Exception $e) {

	echo json_encode(array("error" => "couldNotPublishToGit"));
	return;

}

$_SESSION['lastUpdate'] = time();
echo json_encode(array("great" => "success"));
return;

?>