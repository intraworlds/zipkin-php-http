<?php
namespace IW;

require __DIR__ . '/vendor/autoload.php';

use IW\ZipkinPhpHttp\Tracer;

$tracer = Tracer::create('prototype');

$span = $tracer->spanRequest();

$span->start();
sleep(1);
$span->stop();

var_dump($tracer->save());
