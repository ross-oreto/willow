<?php

namespace Oreto\Willow;

use Base;

class ErrorHandler {
    static string $ERROR_PREFIX  = 'ERROR';

    static function handle($f3) {
        echo match ($f3->get(self::$ERROR_PREFIX.".code")) {
            404 => self::handle404($f3),
            default => self::handle500($f3),
        };
    }

    static function handle404(Base $f3): string {
        $ext = $f3->get('ext');
        self::logError($f3->get(self::$ERROR_PREFIX), false);
        return \Template::instance()->render("_404$ext");
    }

    static function handle500(Base $f3): string {
        $ext = $f3->get('ext');
        self::logError($f3->get(self::$ERROR_PREFIX));
        return \Template::instance()->render("_500$ext");
    }

    /**
     * log error to a source
     * @param array $error The error array object
    `ERROR.code` int - the HTTP status error code (`404`, `500`, etc.)
    `ERROR.status` string - a brief description of the HTTP status code. e.g. `'Not Found'`
    `ERROR.text` string - error context
    `ERROR.trace` string - stack trace stored in an `array()`
    `ERROR.level` int - error reporting level (`E_WARNING`, `E_STRICT`, etc.)
     * @param bool $trace If true add the stack trace to the message, otherwise omit.
     */
    static function logError(array $error, bool $trace = true): void {
        $stackTrace = $trace ? ". trace: ".$error['trace'] : '';
        $message = $error['code'].": ".$error['status']." - ".$error['text'].$stackTrace;
        Willow::$logger->error($message);
    }
}