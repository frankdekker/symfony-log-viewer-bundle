<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function __construct()
    {
    }

//    #[Route(['log-viewer', '/log-viewer/{slug}'], name: self::class, methods: 'GET')]
    //  #[Template('/log/vue/index.html.twig')]
    public function __invoke(): Response
    {
        return new Response('success');
    }
}
