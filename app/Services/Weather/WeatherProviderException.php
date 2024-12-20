<?php
declare(strict_types=1);

namespace App\Services\Weather;

use Exception;
use Throwable;

class WeatherProviderException extends Exception
{
    public function __construct(
        string $message = 'Weather provider error',
        int $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
