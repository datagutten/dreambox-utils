<?php


namespace datagutten\dreambox\web\exceptions;


use Throwable;
use WpOrg\Requests;

class DreamboxHTTPException extends DreamboxException
{
    public Requests\Response $response;

    public function __construct(Requests\Response $response, int $code = 0, Throwable $previous = null)
    {
        $class = Requests\Exception\Http::get_class($response->status_code);
        /** @var Requests\Exception\Http $exception */
        $exception = new $class(null, $response);
        $this->response = $response;
        parent::__construct(sprintf('Dreambox HTTP error: %s', $exception->getMessage()), $code, $previous);
    }
}