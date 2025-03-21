<?php
$url = $currentURL = "http://localhost$_SERVER[REQUEST_URI]";
echo $url;

$url_actual = "http://localhost$_SERVER[REQUEST_URI]";
$partes_url = parse_url($url_actual, PHP_URL_QUERY);
echo $partes_url;

?>