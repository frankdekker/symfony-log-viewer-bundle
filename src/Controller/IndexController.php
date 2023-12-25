<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Twig\Environment;

class IndexController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('@SymfonyLogViewer/index.html.twig'));
    }
}
