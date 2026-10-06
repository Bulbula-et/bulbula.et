<?php

declare(strict_types=1);

namespace Tests\Unit\View;

use Bulbula\View\PageRenderer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(PageRenderer::class)]
final class PageRendererTest extends TestCase
{
    private string $directory = '';

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/bulbula-pages-' . bin2hex(random_bytes(6));
        mkdir($this->directory, 0o775, true);
        file_put_contents($this->directory . '/home.html', '<h1>Bulbula</h1>');

        parent::setUp();
    }

    protected function tearDown(): void
    {
        @unlink($this->directory . '/home.html');
        @rmdir($this->directory);

        parent::tearDown();
    }

    public function test_it_reads_a_page_from_the_directory(): void
    {
        self::assertSame('<h1>Bulbula</h1>', new PageRenderer($this->directory)->render('home.html'));
    }

    public function test_a_missing_page_is_reported(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Page [missing.html] could not be read.');

        new PageRenderer($this->directory)->render('missing.html');
    }

    public function test_it_never_escapes_the_page_directory(): void
    {
        $outside = dirname($this->directory) . '/bulbula-outside-' . bin2hex(random_bytes(4)) . '.html';
        file_put_contents($outside, 'secret');

        try {
            $this->expectException(RuntimeException::class);

            new PageRenderer($this->directory)->render('../' . basename($outside));
        } finally {
            @unlink($outside);
        }
    }

    public function test_a_traversal_attempt_that_resolves_inside_the_directory_still_works(): void
    {
        self::assertSame('<h1>Bulbula</h1>', new PageRenderer($this->directory)->render('../../home.html'));
    }
}
