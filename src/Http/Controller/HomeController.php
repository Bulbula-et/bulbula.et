<?php

declare(strict_types=1);

namespace Bulbula\Http\Controller;

use Bulbula\Http\Response;
use Bulbula\View\PageRenderer;

/**
 * Serves the public pre-launch page.
 */
final readonly class HomeController
{
    public function __construct(private PageRenderer $pages)
    {
    }

    public function show(): Response
    {
        return Response::html($this->pages->render('home.html'));
    }
}
