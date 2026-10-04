<?php

declare(strict_types=1);

/*
 * Deliberately invalid: a route file must return a closure. Used to prove the
 * kernel factory rejects a malformed route file instead of failing silently.
 */
return ['not' => 'a closure'];
