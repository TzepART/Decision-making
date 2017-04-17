<?php
include __DIR__.'/../autoloader.php';

echo App\AppKernel::getInstance()->getTwig()->render('task1.html.twig');