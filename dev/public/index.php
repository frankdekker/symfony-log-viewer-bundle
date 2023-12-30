<?php

use FD\SymfonyLogViewerBundle\Dev\Kernel;

require_once dirname(__DIR__, 2) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};
