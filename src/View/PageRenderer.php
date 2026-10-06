<?php

declare(strict_types=1);

namespace Bulbula\View;

use RuntimeException;

use function sprintf;

/**
 * Reads a static page from the view directory.
 *
 * Templates live in resources/views, outside the document root, so the web
 * server can never serve one directly and compete with the front controller.
 * Phase one has no templating engine because it has no dynamic page; this
 * keeps the HTML out of the controller without inventing a view layer.
 */
final readonly class PageRenderer
{
    public function __construct(private string $directory)
    {
    }

    /**
     * @throws RuntimeException when the page does not exist
     */
    public function render(string $page): string
    {
        $path = $this->directory . '/' . basename($page);
        $contents = is_file($path) ? file_get_contents($path) : false;

        if ($contents === false) {
            throw new RuntimeException(sprintf('Page [%s] could not be read.', $page));
        }

        return $contents;
    }
}
