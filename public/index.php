<?php

use App\MasterController;

session_start();

require_once('../vendor/autoload.php');
require_once('../config/diconfig.php');

$request   = App\Request::newFromGlobals();
$framework = new MasterController($di);
echo $framework->execute($request);
