<?php
/**
 * Author: Maxim
 * Date: 13/11/13
 * Time: 20:02
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Exception;
use \Exception;

class ModuleException extends \Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}