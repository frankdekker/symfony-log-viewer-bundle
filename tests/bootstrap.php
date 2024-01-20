<?php

declare(strict_types=1);

use Symfony\Component\Filesystem\Filesystem;

(new Filesystem())->remove(__DIR__ . '/.kernel/');
