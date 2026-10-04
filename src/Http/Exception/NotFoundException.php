<?php

declare(strict_types=1);

namespace Bulbula\Http\Exception;

use Bulbula\Http\Status;

final class NotFoundException extends HttpException
{
    public static function forPath(string $path): self
    {
        return new self(Status::NotFound, sprintf('No route matches [%s].', $path));
    }
}
