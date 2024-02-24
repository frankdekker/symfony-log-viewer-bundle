<?php

declare(strict_types=1);

use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\Filesystem\Filesystem;

// cleanup kernel cache
(new Filesystem())->remove(__DIR__ . '/.kernel');

// PHPUnit 11 keeps track of which error handler are added and removed. Until this is fixed, we need to register the error handler
// before PHPUnit registration.
ErrorHandler::register(null, false);
