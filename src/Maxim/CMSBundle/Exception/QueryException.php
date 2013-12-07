<?php
/**
 * Author: Maxim
 * Date: 15/10/13
 * Time: 20:34
 * Property of MCSuite
 */

namespace  Maxim\CMSBundle\Exception;
use \Exception;

class QueryException extends \Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}