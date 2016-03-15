<?php

session_start();

require_once('../vendor/autoload.php');
require_once('../config/diconfig.php');

$framework = new App\MasterController($di, $di->get('router'));
$response = $framework->execute($di->get('request'));

$response = new \Aura\Web\ResponseSender($response);
$response();
