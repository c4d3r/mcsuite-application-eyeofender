<?php
namespace Maxim\CMSBundle\Exception;
use \Exception;

class CommandExecutionException extends \Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}