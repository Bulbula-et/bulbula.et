<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controller;

use Bulbula\Http\Controller\HomeController;
use Bulbula\Http\Status;
use Bulbula\View\PageRenderer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HomeController::class)]
final class HomeControllerTest extends TestCase
{
    public function test_it_returns_the_pre_launch_page_as_html(): void
    {
        $response = new HomeController(new PageRenderer(__DIR__ . '/../../../../resources/views'))->show();

        self::assertSame(Status::Ok, $response->status());
        self::assertSame('text/html; charset=utf-8', $response->header('Content-Type'));
        self::assertStringContainsString('<!DOCTYPE html>', $response->body());
        self::assertStringContainsString('BULBULA.ET', $response->body());
    }
}
