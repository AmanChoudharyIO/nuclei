<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class GeneralException.
 */
class GeneralException extends Exception
{
    public $message;

    public $response;

    public $code = 400;

    /**
     * GeneralException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report()
    {
    die('zczxxzxx');
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render()//$request
    {
    die('sdaasdasd');
        //$this->withError($this->message, $this->code);
    }
}
