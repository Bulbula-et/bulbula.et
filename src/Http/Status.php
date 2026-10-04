<?php

declare(strict_types=1);

namespace Bulbula\Http;

/**
 * The HTTP status codes used by the application.
 */
enum Status: int
{
    case Ok = 200;

    case Created = 201;

    case NoContent = 204;

    case MovedPermanently = 301;

    case Found = 302;

    case SeeOther = 303;

    case BadRequest = 400;

    case Unauthorized = 401;

    case Forbidden = 403;

    case NotFound = 404;

    case MethodNotAllowed = 405;

    case UnsupportedMediaType = 415;

    case UnprocessableContent = 422;

    case TooManyRequests = 429;

    case InternalServerError = 500;

    case ServiceUnavailable = 503;

    public function reasonPhrase(): string
    {
        return match ($this) {
            self::Ok => 'OK',
            self::Created => 'Created',
            self::NoContent => 'No Content',
            self::MovedPermanently => 'Moved Permanently',
            self::Found => 'Found',
            self::SeeOther => 'See Other',
            self::BadRequest => 'Bad Request',
            self::Unauthorized => 'Unauthorized',
            self::Forbidden => 'Forbidden',
            self::NotFound => 'Not Found',
            self::MethodNotAllowed => 'Method Not Allowed',
            self::UnsupportedMediaType => 'Unsupported Media Type',
            self::UnprocessableContent => 'Unprocessable Content',
            self::TooManyRequests => 'Too Many Requests',
            self::InternalServerError => 'Internal Server Error',
            self::ServiceUnavailable => 'Service Unavailable',
        };
    }

    public function isSuccessful(): bool
    {
        return match ($this) {
            self::Ok, self::Created, self::NoContent => true,
            default => false,
        };
    }

    public function isRedirect(): bool
    {
        return match ($this) {
            self::MovedPermanently, self::Found, self::SeeOther => true,
            default => false,
        };
    }

    public function isClientError(): bool
    {
        return match ($this) {
            self::BadRequest,
            self::Unauthorized,
            self::Forbidden,
            self::NotFound,
            self::MethodNotAllowed,
            self::UnsupportedMediaType,
            self::UnprocessableContent,
            self::TooManyRequests => true,
            default => false,
        };
    }

    public function isServerError(): bool
    {
        return match ($this) {
            self::InternalServerError, self::ServiceUnavailable => true,
            default => false,
        };
    }

    /**
     * Whether a response with this status may carry a body.
     */
    public function allowsBody(): bool
    {
        return $this !== self::NoContent;
    }
}
