<?php
require __DIR__.'/vendor/autoload.php';
$app = new \Symfony\Component\Console\Application();
$app->add(new \mikemix\NamespaceRewrite\Command\RefactorCommand());
$app->run();
