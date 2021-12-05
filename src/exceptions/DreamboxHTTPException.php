<?php


namespace datagutten\dreambox\web\exceptions;


use WpOrg\Requests;
use Throwable;

class DreamboxHTTPException extends DreamboxException
{
    public $response;
    public function __construct(Requests\Response $response, $code = 0, Throwable $previous = null)
    {
        $this->response = $response;
        parent::__construct(sprintf('Dreambox HTTP error: %d', $response->status_code), $code, $previous);
    }
}