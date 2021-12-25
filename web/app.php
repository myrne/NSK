<?php
$PROJECT_ROOT = '..';

use Symfony\Component\HttpFoundation\Request;

require $PROJECT_ROOT.'/app/autoload.php';

$kernel = new AppKernel('prod', false);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
