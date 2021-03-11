<?php

namespace App\Exceptions;

/**
 * Class Handler.
 */
class Handler
{
    public $exception;
    public function __construct(\Exception $e)
    {
        $this->exception = $e;
        $this->handle($this->exception);
    }

    public function handle()
    {
        die('I am inside ');
    }
}
