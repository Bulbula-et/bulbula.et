<?php

declare(strict_types=1);

namespace Bulbula\Http\Exception;

use Bulbula\Http\Method;
use Bulbula\Http\Status;

final class MethodNotAllowedException extends HttpException
{
    /**
     * @param list<Method> $allowed
     */
    public static function forPath(string $path, Method $used, array $allowed): self
    {
        $names = array_map(static fn (Method $method): string => $method->value, $allowed);

        return new self(
            Status::MethodNotAllowed,
            sprintf('Method [%s] is not allowed for [%s].', $used->value, $path),
            ['Allow' => implode(', ', $names)],
        );
    }
}
